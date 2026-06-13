@extends('layouts.admin')
@section('title', 'Listado de Usuarios')

@section('admin_content')
    <div x-data="userManager()" class="flex flex-col h-full relative">

        @if(session('success'))
            <div class="absolute top-4 right-8 z-50 bg-green-500/90 backdrop-blur-md text-white px-6 py-3 rounded-xl shadow-lg shadow-green-500/20 flex items-center transform transition-all duration-500 animate-fade-in-down" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <header class="h-24 glass border-b border-white/50 px-8 flex items-center justify-between flex-shrink-0 z-10">
            <div>
                <h1 class="font-poppins text-2xl font-bold text-slate-900">Gestión de Usuarios</h1>
                <p class="text-sm font-medium text-slate-500">Administración del personal técnico y cajeros</p>
            </div>

            <button @click="showCreateModal = true" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold text-sm px-5 py-3 rounded-xl shadow-lg shadow-indigo-500/20 hover:-translate-y-0.5 transition-all duration-300">
                + Nuevo Usuario
            </button>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
            <div class="max-w-7xl mx-auto space-y-8">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/60 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Total Usuarios</span>
                            <span class="block text-2xl font-poppins font-bold text-slate-800 mt-1">{{ $totalUsers }}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>

                    <div class="bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/60 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Administradores</span>
                            <span class="block text-2xl font-poppins font-bold text-indigo-600 mt-1">{{ $admins }}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                    </div>

                    <div class="bg-white/40 backdrop-blur-md p-6 rounded-2xl border border-white/60 shadow-sm flex items-center justify-between">
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Cajeros Activos</span>
                            <span class="block text-2xl font-poppins font-bold text-slate-700 mt-1">{{ $cashiers }}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white/50 backdrop-blur-md rounded-3xl border border-white/60 shadow-xl shadow-slate-900/5 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                            <tr class="border-b border-slate-200/60 bg-white/20">
                                <th class="p-5 text-xs font-bold text-slate-400 uppercase tracking-wider pl-8">Usuario</th>
                                <th class="p-5 text-xs font-bold text-slate-400 uppercase tracking-wider">Correo Electrónico</th>
                                <th class="p-5 text-xs font-bold text-slate-400 uppercase tracking-wider">Rol Asignado</th>
                                <th class="p-5 text-xs font-bold text-slate-400 uppercase tracking-wider text-right pr-8">Acciones</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/70">
                            @foreach($users as $user)
                                <tr class="group hover:bg-white/60 transition-colors duration-200">
                                    <td class="p-5 pl-8">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-9 h-9 rounded-xl bg-slate-100 group-hover:bg-indigo-50 text-slate-700 group-hover:text-indigo-600 transition-colors font-bold text-sm flex items-center justify-center border border-slate-200/30">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <span class="font-semibold text-slate-800 tracking-tight text-sm">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-5 text-sm text-slate-600 font-medium">{{ $user->email }}</td>
                                    <td class="p-5">
                                        @if($user->role == 'Root' || $user->role == 'Administrador')
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                            {{ $user->role }}
                                        </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200/40">
                                            {{ $user->role }}
                                        </span>
                                        @endif
                                    </td>
                                    <td class="p-5 text-right pr-8">
                                        <div class="inline-flex space-x-2">
                                            <button @click="openEditModal({{ $user }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-white rounded-lg transition-all shadow-sm border border-transparent hover:border-slate-100 focus:outline-none">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </button>
                                            @if(Auth::id() !== $user->id)
                                                <button @click="openDeleteModal({{ $user->id }}, '{{ $user->name }}')" class="p-2 text-slate-400 hover:text-red-500 hover:bg-white rounded-lg transition-all shadow-sm border border-transparent hover:border-slate-100 focus:outline-none">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/40 backdrop-blur-sm" x-transition.opacity>
            <div class="relative w-full max-w-md p-4" @click.away="showCreateModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                <div class="relative bg-white/80 backdrop-blur-xl rounded-[2rem] border border-white shadow-2xl">
                    <div class="flex items-center justify-between p-6 border-b border-slate-200/50">
                        <h3 class="text-xl font-poppins font-bold text-slate-800">Registrar Usuario</h3>
                        <button @click="showCreateModal = false" class="text-slate-400 hover:text-red-500 transition-colors focus:outline-none">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 ml-1 mb-1">Nombre Completo</label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 bg-white/50 border @error('name') border-red-500 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                                @error('name') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 ml-1 mb-1">Correo Electrónico</label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 bg-white/50 border @error('email') border-red-500 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                                @error('email') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 ml-1 mb-1">Rol</label>
                                <select name="role" required class="w-full px-4 py-3 bg-white/50 border @error('role') border-red-500 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                                    <option value="" disabled selected>Seleccione un Rol</option>
                                    @foreach($availableRoles as $rol)
                                        <option value="{{ $rol->name }}" {{ old('role') == $rol->name ? 'selected' : '' }}>
                                            {{ $rol->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 ml-1 mb-1">Contraseña</label>
                                    <input type="password" name="password" required class="w-full px-4 py-3 bg-white/50 border @error('password') border-red-500 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 ml-1 mb-1">Confirmar</label>
                                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 bg-white/50 border @error('password') border-red-500 @else border-slate-200 @enderror rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                                </div>
                            </div>
                            @error('password') <div class="text-xs text-red-500 font-medium ml-1 mt-1">{{ $message }}</div> @enderror

                            <div class="pt-4 flex justify-end gap-3">
                                <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Cancelar</button>
                                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/30 transition-all">Guardar Usuario</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/40 backdrop-blur-sm" x-transition.opacity>
            <div class="relative w-full max-w-md p-4" @click.away="showEditModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                <div class="relative bg-white/80 backdrop-blur-xl rounded-[2rem] border border-white shadow-2xl">
                    <div class="flex items-center justify-between p-6 border-b border-slate-200/50">
                        <h3 class="text-xl font-poppins font-bold text-slate-800">Actualizar Información</h3>
                        <button @click="showEditModal = false" class="text-slate-400 hover:text-red-500 transition-colors focus:outline-none">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="p-6">
                        <form method="POST" :action="`/usuarios/${editData.id}`" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 ml-1 mb-1">Nombre Completo</label>
                                <input type="text" name="name" x-model="editData.name" required class="w-full px-4 py-3 bg-white/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 ml-1 mb-1">Correo Electrónico</label>
                                <input type="email" name="email" x-model="editData.email" required class="w-full px-4 py-3 bg-white/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 ml-1 mb-1">Rol</label>
                                <select name="role" x-model="editData.role" required class="w-full px-4 py-3 bg-white/50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                                    <option value="" disabled>Seleccione un Rol</option>
                                    @foreach($availableRoles as $rol)
                                        <option value="{{ $rol->name }}">{{ $rol->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="p-4 bg-orange-50/50 border border-orange-100 rounded-xl">
                                <p class="text-xs font-semibold text-orange-600 mb-2">Deja los campos en blanco si no deseas cambiar la contraseña.</p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <input type="password" name="password" placeholder="Nueva Clave" class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                                    </div>
                                    <div>
                                        <input type="password" name="password_confirmation" placeholder="Confirmar Clave" class="w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 transition-all">
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4 flex justify-end gap-3">
                                <button type="button" @click="showEditModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Cancelar</button>
                                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-500/30 transition-all">Actualizar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/60 backdrop-blur-md" x-transition.opacity>
            <div class="relative w-full max-w-sm p-4" @click.away="showDeleteModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                <div class="relative bg-white/90 backdrop-blur-xl rounded-[2rem] border border-red-100 shadow-2xl p-8 text-center">
                    <div class="w-16 h-16 mx-auto bg-red-100 rounded-2xl flex items-center justify-center mb-6 shadow-inner shadow-red-500/20">
                        <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-xl font-poppins font-bold text-slate-900 mb-2">¿Eliminar Usuario?</h3>
                    <p class="text-sm font-medium text-slate-500 mb-6">Estás a punto de revocar el acceso para <br><strong class="text-slate-800" x-text="deleteData.name"></strong>. Esta acción no se puede deshacer de forma directa.</p>

                    <form method="POST" :action="`/usuarios/${deleteData.id}`" class="flex gap-3 justify-center">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="showDeleteModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors w-full focus:outline-none">Cancelar</button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-red-500 hover:bg-red-600 rounded-xl shadow-lg shadow-red-500/30 transition-all w-full focus:outline-none">Sí, Eliminar</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function userManager() {
            const urlParams = new URLSearchParams(window.location.search);

            // Verifica si Laravel devolvió errores y no hay método viejo (para distinguir crear de actualizar)
            const hasCreationErrors = {{ ($errors->any() && !old('_method')) ? 'true' : 'false' }};

            // El modal de creación se abre si venimos del menú lateral O si falló la validación al crear
            const shouldOpenCreate = urlParams.get('create') === 'true' || hasCreationErrors;

            // Limpiamos la URL para que no vuelva a saltar si el usuario recarga la página
            if(urlParams.get('create') === 'true') {
                window.history.replaceState(null, '', window.location.pathname);
            }

            return {
                showCreateModal: shouldOpenCreate,
                showEditModal: false,
                showDeleteModal: false,
                editData: { id: '', name: '', email: '', role: '' },
                deleteData: { id: '', name: '' },

                openEditModal(user) {
                    this.editData = { ...user };
                    this.showEditModal = true;
                },

                openDeleteModal(id, name) {
                    this.deleteData = { id, name };
                    this.showDeleteModal = true;
                }
            }
        }
    </script>
@endsection
