@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded my-4">
        <strong class="font-bold">Éxito:</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
