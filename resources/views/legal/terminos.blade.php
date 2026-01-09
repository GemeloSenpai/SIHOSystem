<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Términos y Condiciones — {{ config('app.company.name') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-full bg-gray-50 dark:bg-neutral-950 text-gray-800 dark:text-gray-100">

    <header class="border-b bg-white/70 dark:bg-neutral-900/70 backdrop-blur">
        <div class="max-w-7xl mx-auto px-4 lg:px-6 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="font-semibold hover:opacity-80">{{ config('app.name', 'Hospital') }}</a>
            <nav class="text-sm flex items-center gap-4">
                <a href="{{ route('legal.terminos') }}" class="font-medium text-blue-600">Términos</a>
                <a href="{{ route('legal.aspectos') }}" class="hover:underline">Aspectos</a>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 lg:px-6 py-10 lg:py-14">
        <section class="text-center mb-8 lg:mb-10">
            <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Términos y Condiciones de Uso</h1>
            <p class="mt-2 text-sm text-gray-500">
                Última actualización: {{ now()->format('Y-m-d') }}
            </p>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
            <!-- TOC -->
            <aside class="hidden lg:block lg:col-span-3">
                <div class="sticky top-24 rounded-xl border bg-white dark:bg-neutral-900 p-4">
                    <h3 class="text-xs font-semibold uppercase text-gray-500 mb-3">Contenido</h3>
                    <nav class="text-sm space-y-2">
                        <a href="#titularidad" class="block hover:underline">1. Titularidad del Software</a>
                        <a href="#licencia" class="block hover:underline">2. Concesión de Uso</a>
                        <a href="#prohibiciones" class="block hover:underline">3. Prohibiciones</a>
                        <a href="#datos" class="block hover:underline">4. Propiedad de Datos</a>
                        <a href="#soporte" class="block hover:underline">5. Soporte y Alcance</a>
                        <a href="#entorno" class="block hover:underline">6. Entorno de Instalación</a>
                        <a href="#responsabilidad" class="block hover:underline">7. Limitación de Responsabilidad</a>
                        <a href="#seguridad" class="block hover:underline">8. Seguridad y Cumplimiento</a>
                        <a href="#actualizaciones" class="block hover:underline">9. Actualizaciones y Cambios</a>
                        <a href="#vigencia" class="block hover:underline">10. Vigencia y Terminación</a>
                        <a href="#jurisdiccion" class="block hover:underline">11. Jurisdicción</a>
                    </nav>
                </div>
            </aside>

            <!-- Contenido -->
            <section class="lg:col-span-9">
                <div class="rounded-2xl border bg-white dark:bg-neutral-900 p-6 lg:p-8 shadow-sm">

                    <div
                        class="mb-6 rounded-xl border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 p-4 text-sm text-blue-900 dark:text-blue-100">
                        <strong>Nota:</strong> El Software es provisto por
                        <strong>{{ config('app.company.name') }}</strong> para uso del Hospital en su red local.
                        Al usar el sistema usted acepta los terminos y condicones de Uso y Aspectos legales. 
                    </div>

                    {{-- Contenido numerado con separación amplia --}}
                    <ol class="space-y-8">
                        <li id="titularidad" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm mt-0.5">1</span>
                                Titularidad del Software
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    El sistema hospitalario "HospySys" es propiedad exclusiva de
                                    <strong>{{ config('app.company.name') }}</strong>
                                    (“Twins Labs”), incluyendo código fuente, componentes, diseño y documentación. No se
                                    transfiere propiedad
                                    intelectual al Hospital.
                                </p>
                            </div>
                        </li>

                        <li id="licencia" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm mt-0.5">2</span>
                                Concesión de Uso
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    Twins Labs concede al Hospital una licencia no exclusiva, no transferible y
                                    revocable para usar el Software
                                    de forma local en su red interna, exclusivamente para sus operaciones. El servicio
                                    del Software se brinda
                                    gratuitamente bajo estos términos.
                                </p>
                            </div>
                        </li>

                        <li id="prohibiciones" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm mt-0.5">3</span>
                                Prohibiciones
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <ul>
                                    <li>Comercialización, venta, sublicencia, cesión o distribución a terceros.</li>
                                    <li>Ingeniería inversa, descompilación o modificación sin consentimiento escrito de
                                        Twins Labs.</li>
                                    <li>Instalación fuera de la infraestructura autorizada del Hospital.</li>
                                </ul>
                            </div>
                        </li>

                        <li id="datos" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm mt-0.5">4</span>
                                Propiedad y Titularidad de Datos
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>Los datos generados y almacenados son propiedad del Hospital. Twins Labs no reclama
                                    titularidad sobre ellos.</p>
                            </div>
                        </li>

                        <li id="soporte" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm mt-0.5">5</span>
                                Soporte y Alcance
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    Twins Labs brindará soporte razonable de uso básico. <em>Configuraciones básicas y
                                        predeterminadas</em> no generan costo.
                                    Cualquier <strong>actualización, mejora o desarrollo a medida</strong> requiere
                                    análisis y presupuesto con valor monetario.
                                </p>
                            </div>
                        </li>

                        <li id="entorno" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm mt-0.5">6</span>
                                Entorno de Instalación
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    Instalación local en la red del Hospital. Twins Labs no asume responsabilidad por
                                    seguridad física/lógica de la
                                    infraestructura (redes, hardware, respaldos, accesos).
                                </p>
                            </div>
                        </li>

                        <li id="responsabilidad" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm mt-0.5">7</span>
                                Limitación de Responsabilidad
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    Twins Labs no será responsable por pérdidas de información, robo, daño o
                                    manipulación del sistema o datos cuando la
                                    instalación/operación se realicen fuera de servidores administrados por Twins Labs o
                                    sin su consentimiento expreso.
                                    El Hospital es responsable de respaldos, DRP y controles de acceso.
                                </p>
                            </div>
                        </li>

                        <li id="seguridad" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm mt-0.5">8</span>
                                Seguridad y Cumplimiento
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    El Hospital debe cumplir la normativa aplicable (protección de datos personales y
                                    registros de salud), incluyendo resguardo
                                    de credenciales, auditoría y trazabilidad.
                                </p>
                            </div>
                        </li>

                        <li id="actualizaciones" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm mt-0.5">9</span>
                                Actualizaciones y Cambios
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    Twins Labs podrá publicar correcciones y mejoras. Su adopción puede requerir
                                    asistencia técnica y/o presupuesto. Cambios
                                    críticos serán coordinados previamente.
                                </p>
                            </div>
                        </li>

                        <li id="vigencia" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm mt-0.5">10</span>
                                Vigencia y Terminación
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    Rige mientras el Hospital use el Software. Twins Labs podrá revocar la licencia por
                                    incumplimiento, con aviso razonable.
                                    Al terminar, el Hospital cesará el uso y eliminará copias; mantiene la titularidad
                                    de sus datos.
                                </p>
                            </div>
                        </li>

                        <li id="jurisdiccion" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-sm mt-0.5">11</span>
                                Jurisdicción
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>Las partes se someten a las leyes y tribunales del lugar donde opere el Hospital,
                                    salvo pacto escrito en contrario.</p>
                            </div>
                        </li>
                    </ol>

                    <div class="mt-10 pt-6 border-t text-xs text-gray-500">
                        Documento para: <strong>{{ config('app.company.name') }}</strong> · Tel:
                        {{ config('app.company.phone') }}
                    </div>
                </div>
            </section>
        </div>
    </main>

    {{-- footer centrado ya lo tienes como componente; si prefieres inline, pega tu HTML aquí --}}
</body>

</html>