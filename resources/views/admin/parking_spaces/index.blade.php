@extends('layouts.admin')
@section('title', 'Gestión de Puestos')

@section('admin_content')
    <div x-data="spaceManager({{ $totalSpaces }})" class="flex flex-col h-full relative">

        @if(session('success'))
            <div class="fixed top-28 left-1/2 -translate-x-1/2 z-[100] bg-slate-900/95 backdrop-blur-xl border border-slate-700 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3"
                 x-data="{ show: true }" x-show="show"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-4"
                 x-init="setTimeout(() => show = false, 4000)">
                <div class="w-8 h-8 bg-green-500/20 text-green-400 rounded-full flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="font-bold text-sm tracking-wide">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="fixed top-28 left-1/2 -translate-x-1/2 z-[100] bg-red-900/95 backdrop-blur-xl border border-red-700 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3"
                 x-data="{ show: true }" x-show="show"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-4"
                 x-init="setTimeout(() => show = false, 5000)">
                <div class="w-8 h-8 bg-red-500/20 text-red-400 rounded-full flex items-center justify-center shrink-0 shadow-inner">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="font-bold text-sm tracking-wide">{{ $errors->first() }}</span>
            </div>
        @endif
        <header class="h-24 glass border-b border-white/50 px-8 flex items-center justify-between flex-shrink-0 z-10">
            <div>
                <h1 class="font-poppins text-2xl font-bold text-slate-900">Capacidad del Parqueo</h1>
                <p class="text-sm font-medium text-slate-500">Gestión masiva e individual de espacios físicos</p>
            </div>
            <div class="flex items-center gap-3">
                <button x-show="selectedSpaces.length > 0" @click="openBulkDeleteModal()" x-transition class="bg-red-500 text-white font-bold text-sm px-5 py-3 rounded-xl shadow-lg shadow-red-500/30 hover:bg-red-600 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Eliminar (<span x-text="selectedSpaces.length"></span>)
                </button>
                <button @click="openCreateModal()" class="bg-gradient-to-r from-qp-blue to-qp-indigo text-white font-bold text-sm px-5 py-3 rounded-xl shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Nuevo Puesto
                </button>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar relative bg-slate-50/50">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-400/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 pointer-events-none"></div>

            <div class="max-w-5xl mx-auto space-y-8 relative z-10">

                <div class="glass px-6 py-4 rounded-2xl flex items-center justify-between shadow-[0_8px_30px_rgba(15,23,42,0.04)] border border-white/80 max-w-sm">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Capacidad Total</span>
                        <span class="block text-2xl font-poppins font-black text-slate-800 leading-none mt-1">{{ $totalSpaces }} Puestos</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-indigo-200 text-indigo-600 flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    </div>
                </div>

                <div class="bg-white/20 backdrop-blur-xl rounded-[2.5rem] border border-white/60 shadow-[0_20px_50px_rgba(15,23,42,0.04)] p-6 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-3">
                            <thead>
                            <tr class="text-slate-400">
                                <th class="pb-3 text-center w-12 pl-4">
                                    <input type="checkbox" @change="toggleAll($event)" :checked="selectedSpaces.length === allSpaceIds.length && allSpaceIds.length > 0" class="w-4 h-4 rounded text-qp-indigo focus:ring-qp-indigo border-slate-300">
                                </th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nro</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Identificador</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Estado Actual</th>
                                <th class="pb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right pr-6">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($spaces as $index => $space)
                                <tr class="group bg-white/40 backdrop-blur-md hover:bg-white hover:shadow-[0_12px_30px_rgba(79,70,229,0.08)] hover:-translate-y-1 transition-all duration-300 transform">

                                    <td class="p-4 pl-4 text-center rounded-l-2xl transition-colors duration-300">
                                        <input type="checkbox" value="{{ $space->id }}" x-model="selectedSpaces" class="w-4 h-4 rounded text-qp-indigo focus:ring-qp-indigo border-slate-300 transition-all cursor-pointer">
                                    </td>

                                    <td class="p-4 text-sm font-bold text-slate-400 transition-colors duration-300">{{ $index + 1 }}</td>

                                    <td class="p-4 transition-colors duration-300">
                                            <span class="inline-flex px-4 py-1.5 rounded-xl text-sm font-black bg-slate-800 text-white tracking-widest uppercase shadow-sm border border-slate-700">
                                                {{ $space->space_number }}
                                            </span>
                                    </td>

                                    <td class="p-4 transition-colors duration-300">
                                        @if($space->status == 'DISPONIBLE')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-green-50 border border-green-100 text-green-600">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_5px_rgba(34,197,94,0.6)]"></span> LIBRE
                                                </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-red-50 border border-red-100 text-red-600">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 shadow-[0_0_5px_rgba(239,68,68,0.6)]"></span> OCUPADO
                                                </span>
                                        @endif
                                    </td>

                                    <td class="p-4 text-right pr-6 rounded-r-2xl transition-colors duration-300">
                                        <button @click="openDeleteModal({{ $space->id }}, '{{ $space->space_number }}')" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all focus:outline-none shadow-sm border border-transparent hover:border-red-100" title="Eliminar Puesto">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-16 text-center bg-white/20 backdrop-blur-md rounded-3xl border border-dashed border-slate-200">
                                        <div class="inline-flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12 mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                                            <span class="font-bold text-lg text-slate-600">Aún no hay puestos</span>
                                            <span class="text-sm font-medium mt-1">Genera la estructura inicial de tu estacionamiento.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md" x-transition.opacity>
            <div class="relative w-full max-w-md p-4" @click.away="showCreateModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] border border-indigo-100/50 shadow-2xl p-8 sm:p-10">

                    <header class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner shrink-0">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-poppins font-black text-slate-800 tracking-tight">Generar Puestos</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Creación individual o masiva</p>
                        </div>
                    </header>

                    <form method="POST" action="{{ route('parking.spaces.store') }}" class="space-y-5">
                        @csrf

                        <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Prefijo (Opcional)</label>
                                <input type="text" name="prefix" x-model="formData.prefix" placeholder="Ej: VIP-, A-" style="text-transform: uppercase" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-qp-indigo transition-all font-bold text-slate-800 uppercase outline-none placeholder:normal-case">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Iniciar en <span class="text-red-500">*</span></label>
                                    <input type="number" min="1" name="start_number" x-model.number="formData.start" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-qp-indigo transition-all font-black text-slate-800 outline-none">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Cantidad <span class="text-red-500">*</span></label>
                                    <input type="number" min="1" max="100" name="quantity" x-model.number="formData.quantity" required class="w-full px-4 py-3 bg-indigo-50 border border-indigo-100 rounded-xl focus:ring-2 focus:ring-qp-indigo transition-all font-black text-indigo-700 outline-none">
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex items-center justify-between">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Vista Previa:</span>
                            <span class="font-black text-sm text-slate-700 tracking-wider bg-white px-3 py-1 rounded-md border border-slate-200" x-text="previewSequence()"></span>
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row-reverse gap-3 justify-end">
                            <button type="submit" class="w-full sm:w-auto px-6 py-3.5 bg-gradient-to-r from-qp-blue to-qp-indigo text-white font-black rounded-xl shadow-xl shadow-indigo-500/30 transform hover:-translate-y-1 transition-all uppercase tracking-widest text-xs text-center">
                                Agregar
                            </button>
                            <button type="button" @click="showCreateModal = false" class="w-full sm:w-auto px-6 py-3.5 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors text-center">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md" x-transition.opacity>
            <div class="relative w-full max-w-sm p-4" @click.away="showDeleteModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <div class="bg-white/90 backdrop-blur-xl rounded-[2.5rem] border border-red-100 shadow-2xl p-8 text-center">
                    <div class="w-16 h-16 mx-auto bg-red-100 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                        <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>

                    <h3 class="text-xl font-poppins font-black text-slate-900 tracking-tight mb-2" x-text="deleteMode === 'bulk' ? '¿Eliminar Puestos?' : '¿Eliminar Puesto?'"></h3>

                    <p class="text-xs font-medium text-slate-500 mb-6 px-4">
                        <span x-show="deleteMode === 'single'">El puesto <strong class="text-slate-800 font-bold" x-text="singleDeleteData.number"></strong> será removido del sistema.</span>
                        <span x-show="deleteMode === 'bulk'">Estás a punto de eliminar <strong class="text-slate-800 font-bold" x-text="selectedSpaces.length"></strong> puestos seleccionados.</span>
                        Esta acción no se puede deshacer.
                    </p>

                    <form method="POST" :action="deleteMode === 'bulk' ? '{{ route('parking.spaces.bulkDestroy') }}' : `/puestos/${singleDeleteData.id}`" class="flex flex-col gap-3">
                        @csrf
                        <template x-if="deleteMode === 'single'">
                            <input type="hidden" name="_method" value="DELETE">
                        </template>

                        <template x-if="deleteMode === 'bulk'">
                            <input type="hidden" name="ids" :value="selectedSpaces.join(',')">
                        </template>

                        <button type="submit" class="w-full px-5 py-3.5 text-xs font-black text-white bg-red-500 hover:bg-red-600 rounded-xl shadow-lg shadow-red-500/30 uppercase tracking-widest transition-all hover:-translate-y-0.5">
                            Sí, Eliminar
                        </button>
                        <button type="button" @click="showDeleteModal = false" class="w-full px-5 py-3.5 text-xs font-bold text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                            Cancelar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function spaceManager(totalCurrentSpaces) {
            return {
                showCreateModal: false,
                showDeleteModal: false,

                // Variables de Selección
                allSpaceIds: @json($spaces->pluck('id')),
                selectedSpaces: [],

                // Variables del Modal
                deleteMode: 'single', // 'single' o 'bulk'
                singleDeleteData: { id: '', number: '' },

                // Variables de Creación
                formData: {
                    prefix: '',
                    start: totalCurrentSpaces + 1, // Sigue la secuencia matemáticamente
                    quantity: 1
                },

                // Lógica de "Seleccionar Todos"
                toggleAll(event) {
                    if (event.target.checked) {
                        this.selectedSpaces = [...this.allSpaceIds];
                    } else {
                        this.selectedSpaces = [];
                    }
                },

                // Lógica de Vista Previa
                previewSequence() {
                    let pre = this.formData.prefix.toUpperCase();
                    let start = parseInt(this.formData.start) || 1;
                    let qty = parseInt(this.formData.quantity) || 1;

                    if (qty === 1) return `${pre}${start}`;
                    if (qty === 2) return `${pre}${start}, ${pre}${start+1}`;
                    return `${pre}${start} ... ${pre}${start + qty - 1}`;
                },

                openCreateModal() {
                    this.formData.start = totalCurrentSpaces + 1; // Refresca por si hubo cambios
                    this.formData.quantity = 1;
                    this.showCreateModal = true;
                },

                openDeleteModal(id, number) {
                    this.deleteMode = 'single';
                    this.singleDeleteData = { id, number };
                    this.showDeleteModal = true;
                },

                openBulkDeleteModal() {
                    this.deleteMode = 'bulk';
                    this.showDeleteModal = true;
                }
            }
        }
    </script>
@endsection
