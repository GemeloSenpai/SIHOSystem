<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 leading-tight text-center">
            Registros de signos vitales — {{ $paciente->persona->nombre }} {{ $paciente->persona->apellido }}
        </h2>
    </x-slot>

    <style>[x-cloak]{display:none!important}</style>

    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <script>
      const TOAST_MS = window.TOAST_DURATION_MS ?? 2000;
      function toastFire(icon, title) {
        if (window.Swal && Swal.fire) {
          Swal.fire({
            icon, title, toast:true, position:'top-end',
            timer:TOAST_MS, timerProgressBar:true,
            showConfirmButton:false, heightAuto:false,
          });
          return TOAST_MS;
        }
        alert(title); return 0;
      }
      window.toastOkDelay   = (msg)=>toastFire('success', msg);
      window.toastWarnDelay = (msg)=>toastFire('warning', msg);
      window.toastErrDelay  = (msg)=>toastFire('error',   msg);

      document.addEventListener('DOMContentLoaded', () => {
        @if(session('success')) toastOkDelay(@json(session('success'))); @endif
        @if(session('error'))   toastErrDelay(@json(session('error')));  @endif
      });
    </script>


        <div class="bg-white p-6 rounded shadow">
            @if ($registros->isEmpty())
                <p class="text-gray-600">No hay signos vitales registrados aún para este paciente.</p>
            @else
                <div class="w-full overflow-x-auto" style="-webkit-overflow-scrolling:touch; overscroll-behavior-x:contain;">
                    <p class="text-xl font-bold text-gray-800 leading-tight text-center">Historial de Signos Vitales</p><br>

                    <table class="w-full table-auto border text-xs md:text-sm bg-white shadow-md rounded">
                        <thead class="bg-gray-200 text-gray-700 uppercase text-xs font-bold">
                            <tr>
                                <th class="border px-2 py-1">Fecha</th>
                                <th class="border px-2 py-1">PA</th>
                                <th class="border px-2 py-1">FC</th>
                                <th class="border px-2 py-1">FR</th>
                                <th class="border px-2 py-1">Temp</th>
                                <th class="border px-2 py-1">SO2</th>
                                <th class="border px-2 py-1">Peso</th>
                                <th class="border px-2 py-1">Glucosa</th>
                                <th class="border px-2 py-1 text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach ($registros as $reg)
                            <tr
                              x-data="{
                                edit:false,
                                saving:false,
                                highlight:false,
                                tmp: {
                                  pa: @js($reg->presion_arterial),
                                  fc: @js($reg->fc),
                                  fr: @js($reg->fr),
                                  temp: @js($reg->temperatura),
                                  so2: @js($reg->so2),
                                  peso: @js($reg->peso),
                                  glu: @js($reg->glucosa),
                                },
                                async save(e){
                                  if(this.saving) return;
                                  this.saving = true;
                                  const url = e.target.action;
                                  const payload = {
                                    _method: 'PUT',
                                    presion_arterial: this.tmp.pa,
                                    fc: this.tmp.fc,
                                    fr: this.tmp.fr,
                                    temperatura: this.tmp.temp,
                                    so2: this.tmp.so2,
                                    peso: this.tmp.peso,
                                    glucosa: this.tmp.glu,
                                  };
                                  try{
                                    const res = await fetch(url, {
                                      method: 'POST',
                                      headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                      },
                                      body: JSON.stringify(payload),
                                    });
                                    if(!res.ok){
                                      const js = await res.json().catch(()=>null);
                                      toastErrDelay(js?.message ?? 'No se pudo actualizar');
                                      return;
                                    }
                                    this.edit = false;
                                    this.highlight = true;
                                    setTimeout(()=> this.highlight=false, 1200);

                                    // CAMBIO PRINCIPAL: toast + recarga tras 2s
                                    const ms = toastOkDelay('Signos vitales actualizados');
                                    setTimeout(()=> location.reload(), ms);
                                  }catch(err){
                                    toastErrDelay('Error de red al actualizar');
                                  }finally{
                                    this.saving = false;
                                  }
                                }
                              }"
                              :class="highlight ? 'bg-emerald-50' : ''"
                              class="align-top hover:bg-slate-50 transition-colors"
                            >
                              <td class="border px-2 py-1 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($reg->fecha_registro)->format('d/m/Y H:i') }}
                              </td>

                              <td class="border px-2 py-1 align-top">
                                <span x-show="!edit" x-cloak>{{ $reg->presion_arterial }}</span>
                                <input x-show="edit" x-cloak form="f{{ $reg->id_signos_vitales }}"
                                       name="presion_arterial" x-model="tmp.pa" pattern="\d{2,3}/\d{2,3}"
                                       class="w-full border rounded px-2 py-1" placeholder="120/80" >
                              </td>

                              <td class="border px-2 py-1 align-top">
                                <span x-show="!edit" x-cloak>{{ $reg->fc }}</span>
                                <input x-show="edit" x-cloak form="f{{ $reg->id_signos_vitales }}" name="fc"
                                       x-model.number="tmp.fc" type="number" 
                                       class="w-full border rounded px-2 py-1" >
                              </td>

                              <td class="border px-2 py-1 align-top">
                                <span x-show="!edit" x-cloak>{{ $reg->fr }}</span>
                                <input x-show="edit" x-cloak form="f{{ $reg->id_signos_vitales }}" name="fr"
                                       x-model.number="tmp.fr" type="number" 
                                       class="w-full border rounded px-2 py-1" >
                              </td>

                              <td class="border px-2 py-1 align-top">
                                <span x-show="!edit" x-cloak>{{ $reg->temperatura }}</span>
                                <input x-show="edit" x-cloak form="f{{ $reg->id_signos_vitales }}" name="temperatura"
                                       x-model.number="tmp.temp" type="number" step="0.1" 
                                       class="w-full border rounded px-2 py-1" >
                              </td>

                              <td class="border px-2 py-1 align-top">
                                <span x-show="!edit" x-cloak>{{ $reg->so2 }}</span>
                                <input x-show="edit" x-cloak form="f{{ $reg->id_signos_vitales }}" name="so2"
                                       x-model.number="tmp.so2" type="number" 
                                       class="w-full border rounded px-2 py-1" >
                              </td>

                              @php
                                $pesoKg = $reg->peso;
                                $pesoLb = is_numeric($pesoKg) ? round($pesoKg * 2.2046226218, 2) : null; // kg -> lb
                              @endphp

                              <td class="border px-2 py-1 align-top">
                                <span x-show="!edit" x-cloak>{{ $reg->peso }} kg ({{ rtrim(rtrim(number_format($pesoLb, 2, '.', ''), '0'), '.') }} lb)</span>
                                <input x-show="edit" x-cloak form="f{{ $reg->id_signos_vitales }}" name="peso"
                                       x-model.number="tmp.peso" type="number" step="0.1" 
                                       class="w-full border rounded px-2 py-1" >
                              </td>

                              <td class="border px-2 py-1 align-top">
                                <span x-show="!edit" x-cloak>{{ $reg->glucosa }}</span>
                                <input x-show="edit" x-cloak form="f{{ $reg->id_signos_vitales }}" name="glucosa"
                                       x-model.number="tmp.glu" type="number" step="0.1" 
                                       class="w-full border rounded px-2 py-1" >
                              </td>

                              <td class="border px-2 py-1 text-center whitespace-nowrap">
                                <form
                                  id="f{{ $reg->id_signos_vitales }}"
                                  method="POST"
                                  action="{{ route('enfermeria.signos.update', $reg->id_signos_vitales) }}"
                                  class="inline"
                                  @submit.prevent="save($event)"
                                >
                                  @csrf
                                  @method('PUT')
                                  <div x-show="edit" x-cloak class="inline-flex gap-2">
                                    <button type="submit"
                                      :disabled="saving"
                                      class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 disabled:cursor-not-allowed text-white px-3 py-1 rounded text-xs font-semibold">
                                      <template x-if="!saving">💾</template>
                                      <template x-if="saving">⏳</template>
                                      <span class="hidden md:inline" x-text="saving ? 'Guardando...' : 'Guardar'"></span>
                                    </button>
                                    <button type="button" @click="edit=false" :disabled="saving"
                                      class="inline-flex items-center gap-1 bg-slate-200 hover:bg-slate-300 text-gray-900 px-3 py-1 rounded text-xs font-semibold">
                                      ✖️ <span class="hidden md:inline">Cancelar</span>
                                    </button>
                                  </div>
                                </form>

                                <button x-show="!edit" x-cloak @click="edit=true"
                                  class="inline-flex items-center gap-1 bg-amber-100 hover:bg-amber-200 border border-amber-300 text-black px-3 py-1 rounded text-xs font-semibold">
                                  ✏️ <span class="hidden md:inline">Editar</span>
                                </button>
                              </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $registros->links() }}
                    </div>
                </div>
            @endif

            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('enfermero.signosvitales.form', ['id' => $paciente->id_paciente]) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-center">
                    ← Registrar Signos Vitales Otra Vez Para Este Paciente
                </a>

                <a href="{{ route('enfermero.signosvitales.form') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-900 px-4 py-2 rounded text-center">
                    Buscar paciente para Registrar Signos Vitales
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
