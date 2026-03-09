<x-app-layout>
  <div class="p-6">

    <!-- Back -->
    <a href="{{ route('admin.settings.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-slate-600 hover:text-slate-800 mb-6">
      <svg class="w-4 h-4"
           fill="none"
           stroke="currentColor"
           viewBox="0 0 24 24">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M10 19l-7-7m0 0l7-7m-7 7h18" />
      </svg>
      Kembali ke Menu Pengaturan
    </a>

    @if (session('success'))
      <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
        {{ session('success') }}
      </div>
    @endif

    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-slate-800">Kategori POS</h1>
      <p class="text-sm text-slate-500 mt-1">Atur kategori mana yang tampil di POS, sumbernya, dan ke mana diarahkan</p>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6 text-sm text-blue-700">
      <p class="font-semibold mb-2">Petunjuk:</p>
      <ul class="space-y-1 list-disc list-inside">
        <li><strong>Tampil di POS</strong> — aktifkan agar kategori ini muncul di halaman POS.</li>
        <li><strong>Sumber</strong> — <em>BOM</em>: dari resep BOM, <em>Inventory</em>: langsung dari stok, <em>Keduanya</em>: gabungan.</li>
        <li><strong>Arah</strong> — <em>Kitchen</em>: kirim ke dapur, <em>Bar</em>: kirim ke bar, <em>Langsung</em>: masuk transaksi saja tanpa order kitchen/bar.</li>
      </ul>
    </div>

    @if ($knownTypes->isEmpty())
      <div class="bg-white border border-slate-200 rounded-xl p-10 text-center text-slate-500">
        Belum ada data inventory. Sync dari Accurate terlebih dahulu.
      </div>
    @else
      <form method="POST"
            action="{{ route('admin.settings.pos-categories.save') }}">
        @csrf
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
          <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
              <tr>
                <th class="px-5 py-3 text-left font-medium text-slate-600">Kategori</th>
                <th class="px-5 py-3 text-center font-medium text-slate-600">Tampil di POS</th>
                <th class="px-5 py-3 text-left font-medium text-slate-600">Sumber</th>
                <th class="px-5 py-3 text-left font-medium text-slate-600">Arah</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @foreach ($knownTypes as $type)
                @php $s = $settings->get($type) @endphp
                <tr class="hover:bg-slate-50 transition">
                  <td class="px-5 py-3 font-medium text-slate-800">{{ $type }}</td>

                  {{-- Tampil di POS toggle --}}
                  <td class="px-5 py-3 text-center">
                    <input type="hidden"
                           name="categories[{{ $type }}][_present]"
                           value="1">
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input type="checkbox"
                             name="show_in_pos[{{ $type }}]"
                             value="1"
                             class="sr-only peer"
                             {{ $s && $s->show_in_pos ? 'checked' : '' }}>
                      <div class="w-9 h-5 bg-slate-200 rounded-full peer peer-checked:bg-indigo-500
                                  after:content-[''] after:absolute after:top-0.5 after:left-[2px]
                                  after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all
                                  peer-checked:after:translate-x-full"></div>
                    </label>
                  </td>

                  {{-- Sumber --}}
                  <td class="px-5 py-3">
                    <select name="categories[{{ $type }}][source]"
                            class="text-sm border border-slate-200 rounded-lg px-3 py-1.5 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                      <option value="bom"
                              {{ !$s || $s->source === 'bom' ? 'selected' : '' }}>BOM (Resep)</option>
                      <option value="inventory"
                              {{ $s && $s->source === 'inventory' ? 'selected' : '' }}>Langsung Inventory</option>
                      <option value="both"
                              {{ $s && $s->source === 'both' ? 'selected' : '' }}>Keduanya</option>
                    </select>
                  </td>

                  {{-- Arah --}}
                  <td class="px-5 py-3">
                    <select name="categories[{{ $type }}][preparation_location]"
                            class="text-sm border border-slate-200 rounded-lg px-3 py-1.5 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                      <option value="kitchen"
                              {{ $s && $s->preparation_location === 'kitchen' ? 'selected' : '' }}>🍳 Kitchen</option>
                      <option value="bar"
                              {{ !$s || $s->preparation_location === 'bar' ? 'selected' : '' }}>🍹 Bar</option>
                      <option value="direct"
                              {{ $s && $s->preparation_location === 'direct' ? 'selected' : '' }}>⚡ Langsung (tanpa order)</option>
                    </select>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="mt-5 flex justify-end">
          <button type="submit"
                  class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
            Simpan Pengaturan
          </button>
        </div>
      </form>
    @endif
  </div>
</x-app-layout>
