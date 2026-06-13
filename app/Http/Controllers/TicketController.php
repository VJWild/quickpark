<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Ticket;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Price;
use App\Models\ParkingSpace;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use TCPDF;
use App\Traits\NumberToLettersTrait;
use Illuminate\Support\Facades\Http;

class TicketController extends Controller
{
    use NumberToLettersTrait;

    /**
     * REGISTRO DE ENTRADA (Dispensadora)
     */
    public function storeEntry(Request $request)
    {
        $request->validate([
            'parking_space_id' => 'required|exists:parking_spaces,id',
            'plate' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'document' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        try {
            $ticket = DB::transaction(function () use ($request) {
                // Buscamos primero por Cédula/Documento para proteger la identidad del cliente
                $client = Client::where('document', $request->document)->first();

                if (!$client) {
                    // Si no existe por documento, buscamos por placa para verificar si es un vehículo conocido
                    $client = Client::where('plate', strtoupper($request->plate))->first();
                }

                if ($client) {
                    // Si el cliente existe (por documento o placa), actualizamos sus datos unificados
                    $client->update([
                        'name' => $request->name,
                        'document' => $request->document,
                        'phone' => $request->phone,
                        'plate' => strtoupper($request->plate)
                    ]);
                } else {
                    // Si es un cliente completamente nuevo en el sistema
                    $client = Client::create([
                        'name' => $request->name,
                        'document' => $request->document,
                        'phone' => $request->phone,
                        'plate' => strtoupper($request->plate)
                    ]);
                }

                $newTicket = Ticket::create([
                    'client_id' => $client->id,
                    'parking_space_id' => $request->parking_space_id,
                    'user_id' => Auth::id(),
                    'entry_time' => now(),
                    'status' => 'ACTIVO'
                ]);

                ParkingSpace::where('id', $request->parking_space_id)
                    ->update(['status' => 'OCUPADO']);

                return $newTicket;
            });

            return back()
                ->with('success', 'Entrada registrada con éxito.')
                ->with('print_ticket_url', route('tickets.print', $ticket->id));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al registrar entrada: ' . $e->getMessage()]);
        }
    }

    /**
     * CÁLCULO DE TARIFA EN TIEMPO REAL (Validadora - Consulta)
     */
    public function calculateFee($space_id)
    {
        $ticket = Ticket::with('client')->where('parking_space_id', $space_id)
            ->where('status', 'ACTIVO')->first();

        if (!$ticket) {
            return response()->json(['error' => 'No hay ticket activo en este puesto'], 404);
        }

        $entryTime = \Carbon\Carbon::parse($ticket->entry_time);
        $exitTime = now();
        $diff = $entryTime->diff($exitTime);
        $timeString = "{$diff->d} días con {$diff->h} horas con {$diff->i} minutos";

        // Obtenemos los valores configurados
        $mode = Price::where('detail', 'MODO')->first()->amount ?? 1; // 1: Mixto, 2: Plana, 3: Por Hora
        $pricePerDay = Price::where('detail', 'DIAS')->first()->amount ?? 3.00;
        $pricePerHour = Price::where('detail', 'HORAS')->first()->amount ?? 0.50;

        $totalAmount = 0;

        // Si lleva 0 días y 0 horas, cobramos mínimo 1 hora de estadía.
        $hours = ($diff->d == 0 && $diff->h == 0) ? 1 : $diff->h;

        // LÓGICA DE COBRO SEGÚN LA MODALIDAD
        if ($mode == 1) {
            // 1. MIXTO: Cobra los días completos + las horas sueltas
            $totalAmount = ($diff->d * $pricePerDay) + ($hours * $pricePerHour);

        } elseif ($mode == 2) {
            // 2. TARIFA PLANA: Cobra el día completo sin importar si estuvo 1 hora o 23 horas.
            $days = $diff->d;
            // Si hay fracción de hora o minuto, salta al siguiente día completo
            if ($diff->h > 0 || $diff->i > 0) { $days++; }
            if ($days == 0) { $days = 1; } // Mínimo 1 día de cobro

            $totalAmount = $days * $pricePerDay;

        } elseif ($mode == 3) {
            // 3. POR HORA: Ignora los días. Convierte todo el tiempo a horas y lo multiplica.
            $totalHours = ($diff->d * 24) + $hours;
            $totalAmount = $totalHours * $pricePerHour;
        }

        return response()->json([
            'ticket_id' => $ticket->id,
            'plate' => $ticket->client->plate,
            'client_name' => $ticket->client->name,
            'client_document' => $ticket->client->document,
            'client_phone' => $ticket->client->phone ?? 'N/A',
            'entry_time' => $entryTime->format('d/m/Y h:i A'),
            'duration' => $timeString,
            'total_amount' => number_format($totalAmount, 2, '.', '')
        ]);
    }

    /**
     * PROCESAMIENTO DE SALIDA (Validadora - Cobro)
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'payments' => 'required|array|min:1',
            'payments.*.method' => 'required|string',
            'payments.*.amount' => 'required|numeric|min:0.01'
        ]);

        try {
            $invoice = DB::transaction(function () use ($request) {
                $ticket = Ticket::with('client')->findOrFail($request->ticket_id);
                $exitTime = now();

                $diff = Carbon::parse($ticket->entry_time)->diff($exitTime);
                $timeString = "{$diff->d} días con {$diff->h} horas con {$diff->i} minutos";
                $totalPaid = collect($request->payments)->sum('amount');

                $activeShift = \App\Models\Shift::where('user_id', Auth::id())->where('status', 'ABIERTO')->first();

                if (!$activeShift) {
                    throw new \Exception("No puedes facturar porque no has abierto tu caja/turno.");
                }

                $currentRate = \App\Models\ExchangeRate::first()->rate ?? 1.00;

                $newInvoice = Invoice::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => Auth::id(),
                    'shift_id' => $activeShift->id,
                    'invoice_number' => 'FAC-' . strtoupper(uniqid()),
                    'exit_time' => $exitTime,
                    'total_time' => $timeString,
                    'price' => $totalPaid,
                    'quantity' => 1,
                    'total_amount' => $totalPaid,
                    'exchange_rate' => $currentRate,
                    'amount_literal' => $this->numtoletras($totalPaid),
                    'qr_code_data' => "Factura para Placa: " . $ticket->client->plate,
                ]);

                foreach ($request->payments as $payment) {
                    InvoicePayment::create([
                        'invoice_id' => $newInvoice->id,
                        'method' => $payment['method'],
                        'amount' => $payment['amount']
                    ]);
                }

                $ticket->update([
                    'exit_time' => $exitTime,
                    'status' => 'FINALIZADO'
                ]);

                ParkingSpace::where('id', $ticket->parking_space_id)
                    ->update(['status' => 'DISPONIBLE']);

                return $newInvoice;
            });

            return back()
                ->with('success', 'Factura procesada y puesto liberado.')
                ->with('print_invoice_url', route('invoices.print', $invoice->id));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Hubo un error al facturar: ' . $e->getMessage()]);
        }
    }

    /**
     * ABSOLVER / EXONERAR PAGO (Cortesía)
     */
    public function waivePayment(Request $request)
    {
        $request->validate(['ticket_id' => 'required|exists:tickets,id']);

        try {
            $invoice = DB::transaction(function () use ($request) {
                $ticket = Ticket::with('client')->findOrFail($request->ticket_id);
                $exitTime = now();
                $diff = \Carbon\Carbon::parse($ticket->entry_time)->diff($exitTime);
                $timeString = "{$diff->d} días con {$diff->h} horas con {$diff->i} minutos";

                $activeShift = \App\Models\Shift::where('user_id', Auth::id())->where('status', 'ABIERTO')->first();
                if (!$activeShift) {
                    throw new \Exception("No puedes facturar cortesías sin abrir turno.");
                }

                $currentRate = \App\Models\ExchangeRate::first()->rate ?? 1.00;

                $newInvoice = Invoice::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => Auth::id(),
                    'shift_id' => $activeShift->id,
                    'invoice_number' => 'FAC-' . strtoupper(uniqid()),
                    'exit_time' => $exitTime,
                    'total_time' => $timeString,
                    'price' => 0,
                    'quantity' => 1,
                    'total_amount' => 0,
                    'exchange_rate' => $currentRate,
                    'amount_literal' => 'CERO CON 00/100',
                    'qr_code_data' => "EXONERADO - Placa: " . $ticket->client->plate,
                ]);

                InvoicePayment::create([
                    'invoice_id' => $newInvoice->id,
                    'method' => 'Exonerado (Cortesía)',
                    'amount' => 0
                ]);

                $ticket->update([
                    'exit_time' => $exitTime,
                    'status' => 'FINALIZADO'
                ]);

                ParkingSpace::where('id', $ticket->parking_space_id)->update(['status' => 'DISPONIBLE']);

                return $newInvoice;
            });

            return back()
                ->with('success', 'El pago ha sido exonerado por cortesía.')
                ->with('print_invoice_url', route('invoices.print', $invoice->id));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al exonerar: ' . $e->getMessage()]);
        }
    }

