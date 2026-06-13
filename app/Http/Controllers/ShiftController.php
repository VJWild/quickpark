<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use TCPDF;

class ShiftController extends Controller
{
    // =========================================================================
    // LISTADO DE TURNOS PARA EL ADMINISTRADOR (AUDITORÍA)
    // =========================================================================
    public function index()
    {
        // Traemos todos los turnos ordenados del más reciente al más antiguo
        $shifts = Shift::with('user')->orderBy('id', 'desc')->get();

        return view('admin.shifts.index', compact('shifts'));
    }

    // ABRIR CAJA
    public function openShift(Request $request)
    {
        $request->validate(['opening_amount' => 'required|numeric|min:0']);

        if (Shift::where('user_id', Auth::id())->where('status', 'ABIERTO')->exists()) {
            return back()->withErrors(['error' => 'Ya tienes un turno abierto en este momento.']);
        }

        Shift::create([
            'user_id' => Auth::id(),
            'start_time' => now(),
            'opening_amount' => $request->opening_amount,
            'status' => 'ABIERTO'
        ]);

        return back()->with('success', 'Caja abierta exitosamente.');
    }

    // CERRAR CAJA (CON DESGLOSE)
    public function closeShift(Request $request)
    {
        // Validamos que envíe el array de declaraciones
        $request->validate([
            'declared' => 'required|array',
            'declared.*' => 'required|numeric|min:0'
        ]);

        $shift = Shift::where('user_id', Auth::id())->where('status', 'ABIERTO')->first();

        if (!$shift) {
            return back()->withErrors(['error' => 'No tienes ningún turno abierto para cerrar.']);
        }

        // 1. Obtenemos todas las facturas y pagos de este turno
        $invoicesIds = Invoice::where('shift_id', $shift->id)->pluck('id');
        $payments = InvoicePayment::whereIn('invoice_id', $invoicesIds)->get();

        // 2. Sumamos lo que dice el sistema por cada método
        $systemDetails = [
            'Efectivo' => $payments->where('method', 'Efectivo')->sum('amount'),
            'Tarjeta' => $payments->where('method', 'Tarjeta')->sum('amount'),
            'Pago Móvil' => $payments->where('method', 'Pago Móvil')->sum('amount'),
            'Divisas' => $payments->where('method', 'Divisas')->sum('amount'),
        ];

        $declaredDetails = $request->declared;
        $differencesDetails = [];

        $totalSystemSales = 0;
        $totalDeclared = 0;

        // 3. Calculamos la diferencia por cada método
        foreach (['Efectivo', 'Tarjeta', 'Pago Móvil', 'Divisas'] as $method) {
            $sys = $systemDetails[$method];

            // OJO: El fondo de caja inicial SIEMPRE es Efectivo físico
            if ($method === 'Efectivo') {
                $sys += $shift->opening_amount;
            }

            $dec = floatval($declaredDetails[$method] ?? 0);

            // Faltante será negativo, Sobrante será positivo
            $differencesDetails[$method] = $dec - $sys;

            // Para el total, la venta neta del sistema no incluye el fondo base
            $totalSystemSales += $systemDetails[$method];
            $totalDeclared += $dec;
        }

        // Total del sistema incluyendo el fondo de caja
        $totalExpected = $totalSystemSales + $shift->opening_amount;

        // 4. Cerramos el turno
        $shift->update([
            'end_time' => now(),
            'system_amount' => $totalSystemSales, // Solo las ventas puras
            'system_details' => $systemDetails,
            'declared_amount' => $totalDeclared,
            'declared_details' => $declaredDetails,
            'difference' => $totalDeclared - $totalExpected,
            'differences_details' => $differencesDetails,
            'status' => 'CERRADO'
        ]);

        return back()
            ->with('success', 'Turno cerrado. Imprimiendo Reporte...')
            ->with('print_z_report_url', route('shifts.printZ', $shift->id));
    }

// =========================================================================
    // IMPRESIÓN TCPDF: REPORTE Z (DECLARACIÓN DE CAJA DEL CAJERO)
    // =========================================================================
    public function printZReport(Shift $shift)
    {
        try {
            $shift->load('user');

            // Carga segura y protección Anti-Null para cadenas UTF-8
            $info = \App\Models\Information::first();
            $rateRecord = \App\Models\ExchangeRate::first();

            $companyName = $info->company_name ?? 'QUICKPARK C.A.';
            $activity = $info->activity ?? 'Servicio de Estacionamiento';
            $branch = $info->branch ?? 'SUCURSAL No 1';
            $phone = $info->phone ? 'TELÉFONO: ' . $info->phone : '';
            $locationArray = array_filter([$info->address ?? '', $info->zone ?? '', $info->city ?? '', $info->country ?? '']);
            $location = implode(', ', $locationArray);
            $tasa = $rateRecord ? (float)$rateRecord->rate : 1.00;

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(79, 210), true, 'UTF-8', false);
            $pdf->setCreator(PDF_CREATOR);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->setMargins(2, 2, 5);
            $pdf->setAutoPageBreak(true, 5);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 7);

