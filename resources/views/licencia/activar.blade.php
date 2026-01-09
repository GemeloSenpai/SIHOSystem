<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Activación de licencia — {{ config('app.name','Sistema Hospital') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body x-data="{ modalOpen:false, modalTitle:'', modalBody:'' }"
    class="h-full min-h-screen bg-gray-50 dark:bg-neutral-950 text-gray-800 dark:text-gray-100">

    {{-- Header --}}
    <header class="w-full border-b bg-white/90 dark:bg-neutral-900/90 backdrop-blur">
        <div class="max-w-7xl mx-auto px-4 lg:px-6 h-14 flex items-center justify-between">
            <a href="{{ url('/') }}" class="font-semibold tracking-tight hover:opacity-80">
                {{ config('app.name','Sistema Hospital') }}
            </a>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <svg class="w-4 h-4 opacity-70" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M12 2a7 7 0 00-7 7v3H4a2 2 0 00-2 2v6h20v-6a2 2 0 00-2-2h-1V9a7 7 0 00-7-7zm0 2a5 5 0 015 5v3H7V9a5 5 0 015-5z" />
                </svg>
                Licencias
            </div>
        </div>
    </header>

    {{-- Dispara el toast del sistema según la sesión --}}
    @if(session('ok') || session('alerta') || session('error'))
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('ok'))
        window.toastOk(@json(session('ok')));
        @endif
        @if(session('alerta'))
        window.toastWarn(@json(session('alerta')));
        @endif
        @if(session('error'))
        window.toastErr(@json(session('error')));
        @endif
    });
    </script>
    @endif

    <main class="w-full min-h-[calc(100vh-3.5rem)] flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-5xl">

            {{-- ======= HERO ======= --}}
            <div class="flex items-start gap-4 mb-6">
                <div class="h-12 w-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2a7 7 0 00-7 7v3H4a2 2 0 00-2 2v6h20v-6a2 2 0 00-2-2h-1V9a7 7 0 00-7-7zm0 2a5 5 0 015 5v3H7V9a5 5 0 015-5z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl sm:text-3xl font-bold leading-tight">Activación de licencia</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        Ingrese su clave para habilitar el sistema. Si no tiene licencia, escríbanos a
                        <button type="button" class="underline hover:no-underline" @click="modalTitle='Soporte'; modalBody=`<p class='mb-2'>Contáctenos:</p>
                                <ul class=\'list-disc ml-5 space-y-1 text-sm\'>
                                    <li>Email: <code class=\'font-mono\'>twinslaboratories@gmail.com</code></li>
                                    <li>Teléfono/WhatsApp: <code class=\'font-mono\'>+504 3396 0213</code></li>
                                </ul>`; modalOpen=true;">
                            soporte
                        </button>.
                    </p>
                </div>
            </div>

            @php
            $licActiva = $lic && (is_null($lic->expira_en) || \Carbon\Carbon::parse($lic->expira_en)->isFuture());
            $isPerma = $lic && is_null($lic->expira_en);
            $expiraCarbon = ($lic && $lic->expira_en) ? \Carbon\Carbon::parse($lic->expira_en) : null;

            $progressPct = null;
            if ($lic && $lic->activada_en && $lic->expira_en) {
            $inicio = \Carbon\Carbon::parse($lic->activada_en);
            $fin = \Carbon\Carbon::parse($lic->expira_en);
            $total = max($inicio->diffInSeconds($fin), 1);
            $resto = now()->greaterThanOrEqualTo($fin) ? 0 : now()->diffInSeconds($fin);
            $usado = $total - $resto;
            $progressPct = min(100, max(0, round(($usado / $total) * 100)));
            }
            @endphp

            {{-- ======= GRID PRINCIPAL ======= --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- ======= ESTADO ======= --}}
                <section class="rounded-2xl border bg-white dark:bg-neutral-900 shadow-sm p-6">
                    {{-- Banner estado --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs
                            {{ $licActiva ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                                @if($licActiva)
                                <path d="M9 12l2 2 4-4 2 2-6 6-4-4 2-2z" />
                                @else
                                <path d="M12 2a10 10 0 1010 10A10.012 10.012 0 0012 2zm1 15h-2v-2h2zm0-4h-2V7h2z" />
                                @endif
                            </svg>
                            {{ $licActiva ? 'Licencia activa' : 'Sin licencia' }}
                        </span>

                        @if($lic?->tipo)
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs
                                {{ $lic->tipo === 'pro' ? 'bg-blue-100 text-blue-700' :
                                   ($lic->tipo === 'enterprise' ? 'bg-purple-100 text-purple-700' :
                                   ($lic->tipo === 'trial' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700')) }}">
                            {{ Str::ucfirst($lic->tipo) }}
                        </span>
                        @endif

                        @if(!$isPerma && $expiraCarbon)
                        @php $daysLeft = now()->lessThan($expiraCarbon) ? now()->diffInDays($expiraCarbon) : 0; @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs
                                 {{ $daysLeft <= 7 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $licActiva ? ($daysLeft > 0 ? "Expira en {$daysLeft} día".($daysLeft===1?'':'s') : 'Expira hoy') : 'Expirada' }}
                        </span>
                        @elseif($isPerma && $licActiva)
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                            Permanente
                        </span>
                        @endif
                    </div>

                    {{-- Progreso --}}
                    @if(!is_null($progressPct))
                    <div class="mt-5">
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                            <span>Vigencia de la licencia</span>
                            <span>{{ $progressPct }}%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-gray-100 dark:bg-neutral-800 overflow-hidden">
                            <div class="h-2 bg-gradient-to-r from-blue-500 to-indigo-600"
                                style="width: {{ $progressPct }}%"></div>
                        </div>
                        <div class="mt-2 text-xs text-gray-500 flex items-center justify-between">
                            <span>Desde: {{ \Carbon\Carbon::parse($lic->activada_en)->format('Y-m-d H:i') }}</span>
                            <span>Hasta: {{ \Carbon\Carbon::parse($lic->expira_en)->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                    @endif

                    {{-- Detalles --}}
                    <div class="mt-6 grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl border p-3">
                            <div class="text-xs uppercase text-gray-500 mb-0.5">Tipo</div>
                            <div>{{ $lic->tipo ?? '—' }}</div>
                        </div>
                        <div class="rounded-xl border p-3">
                            <div class="text-xs uppercase text-gray-500 mb-0.5">Estado</div>
                            <div>{{ $lic->estado ?? '—' }}</div>
                        </div>
                        <div class="rounded-xl border p-3 col-span-2">
                            <div class="text-xs uppercase text-gray-500 mb-0.5">Expira</div>
                            <div>
                                @if(!empty($lic?->expira_en))
                                {{ \Carbon\Carbon::parse($lic->expira_en)->format('Y-m-d H:i') }}
                                @elseif($lic)
                                Permanente
                                @else
                                —
                                @endif
                            </div>
                        </div>
                        <div class="rounded-xl border p-3 col-span-2">
                            <div class="text-xs uppercase text-gray-500 mb-0.5">Clave Aplicada</div>
                            <div>
                                @if(!empty($terminaEn))
                                termina en ••••-••••-•••{{ $terminaEn }}
                                @else
                                —
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ======= FORMULARIO ======= --}}
                <section class="rounded-2xl border bg-white dark:bg-neutral-900 shadow-sm p-6">
                    <form method="POST" action="{{ route('licencia.activar') }}" x-data="{
                            val: '{{ old('clave', '') }}',
                            valid:false,
                            format(){
                                let s=(this.val||'').toUpperCase().replace(/[^A-Z0-9]/g,'');
                                let parts=[]; for(let i=0;i<s.length;i+=4) parts.push(s.substr(i,4));
                                this.val=parts.slice(0,3).join('-');
                                this.valid = (this.val.length===14); // AAAA-BBBB-CCCC
                            }
                          }" x-init="format()" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium mb-1">Clave de licencia</label>
                            <div
                                class="flex rounded-lg border focus-within:ring-2 focus-within:ring-blue-200 overflow-hidden">
                                <div
                                    class="px-3 hidden sm:flex items-center text-xs text-gray-500 bg-gray-50 dark:bg-neutral-800">
                                    AAAA-BBBB-CCCC
                                </div>
                                <input name="clave" x-model="val" x-on:input="format()"
                                    :class="valid ? 'border-0' : 'border-0'"
                                    class="w-full px-3 py-2 outline-none bg-white dark:bg-neutral-900"
                                    placeholder="AAAA-BBBB-CCCC" autocomplete="off" spellcheck="false">
                                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold"
                                    type="submit">
                                    Activar / Renovar
                                </button>
                            </div>
                            <div class="mt-1 flex items-center gap-2 text-xs">
                                <span :class="valid ? 'text-emerald-600' : 'text-amber-600'">
                                    <template x-if="valid">Formato válido</template>
                                    <template x-if="!valid">Formato: AAAA-BBBB-CCCC</template>
                                </span>
                                @error('clave')
                                <span class="text-rose-600">• {{ $message }}</span>
                                @enderror
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Consejo: copie/pegue la clave y verifique que no tenga espacios al inicio o final.
                            </p>
                        </div>
                    </form>

                    <div class="mt-6 rounded-xl bg-gray-50 dark:bg-neutral-800 p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold">Seguridad de la clave</h3>
                            <button type="button" class="text-xs underline hover:no-underline" @click="modalTitle='¿Qué es la licencia?';
                                            modalBody=`<p class='mb-2 text-sm'>La licencia habilita el acceso y define la vigencia del servicio.</p>
                                            <ul class='list-disc ml-5 space-y-1 text-sm'>
                                                <li>Formatee la clave como <b>AAAA-BBBB-CCCC</b>.</li>
                                                <li>Las letras van en <b>mayúsculas</b>.</li>
                                                <li>Si falla, confirme con soporte que la clave esté activa.</li>
                                            </ul>`; modalOpen=true;">
                                Más información
                            </button>
                        </div>
                        <ul class="mt-2 text-sm text-gray-600 dark:text-gray-300 list-disc ml-5 space-y-1">
                            <li>Incluya los guiones (-) al escribir la clave.</li>
                            <li>Las letras deben ir en mayúsculas.</li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>
    </main>

    {{-- ======= MODAL REUTILIZABLE (centrado, caja media) ======= --}}
    <div x-show="modalOpen" x-cloak x-transition class="fixed inset-0 z-[70] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" @click="modalOpen=false"></div>

        <div class="relative bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl
                    w-[92vw] sm:w-[480px] max-w-[92vw] max-h-[80vh] overflow-hidden flex flex-col">
            <div class="px-5 py-3 border-b flex items-center justify-between">
                <h3 class="text-base font-semibold" x-text="modalTitle"></h3>
                <button class="text-slate-500 hover:text-slate-700" @click="modalOpen=false"
                    aria-label="Cerrar">✕</button>
            </div>
            <div class="px-5 py-4 overflow-y-auto text-sm leading-relaxed">
                <div x-html="modalBody"></div>
            </div>
            <div class="px-5 py-3 border-t flex justify-end bg-white dark:bg-neutral-900">
                <button class="px-3 py-1.5 rounded bg-slate-100 dark:bg-neutral-800 hover:bg-slate-200"
                    @click="modalOpen=false">Cerrar</button>
            </div>
        </div>
    </div>

    @include('components.app-footer')

    {{-- SweetAlert2 (si ya viene en tu app.js puedes omitir esta línea) --}}
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

    {{-- Helpers del sistema: sólo se crean si no existen --}}
    <script>
    (function() {
        if (!window.Swal) return;

        if (!window.toastOk) {
            window.toastOk = function(msg = 'Listo') {
                Swal.fire({
                    icon: 'success',
                    title: msg,
                    timer: 1700,
                    showConfirmButton: false,
                    heightAuto: false
                });
            };
        }
        if (!window.toastWarn) {
            window.toastWarn = function(msg = 'Aviso') {
                Swal.fire({
                    icon: 'warning',
                    title: msg,
                    heightAuto: false
                });
            };
        }
        if (!window.toastErr) {
            window.toastErr = function(html = 'Ocurrió un error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Hay errores',
                    html,
                    heightAuto: false
                });
            };
        }
    })();
    </script>

    {{-- Dispara el toast del sistema según la sesión (hazlo tras 'load' para asegurar dependencias) --}}
    @if(session('ok') || session('alerta') || session('error'))
    <script>
    window.addEventListener('load', () => {
        @if(session('ok'))
        window.toastOk(@json(session('ok')));
        @endif
        @if(session('alerta'))
        window.toastWarn(@json(session('alerta')));
        @endif
        @if(session('error'))
        window.toastErr(@json(session('error')));
        @endif
    });
    </script>
    @endif

</body>

</html>