@extends('layouts.admin')
@section('title', 'Directorio de Clientes')

@section('admin_content')
    <div x-data="clientManager()" class="flex flex-col h-full relative">

        @if(session('success'))
            <div class="fixed top-28 left-1/2 -translate-x-1/2 z-[100] bg-slate-900/95 backdrop-blur-xl border border-slate-700 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3"
                 x-data="{ show: true }"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 x-init="setTimeout(() => show = false, 4000)">
                <div class="w-8 h-8 bg-green-500/20 text-green-400 rounded-full flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="font-bold text-sm tracking-wide">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="fixed top-28 left-1/2 -translate-x-1/2 z-[100] bg-red-900/95 backdrop-blur-xl border border-red-700 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3"
                 x-data="{ show: true }"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 x-init="setTimeout(() => show = false, 5000)">
                <div class="w-8 h-8 bg-red-500/20 text-red-400 rounded-full flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="font-bold text-sm tracking-wide">{{ $errors->first() }}</span>
            </div>
        @endif

        <header class="h-24 glass border-b border-white/50 px-8 flex items-center justify-between flex-shrink-0 z-10">
            <div>
                <h1 class="font-poppins text-2xl font-bold text-slate-900">Directorio de Clientes</h1>
                <p class="text-sm font-medium text-slate-500">Gestión de conductores e historial de matrículas</p>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-slate-50/50 relative">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-400/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 pointer-events-none"></div>

            <div class="max-w-7xl mx-auto space-y-8 relative z-10">

                <div class="flex flex-col sm:flex-row justify-between items-end gap-6">

                    <div class="glass px-6 py-4 rounded-2xl flex items-center gap-4 shadow-[0_8px_30px_rgba(15,23,42,0.04)] border border-white/80 w-full sm:w-auto">
                        <div class="w-12 h-12 bg-gradient-to-br from-qp-blue to-qp-indigo rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 shrink-0 transform -rotate-3">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Registrados</span>
                            <span class="block text-2xl font-poppins font-black text-slate-800 leading-none mt-1">{{ $totalClients }}</span>
                        </div>
                    </div>

                    <div class="relative w-full sm:w-96 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-qp-indigo transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" x-model="search" placeholder="Buscar por placa, cédula o nombre..."
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
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest pl-6">Placa</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cliente</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Documento / CI</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Teléfono</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right pr-6">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $client)
                                <tr x-show="matchesSearch('{{ $client->plate }}', '{{ $client->name }}', '{{ $client->document }}')"
                                    x-transition.opacity.duration.300ms
                                    class="group bg-white/40 backdrop-blur-md hover:bg-white hover:shadow-[0_12px_30px_rgba(79,70,229,0.08)] hover:-translate-y-1 transition-all duration-300 transform">

                                    <td class="p-4 pl-6 rounded-l-2xl transition-colors duration-300">
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-sm font-black bg-slate-800 text-white tracking-widest uppercase shadow-sm border border-slate-700">
                                                {{ $client->plate }}
                                            </span>
                                    </td>

                                    <td class="p-4 transition-colors duration-300">
                                        <span class="font-bold text-slate-800">{{ $client->name }}</span>
                                    </td>

                                    <td class="p-4 transition-colors duration-300">
                                        <span class="font-semibold text-slate-500 bg-slate-100/50 px-3 py-1 rounded-lg border border-slate-200/50">{{ $client->document }}</span>
                                    </td>

                                    <td class="p-4 font-medium text-slate-500 transition-colors duration-300">
                                        {{ $client->phone ?? 'Sin registrar' }}
                                    </td>

                                    <td class="p-4 text-right pr-6 rounded-r-2xl transition-colors duration-300">
                                        <button @click="openEditModal({{ $client }})" class="p-2 text-slate-400 hover:text-qp-indigo hover:bg-indigo-50 rounded-xl transition-all focus:outline-none shadow-sm border border-transparent hover:border-indigo-100" title="Editar Cliente">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                            <tr x-show="!hasResults()" style="display: none;">
                                <td colspan="5" class="p-16 text-center bg-white/20 backdrop-blur-md rounded-3xl border border-dashed border-slate-200">
                                    <div class="inline-flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-12 h-12 mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span class="font-bold text-lg text-slate-600">No hay coincidencias</span>
                                        <span class="text-sm font-medium mt-1">Intenta con otra placa, nombre o documento.</span>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md" x-transition.opacity>
            <div class="relative w-full max-w-lg p-4" @click.away="showEditModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] border border-indigo-100/50 shadow-2xl p-8 sm:p-10">

                    <header class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner shrink-0">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-poppins font-black text-slate-800 tracking-tight">Editar Cliente</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Actualización de datos maestros</p>
                        </div>
                    </header>

                    <form method="POST" :action="`/clientes/${editData.id}`" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nombre Completo <span class="text-red-500">*</span></label>
                                <input type="text" name="name" x-model="editData.name" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-qp-indigo transition-all font-semibold text-slate-800 outline-none">
                            </div>

                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Cédula / NIT <span class="text-red-500">*</span></label>
                                    <input type="text" name="document" x-model="editData.document" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-qp-indigo transition-all font-semibold text-slate-800 outline-none">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Matrícula <span class="text-red-500">*</span></label>
                                    <input type="text" name="plate" x-model="editData.plate" required style="text-transform: uppercase" class="w-full px-4 py-3 bg-slate-800 border border-slate-700 rounded-xl focus:ring-2 focus:ring-qp-indigo transition-all font-black text-white uppercase tracking-widest outline-none shadow-inner">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Teléfono</label>
                                <input type="text" name="phone" x-model="editData.phone" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-qp-indigo transition-all font-semibold text-slate-800 outline-none">
                            </div>
                        </div>

                        <div class="pt-4 flex flex-col sm:flex-row-reverse gap-3 justify-end">
                            <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-qp-blue to-qp-indigo text-white font-black rounded-2xl shadow-xl shadow-indigo-500/30 transform hover:-translate-y-1 transition-all uppercase tracking-widest text-sm text-center">
                                Guardar Cambios
                            </button>
                            <button type="button" @click="showEditModal = false" class="w-full sm:w-auto px-8 py-4 text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-2xl transition-colors text-center">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function clientManager() {
            return {
                search: '',
                showEditModal: false,
                editData: { id: '', name: '', document: '', plate: '', phone: '' },

                matchesSearch(plate, name, document) {
                    if (this.search.trim() === '') return true;
                    const term = this.search.toLowerCase();
                    return plate.toLowerCase().includes(term) ||
                        name.toLowerCase().includes(term) ||
                        document.toLowerCase().includes(term);
                },

                hasResults() {
                    if (this.search.trim() === '') return true;
                    const rows = document.querySelectorAll('tbody tr.group');
                    let found = false;
                    rows.forEach(row => {
                        if(row.style.display !== 'none') found = true;
                    });
                    return found;
                },

                openEditModal(client) {
                    this.editData = {
                        id: client.id,
                        name: client.name,
                        document: client.document,
                        plate: client.plate,
                        phone: client.phone || ''
                    };
                    this.showEditModal = true;
                }
            }
        }
    </script>
@endsection
