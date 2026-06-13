<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function index()
    {
        // Traemos todas las facturas con sus relaciones (Cliente, Ticket y Cajero)
        // Ordenadas de la más reciente a la más antigua
        $invoices = Invoice::with(['ticket.client', 'user', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalInvoices = $invoices->count();
        $totalRevenue = $invoices->sum('total_amount');

        return view('admin.records.index', compact('invoices', 'totalInvoices', 'totalRevenue'));
    }
}
