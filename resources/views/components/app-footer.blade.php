<footer class="w-full border-t mt-10">
    <div class="max-w-7xl mx-auto px-4 py-6 text-sm text-gray-600
              flex flex-col items-center justify-center gap-2 text-center">

        <div class="flex flex-col sm:flex-row items-center justify-center gap-2">
            <span class="font-semibold">{{ config('app.company.name') }}</span>
            <span class="hidden sm:inline">·</span>
            <a href="tel:{{ config('app.company.phone') }}" class="hover:underline">
                Contactanos: {{ config('app.company.phone') }}
            </a>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2">
          <p> Email: twinslaboratories@gmail.com </p>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2">
            <a class="hover:underline" href="{{ route('legal.terminos') }}">Términos y Condiciones</a>
            <a class="hover:underline" href="{{ route('legal.aspectos') }}">Aspectos Legales</a>
            @if(config('app.company.website'))
            <a class="hover:underline" href="{{ config('app.company.website') }}" target="_blank" rel="noopener">Sitio
                web</a>
            @endif
        </div>

        <div class="text-xs text-gray-400">
            © {{ now()->year }} {{ config('app.company.name') }}. Todos los derechos reservados.
        </div>
    </div>
</footer>