            // Refuerzo extremo para lectura de arrays JSON
            $sysData = is_string($shift->system_details) ? json_decode($shift->system_details, true) : $shift->system_details;
            $decData = is_string($shift->declared_details) ? json_decode($shift->declared_details, true) : $shift->declared_details;
            $diffData = is_string($shift->differences_details) ? json_decode($shift->differences_details, true) : $shift->differences_details;

            $system_details = is_array($sysData) ? $sysData : [];
            $declared_details = is_array($decData) ? $decData : [];
            $differences_details = is_array($diffData) ? $diffData : [];

            $html = '
            <div style="text-align: left">
                <p style="text-align: center">
                    <img src="' . public_path('img/minimalist--futuristic--quickpark-letter.svg.png') . '" width="130" /> <br>                    <span style="font-size: 7pt; color: #555;">' . mb_strtoupper($activity, 'UTF-8') . '</span> <br>
                    <span style="font-size: 7pt;">' . mb_strtoupper($branch, 'UTF-8') . ' | ' . $phone . '</span><br>
                    <span style="font-size: 7pt;">' . mb_strtoupper($location, 'UTF-8') . '</span> <br>
                    --------------------------------------------------------------------------------<br>
                    <b style="font-size: large;">REPORTE DE CAJA (CIERRE Z)</b><br>
                    --------------------------------------------------------------------------------
                </p>
                <b>Turno Nº: </b> ' . $shift->id . '<br>
                <b>Cajero: </b> ' . ($shift->user->name ?? 'Desconocido') . ' <br>
                <b>Apertura: </b> ' . \Carbon\Carbon::parse($shift->start_time)->format('d/m/Y h:i A') . ' <br>
                <b>Cierre: </b> ' . ($shift->end_time ? \Carbon\Carbon::parse($shift->end_time)->format('d/m/Y h:i A') : 'EN CURSO') . ' <br>
                <b>Tasa Aplicada: </b> Bs ' . number_format($tasa, 2) . '<br>
                --------------------------------------------------------------------------------<br>
                <p style="text-align: center"><b>DESGLOSE DE ARQUEO ($)</b></p>
                <table border="1" cellpadding="2">
                    <tr style="background-color: #e0e0e0;">
                        <td width="48px"><b>Método</b></td>
                        <td width="34px" style="text-align:center"><b>Sis.</b></td>
                        <td width="34px" style="text-align:center"><b>Decl.</b></td>
                        <td width="34px" style="text-align:center"><b>Dif.</b></td>
                    </tr>';

            foreach (['Efectivo', 'Tarjeta', 'Pago Móvil', 'Divisas'] as $method) {
                $sys = isset($system_details[$method]) ? (float)$system_details[$method] : 0;
                if($method === 'Efectivo') $sys += (float)$shift->opening_amount;

                $dec = isset($declared_details[$method]) ? (float)$declared_details[$method] : 0;
                $dif = isset($differences_details[$method]) ? (float)$differences_details[$method] : 0;

                // AQUÍ ESTABA EL ERROR. Quitamos el substr y dejamos el nombre completo del método.
                $html .= '<tr>
                            <td>' . $method . '</td>
                            <td style="text-align:right">' . number_format($sys, 1) . '</td>
                            <td style="text-align:right">' . number_format($dec, 1) . '</td>
                            <td style="text-align:right">' . number_format($dif, 1) . '</td>
                          </tr>';
            }

            $html .= '
                </table>
                <br>
                --------------------------------------------------------------------------------<br>
                <p style="text-align: right">
                    <b>Total Diferencia ($): </b>$ ' . number_format((float)$shift->difference, 2) . '<br>
                    <b>Total Diferencia (Bs): </b>Bs ' . number_format((float)$shift->difference * $tasa, 2) . '
                </p>
                --------------------------------------------------------------------------------<br>
                <br><br><br>
                <p style="text-align: center">
                    __________________________________<br>
                    Firma del Cajero
                </p>
                <br><br>
                <p style="text-align: center; font-size: 7px; color: #333;">POWERED BY <b>EVG DEV STUDIO</b></p>
            </div>';

            $pdf->writeHTML($html, true, false, true, false, '');

            while (ob_get_level()) { ob_end_clean(); }

