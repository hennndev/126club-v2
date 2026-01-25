<x-app-layout>
  <div class="p-6">
    @if (session('success'))
      <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        {{ session('success') }}
      </div>
    @endif

    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
        <div class="bg-orange-500 rounded-lg p-2">
          <svg class="w-6 h-6 text-white"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
        </div>
        Kitchen Orders
      </h1>
      <p class="text-sm text-gray-500 mt-1">Monitor dan kelola order makanan (Food items)</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-4 gap-4 mb-6">
      <div class="bg-white border border-gray-200 rounded-xl p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500 font-medium">Total</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
          </div>
          <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-red-600 font-medium">Baru</p>
            <p class="text-3xl font-bold text-red-700">{{ $baruOrders }}</p>
          </div>
          <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-red-600"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-yellow-700 font-medium">Proses</p>
            <p class="text-3xl font-bold text-yellow-800">{{ $prosesOrders }}</p>
          </div>
          <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-yellow-600"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-green-50 border border-green-200 rounded-xl p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-green-600 font-medium">Selesai</p>
            <p class="text-3xl font-bold text-green-700">{{ $selesaiOrders }}</p>
          </div>
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="bg-slate-800 text-white rounded-xl p-4 mb-6">
      <div class="flex items-center gap-4">
        <button onclick="filterByStatus('')"
                id="tabAll"
                class="px-4 py-2 rounded-lg bg-white bg-opacity-20 font-medium transition hover:bg-opacity-30">
          Semua ({{ $totalOrders }})
        </button>
        <button onclick="filterByStatus('proses')"
                id="tabProses"
                class="px-4 py-2 rounded-lg font-medium transition hover:bg-white hover:bg-opacity-20">
          ⏳ Dalam Proses ({{ $prosesOrders }})
        </button>
        <button onclick="filterByStatus('selesai')"
                id="tabSelesai"
                class="px-4 py-2 rounded-lg font-medium transition hover:bg-white hover:bg-opacity-20">
          ✅ Selesai ({{ $selesaiOrders }})
        </button>
      </div>
    </div>

    @if ($orders->count() > 0)
      <!-- Orders Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12"></th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer / Meja</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Food Items</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach ($orders as $order)
                <tr class="order-row hover:bg-gray-50 transition"
                    data-status="{{ $order->status }}">
                  <td class="px-4 py-4">
                    <button onclick="toggleRow({{ $order->id }})"
                            class="text-gray-400 hover:text-gray-600">
                      <svg id="chevron-{{ $order->id }}"
                           class="w-5 h-5 transform transition-transform"
                           fill="none"
                           stroke="currentColor"
                           viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 5l7 7-7 7" />
                      </svg>
                    </button>
                  </td>
                  <td class="px-4 py-4">
                    <div class="font-bold text-gray-900">{{ $order->order_number }}</div>
                  </td>
                  <td class="px-4 py-4">
                    <div class="text-sm">
                      <div class="font-medium text-gray-900">{{ $order->created_at->format('H:i') }}</div>
                      <div class="text-gray-500">{{ $order->created_at->format('d M') }}</div>
                    </div>
                  </td>
                  <td class="px-4 py-4">
                    <div class="flex items-center gap-2">
                      <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                          <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900">{{ $order->customer->profile->name }}</div>
                        <div class="text-xs text-gray-500">{{ $order->customer->profile->phone }}</div>
                        <div class="text-xs text-orange-600 font-medium">🪑 {{ $order->table->area->name }} {{ $order->table->number }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-4">
                    <span class="px-3 py-1 text-sm font-medium rounded bg-orange-100 text-orange-700">
                      {{ $order->items->count() }} items
                    </span>
                  </td>
                  <td class="px-4 py-4">
                    <div class="w-full">
                      <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-medium text-gray-700">{{ $order->items->where('is_completed', true)->count() }}/{{ $order->items->count() }}</span>
                        <span class="font-bold text-gray-900">{{ $order->progress }}%</span>
                      </div>
                      <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gray-900 h-2 rounded-full transition-all"
                             style="width: {{ $order->progress }}%"></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-4">
                    @if ($order->status === 'selesai')
                      <span class="px-3 py-1 text-xs font-medium rounded bg-green-100 text-green-700 inline-flex items-center gap-1">
                        ✓ SELESAI
                      </span>
                    @elseif($order->status === 'proses')
                      <span class="px-3 py-1 text-xs font-medium rounded bg-yellow-100 text-yellow-700 inline-flex items-center gap-1">
                        ⏳ PROSES
                      </span>
                    @else
                      <span class="px-3 py-1 text-xs font-medium rounded bg-red-100 text-red-700 inline-flex items-center gap-1">
                        ⚠ BARU
                      </span>
                    @endif
                  </td>
                  <td class="px-4 py-4">
                    <div class="text-sm">
                      <div class="font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                      <div class="text-xs text-gray-500">
                        @if ($order->payment_method === 'credit-card')
                          credit-card
                        @elseif($order->payment_method === 'debit-card')
                          debit-card
                        @elseif($order->payment_method === 'e-wallet')
                          e-wallet
                        @else
                          cash
                        @endif
                      </div>
                    </div>
                  </td>
                  <td class="px-4 py-4">
                    @if ($order->status !== 'selesai')
                      <form action="{{ route('admin.kitchen.complete-all', $order) }}"
                            method="POST"
                            class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="px-4 py-2 bg-orange-500 text-white text-sm font-medium rounded-lg hover:bg-orange-600 transition">
                          ✓ Selesai Semua
                        </button>
                      </form>
                    @endif
                  </td>
                </tr>
                <!-- Expandable Row -->
                <tr id="details-{{ $order->id }}"
                    class="hidden bg-gray-50">
                  <td colspan="9"
                      class="px-4 py-4">
                    <div class="pl-12">
                      <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-orange-600"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                          <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h4 class="font-bold text-gray-900">Food Items ({{ $order->items->count() }})</h4>
                      </div>
                      <div class="space-y-2">
                        @foreach ($order->items as $item)
                          <div class="flex items-center justify-between bg-white border @if ($item->is_completed) border-green-200 @else border-gray-200 @endif rounded-lg p-3">
                            <div class="flex items-center gap-3 flex-1">
                              <form action="{{ route('admin.kitchen.toggle-item', $item) }}"
                                    method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="w-6 h-6 rounded @if ($item->is_completed) bg-green-500 @else bg-gray-300 @endif flex items-center justify-center transition hover:scale-110">
                                  @if ($item->is_completed)
                                    <svg class="w-4 h-4 text-white"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                      <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                  @endif
                                </button>
                              </form>
                              <div class="flex-1">
                                <div class="flex items-center gap-2">
                                  <span class="font-medium text-gray-900 @if ($item->is_completed) line-through text-gray-500 @endif">
                                    {{ $item->recipe->name }}
                                  </span>
                                  <span class="px-2 py-0.5 text-xs font-medium rounded bg-orange-100 text-orange-700">Food</span>
                                </div>
                                <div class="text-sm text-gray-500">
                                  Qty: {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }} = Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                </div>
                              </div>
                            </div>
                            @if ($item->is_completed)
                              <form action="{{ route('admin.kitchen.toggle-item', $item) }}"
                                    method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="px-3 py-1 text-sm text-gray-600 hover:text-gray-900 transition">
                                  Undo
                                </button>
                              </form>
                            @endif
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @else
      <!-- Empty State -->
      <div class="bg-gradient-to-br from-orange-50 to-white border-2 border-dashed border-orange-300 rounded-xl p-12 text-center">
        <div class="flex items-center justify-center w-20 h-20 mx-auto bg-orange-100 rounded-full mb-4">
          <svg class="w-10 h-10 text-orange-500"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Order</h3>
        <p class="text-gray-500">Belum ada order makanan yang masuk ke kitchen</p>
      </div>
    @endif
  </div>

  @push('scripts')
    <script>
      function toggleRow(orderId) {
        const detailsRow = document.getElementById(`details-${orderId}`);
        const chevron = document.getElementById(`chevron-${orderId}`);

        if (detailsRow.classList.contains('hidden')) {
          detailsRow.classList.remove('hidden');
          chevron.classList.add('rotate-90');
        } else {
          detailsRow.classList.add('hidden');
          chevron.classList.remove('rotate-90');
        }
      }

      function filterByStatus(status) {
        const rows = document.querySelectorAll('.order-row');
        const tabs = ['tabAll', 'tabProses', 'tabSelesai'];

        // Reset tabs
        tabs.forEach(tabId => {
          const tab = document.getElementById(tabId);
          tab.classList.remove('bg-white', 'bg-opacity-20');
        });

        // Set active tab
        let activeTab = 'tabAll';
        if (status === 'proses') activeTab = 'tabProses';
        if (status === 'selesai') activeTab = 'tabSelesai';
        document.getElementById(activeTab).classList.add('bg-white', 'bg-opacity-20');

        // Filter rows
        rows.forEach(row => {
          const nextRow = row.nextElementSibling; // details row
          if (status === '' || row.dataset.status === status) {
            row.style.display = '';
            if (nextRow && !nextRow.classList.contains('hidden')) {
              nextRow.style.display = '';
            }
          } else {
            row.style.display = 'none';
            if (nextRow) {
              nextRow.style.display = 'none';
            }
          }
        });
      }
    </script>
  @endpush
</x-app-layout>