    /**
     * ANULAR TICKET (Por error de ingreso)
     */
    public function cancelTicket(Ticket $ticket)
    {
        try {
            \App\Models\ParkingSpace::where('id', $ticket->parking_space_id)->update(['status' => 'DISPONIBLE']);

            $ticket->update([
                'status' => 'ANULADO',
                'exit_time' => now()
            ]);

            return back()->with('success', 'Ticket anulado correctamente. El puesto ha sido liberado.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al anular el ticket: ' . $e->getMessage()]);
        }
    }

    /**
     * IMPRESIÓN TCPDF: TICKET DE ENTRADA
     */
    public function printTicket(Ticket $ticket)
    {
        $ticket->load(['client', 'user', 'parkingSpace']);

        $info = Information::first();
        $companyName = $info->company_name ?? 'QUICKPARK C.A.';
        $activity = $info->activity ?? 'Servicio de Estacionamiento';
        $branch = $info->branch ? 'SUCURSAL ' . $info->branch : 'SUCURSAL No 1';
        $phone = $info->phone ? 'TELÉFONO: ' . $info->phone : '';
        $locationArray = array_filter([$info->address ?? '', $info->zone ?? '', $info->city ?? '', $info->country ?? '']);
        $location = implode(', ', $locationArray);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(79, 250), true, 'UTF-8', false);
        $pdf->setCreator(PDF_CREATOR);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setMargins(2, 2, 5);
        $pdf->setAutoPageBreak(true, 5);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 7);

        $html1 = '
        <div>
            <p style="text-align: center">
                <b style="font-size: larger">' . mb_strtoupper($companyName, 'UTF-8') . '</b> <br>
                ' . $activity . ' <br>
                ' . mb_strtoupper($branch, 'UTF-8') . ' <br>
                ' . $phone . '<br>
                ' . $location . ' <br>
                ---------------------------------------------------------------------------<br>
                <b style="font-size: large">TICKET Nº [ ' . $ticket->id . ' ]</b> <br>
                <b style="font-size: large">PUESTO: ' . $ticket->parkingSpace->space_number . '</b> <br>
                <b style="font-size: large">[' . \Carbon\Carbon::parse($ticket->entry_time)->format('d/m/Y h:i A') . ']</b><br>
                ---------------------------------------------------------------------------
                <div style="text-align: left">
                    <b style="font-size: larger">DATOS DEL REGISTRO</b> <br>
                    <b>CLIENTE: </b> ' . $ticket->client->name . '  <br>
                    <b>CI/RIF: </b> ' . $ticket->client->document . '   <br>
                    <b>TELÉFONO: </b> ' . ($ticket->client->phone ?? 'N/A') . '  <br>
                    <b>PLACA: </b> ' . $ticket->client->plate . '  <br>
                    <b>CAJERO: </b> ' . $ticket->user->name . ' <br>
                 -------------------------------------------------------------------------- <br>
                 <div style="font-size: 6.5px; text-align: justify;">
                    <b>CONDICIONES DEL SERVICIO:</b><br>
                    1. La empresa no se hace responsable por daños, robo o hurto de objetos dejados en el interior del vehículo.<br>
                    2. El extravío de este ticket genera un recargo por penalidad según las tarifas vigentes.<br>
                    3. El servicio se cancela al momento de retirar su vehículo.
                 </div>
                </div>
            </p>
        </div>';

        $pdf->writeHTML($html1, true, false, true, false, '');

        $y = $pdf->GetY() + 2;
        $style = array('border' => 2, 'vpadding' => 'auto', 'hpadding' => 'auto', 'fgcolor' => array(0,0,0), 'bgcolor' => false, 'module_width' => 1, 'module_height' => 1);
        $pdf->write2DBarcode($ticket->parkingSpace->space_number, 'QRCODE,Q', 17, $y, 45, 45, $style, 'N');

        $pdf->SetY($y + 48);
        $html2 = '<p style="text-align: center; font-size: 6px; color: #555;">Elaborado por: <b>EVG Dev Studio</b></p>';
        $pdf->writeHTML($html2, true, false, true, false, '');

        $pdf->Output('Ticket_'.$ticket->id.'.pdf', 'I');
        exit;
    }

    /**
     * IMPRESIÓN TCPDF: FACTURA FINAL DE SALIDA
     */
    public function printInvoice(Invoice $invoice)
    {
        $invoice->load(['ticket.client', 'ticket.parkingSpace', 'user', 'payments']);

        $info = Information::first();
        $companyName = $info->company_name ?? 'QUICKPARK C.A.';
        $activity = $info->activity ?? 'Servicio de Estacionamiento';
        $branch = $info->branch ? 'SUCURSAL ' . $info->branch : 'SUCURSAL No 1';
        $phone = $info->phone ? 'TELÉFONO: ' . $info->phone : '';
        $locationArray = array_filter([$info->address ?? '', $info->zone ?? '', $info->city ?? '', $info->country ?? '']);
        $location = implode(', ', $locationArray);

        $tasa = $invoice->exchange_rate ?? 1.00;
        $totalBs = $invoice->total_amount * $tasa;

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(79, 230), true, 'UTF-8', false);
        $pdf->setCreator(PDF_CREATOR);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setMargins(2, 2, 5);
        $pdf->setAutoPageBreak(true, 5);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 7);

        $html1 = '
        <div>
            <p style="text-align: center">
                <b style="font-size: larger">' . mb_strtoupper($companyName, 'UTF-8') . '</b> <br>
                ' . $activity . ' <br>
                ' . mb_strtoupper($branch, 'UTF-8') . ' <br>
                ' . $phone . '<br>
                ' . $location . ' <br>
                --------------------------------------------------------------------------------<br>
                 <b>FACTURA Nº [ '.$invoice->invoice_number.' ]</b><br>
                --------------------------------------------------------------------------------
                <div style="text-align: left">
                    <b>DATOS DEL CLIENTE</b> <br>
                    <b>NOMBRE: </b> '.$invoice->ticket->client->name.' <br>
                    <b>CI/RIF: </b> '.$invoice->ticket->client->document.'  <br>
                    <b>TELÉFONO: </b> '.($invoice->ticket->client->phone ?? 'N/A').'  <br>
                    <b>PLACA: </b> '.$invoice->ticket->client->plate.'  <br>
                    <b>PUESTO: </b> '.$invoice->ticket->parkingSpace->space_number.'  <br>
                    -------------------------------------------------------------------------------- <br>
                <b>Ingreso: </b> '.\Carbon\Carbon::parse($invoice->ticket->entry_time)->format('d/m/Y h:i A').'<br>
                <b>Salida: </b> '.\Carbon\Carbon::parse($invoice->exit_time)->format('d/m/Y h:i A').'<br>
                <b>Tiempo:  </b> '.$invoice->total_time.'<br>
                 -------------------------------------------------------------------------------- <br>
                 <table border="1" cellpadding="3">
                 <tr>
                    <td style="text-align: center" width="99px"><b>Detalle</b></td>
                    <td style="text-align: center" width="80px"><b>Importe</b></td>
                 </tr>
                 <tr>
                    <td>Servicio de estacionamiento</td>
                    <td style="text-align: right">$ '.number_format($invoice->total_amount, 2).'</td>
                 </tr>
                 </table>
                 <br>
                 <b>MÉTODOS DE PAGO APLICADOS:</b><br>';

        foreach($invoice->payments as $payment) {
            $html1 .= ' - ' . $payment->method . ': $' . number_format($payment->amount, 2) . '<br>';
        }

        $html1 .= '
                 -------------------------------------------------------------------------------- <br>
                 <table border="0" cellpadding="1">
                    <tr>
                        <td width="50%" style="font-size: larger;"><b>TOTAL $:</b></td>
                        <td width="50%" style="text-align: right; font-size: larger;"><b>$ '.number_format($invoice->total_amount, 2).'</b></td>
                    </tr>
                    <tr>
                        <td width="50%" style="font-size: larger;"><b>TOTAL Bs:</b></td>
                        <td width="50%" style="text-align: right; font-size: larger;"><b>Bs '.number_format($totalBs, 2).'</b></td>
                    </tr>
                 </table>
                 <p><b>Tasa de conversión (BCV):</b> Bs '.number_format($tasa, 2).'</p>
                 <p><b>Son: </b>'.$invoice->amount_literal.'</p>
                 -------------------------------------------------------------------------------- <br>
                 <b>Cajero:</b> '.$invoice->user->name.' <br><br>

                <p style="text-align: center">
                    <b>¡GRACIAS POR SU VISITA, VUELVA PRONTO!</b><br>
                    Favor presentar este comprobante al salir.
                </p>
                </div>
            </p>
        </div>';

        $pdf->writeHTML($html1, true, false, true, false, '');

        $y = $pdf->GetY() + 2;
        $style = array('border' => 0, 'vpadding' => '3', 'hpadding' => '3', 'fgcolor' => array(0,0,0), 'bgcolor' => false, 'module_width' => 1, 'module_height' => 1);
        $pdf->write2DBarcode($invoice->ticket->parkingSpace->space_number, 'QRCODE,L', 17, $y, 45, 45, $style);

        $pdf->SetY($y + 48);
        $html2 = '<p style="text-align: center; font-size: 6px; color: #555;">Elaborado por: <b>EVG Dev Studio</b></p>';
        $pdf->writeHTML($html2, true, false, true, false, '');

        $pdf->Output('Factura_'.$invoice->invoice_number.'.pdf', 'I');
        exit;
    }

    /**
     * PUENTE ALPR: Envía la imagen al microservicio de Python
     */
    public function scanPlate(Request $request)
    {
        try {
            if (!$request->hasFile('image')) {
                return response()->json(['success' => false, 'error' => 'Laravel no recibió la imagen de la cámara.'], 400);
            }

            $file = $request->file('image');
            $imagePath = $file->getPathname();
            $imageName = $file->getClientOriginalName() ?? 'captura.jpg';

            $response = \Illuminate\Support\Facades\Http::timeout(15)->attach(
                'file', file_get_contents($imagePath), $imageName
            )->post('http://127.0.0.1:8000/scan');

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['plates_found']) && $data['plates_found'] > 0) {
                    return response()->json([
                        'success' => true,
                        'plate' => $data['plates'][0]['plate_text'],
                        'confidence' => $data['plates'][0]['ocr_confidence']
                    ]);
                }

                return response()->json(['success' => false, 'error' => 'La IA no detectó ninguna placa legible en la foto.'], 404);
            }

            return response()->json(['success' => false, 'error' => 'El servidor Python rechazó la solicitud (Error HTTP).'], $response->status());

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => 'Crash interno en Laravel: ' . $th->getMessage() . ' (Línea ' . $th->getLine() . ')'
            ], 500);
        }
    }

    /**
     * BUSCAR CLIENTE POR CÉDULA (Para autocompletado en Dispensadora)
     */
    public function searchClientByDocument($document)
    {
        // Buscamos el registro más reciente con esa cédula/RIF
        $client = \App\Models\Client::where('document', $document)->latest()->first();

        if ($client) {
            return response()->json([
                'success' => true,
                'name' => $client->name,
                'phone' => $client->phone
            ]);
        }

        return response()->json(['success' => false]);
    }

}
