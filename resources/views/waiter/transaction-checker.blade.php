<x-waiter-mobile-layout>
  <div class="p-4">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-lg font-bold text-gray-800">Transaksi</h1>
      <a href="{{ route('waiter.transaction-checker', ['tab' => $tab]) }}"
         class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-700 transition">
        <svg class="w-4 h-4"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Refresh
      </a>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-2 mb-4">
      <a href="{{ route('waiter.transaction-checker', ['tab' => 'proses']) }}"
         class="flex-1 text-center py-2 rounded-xl text-sm font-semibold transition
                {{ $tab === 'proses' ? 'bg-orange-500 text-white shadow-sm' : 'bg-gray-100 text-gray-500' }}">
        Dalam Proses
        @if ($prosesCount > 0)
          <span class="ml-1 inline-block bg-white/30 text-{{ $tab === 'proses' ? 'white' : 'orange-500' }} rounded-full px-1.5 text-xs font-bold">
            {{ $prosesCount }}
          </span>
        @endif
      </a>
      <a href="{{ route('waiter.transaction-checker', ['tab' => 'selesai']) }}"
         class="flex-1 text-center py-2 rounded-xl text-sm font-semibold transition
                {{ $tab === 'selesai' ? 'bg-green-500 text-white shadow-sm' : 'bg-gray-100 text-gray-500' }}">
        Selesai
        @if ($selesaiCount > 0)
          <span class="ml-1 inline-block bg-white/30 text-{{ $tab === 'selesai' ? 'white' : 'green-600' }} rounded-full px-1.5 text-xs font-bold">
            {{ $selesaiCount }}
          </span>
        @endif
      </a>
    </div>

    {{-- Empty State --}}
    @if ($orders->isEmpty())
      <div class="flex flex-col items-center justify-center py-20 text-gray-400">
        <svg class="w-14 h-14 mb-3 text-gray-300"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p class="text-sm font-medium">Tidak ada order</p>
        <p class="text-xs mt-1">{{ $tab === 'proses' ? 'Semua order sudah selesai' : 'Belum ada order selesai' }}</p>
      </div>
    @else
      {{-- Order Cards --}}
      <div class="space-y-3">
        @foreach ($orders as $order)
          @php
            $activeItems = $order->items->where('status', '!=', 'cancelled');
            $totalItems = $activeItems->count();
            $servedItems = $activeItems->where('status', 'served')->count();
            $hasUnserved = $activeItems->whereNotIn('status', ['served'])->isNotEmpty();
          @endphp

          <div x-data="{
              expanded: false,
              loading: false,
              hidden: false,
              orderStatus: '{{ $order->status }}',
              servedCount: {{ $servedItems }},
              totalCount: {{ $totalItems }},
              items: @js(
    $activeItems
        ->map(
            fn($i) => [
                'id' => $i->id,
                'name' => $i->item_name,
                'quantity' => $i->quantity,
                'status' => $i->status,
                'location' => $i->preparation_location,
            ],
        )
        ->values()
        ->toArray(),
),
              get progressPct() {
                  return this.totalCount > 0 ? Math.round((this.servedCount / this.totalCount) * 100) : 0;
              },
              itemStatusClass(s) {
                  return {
                      pending: 'bg-red-100 text-red-700',
                      preparing: 'bg-yellow-100 text-yellow-700',
                      ready: 'bg-blue-100 text-blue-700',
                      served: 'bg-green-100 text-green-700',
                  } [s] ?? 'bg-gray-100 text-gray-500';
              },
              itemStatusLabel(s) {
                  return { pending: 'Baru', preparing: 'Proses', ready: 'Siap', served: 'Selesai' } [s] ?? s;
              },
              async checkItem(itemId) {
                  if (this.loading) return;
                  this.loading = true;
                  try {
                      const url = '{{ route('waiter.transaction-checker.checkItem', ':id') }}'.replace(':id', itemId);
                      const res = await fetch(url, {
                          method: 'PATCH',
                          headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
                      });
                      const data = await res.json();
                      if (data.success) {
                          this.items = this.items.map(i => i.id === itemId ? { ...i, status: 'served' } : i);
                          this.servedCount = data.served_count;
                          this.totalCount = data.total_count;
                          this.orderStatus = data.order_status;
                      }
                  } finally { this.loading = false; }
              },
              async checkAll() {
                  if (this.loading || this.orderStatus === 'completed') return;
                  this.loading = true;
                  try {
                      const url = '{{ route('waiter.transaction-checker.checkAll', ':id') }}'.replace(':id', '{{ $order->id }}');
                      const res = await fetch(url, {
                          method: 'PATCH',
                          headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
                      });
                      const data = await res.json();
                      if (data.success) {
                          this.items = this.items.map(i => ({ ...i, status: 'served' }));
                          this.servedCount = this.totalCount;
                          this.orderStatus = data.order_status;
                          this.hidden = true;
                      }
                  } finally { this.loading = false; }
              }
          }"
               x-show="!hidden"
               class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Card Header --}}
            <div class="px-4 pt-4 pb-3">
              <div class="flex items-start justify-between gap-2 mb-2">
                {{-- Table / Customer info --}}
                <div>
                  <div class="flex items-center gap-2 flex-wrap">
                    @if ($order->tableSession?->table)
                      <span class="inline-block bg-slate-800 text-white text-xs font-bold px-2 py-0.5 rounded-lg">
                        Meja {{ $order->tableSession->table->table_number }}
                      </span>
                    @endif
                    @if ($order->tableSession?->table?->area)
                      <span class="text-xs text-gray-400">{{ $order->tableSession->table->area->name }}</span>
                    @endif
                  </div>
                  <div class="mt-1 text-sm font-semibold text-gray-800">
                    {{ $order->tableSession?->customer?->name ?? 'Guest' }}
                  </div>
                  @if ($order->ordered_at)
                    <div class="text-xs text-gray-400 mt-0.5">{{ $order->ordered_at->format('H:i · d M Y') }}</div>
                  @endif
                </div>

                {{-- Order status badge --}}
                <span class="shrink-0 inline-block text-xs font-semibold px-2 py-1 rounded-full
                             {{ match ($order->status) {
                                 'pending' => 'bg-red-100 text-red-700',
                                 'preparing' => 'bg-yellow-100 text-yellow-700',
                                 'ready' => 'bg-blue-100 text-blue-700',
                                 'completed' => 'bg-green-100 text-green-700',
                                 default => 'bg-gray-100 text-gray-500',
                             } }}">
                  {{ match ($order->status) {
                      'pending' => 'Baru',
                      'preparing' => 'Proses',
                      'ready' => 'Siap',
                      'completed' => 'Selesai',
                      default => $order->status,
                  } }}
                </span>
              </div>

              {{-- Progress bar --}}
              <div class="flex items-center gap-2 mb-3">
                <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                  <div class="bg-orange-500 h-1.5 rounded-full transition-all duration-300"
                       :style="`width: ${progressPct}%`"></div>
                </div>
                <span class="text-xs text-gray-500 whitespace-nowrap"
                      x-text="`${servedCount}/${totalCount}`"></span>
              </div>

              {{-- Action buttons --}}
              <div class="flex gap-2">
                <button @click="expanded = !expanded"
                        class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl bg-gray-100 text-gray-600 text-xs font-medium transition hover:bg-gray-200">
                  <svg class="w-3.5 h-3.5 transition-transform duration-200"
                       :class="{ 'rotate-180': expanded }"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                  </svg>
                  <span x-text="expanded ? 'Sembunyikan' : `Lihat ${totalCount} Item`"></span>
                </button>

                @if ($tab === 'proses')
                  <button @click="checkAll()"
                          :disabled="loading || orderStatus === 'completed'"
                          :class="orderStatus === 'completed'
                              ?
                              'bg-gray-100 text-gray-400 cursor-not-allowed opacity-60' :
                              'bg-slate-800 text-white hover:bg-slate-700'"
                          class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                      <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tandai Semua
                  </button>
                @endif
              </div>
            </div>

            {{-- Items List (expandable) --}}
            <div x-show="expanded"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="border-t border-gray-100 px-4 py-3 bg-gray-50">
              <div class="space-y-2">
                <template x-for="item in items"
                          :key="item.id">
                  <div class="flex items-center gap-3 bg-white rounded-xl px-3 py-2.5 border border-gray-100 shadow-xs">
                    {{-- Item info --}}
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-800 text-sm truncate"
                              x-text="item.name"></span>
                        <span class="shrink-0 text-xs bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded font-mono"
                              x-text="`×${item.quantity}`"></span>
                      </div>
                      <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                              :class="itemStatusClass(item.status)"
                              x-text="itemStatusLabel(item.status)"></span>
                        <span x-show="item.location"
                              class="text-xs text-gray-400"
                              x-text="'· ' + item.location"></span>
                      </div>
                    </div>

                    {{-- Check button --}}
                    @if ($tab === 'proses')
                      <button @click="checkItem(item.id)"
                              :disabled="item.status === 'served' || loading"
                              :class="item.status === 'served' ?
                                  'bg-green-500 text-white cursor-default' :
                                  'bg-slate-800 text-white hover:bg-slate-700'"
                              class="shrink-0 w-9 h-9 rounded-xl flex items-center justify-center transition">
                        <svg class="w-4 h-4"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                          <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                      </button>
                    @endif
                  </div>
                </template>
              </div>
            </div>

          </div>
        @endforeach
      </div>
    @endif

  </div>
</x-waiter-mobile-layout>