            $pdf->Output('Cierre_Z_'.$shift->id.'.pdf', 'I');
            exit;

        } catch (\Exception $e) {
            dd("Error Fatal generando el Reporte Z. Detalles: " . $e->getMessage() . " en la línea " . $e->getLine());
        }
    }

    // =========================================================================
    // IMPRESIÓN TCPDF: RESUMEN DE VENTAS (AUDITORÍA DEL SISTEMA)
    // =========================================================================
    public function printSalesSummary(Shift $shift)
    {
        try {
            $shift->load('user');

            $info = \App\Models\Information::first();
            $rateRecord = \App\Models\ExchangeRate::first();

            $companyName = $info->company_name ?? 'QUICKPARK C.A.';
            $activity = $info->activity ?? 'Servicio de Estacionamiento';
            $branch = $info->branch ? 'SUCURSAL ' . $info->branch : 'SUCURSAL No 1';
            $phone = $info->phone ? 'TELÉFONO: ' . $info->phone : '';
            $locationArray = array_filter([$info->address ?? '', $info->zone ?? '', $info->city ?? '', $info->country ?? '']);
            $location = implode(', ', $locationArray);
            $tasa = $rateRecord ? (float)$rateRecord->rate : 1.00;

            // Obtener todas las facturas del turno para hacer el conteo
            $invoices = Invoice::where('shift_id', $shift->id)->get();
            $totalInvoices = $invoices->count();

            // Calculamos exoneradas (Monto 0) y cobradas
            $exoneratedCount = $invoices->where('total_amount', 0)->count();
            $paidCount = $totalInvoices - $exoneratedCount;

            // Altura reducida a 180 porque ya no es una lista larga
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(79, 180), true, 'UTF-8', false);
            $pdf->setCreator(PDF_CREATOR);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->setMargins(2, 2, 5);
            $pdf->setAutoPageBreak(true, 5);
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 7);

            $sysData = is_string($shift->system_details) ? json_decode($shift->system_details, true) : $shift->system_details;
            $system_details = is_array($sysData) ? $sysData : [];

            $html = '
            <div style="text-align: left">
                <p style="text-align: center">
                    <img src="' . public_path('img/minimalist--futuristic--quickpark-letter.svg.png') . '" width="130" /> <br>                    <span style="font-size: 7pt; color: #555;">' . mb_strtoupper($activity, 'UTF-8') . '</span> <br>
                    <span style="font-size: 7pt;">' . mb_strtoupper($branch, 'UTF-8') . ' | ' . $phone . '</span><br>
                    <span style="font-size: 7pt;">' . mb_strtoupper($location, 'UTF-8') . '</span> <br>
                    --------------------------------------------------------------------------------<br>
                    <b style="font-size: large;">RESUMEN DE VENTAS</b><br>
                    --------------------------------------------------------------------------------
                </p>
                <b>Turno Nº: </b> ' . $shift->id . '<br>
                <b>Cajero: </b> ' . $shift->user->name . ' <br>
                <b>Apertura: </b> ' . \Carbon\Carbon::parse($shift->start_time)->format('d/m/Y h:i A') . ' <br>
                <b>Cierre: </b> ' . ($shift->end_time ? \Carbon\Carbon::parse($shift->end_time)->format('d/m/Y h:i A') : 'EN CURSO') . ' <br>
                <b>Tasa Aplicada: </b> Bs ' . number_format($tasa, 2) . '<br>
                --------------------------------------------------------------------------------<br>
                <p style="text-align: center"><b>INGRESOS POR MÉTODO ($)</b></p>
                <table border="1" cellpadding="2">
                    <tr style="background-color: #e0e0e0;">
                        <td width="70px"><b>Método</b></td>
                        <td width="45px" style="text-align:right"><b>Total $</b></td>
                    </tr>';

            $totalVentas = 0;
            if (!empty($system_details)) {
                foreach ($system_details as $metodo => $monto) {
                    $html .= '<tr>
                                <td>' . $metodo . '</td>
                                <td style="text-align:right">$ ' . number_format((float)$monto, 2) . '</td>
                              </tr>';
                    $totalVentas += (float)$monto;
                }
            } else {
                $html .= '<tr><td colspan="2" style="text-align:center">Turno sin ventas</td></tr>';
            }

            $html .= '
                </table>
                <br>
                --------------------------------------------------------------------------------<br>
                <p style="text-align: right">
                    <b>Total Facturado ($): </b>$ ' . number_format($totalVentas, 2) . '<br>
                    <b>Total Facturado (Bs): </b>Bs ' . number_format($totalVentas * $tasa, 2) . '
                </p>
                --------------------------------------------------------------------------------<br>
                <p style="text-align: center"><b>RESUMEN DE OPERACIONES</b></p>
                <table border="0" cellpadding="1">
                    <tr><td width="70%">Facturas Cobradas:</td><td width="30%" style="text-align:right"><b>' . $paidCount . '</b></td></tr>
                    <tr><td width="70%">Facturas Exoneradas:</td><td width="30%" style="text-align:right"><b>' . $exoneratedCount . '</b></td></tr>
                    <tr style="border-top: 1px solid #000;"><td width="70%"><b>Total de Operaciones:</b></td><td width="30%" style="text-align:right"><b>' . $totalInvoices . '</b></td></tr>
                </table>
                --------------------------------------------------------------------------------<br>
                <p style="text-align: center"><i>Fin del Reporte</i></p>
                <br>
                <p style="text-align: center; font-size: 7px; color: #333;">POWERED BY <b>EVG DEV STUDIO</b></p>
            </div>';

            $pdf->writeHTML($html, true, false, true, false, '');

            while (ob_get_level()) { ob_end_clean(); }

            $pdf->Output('Ventas_Turno_'.$shift->id.'.pdf', 'I');
            exit;

        } catch (\Exception $e) {
            dd("Error Fatal generando el Resumen de Ventas. Detalles: " . $e->getMessage() . " en la línea " . $e->getLine());
        }
    }

}
