@extends('layouts.admin')
@section('title', 'Historial de Registros')

@section('admin_content')
    <div x-data="recordsManager()" class="flex flex-col h-full relative">

        <header class="h-24 glass border-b border-white/50 px-8 flex items-center justify-between flex-shrink-0 z-10">
            <div>
                <h1 class="font-poppins text-2xl font-bold text-slate-900">Historial de Operaciones</h1>
                <p class="text-sm font-medium text-slate-500">Registro de facturación y movimientos</p>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-slate-50/50 relative">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-400/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 pointer-events-none"></div>

            <div class="max-w-7xl mx-auto space-y-8 relative z-10">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="glass p-6 rounded-[2rem] border border-white/80 shadow-[0_8px_30px_rgba(15,23,42,0.04)] flex items-center gap-5 transition-transform hover:-translate-y-1 duration-300">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30 shrink-0">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tickets Facturados</span>
                            <span class="block text-3xl font-poppins font-black text-slate-800 mt-1 leading-none">{{ $totalInvoices }}</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-500 to-green-600 p-6 rounded-[2rem] border border-green-400 shadow-xl shadow-green-500/20 flex items-center gap-5 text-white transition-transform hover:-translate-y-1 duration-300">
                        <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shadow-inner shrink-0 border border-white/30">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-green-100 uppercase tracking-widest">Ingresos Totales</span>
                            <span class="block text-3xl font-poppins font-black mt-1 leading-none">${{ number_format($totalRevenue, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <div class="relative w-full sm:w-[28rem] group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-qp-indigo transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" x-model="search" placeholder="Buscar por Nro de Factura, Placa o Cliente..."
                               class="w-full pl-12 pr-10 py-4 bg-white/60 backdrop-blur-md border border-white/80 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-qp-indigo transition-all font-semibold text-slate-700 shadow-[0_8px_30px_rgba(15,23,42,0.04)] placeholder-slate-400 outline-none">

                        <button type="button" x-show="search !== ''" @click="search = ''" style="display: none;" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-red-500 transition-colors focus:outline-none">
                            <svg class="h-5 w-5 bg-slate-100 rounded-full p-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <div class="bg-white/20 backdrop-blur-xl rounded-[2.5rem] border border-white/60 shadow-[0_20px_50px_rgba(15,23,42,0.04)] p-6 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                            <tr class="text-slate-400">
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest pl-6">Factura / Ticket</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehículo</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Fechas</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cobro</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right pr-6">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($invoices as $invoice)
                                <tr x-show="matchesSearch('{{ $invoice->invoice_number }}', '{{ $invoice->ticket->client->plate }}', '{{ $invoice->ticket->client->name }}')"
                                    x-transition.opacity.duration.300ms
                                    class="group bg-white/40 backdrop-blur-md hover:bg-white hover:shadow-[0_12px_30px_rgba(79,70,229,0.08)] hover:-translate-y-1 transition-all duration-300 transform">

                                    <td class="p-4 pl-6 rounded-l-2xl transition-colors duration-300">
                                        <span class="block text-sm font-black text-indigo-600 tracking-wide">{{ $invoice->invoice_number }}</span>
                                        <span class="block text-[11px] font-bold text-slate-400 mt-0.5 uppercase tracking-wider">Cajero: {{ $invoice->user->name }}</span>
                                    </td>

                                    <td class="p-4 transition-colors duration-300">
                                            <span class="inline-flex px-3 py-1 rounded-lg text-xs font-black bg-slate-800 text-white tracking-widest uppercase mb-1 shadow-sm border border-slate-700">
                                                {{ $invoice->ticket->client->plate }}
                                            </span>
                                        <span class="block text-sm font-bold text-slate-700">{{ $invoice->ticket->client->name }}</span>
                                    </td>

                                    <td class="p-4 text-xs font-semibold text-slate-600 transition-colors duration-300">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <div class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_5px_rgba(34,197,94,0.6)]"></div>
                                            {{ \Carbon\Carbon::parse($invoice->ticket->entry_time)->format('d/m/Y h:i A') }}
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-red-500 shadow-[0_0_5px_rgba(239,68,68,0.6)]"></div>
                                            {{ \Carbon\Carbon::parse($invoice->exit_time)->format('d/m/Y h:i A') }}
                                        </div>
                                    </td>

                                    <td class="p-4 transition-colors duration-300">
                                        <span class="block text-xl font-poppins font-black text-slate-800 leading-none">${{ number_format($invoice->total_amount, 2) }}</span>
                                        <div class="flex flex-wrap gap-1.5 mt-2">
                                            @foreach($invoice->payments as $payment)
                                                <span class="text-[9px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-600 border border-indigo-100" title="${{ $payment->amount }}">
                                                        {{ $payment->method }}
                                                    </span>
                                            @endforeach
                                        </div>
                                    </td>

                                    <td class="p-4 text-right pr-6 rounded-r-2xl transition-colors duration-300">
                                        <a href="{{ route('invoices.print', $invoice->id) }}" target="_blank" class="inline-flex items-center justify-center p-2.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all focus:outline-none shadow-sm border border-transparent hover:border-slate-700" title="Imprimir Copia">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-16 text-center bg-white/20 backdrop-blur-md rounded-3xl border border-dashed border-slate-200">
                                        <div class="inline-flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12 mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            <span class="font-bold text-lg text-slate-600">No hay facturas</span>
                                            <span class="text-sm font-medium mt-1">Aún no se han registrado operaciones en el sistema.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            @if($invoices->count() > 0)
                                <tr x-show="!hasResults()" style="display: none;">
                                    <td colspan="5" class="p-16 text-center bg-white/20 backdrop-blur-md rounded-3xl border border-dashed border-slate-200 mt-4">
                                        <div class="inline-flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12 mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <span class="font-bold text-lg text-slate-600">No hay coincidencias</span>
                                            <span class="text-sm font-medium mt-1">Intenta con otro número de factura, placa o cliente.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function recordsManager() {
            return {
                search: '',

                matchesSearch(invoice, plate, name) {
                    if (this.search.trim() === '') return true;
                    const term = this.search.toLowerCase();
                    return invoice.toLowerCase().includes(term) ||
                        plate.toLowerCase().includes(term) ||
                        name.toLowerCase().includes(term);
                },

                hasResults() {
                    if (this.search.trim() === '') return true;
                    const rows = document.querySelectorAll('tbody tr.group');
                    let found = false;
                    rows.forEach(row => {
                        if(row.style.display !== 'none') found = true;
                    });
                    return found;
                }
            }
        }
    </script>
@endsection
