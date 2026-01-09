<nav x-data="{ open: false }" class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200 shadow-sm">
    @php
        $home = match (Auth::user()->role ?? null) {
            'admin' => route('dashboard.admin'),
            'recepcionista' => route('dashboard.recepcion'),
            'enfermero' => route('dashboard.enfermero'),
            'medico' => route('dashboard.medico'),
            default => url('/'),
        };

        // helper para clases de link
        $navLink = function (bool $active) {
            return 'inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-[13px] font-medium transition-colors ' .
                ($active
                    ? 'text-indigo-700 bg-indigo-50 ring-1 ring-indigo-200'
                    : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50');
        };

        // avatar con iniciales
        $name = Auth::user()->name ?? '';
        $initials = collect(explode(' ', trim($name)))
            ->map(fn($p) => mb_substr($p, 0, 1))
            ->take(2)
            ->implode('');
        $role = ucfirst(Auth::user()->role ?? 'Usuario');
    @endphp

    <!-- Top bar -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6">
        <div class="h-14 flex items-center justify-between gap-3">

            <!-- Izquierda: logo + rol -->
            <div class="flex items-center gap-4">
                <a href="{{ $home }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/SIHO.png') }}" alt="Logo" class="h-8 w-auto">
                </a>
                <span
                    class="hidden md:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 12c2.7 0 5-2.3 5-5S14.7 2 12 2 7 4.3 7 7s2.3 5 5 5zm0 2c-4.4 0-9 2.2-9 5.5 0 .8.7 1.5 1.5 1.5h15c.8 0 1.5-.7 1.5-1.5 0-3.3-4.6-5.5-9-5.5z" />
                    </svg>
                    {{ $role }}
                </span>
            </div>

            <!-- Centro: navegación por rol (desktop) -->
            <div class="hidden sm:flex items-center gap-1.5">

                <!-- Opciones disponibles para el Administrador -->
                @if (Auth::user()->role === 'admin')
                    <a href="{{ route('dashboard.admin') }}"
                        class="{{ $navLink(request()->routeIs('dashboard.admin')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 12l9-9 9 9h-2v8a1 1 0 0 1-1 1h-4v-6H10v6H6a1 1 0 0 1-1-1v-8H3z" />
                        </svg>
                        Inicio
                    </a>
                    <a href="{{ route('admin.registrar.usuario') }}"
                        class="{{ $navLink(request()->routeIs('admin.registrar.usuario')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M15 14c2.8 0 5 2.2 5 5v1H4v-1c0-2.8 2.2-5 5-5h6zM12 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zM19 3h2v4h4v2h-4v4h-2V9h-4V7h4z" />
                        </svg>
                        Registrar Usuario
                    </a>
                    <a href="{{ route('admin.expedientes.index') }}"
                        class="{{ $navLink(request()->routeIs('admin.expedientes.index')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 4h6l2 2h8a1 1 0 0 1 1 1v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z" />
                        </svg>
                        Gestionar Expedientes
                    </a>
                    <a href="{{ route('admin.usuarios.index') }}"
                        class="{{ $navLink(request()->routeIs('admin.usuarios.*')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M16 11a4 4 0 1 0-8 0 4 4 0 0 0 8 0zm-8 6a6 6 0 0 0-6 6h2a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4h2a6 6 0 0 0-6-6H8z" />
                        </svg>
                        Gestionar usuarios
                    </a>
                    <a href="{{ route('admin.gestionar.examenes') }}"
                        class="{{ $navLink(request()->routeIs('admin.gestionar.examenes')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M7 2h10l3 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7l3-5zm1 7h8v2H8V9zm0 4h8v2H8v-2z" />
                        </svg>
                        Gestionar Exámenes
                    </a>
                @endif

                <!-- Opciones disponibles para el laboratorio -->
                @if (Auth::user()->role === 'laboratorio')
                    <a href="{{ route('dashboard.laboratorio') }}"
                        class="{{ $navLink(request()->routeIs('dashboard.laboratorio')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 12l9-9 9 9h-2v8H5v-8H3z" />
                        </svg>
                        Inicio
                    </a>

                    <a href="{{ route('expedientes.index') }}"
                        class="{{ $navLink(request()->routeIs('expedientes.index')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 4h6l2 2h8v13a2 2 0 0 1-2 2H4z" />
                        </svg>
                        Gestionar Expedientes
                    </a>
                @endif
                
                <!-- Opciones disponibles para el Recepcionista -->
                @if (Auth::user()->role === 'recepcionista')
                    <a href="{{ route('dashboard.recepcion') }}"
                        class="{{ $navLink(request()->routeIs('dashboard.recepcion')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 12l9-9 9 9h-2v8H5v-8H3z" />
                        </svg>
                        Inicio
                    </a>
                    <a href="{{ route('recepcion.pacientes.form') }}"
                        class="{{ $navLink(request()->routeIs('recepcion.pacientes.form')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm-7 9a7 7 0 0 1 14 0z" />
                        </svg>
                        Registrar Paciente
                    </a>
                    <a href="{{ route('enfermero.signosvitales.form') }}"
                        class="{{ $navLink(request()->routeIs('enfermero.signosvitales.form')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 13h4l2-6 4 12 2-6h6" />
                        </svg>
                        Registrar Signos Vitales
                    </a>
                    <a href="{{ route('recepcion.verPacientes') }}"
                        class="{{ $navLink(request()->routeIs('recepcion.verPacientes')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 11a4 4 0 1 0-8 0 4 4 0 0 0 8 0zm-8 6a6 6 0 0 0-6 6h20a6 6 0 0 0-6-6H8z" />
                        </svg>
                        Gestionar Pacientes
                    </a>
                @endif

                <!-- Opciones disponibles para el Enfermero -->
                @if (Auth::user()->role === 'enfermero')
                    <!-- Dashboard de recepciopn que lo ve enfermeria -->
                    <a href="{{ route('dashboard.recepcion') }}"
                        class="{{ $navLink(request()->routeIs('dashboard.recepcion')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 12l9-9 9 9h-2v8H5v-8H3z" />
                        </svg>
                        Inicio
                    </a>
                    {{-- 
                    <!-- Se ha ocultado no se usa
                    <a href="{{ route('dashboard.enfermero') }}"
                        class="{{ $navLink(request()->routeIs('dashboard.enfermero')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 12l9-9 9 9H3z" />
                        </svg>
                        Inicio
                    </a>
                    -->
                    --}}
                    <a href="{{ route('recepcion.pacientes.form') }}"
                        class="{{ $navLink(request()->routeIs('recepcion.pacientes.form')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm-7 9a7 7 0 0 1 14 0z" />
                        </svg>
                        Registrar Paciente
                    </a>
                    <a href="{{ route('enfermero.signosvitales.form') }}"
                        class="{{ $navLink(request()->routeIs('enfermero.signosvitales.form')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 13h4l2-6 4 12 2-6h6" />
                        </svg>
                        Registrar Signos Vitales
                    </a>
                    <a href="{{ route('recepcion.verPacientes') }}"
                        class="{{ $navLink(request()->routeIs('recepcion.verPacientes')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 11a4 4 0 1 0-8 0 4 4 0 0 0 8 0zm-8 6a6 6 0 0 0-6 6h20a6 6 0 0 0-6-6H8z" />
                        </svg>
                        Gestionar Pacientes
                    </a>
                @endif

                <!-- Opciones disponibles para el Medico -->
                @if (Auth::user()->role === 'medico')
                    <a href="{{ route('dashboard.medico') }}"
                        class="{{ $navLink(request()->routeIs('dashboard.medico')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 12l9-9 9 9h-2v8H5v-8H3z" />
                        </svg>
                        Inicio
                    </a>
                    <a href="{{ route('medico.consulta.form') }}"
                        class="{{ $navLink(request()->routeIs('medico.consulta.form')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 4h14a1 1 0 0 1 1 1v14l-4-3H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z" />
                        </svg>
                        Registrar Consulta
                    </a>
                    <a href="{{ route('expedientes.index') }}"
                        class="{{ $navLink(request()->routeIs('expedientes.index')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 4h6l2 2h8v13a2 2 0 0 1-2 2H4z" />
                        </svg>
                        Gestionar Expedientes
                    </a>
                    <a href="{{ route('medico.examenes.index') }}"
                        class="{{ $navLink(request()->routeIs('medico.examenes.index')) }}">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M7 2h10l3 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7z" />
                        </svg>
                        Gestionar Exámenes
                    </a>
                @endif
            </div>

            <!-- Derecha: usuario -->
            <div class="hidden sm:flex items-center">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 ps-2 pe-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-[13px] text-slate-700 hover:bg-slate-50">
                            <span
                                class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-indigo-600 text-white text-[11px] font-semibold">{{ $initials }}</span>
                            <span class="hidden md:inline">{{ $name }}</span>
                            <svg class="h-3.5 w-3.5 opacity-70" viewBox="0 0 20 20" fill="none">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5" d="m6 8 4 4 4-4" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="p-1 min-w-[14rem]">
                            <div class="px-2 py-1.5 text-[12px] text-slate-500">
                                Conectado como <span class="font-medium text-slate-700">{{ $role }}</span>
                            </div>
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 px-2 py-1.5 text-[13px]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm-7 9a7 7 0 0 1 14 0z" />
                                </svg>
                                Perfil
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}" class="mt-0.5">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    class="flex items-center gap-2 px-2 py-1.5 text-[13px]"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M16 13v-2H7V8l-5 4 5 4v-3h9zM20 3h-8c-1.1 0-2 .9-2 2v4h2V5h8v14h-8v-4h-2v4c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z" />
                                    </svg>
                                    Cerrar Sesion
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Botón móvil -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 border-t border-gray-200">
            {{-- ADMIN --}}
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('dashboard.admin') }}"
                    class="{{ $navLink(request()->routeIs('dashboard.admin')) }} block w-full">
                    Inicio
                </a>
                <a href="{{ route('admin.registrar.usuario') }}"
                    class="{{ $navLink(request()->routeIs('admin.registrar.usuario')) }} block w-full">
                    Registrar Usuario
                </a>
                <a href="{{ route('admin.expedientes.index') }}"
                    class="{{ $navLink(request()->routeIs('admin.expedientes.index')) }} block w-full">
                    Gestionar Expedientes
                </a>
                <a href="{{ route('admin.usuarios.index') }}"
                    class="{{ $navLink(request()->routeIs('admin.usuarios.*')) }} block w-full">
                    Gestionar usuarios
                </a>
                <a href="{{ route('admin.gestionar.examenes') }}"
                    class="{{ $navLink(request()->routeIs('admin.gestionar.examenes')) }} block w-full">
                    Gestionar Exámenes
                </a>
            @endif

            {{-- RECEPCIONISTA --}}
            @if (Auth::user()->role === 'recepcionista' || Auth::user()->role === 'enfermero')
                <a href="{{ route('dashboard.recepcion') }}"
                    class="{{ $navLink(request()->routeIs('dashboard.recepcion')) }} block w-full">Inicio</a>
                <a href="{{ route('recepcion.pacientes.form') }}"
                    class="{{ $navLink(request()->routeIs('recepcion.pacientes.form')) }} block w-full">Registrar
                    Paciente</a>
                <a href="{{ route('recepcion.verPacientes') }}"
                    class="{{ $navLink(request()->routeIs('recepcion.verPacientes')) }} block w-full">Gestionar
                    Pacientes</a>
                    <a href="{{ route('enfermero.signosvitales.form') }}"
                    class="{{ $navLink(request()->routeIs('enfermero.signosvitales.form')) }} block w-full">Registrar
                    Signos
                    Vitales</a>
            @endif

            {{-- ENFERMERO 
            @if (Auth::user()->role === 'enfermero')
                <a href="{{ route('dashboard.enfermero') }}"
                    class="{{ $navLink(request()->routeIs('dashboard.recepcion')) }} block w-full">Inicio</a>
                <a href="{{ route('enfermero.signosvitales.form') }}"
                    class="{{ $navLink(request()->routeIs('enfermero.signosvitales.form')) }} block w-full">Registrar
                    Signos
                    Vitales</a>
            @endif
            --}}

            {{-- MÉDICO --}}
            @if (Auth::user()->role === 'medico')
                <a href="{{ route('dashboard.medico') }}"
                    class="{{ $navLink(request()->routeIs('dashboard.medico')) }} block w-full">Inicio</a>
                <a href="{{ route('medico.consulta.form') }}"
                    class="{{ $navLink(request()->routeIs('medico.consulta.form')) }} block w-full">Registrar
                    Consulta</a>
                <a href="{{ route('expedientes.index') }}"
                    class="{{ $navLink(request()->routeIs('expedientes.index')) }} block w-full">Gestionar
                    Expedientes</a>
                <a href="{{ route('medico.examenes.index') }}"
                    class="{{ $navLink(request()->routeIs('medico.examenes.index')) }} block w-full">Gestionar
                    Exámenes</a>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar Sesion') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
