<x-app-layout>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Title Section -->
      <div class="mb-6">
        <div class="flex items-center mb-2">
          <div class="bg-purple-600 rounded-lg p-2 mr-3">
            <svg class="w-6 h-6 text-white"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
          <div>
            <h2 class="text-2xl font-bold text-gray-900">Bar Orders</h2>
            <p class="text-sm text-gray-600">Monitor dan kelola order minuman (Beverage items)</p>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500 font-medium">Total</p>
              <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
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
              <p class="text-3xl font-bold text-red-700">{{ $stats['baru'] }}</p>
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
              <p class="text-3xl font-bold text-yellow-800">{{ $stats['proses'] }}</p>
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
              <p class="text-3xl font-bold text-green-700">{{ $stats['selesai'] }}</p>
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

      <!-- Filter Tabs -->
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
          <div class="flex space-x-2">
            <a href="{{ route('admin.bar.index') }}"
               class="px-4 py-2 rounded-lg {{ !$status ? 'bg-gray-100 text-gray-700 border-2 border-gray-300' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
              Semua ({{ $counts['semua'] }})
            </a>
            <a href="{{ route('admin.bar.index', ['status' => 'dalam-proses']) }}"
               class="px-4 py-2 rounded-lg {{ $status === 'dalam-proses' ? 'bg-orange-400 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
              Dalam Proses ({{ $counts['dalam_proses'] }})
            </a>
            <a href="{{ route('admin.bar.index', ['status' => 'selesai']) }}"
               class="px-4 py-2 rounded-lg {{ $status === 'selesai' ? 'bg-green-400 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
              Selesai ({{ $counts['selesai'] }})
            </a>
          </div>
        </div>
      </div>

      <!-- Orders Table -->
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          @if ($orders->isEmpty())
            <div class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada order</h3>
              <p class="mt-1 text-sm text-gray-500">Belum ada order bar yang tersedia.</p>
            </div>
          @else
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer / Meja</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beverage Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                @foreach ($orders as $order)
                  @php
                    $completedCount = $order->items->where('is_completed', true)->count();
                    $totalCount = $order->items->count();
                  @endphp
                  <tbody class="bg-white divide-y divide-gray-200"
                         x-data="{ expanded: true }">
                    <tr class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <button @click="expanded = !expanded"
                                  type="button"
                                  class="mr-2 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="h-5 w-5 transform transition-transform duration-200"
                                 :class="{ 'rotate-90': expanded }"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                              <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                          </button>
                          <span class="text-sm font-medium text-gray-900">{{ $order->order_number }}</span>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $order->created_at->format('H:i') }}</div>
                        <div class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y') }}</div>
                      </td>
                      <td class="px-6 py-4">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-5 w-5 text-blue-600"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                              <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                          </div>
                          <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer->profile->full_name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $order->customer->phone ?? 'N/A' }}</div>
                          </div>
                        </div>
                        <div class="text-xs text-purple-600 mt-1">{{ $order->table->area->name ?? 'N/A' }} - Meja {{ $order->table->number ?? 'N/A' }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-900 font-medium">{{ $order->items->count() }} items</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">{{ $completedCount }}/{{ $totalCount }}</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        @if ($order->status === 'baru')
                          <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-md bg-pink-100 text-pink-700 uppercase">
                            Baru
                          </span>
                        @elseif($order->status === 'proses')
                          <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-md bg-yellow-100 text-yellow-700 uppercase">
                            Proses
                          </span>
                        @else
                          <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-md bg-green-100 text-green-700 uppercase">
                            Selesai
                          </span>
                        @endif
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                        <div class="text-xs text-gray-500">{{ $order->payment_method }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @if ($order->status !== 'selesai')
                          <form action="{{ route('admin.bar.complete-all', $order->id) }}"
                                method="POST"
                                class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                              Selesai Semua
                            </button>
                          </form>
                        @endif
                      </td>
                    </tr>
                    <tr x-show="expanded"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="bg-gray-50">
                      <td colspan="8"
                          class="px-6 py-4">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                          <div class="flex items-center mb-4">
                            <svg class="h-5 w-5 text-purple-600 mr-2"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                              <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h4 class="font-bold text-gray-900">Beverage Items ({{ $order->items->count() }})</h4>
                          </div>
                          <div class="space-y-2">
                            @foreach ($order->items as $item)
                              <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg border border-gray-100 hover:border-purple-200 transition">
                                <div class="flex items-center flex-1">
                                  <form action="{{ route('admin.bar.toggle-item', $item->id) }}"
                                        method="POST"
                                        class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="checkbox"
                                           {{ $item->is_completed ? 'checked' : '' }}
                                           onchange="this.form.submit()"
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded cursor-pointer">
                                  </form>
                                  <div class="ml-3 flex-1">
                                    <div class="flex items-center">
                                      <span class="text-sm font-semibold text-gray-900 {{ $item->is_completed ? 'line-through text-gray-400' : '' }}">
                                        {{ $item->recipe->name ?? 'N/A' }}
                                      </span>
                                      <span class="ml-2 px-2 py-0.5 text-xs font-bold rounded bg-purple-100 text-purple-700">
                                        Beverage
                                      </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                      Qty: {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }} = <span class="font-semibold">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</span>
                                    </div>
                                  </div>
                                </div>
                                @if (!$item->is_completed)
                                  <form action="{{ route('admin.bar.toggle-item', $item->id) }}"
                                        method="POST"
                                        class="inline ml-3">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded text-xs font-medium transition">
                                      Selesai
                                    </button>
                                  </form>
                                @else
                                  <span class="ml-3 text-green-600 text-xs font-medium flex items-center">
                                    <svg class="h-4 w-4 mr-1"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                      <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Selesai
                                  </span>
                                @endif
                              </div>
                            @endforeach
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                @endforeach
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
