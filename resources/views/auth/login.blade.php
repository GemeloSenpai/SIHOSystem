<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>SIHO System</title>

    <!-- Fuente principal -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <style>
        /* =========================================================
           NUEVA PALETA DE COLORES - MORADO, AZUL Y BLANCO
        ========================================================= */
        :root {
            /* Colores principales - MÁS COMPACTO */
            --primary-dark: #4A00E0;
            --primary-main: #6C5CE7;
            --primary-light: #8E83F4;
            --primary-lighter: #B5A9FF;
            --primary-lightest: #E6E4FF;

            /* Azules */
            --blue-dark: #2D82B7;
            --blue-main: #48C6EF;
            --blue-light: #6FDFF9;
            --blue-lighter: #A6F0FF;
            --blue-lightest: #E0F7FF;

            /* Morados */
            --purple-dark: #8E2DE2;
            --purple-main: #A855F7;
            --purple-light: #C084FC;
            --purple-lighter: #E9D5FF;
            --purple-lightest: #F5F0FF;

            /* Gradientes */
            --gradient-primary: linear-gradient(135deg, var(--primary-dark) 0%, var(--purple-main) 50%, var(--blue-main) 100%);
            --gradient-secondary: linear-gradient(135deg, var(--primary-main) 0%, var(--blue-main) 100%);
            --gradient-light: linear-gradient(135deg, var(--primary-lightest) 0%, var(--blue-lightest) 100%);
            --gradient-glass: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.05) 100%);

            /* Grises */
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;

            /* Variables de diseño - MÁS PEQUEÑAS */
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 16px;
            --radius-xl: 22px;
            --radius-2xl: 30px;

            --shadow-sm: 0 1px 3px rgba(108, 92, 231, 0.1);
            --shadow-md: 0 3px 5px -1px rgba(108, 92, 231, 0.1), 0 1px 3px -1px rgba(108, 92, 231, 0.1);
            --shadow-lg: 0 8px 12px -3px rgba(108, 92, 231, 0.1), 0 3px 5px -2px rgba(108, 92, 231, 0.1);
            --shadow-xl: 0 15px 20px -5px rgba(108, 92, 231, 0.15), 0 6px 8px -4px rgba(108, 92, 231, 0.1);
            --shadow-2xl: 0 20px 35px -8px rgba(108, 92, 231, 0.25);
            --shadow-glow: 0 0 30px rgba(108, 92, 231, 0.3);

            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        html,
        body {
            height: 100%;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            line-height: 1.4;
            color: var(--gray-900);
            background: var(--gradient-light);
            font-size: 14px;
        }

        /* =========================================================
           BACKGROUND CON NUEVA PALETA - MÁS COMPACTO
        ========================================================= */
        .login-body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            position: relative;
            overflow: auto;
            background: var(--gradient-light);
        }

        .background-animation {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(50px);
            opacity: 0.15;
            animation: float 20s infinite ease-in-out;
            mix-blend-mode: multiply;
        }

        .blob-1 {
            width: 300px;
            height: 300px;
            background: var(--purple-main);
            top: -100px;
            right: -80px;
            animation-delay: 0s;
        }

        .blob-2 {
            width: 250px;
            height: 250px;
            background: var(--blue-main);
            bottom: -80px;
            left: -60px;
            animation-delay: 8s;
        }

        .blob-3 {
            width: 200px;
            height: 200px;
            background: var(--primary-main);
            top: 40%;
            left: 75%;
            transform: translate(-50%, -50%);
            animation-delay: 16s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            25% {
                transform: translate(30px, -40px) scale(1.05);
            }

            50% {
                transform: translate(-20px, 30px) scale(0.95);
            }

            75% {
                transform: translate(15px, 20px) scale(1.03);
            }
        }

        /* =========================================================
           MAIN CONTAINER - MÁS COMPACTO
        ========================================================= */
        .container {
            width: 100%;
            max-width: 1000px;
            height: 550px;
            max-height: 85vh;
            display: flex;
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(15px);
            border-radius: var(--radius-2xl);
            box-shadow:
                var(--shadow-2xl),
                inset 0 1px 0 0 rgba(255, 255, 255, 0.6),
                inset 0 -1px 0 0 rgba(0, 0, 0, 0.05);
            overflow: hidden;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        /* =========================================================
           LEFT SIDE - MÁS COMPACTO CON IMAGEN DEL DOCTOR
        ========================================================= */
        .left {
            flex: -1;
            padding: 40px 35px;
            background: var(--gradient-primary);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            color: white;
        }

        /* Efectos decorativos más sutiles */
        .left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.06) 0%, transparent 40%);
            pointer-events: none;
        }

        .brand-section {
            z-index: 5;
            position: relative;
            margin-bottom: 0px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .brand-logo img {
            height: 45px;
            width: auto;
            filter: brightness(0) invert(1);
            transition: transform var(--transition-base);
        }

        .brand-logo img:hover {
            transform: scale(1.03);
        }

        /* Mensaje de bienvenida MÁS COMPACTO */
        .welcome-message {
            margin-top: 15px;
            position: relative;
            z-index: 5;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 2px;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 1) 0%,
                    rgba(255, 255, 255, 0.9) 50%,
                    rgba(230, 228, 255, 0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 1px 5px rgba(108, 92, 231, 0.2);
        }

        .welcome-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.95);
            line-height: 1.5;
            margin-bottom: 25px;
            max-width: 400px;
            font-weight: 400;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
        }

        .welcome-features {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
        }

        .feature-icon {
            width: 30px;
            height: 30px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            flex-shrink: 0;
            transition: all var(--transition-base);
        }

        .feature-icon .material-icons-round {
            font-size: 16px;
            color: white;
        }

        .feature-item:hover .feature-icon {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }

        /* =========================================================
           IMAGEN DEL DOCTOR - SIN DIVS, SOLO LA IMAGEN
        ========================================================= */
        .doctor-image-container {
            flex: 1;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            margin-top: auto;
            position: relative;
            z-index: 5;
        }

        .doctor-image {
            width: 320px;
            height: 220px;
            max-height: 311px;
            object-fit: contain;
            filter:
                drop-shadow(0 15px 30px rgba(0, 0, 0, 0.25)) drop-shadow(0 5px 15px rgba(0, 0, 0, 0.15));
            transform: scaleX(-1);
            /* Voltea horizontalmente si es necesario */
            transition: all var(--transition-base);
            margin-bottom: -18px;
            animation: floatDoctor 6s ease-in-out infinite;
        }

        @keyframes floatDoctor {

            0%,
            100% {
                transform: scaleX(-1) translateY(0);
            }

            50% {
                transform: scaleX(-1) translateY(-10px);
            }
        }

        .doctor-image:hover {
            transform: scaleX(-1) scale(1.05);
            filter:
                drop-shadow(0 20px 40px rgba(0, 0, 0, 0.3)) drop-shadow(0 8px 20px rgba(0, 0, 0, 0.2));
        }

        /* =========================================================
           RIGHT SIDE - Login Form MÁS COMPACTO
        ========================================================= */
        .right {
            flex: 1;
            padding: 40px 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            position: relative;
            overflow-y: auto;
        }

        .form-header {
            margin-bottom: 13px;
            text-align: center;
        }

        .form-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 10px;
            letter-spacing: -0.01em;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--purple-main) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-header p {
            color: var(--gray-600);
            font-size: 0.95rem;
            font-weight: 400;
            line-height: 1.5;
        }

        /* Form Styles */
        .login-form {
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 5px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
            transition: color var(--transition-fast);
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            font-size: 0.95rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-md);
            background: white;
            color: var(--gray-900);
            transition: all var(--transition-base);
            outline: none;
            box-shadow: var(--shadow-sm);
        }

        .form-input:focus {
            border-color: var(--primary-main);
            box-shadow:
                0 0 0 3px rgba(108, 92, 231, 0.12),
                var(--shadow-sm);
            background: white;
        }

        .form-input:focus+.input-icon {
            color: var(--primary-main);
            transform: scale(1.05);
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            transition: all var(--transition-base);
            font-size: 1.2rem;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            padding: 6px;
            border-radius: var(--radius-sm);
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: var(--primary-main);
            background: var(--primary-lightest);
        }

        /* Caps Lock Warning */
        .caps-warning {
            display: none;
            margin-top: 10px;
            padding: 10px 12px;
            background: var(--primary-lightest);
            border: 1px solid var(--primary-lighter);
            border-radius: var(--radius-md);
            color: var(--primary-dark);
            font-size: 0.85rem;
            animation: slideDown 0.2s ease-out;
        }

        .caps-warning.show {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .caps-icon {
            color: var(--primary-main);
            font-size: 1.1rem;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 16px 24px;
            background: var(--gradient-secondary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all var(--transition-base);
            margin-top: 5px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.2),
                    transparent);
            transition: left 0.6s;
        }

        .submit-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--purple-main) 100%);
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }

        .submit-btn:hover:not(:disabled)::before {
            left: 100%;
        }

        .submit-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        /* Links */
        .form-links {
            text-align: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--gray-200);
        }

        .form-links p {
            color: var(--gray-600);
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .form-links a {
            color: var(--primary-main);
            text-decoration: none;
            font-weight: 600;
            transition: all var(--transition-fast);
            position: relative;
            padding: 1px 0;
        }

        .form-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--primary-main);
            transition: width var(--transition-base);
        }

        .form-links a:hover {
            color: var(--primary-dark);
        }

        .form-links a:hover::after {
            width: 100%;
        }

        /* Alerts */
        .alert {
            padding: 14px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 15px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.3s ease-out;
            border: 1px solid;
        }

        .alert-info {
            background: var(--blue-lightest);
            border-color: var(--blue-lighter);
            color: var(--blue-dark);
        }

        .alert-error {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .alert-icon {
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        /* =========================================================
           LOGIN FOOTER MÁS COMPACTO
        ========================================================= */
        .login-footer {
            width: 100%;
            text-align: center;
            font-size: 0.8rem;
            color: var(--gray-500);
            padding: 0px 0 0;
            margin-top: -5px;
            letter-spacing: 0.02em;
            opacity: 0.5;
            user-select: none;
            transition: opacity var(--transition-fast);
            position: relative;
        }

        .login-footer::before {
            content: "";
            display: block;
            width: 120px;
            height: 1px;
            margin: 0 auto 15px;
            background: linear-gradient(to right,
                    transparent,
                    var(--gray-300),
                    transparent);
            opacity: 0.5;
        }

        .login-footer:hover {
            opacity: 1;
        }

        /* =========================================================
           RESPONSIVE DESIGN - COMPACTO PARA TODAS LAS PANTALLAS
        ========================================================= */

        /* Laptop Medium */
        @media (max-width: 1200px) {
            .container {
                max-width: 900px;
                height: 520px;
            }

            .left,
            .right {
                padding: 35px 30px;
            }

            .welcome-title {
                font-size: 2.2rem;
            }

            .doctor-image {
                width: 240px;
                max-height: 240px;
            }
        }

        /* Tablet Landscape */
        @media (max-width: 1024px) {
            .container {
                max-width: 850px;
                height: 500px;
            }

            .left,
            .right {
                padding: 30px 25px;
            }

            .welcome-title {
                font-size: 2rem;
            }

            .welcome-subtitle {
                font-size: 0.95rem;
            }

            .doctor-image {
                width: 220px;
                max-height: 220px;
                margin-bottom: -15px;
            }

            .form-header h2 {
                font-size: 1.8rem;
            }
        }

        /* Tablet Portrait */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                max-width: 636px;
                height: 517px;
                min-height: auto;
            }

            .left {
                position: relative;
                padding: 5px 8px;
                height: auto;
                min-height: 95px;
                border-radius: var(--radius-xl) var(--radius-xl) 0 0;
                overflow: visible;
                /* Permite que la imagen se salga */
            }

            .welcome-message {
                text-align: center;
                margin-top: 3px;
            }

            .welcome-title {
                font-size: 1.8rem;
                margin-bottom: 5px;
            }

            .welcome-subtitle {
                font-size: 0.9rem;
                margin: 0 auto 9px;
                max-width: 100%;
            }

            .welcome-features {
                display: none;
            }

            .brand-logo {
                justify-content: center;
                margin-bottom: 10px;
            }

            .brand-logo img {
                height: 40px;
            }

            /* OCULTAR LA IMAGEN DEL DOCTOR EN TABLET Y MÓVIL */
            .doctor-image-container {
                display: none;
            }

            .right {
                padding: 30px 25px;
                border-radius: 0 0 var(--radius-xl) var(--radius-xl);
            }

            .form-header {
                margin-bottom: 8px;
            }

            .form-header h2 {
                font-size: 1.6rem;
            }

            .form-header p {
                font-size: 0.9rem;
            }

            .login-form {
                max-width: 100%;
            }
        }

        /* Mobile */
        @media (max-width: 480px) {
            .login-body {
                padding: 10px;
            }

            .container {
                border-radius: var(--radius-xl);
                max-width: 100%;
            }

            .left {
                padding: 2px 3px;
                min-height: 80px;
            }

            .welcome-title {
                font-size: 1.6rem;
            }

            .welcome-subtitle {
                font-size: 0.85rem;
                margin-bottom: 12px;
            }

            .right {
                padding: 25px 20px;
            }

            .form-header h2 {
                font-size: 1.4rem;
            }

            .form-header p {
                font-size: 0.85rem;
            }

            .form-input {
                padding: 12px 14px 12px 44px;
                font-size: 0.9rem;
            }

            .input-icon {
                left: 14px;
                font-size: 1.1rem;
            }

            .submit-btn {
                padding: 14px 20px;
                font-size: 0.95rem;
            }

            .form-links p {
                font-size: 0.8rem;
            }
        }

        /* Small Mobile */
        @media (max-width: 360px) {
            .left {
                padding: 5px 3px;
                min-height: 80px;
            }

            .welcome-title {
                font-size: 1.4rem;
            }

            .welcome-subtitle {
                font-size: 0.8rem;
            }

            .right {
                padding: 20px 15px;
            }

            .form-header h2 {
                font-size: 1.3rem;
            }

            .form-input {
                padding: 10px 12px 10px 40px;
                font-size: 0.85rem;
            }

            .submit-btn {
                padding: 12px 16px;
                font-size: 0.9rem;
            }
        }

        /* Pantallas muy altas */
        @media (min-height: 900px) {
            .container {
                height: 600px;
            }

            .doctor-image {
                width: 320px;
                max-height: 320px;
                margin-bottom: -30px;
            }
        }

        /* Accesibilidad */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        @media (prefers-contrast: high) {
            .doctor-image {
                filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.4));
            }

            .form-input {
                border-width: 2px;
            }
        }

        /* ANIMACIONES */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Estilos iniciales para animación de entrada */
        .container {
            opacity: 0;
            transform: translateY(20px) scale(0.98);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .container.loaded {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        /* Estilos para imágenes */
        img {
            transition: opacity 0.4s ease;
        }
    </style>
</head>

<body>

    @php
        $errorMessages = $errors->all();
        foreach ($errorMessages as $key => $message) {
            // Corrección del typo: $messege → $message
            if ($message === 'These credentials do not match our records.') {
                $errorMessages[$key] = 'Credenciales incorrectas. Verifique sus datos.';
            } elseif ($message === 'The email field must be a valid email address.') {
                $errorMessages[$key] = 'Email incorrecto. Verifique sus datos.';
            }
        }
    @endphp

    <div class="login-body">
        <!-- Background Animation -->
        <div class="background-animation">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="blob blob-3"></div>
        </div>

        <!-- Main Container -->
        <div class="container" id="mainContainer">
            <!-- Left Side - Brand y Mensaje de Bienvenida -->
            <div class="left">
                <!--
                <div class="brand-section">
                    <div class="brand-logo">
                        <img src="{{ asset('images/logo.png') }}" alt="SIHO System Logo"
                            onerror="this.style.display='none'">
                    </div>
                </div>
                 -->

                <!-- Mensaje de Bienvenida COMPACTO -->
                <div class="welcome-message">
                    <h1 class="welcome-title">¡Bienvenido a SIHO System!</h1>
                    <p class="welcome-subtitle">
                        Gestione las actividades de su hospital de eficiente y segura.
                    </p>

                    <!-- Features List -->
                    <div class="welcome-features">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <span class="material-icons-round">security</span>
                            </div>
                            <span>Acceso seguro y encriptado</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <span class="material-icons-round">speed</span>
                            </div>
                            <span>Interfaz optimizada para productividad</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <span class="material-icons-round">support_agent</span>
                            </div>
                            <span>Soporte técnico 24/7</span>
                        </div>
                    </div>
                </div>

                <!-- Imagen del Doctor - SIN DIVS, SOLO LA IMAGEN -->
                <div class="doctor-image-container">
                    <img class="doctor-image" src="{{ asset('images/mi_imagen.png') }}" alt="Doctor SIHO System"
                        onerror="this.style.display='none'">
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="right">
                <div class="form-header">
                    <h2>Iniciar Sesión</h2>
                    <p>Ingrese sus credenciales para acceder al sistema</p>
                </div>

                <!-- Session Messages -->
                @if (session('status'))
                    <div class="alert alert-info">
                        <span class="alert-icon material-icons-round">info</span>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form id="loginForm" class="login-form" method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <div class="input-wrapper">
                            <span class="input-icon material-icons-round">mail</span>
                            <input type="email" id="email" name="email"
                                class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                                value="{{ old('email') }}" autocomplete="username" required
                                placeholder="ejemplo@correo.com">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-wrapper">
                            <span class="input-icon material-icons-round">lock</span>
                            <input type="password" id="password" name="password"
                                class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                                autocomplete="current-password" required placeholder="••••••••">
                            <button type="button" class="password-toggle" id="togglePassword"
                                aria-label="Mostrar u ocultar contraseña">
                                <span class="material-icons-round" id="toggleIcon">visibility</span>
                            </button>
                        </div>

                        <!-- Caps Lock Warning -->
                        <div id="capsWarning" class="caps-warning">
                            <span class="caps-icon material-icons-round">keyboard_capslock</span>
                            <span>Bloq Mayús está activado</span>
                        </div>

                        @foreach ($errorMessages as $message)
                            <div class="alert alert-error">
                                <span class="alert-icon material-icons-round">error</span>
                                <span>{{ $message }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <span id="btnText">Acceder al Sistema</span>
                        <div id="btnSpinner" class="btn-spinner" style="display: none;"></div>
                    </button>
                </form>

                <!-- Additional Links -->
                <div class="form-links">
                    <p>
                        ¿Problemas para ingresar?<br>
                        Contacte al administrador del Sistema.
                        <!-- <a href="mailto:soporte@sihosystem.com">Contacte al administrador TI</a>
                        -->
                    </p>
                </div>

                <footer class="login-footer">
                    © 2025 Twins Labs. Todos los derechos reservados.
                </footer>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const toggleIcon = document.getElementById('toggleIcon');
        const passwordInput = document.getElementById('password');
        const capsWarning = document.getElementById('capsWarning');
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const emailInput = document.getElementById('email');
        const mainContainer = document.getElementById('mainContainer');

        // Toggle password visibility
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            toggleIcon.textContent = type === 'password' ? 'visibility' : 'visibility_off';
            this.setAttribute('aria-label',
                type === 'password' ? 'Mostrar contraseña' : 'Ocultar contraseña');
        });

        // Caps Lock detection
        passwordInput.addEventListener('keyup', function(e) {
            if (e.getModifierState && e.getModifierState('CapsLock')) {
                capsWarning.classList.add('show');
            } else {
                capsWarning.classList.remove('show');
            }
        });

        // Form submission
        loginForm.addEventListener('submit', function(e) {
            // Basic validation
            if (!emailInput.value.trim() || !passwordInput.value.trim()) {
                e.preventDefault();
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            btnText.textContent = 'Ingresando...';
            btnSpinner.style.display = 'block';
            submitBtn.style.cursor = 'wait';
        });

        // Input validation styling
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim()) {
                    this.classList.add('filled');
                } else {
                    this.classList.remove('filled');
                }
            });

            input.addEventListener('input', function() {
                this.classList.remove('error');
                const errorMessage = this.parentElement.parentElement.querySelector('.error-message');
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
            });
        });

        // Auto-focus email field
        document.addEventListener('DOMContentLoaded', function() {
            if (emailInput) {
                emailInput.focus();
            }

            // Animación de entrada
            setTimeout(function() {
                mainContainer.classList.add('loaded');
            }, 100);

            // Pre-cargar imágenes
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                if (img.complete) {
                    img.style.opacity = '1';
                } else {
                    img.addEventListener('load', function() {
                        this.style.opacity = '1';
                    });
                    img.addEventListener('error', function() {
                        this.style.opacity = '0';
                    });
                }
            });
        });

        // Todas las imágenes empiezan invisibles
        document.querySelectorAll('img').forEach(img => {
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.4s ease';
        });
    </script>
</body>

</html>
