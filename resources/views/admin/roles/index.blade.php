@extends('layouts.admin')
@section('title', 'Listado de Roles')

@section('admin_content')
    <div x-data="roleManager()" class="flex flex-col h-full relative">

        @if(session('success'))
            <div class="absolute top-4 right-8 z-50 bg-green-500/90 backdrop-blur-md text-white px-6 py-3 rounded-xl shadow-lg flex items-center animate-fade-in-down" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="absolute top-4 right-8 z-50 bg-red-500/90 backdrop-blur-md text-white px-6 py-3 rounded-xl shadow-lg flex items-center animate-fade-in-down" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-semibold">{{ $errors->first() }}</span>
            </div>
        @endif

        <header class="h-24 glass border-b border-white/50 px-8 flex items-center justify-between flex-shrink-0 z-10">
            <div>
                <h1 class="font-poppins text-2xl font-bold text-slate-900">Niveles de Acceso</h1>
                <p class="text-sm font-medium text-slate-500">Gestión de roles del sistema</p>
            </div>
            <button @click="showCreateModal = true" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-sm px-5 py-3 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all">
                + Nuevo Rol
            </button>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            <div class="max-w-5xl mx-auto space-y-8">
                <div class="bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/60 shadow-sm flex items-center justify-between max-w-sm">
                    <div>
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Roles Registrados</span>
                        <span class="block text-2xl font-poppins font-bold text-indigo-600 mt-1">{{ $totalRoles }}</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                </div>

                <div class="bg-white/50 backdrop-blur-md rounded-3xl border border-white/60 shadow-xl overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="border-b border-slate-200/60 bg-white/20">
                            <th class="p-5 text-xs font-bold text-slate-400 uppercase pl-8 w-24">Nro</th>
                            <th class="p-5 text-xs font-bold text-slate-400 uppercase">Nombre del Rol</th>
                            <th class="p-5 text-xs font-bold text-slate-400 uppercase text-right pr-8">Acciones</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/70">
                        @foreach($roles as $index => $role)
                            <tr class="group hover:bg-white/60 transition-colors">
                                <td class="p-5 pl-8 text-sm font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="p-5">
                                <span class="inline-flex px-4 py-1.5 rounded-xl text-sm font-bold bg-indigo-50 text-indigo-700 border border-indigo-100/50">
                                    {{ $role->name }}
                                </span>
                                </td>
                                <td class="p-5 text-right pr-8">
                                    @if(!in_array($role->name, ['Root', 'Administrador']))
                                        <button @click="openDeleteModal({{ $role->id }}, '{{ $role->name }}')" class="p-2 text-slate-400 hover:text-red-500 hover:bg-white rounded-lg transition-all focus:outline-none">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @else
                                        <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">Protegido</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm" x-transition.opacity>
            <div class="relative w-full max-w-sm p-4" @click.away="showCreateModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="bg-white/90 backdrop-blur-xl rounded-[2rem] border border-white shadow-2xl p-6">
                    <h3 class="text-xl font-poppins font-bold text-slate-800 mb-4">Nuevo Rol</h3>
                    <form method="POST" action="{{ route('roles.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 ml-1 mb-1">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 bg-white border @error('name') border-red-500 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all">
                            @error('name') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="pt-2 flex justify-end gap-3">
                            <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl">Cancelar</button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md" x-transition.opacity>
            <div class="relative w-full max-w-sm p-4" @click.away="showDeleteModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <div class="bg-white/90 backdrop-blur-xl rounded-[2rem] border border-red-100 shadow-2xl p-8 text-center">
                    <div class="w-16 h-16 mx-auto bg-red-100 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-xl font-poppins font-bold text-slate-900 mb-2">¿Eliminar Rol?</h3>
                    <p class="text-sm font-medium text-slate-500 mb-6">Estás a punto de borrar el rol <br><strong class="text-slate-800" x-text="deleteData.name"></strong>.</p>

                    <form method="POST" :action="`/roles/${deleteData.id}`" class="flex gap-3 justify-center">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl w-full">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-red-500 hover:bg-red-600 rounded-xl w-full">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function roleManager() {
            const urlParams = new URLSearchParams(window.location.search);
            const hasCreationErrors = {{ ($errors->any() && !old('_method')) ? 'true' : 'false' }};
            const shouldOpenCreate = urlParams.get('create') === 'true' || hasCreationErrors;

            if(urlParams.get('create') === 'true') {
                window.history.replaceState(null, '', window.location.pathname);
            }

            return {
                showCreateModal: shouldOpenCreate,
                showDeleteModal: false,
                deleteData: { id: '', name: '' },
                openDeleteModal(id, name) {
                    this.deleteData = { id, name };
                    this.showDeleteModal = true;
                }
            }
        }
    </script>
@endsection
