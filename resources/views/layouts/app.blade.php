<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script> 

    <meta charset="utf-8">
    <!-- Viewport optimizado para dispositivos móviles -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- FAVICONS SIHO -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#0f172a">

    <!-- Preconexión y precarga de recursos críticos -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </noscript>

    <!-- libreria de aletas -->
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}">

    <!-- Fuentes -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Vite assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Estilos inline críticos para mejorar el rendimiento inicial -->
    <style>
      html { scroll-behavior: smooth; }
      body {
        -webkit-text-size-adjust: 100%;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
      }
      @media (max-width: 640px) {
        .sm-px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
      }
    </style>
</head>


<body class="font-sans antialiased text-gray-800 bg-gray-100">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="text-lg sm:text-xl font-semibold text-gray-900">
                    {{ $header }}
                </div>
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-1">
            <div class=" mx-auto px-4 sm:px-6 lg:px-9 py-6">
                {{ $slot }}
            </div>
        </main>

        @include('components.app-footer')
    </div>

    <!-- Scripts para mejorar la experiencia en móviles -->
    <script>
    // Detectar touch devices
    function isTouchDevice() {
        return 'ontouchstart' in window || navigator.maxTouchPoints;
    }

    if (isTouchDevice()) {
        document.body.classList.add('touch-device');
    }

    // Manejar el viewport en móviles + ajuste de font-size en iOS
    document.addEventListener('DOMContentLoaded', function() {
        let viewport = document.querySelector('meta[name="viewport"]');
        if (window.innerWidth <= 640) {
            viewport.setAttribute('content', 'width=device-width, initial-scale=1, maximum-scale=1');
        }
        if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {
            document.addEventListener('focus', function(e) {
                if (['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) {
                    document.body.style.fontSize = '16px';
                }
            }, true);
            document.addEventListener('blur', function() {
                document.body.style.fontSize = '';
            }, true);
        }
    });

    </script>
     <!-- SweetAlert2 OFFLINE (cárgalo desde public/vendor/...) -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}" defer></script>

    <!-- Helpers + disparadores globales -->
    <script>
    // Helpers globales
    window.showSuccess = (msg = 'Listo') => {
        if (window.Swal) {
            Swal.fire({
                icon: 'success',
                title: msg,
                position: 'center',
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true,
                heightAuto: false
            });
        } else { alert(msg); }
    };
    window.showError = (htmlMsg = 'Ocurrió un error') => {
        if (window.Swal) {
            Swal.fire({
                icon: 'error',
                title: 'Hay errores',
                html: htmlMsg,
                position: 'center',
                confirmButtonText: 'Entendido',
                heightAuto: false
            });
        } else { alert(htmlMsg.replace(/<br>/g, '\n')); }
    };

    // Dispara automáticamente si hay mensajes de sesión o validaciones
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
            showSuccess(@json(session('success')));
        @endif
        @if(session('error'))
            showError(@json(session('error')));
        @endif
        @if($errors->any())
            showError(@json(implode('<br>', $errors->all())));
        @endif
    });
    </script>
</body>

</html>