<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Aspectos Legales — {{ config('app.company.name') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-full bg-gray-50 dark:bg-neutral-950 text-gray-800 dark:text-gray-100">

    <header class="border-b bg-white/70 dark:bg-neutral-900/70 backdrop-blur">
        <div class="max-w-7xl mx-auto px-4 lg:px-6 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="font-semibold hover:opacity-80">{{ config('app.name', 'Hospital') }}</a>
            <nav class="text-sm flex items-center gap-4">
                <a href="{{ route('legal.terminos') }}" class="hover:underline">Términos</a>
                <a href="{{ route('legal.aspectos') }}" class="font-medium text-blue-600">Aspectos</a>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 lg:px-6 py-10 lg:py-14">
        <section class="text-center mb-8 lg:mb-10">
            <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Aspectos Legales y de Cumplimiento</h1>
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
                        <a href="#pi" class="block hover:underline">1. Propiedad Intelectual</a>
                        <a href="#pd" class="block hover:underline">2. Protección de Datos</a>
                        <a href="#soporte" class="block hover:underline">3. Soporte y Mantenimientos</a>
                        <a href="#lr" class="block hover:underline">4. Limitación de Responsabilidad</a>
                        <a href="#ua" class="block hover:underline">5. Uso Autorizado</a>
                        <a href="#auditorias" class="block hover:underline">6. Auditorías</a>
                        <a href="#contacto" class="block hover:underline">7. Contacto</a>
                    </nav>
                </div>
            </aside>

            <!-- Contenido -->
            <section class="lg:col-span-9">
                <div class="rounded-2xl border bg-white dark:bg-neutral-900 p-6 lg:p-8 shadow-sm">

                    <div
                        class="mb-6 rounded-xl border-l-4 border-amber-500 bg-amber-50 dark:bg-amber-900/20 p-4 text-sm text-amber-900 dark:text-amber-100">
                        <strong>Recomendación:</strong> Definir políticas internas de respaldo cifrado, control de
                        accesos,
                        gestión de vulnerabilidades y respuesta a incidentes para el entorno local del Hospital.
                    </div>

                    {{-- Contenido numerado con separación amplia --}}
                    <ol class="space-y-8">
                        <li id="pi" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-700 text-sm mt-0.5">1</span>
                                Propiedad Intelectual
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    Todo derecho de propiedad intelectual sobre el Software corresponde a
                                    <strong>{{ config('app.company.name') }}</strong>.
                                    Su uso se limita a la licencia otorgada al Hospital.
                                </p>
                            </div>
                        </li>

                        <li id="pd" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-700 text-sm mt-0.5">2</span>
                                Protección de Datos
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <ul>
                                    <li>El Hospital es titular de los datos y responsable del cumplimiento regulatorio
                                        aplicable.</li>
                                    <li>Se recomienda cifrado de copias de seguridad, políticas de retención y registros
                                        de acceso.</li>
                                    <li>El Hospital debe notificar a Twins Labs ante incidentes de seguridad que afecten
                                        al Software.</li>
                                </ul>
                            </div>
                        </li>

                        <li id="soporte" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-700 text-sm mt-0.5">3</span>
                                Soporte y Mantenimientos
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    Twins Labs colaborará con soporte básico. Mantenimientos evolutivos o integraciones
                                    quedan sujetos
                                    a presupuesto y cronograma acordados con el Hospital.
                                </p>
                            </div>
                        </li>

                        <li id="lr" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-700 text-sm mt-0.5">4</span>
                                Limitación de Responsabilidad
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    En instalaciones locales del Hospital, Twins Labs no asume responsabilidad por
                                    fallos de infraestructura,
                                    medidas de seguridad inadecuadas, accesos no autorizados o pérdidas por falta de
                                    respaldos.
                                </p>
                            </div>
                        </li>

                        <li id="ua" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-700 text-sm mt-0.5">5</span>
                                Uso Autorizado
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    Se prohíbe la comercialización o distribución a terceros. Cualquier despliegue fuera
                                    de la red del Hospital
                                    requiere autorización escrita de Twins Labs.
                                </p>
                            </div>
                        </li>

                        <li id="auditorias" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-700 text-sm mt-0.5">6</span>
                                Auditorías
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    El Hospital puede habilitar auditorías sobre el uso del Software y accesos, de
                                    acuerdo con su normativa interna
                                    y la ley aplicable.
                                </p>
                            </div>
                        </li>

                        <li id="contacto" class="scroll-mt-24">
                            <h2 class="text-xl lg:text-2xl font-semibold flex items-start gap-3">
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-700 text-sm mt-0.5">7</span>
                                Contacto
                            </h2>
                            <div class="mt-3 prose prose-blue dark:prose-invert max-w-none">
                                <p>
                                    Para soporte o consultas legales: <strong>{{ config('app.company.name') }}</strong>
                                    · Tel: {{ config('app.company.phone') }}
                                    @if(config('app.company.website'))
                                    · Sitio: <a href="{{ config('app.company.website') }}"
                                        class="text-blue-600 hover:underline" target="_blank" rel="noopener">
                                        {{ config('app.company.website') }}
                                    </a>
                                    @endif
                                </p>
                            </div>
                        </li>
                    </ol>

                    <div class="mt-10 pt-6 border-t text-xs text-gray-500">
                        Documento informativo. Revísese con asesoría especializada según la jurisdicción del Hospital.
                    </div>
                </div>
            </section>

        </div>
    </main>

</body>

</html>