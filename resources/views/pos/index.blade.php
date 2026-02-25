<x-app-layout>
  <div class="flex w-full h-[calc(100vh-6rem)]"
       x-data="posApp()"
       x-init="init()">

    <div class="flex-1 flex flex-col">
      <div class="flex-1 flex gap-6 p-6 overflow-hidden">
        <!-- Products Section -->
        <div class="flex-1 flex flex-col overflow-hidden">
          <!-- Search & Filter -->
          <div class="flex gap-4 mb-6 flex-shrink-0">
            <div class="flex-1 relative">
              <form method="GET"
                    action="{{ route('admin.pos.index') }}">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari produk..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </form>
            </div>
            <select class="px-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-[120px]">
              <option value="all">🥃 All</option>
            </select>

            <!-- Counter Location Selector -->
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-600">Counter:</span>
              <select id="counterLocationSelect"
                      class="px-4 py-2.5 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-[160px]"
                      x-model="counterLocation"
                      @change="selectCounter($event)">
                <option value="">-- Pilih Counter --</option>
                @foreach ($printerLocations as $group => $locations)
                  <optgroup label="{{ $group }}">
                    @foreach ($locations as $value => $label)
                      <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                  </optgroup>
                @endforeach
              </select>
              <span x-show="counterLocation"
                    x-text="getCounterLabel()"
                    class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full"></span>
            </div>
          </div>

          <!-- Products Grid -->
          <div class="overflow-y-auto flex-1 pr-2">
            <div class="grid grid-cols-4 gap-4">
              @forelse($products as $product)
                <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl overflow-hidden relative">
                  <!-- Favorite Icon -->
                  <button class="absolute top-3 right-3 z-10 w-8 h-8 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center hover:bg-white/30 transition">
                    <svg class="w-4 h-4 text-white"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                      <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                  </button>

                  <!-- Stock Badge -->
                  @if (isset($product['type']) && $product['type'] === 'item' && strtolower($product['category']) === 'drink')
                    <div class="absolute top-3 left-3 z-10">
                      @if ($product['stock'] > 10)
                        <span class="px-2 py-1 bg-green-500 text-white text-xs font-bold rounded">Stock: {{ $product['stock'] }}</span>
                      @elseif($product['stock'] > 0)
                        <span class="px-2 py-1 bg-red-500 text-white text-xs font-bold rounded">Stock: {{ $product['stock'] }}</span>
                      @else
                        <span class="px-2 py-1 bg-gray-500 text-white text-xs font-bold rounded">Stock: 0</span>
                      @endif
                    </div>
                  @endif

                  <!-- Product Image -->
                  <div class="h-32 flex items-center justify-center p-4">
                    <div class="text-6xl">🍸</div>
                  </div>

                  <!-- Product Info -->
                  <div class="bg-white p-4">
                    <h3 class="font-bold text-gray-900 text-sm mb-1 truncate">{{ $product['name'] }}</h3>
                    <p class="text-xs text-gray-500 mb-3">{{ $product['category'] }}</p>

                    <div class="flex items-center justify-between">
                      <div class="font-bold text-gray-900 text-sm">Rp {{ number_format($product['price'], 0, ',', '.') }}</div>
                      <button type="button"
                              @click="addToCart('{{ $product['id'] }}')"
                              :disabled="isProcessing || {{ isset($product['type']) && $product['type'] === 'item' && $product['stock'] <= 0 ? 'true' : 'false' }}"
                              :class="{ 'opacity-50 cursor-not-allowed': isProcessing }"
                              class="w-8 h-8 bg-gray-800 hover:bg-gray-900 text-white rounded-lg flex items-center justify-center transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg x-show="!isProcessing"
                             class="w-4 h-4"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                          <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        <svg x-show="isProcessing"
                             class="w-4 h-4 animate-spin"
                             fill="none"
                             viewBox="0 0 24 24">
                          <circle class="opacity-25"
                                  cx="12"
                                  cy="12"
                                  r="10"
                                  stroke="currentColor"
                                  stroke-width="4"></circle>
                          <path class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              @empty
                <div class="col-span-4 text-center py-12">
                  <svg class="mx-auto h-12 w-12 text-gray-400"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                  </svg>
                  <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada produk</h3>
                  <p class="mt-1 text-sm text-gray-500">Produk belum tersedia.</p>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <!-- Customer Type Selection Modal -->
      <div x-show="showCustomerTypeModal"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0"
           style="display: none;"
           class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60]"
           @click.self="showCustomerTypeModal = false">
        <div class="bg-white rounded-2xl p-6 max-w-lg w-full mx-4"
             @click.stop>
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
              <div class="bg-blue-600 rounded-lg p-2">
                <svg class="w-6 h-6 text-white"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <h3 class="text-xl font-bold text-gray-900">Pilih Pelanggan</h3>
            </div>
            <button @click="showCustomerTypeModal = false"
                    class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <p class="text-gray-600 text-sm mb-6">Pilih tipe pelanggan untuk melanjutkan transaksi</p>

          <div class="grid grid-cols-2 gap-4">
            <!-- Booking Option -->
            <button @click="selectCustomerType('booking')"
                    class="p-6 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition group">
              <div class="bg-blue-600 rounded-lg p-3 w-fit mx-auto mb-4 group-hover:scale-110 transition">
                <svg class="w-8 h-8 text-white"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <h4 class="font-bold text-gray-900 mb-2">Booking</h4>
              <p class="text-xs text-gray-600 mb-3">Pelanggan dengan reservasi</p>
              <div class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full inline-block">
                {{ $tableSessions->count() }} Booking Aktif
              </div>
            </button>

            <!-- Walk-in Option -->
            <button @click="selectCustomerType('walk-in')"
                    class="p-6 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition group">
              <div class="bg-blue-600 rounded-lg p-3 w-fit mx-auto mb-4 group-hover:scale-110 transition">
                <svg class="w-8 h-8 text-white"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <h4 class="font-bold text-gray-900 mb-2">Walk-in</h4>
              <p class="text-xs text-gray-600 mb-3">Pelanggan tanpa reservasi</p>
              <div class="bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full inline-block">
                Coming Soon
              </div>
            </button>
          </div>
        </div>
      </div>

      <!-- Checkout Modal -->
      <div x-show="showCheckoutModal"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0"
           style="display: none;"
           class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60]"
           @click.self="showCheckoutModal = false">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4"
             @click.stop>
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Checkout Order</h3>
            <button @click="showCheckoutModal = false"
                    class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <form @submit.prevent="submitCheckout"
                class="space-y-4">
            <!-- Customer Type (Hidden) -->
            <input type="hidden"
                   name="customer_type"
                   x-model="checkoutForm.customer_type">

            <!-- Customer Selection -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
              <select name="customer_user_id"
                      x-model="checkoutForm.customer_user_id"
                      @change="handleCustomerChange()"
                      required
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Pilih Customer</option>
                @foreach ($tableSessions as $session)
                  <option value="{{ $session->customer_id }}"
                          data-table-id="{{ $session->table_id }}"
                          data-table-number="{{ $session->table->table_number ?? $session->table->number ?? '' }}"
                          data-area-name="{{ $session->table->area->name ?? '' }}">
                    {{ $session->customer->name ?? 'Unknown' }} - {{ $session->table->area->name ?? 'N/A' }} - Meja {{ $session->table->table_number ?? $session->table->number ?? 'N/A' }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Table Selection -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Meja</label>
              <input type="text"
                     x-model="checkoutForm.table_display"
                     readonly
                     class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                     placeholder="Pilih customer terlebih dahulu">
              <input type="hidden"
                     name="table_id"
                     x-model="checkoutForm.table_id">
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
              <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal (<span x-text="cart.length"></span> items)</span>
                <span x-text="formatCurrency(cartTotal)"></span>
              </div>
              <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-gray-200">
                <span>Total</span>
                <span x-text="formatCurrency(cartTotal)"></span>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4">
              <button type="button"
                      @click="showCheckoutModal = false"
                      class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition">
                Batal
              </button>
              <button type="submit"
                      :disabled="isProcessing"
                      class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <svg x-show="isProcessing"
                     class="w-4 h-4 animate-spin"
                     fill="none"
                     viewBox="0 0 24 24">
                  <circle class="opacity-25"
                          cx="12"
                          cy="12"
                          r="10"
                          stroke="currentColor"
                          stroke-width="4"></circle>
                  <path class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="isProcessing ? 'Processing...' : 'Proses Order'"></span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Success Toast -->
      <div x-show="showToast"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="opacity-0 transform translate-y-2"
           x-transition:enter-end="opacity-100 transform translate-y-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="opacity-100 transform translate-y-0"
           x-transition:leave-end="opacity-0 transform translate-y-2"
           @click="showToast = false"
           class="fixed bottom-4 right-4 z-[70] cursor-pointer">
        <div :class="toastType === 'success' ? 'bg-green-500' : 'bg-red-500'"
             class="px-6 py-3 rounded-lg shadow-lg text-white font-medium flex items-center gap-2">
          <svg x-show="toastType === 'success'"
               class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M5 13l4 4L19 7" />
          </svg>
          <svg x-show="toastType === 'error'"
               class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12" />
          </svg>
          <span x-text="toastMessage"></span>
        </div>
      </div>
    </div>

    <!-- Cart Section - Fixed to right edge -->
    <div class="w-96 sticky top-0 right-0 bg-white border-l border-gray-200 flex flex-col h-[calc(100vh-5.5rem)]">
      <div class="p-6 flex flex-col h-full">
        <!-- Cart Header -->
        <div class="flex items-center justify-between mb-6 flex-shrink-0">
          <div class="flex items-center gap-3">
            <div class="bg-blue-600 rounded-lg p-2">
              <svg class="w-5 h-5 text-white"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <div>
              <h3 class="text-gray-900 font-bold">Keranjang</h3>
              <p class="text-gray-500 text-sm"><span x-text="cart.length"></span> item</p>
            </div>
          </div>
          <button x-show="cart.length > 0"
                  @click="clearCart()"
                  :disabled="isProcessing"
                  class="text-gray-400 hover:text-gray-600 disabled:opacity-50"
                  title="Kosongkan">
            <svg class="w-5 h-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>

        <!-- Cart Items -->
        <div class="space-y-3 max-h-[500px] mb-6 flex-1 overflow-y-auto">
          <template x-for="item in cart"
                    :key="item.id">
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 hover:bg-gray-100 transition">
              <div class="flex items-start gap-3">
                <div class="bg-blue-600 rounded-lg p-2 flex-shrink-0">
                  <div class="text-2xl">🍸</div>
                </div>
                <div class="flex-1 min-w-0">
                  <h4 class="text-gray-900 font-semibold text-sm truncate"
                      x-text="item.name"></h4>
                  <p class="text-gray-500 text-xs"
                     x-text="formatCurrency(item.price)"></p>

                  <div class="flex items-center justify-between mt-3">
                    <div class="flex items-center gap-2">
                      <button type="button"
                              @click="updateCartQuantity(item.id, 'decrease')"
                              :disabled="isProcessing"
                              class="w-6 h-6 bg-white border border-gray-300 hover:bg-gray-100 rounded text-gray-700 flex items-center justify-center text-sm font-bold disabled:opacity-50">-</button>
                      <span class="text-gray-900 font-medium w-8 text-center"
                            x-text="item.quantity"></span>
                      <button type="button"
                              @click="updateCartQuantity(item.id, 'increase')"
                              :disabled="isProcessing"
                              class="w-6 h-6 bg-white border border-gray-300 hover:bg-gray-100 rounded text-gray-700 flex items-center justify-center text-sm font-bold disabled:opacity-50">+</button>
                    </div>
                    <button type="button"
                            @click="removeFromCart(item.id)"
                            :disabled="isProcessing"
                            class="text-red-500 hover:text-red-700 disabled:opacity-50">
                      <svg class="w-4 h-4"
                           fill="none"
                           stroke="currentColor"
                           viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </template>

          <!-- Empty Cart -->
          <div x-show="cart.length === 0"
               class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="mt-2 text-gray-500 text-sm">Keranjang kosong</p>
          </div>
        </div>

        <!-- Cart Total -->
        <div x-show="cart.length > 0"
             class="border-t border-gray-200 pt-4 space-y-3 flex-shrink-0">
          <div class="flex justify-between text-gray-600">
            <span>Subtotal</span>
            <span x-text="formatCurrency(cartTotal)"></span>
          </div>
          <div class="flex justify-between text-gray-900 text-lg font-bold">
            <span>Total</span>
            <span x-text="formatCurrency(cartTotal)"></span>
          </div>
          <button type="button"
                  @click="openCustomerTypeModal()"
                  :disabled="isProcessing"
                  class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl flex items-center justify-center gap-2 transition disabled:opacity-50 disabled:cursor-not-allowed">
            <svg class="w-5 h-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Checkout
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function posApp() {
      return {
        cart: @json($cartItems->values()),
        cartTotal: {{ $cartTotal }},
        isProcessing: false,
        showCustomerTypeModal: false,
        showCheckoutModal: false,
        showToast: false,
        toastMessage: '',
        toastType: 'success',
        counterLocation: '{{ $currentCounter ?? '' }}',
        checkoutForm: {
          customer_type: '',
          customer_user_id: '',
          table_id: '',
          table_display: '',
        },

        init() {
          // Initialize from session data
          this.cart = @json($cartItems->values());
          this.cartTotal = {{ $cartTotal }};
        },

        formatCurrency(amount) {
          return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        },

        getCounterLabel() {
          const select = document.getElementById('counterLocationSelect');
          if (select && this.counterLocation) {
            const option = select.querySelector(`option[value="${this.counterLocation}"]`);
            return option ? option.textContent : this.counterLocation;
          }
          return '';
        },

        async selectCounter(event) {
          const location = event.target.value;
          try {
            const response = await fetch('{{ route("admin.pos.select-counter") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
              },
              body: JSON.stringify({ counter_location: location })
            });
            const data = await response.json();
            if (data.success) {
              this.showToastMessage('Counter location updated', 'success');
            }
          } catch (error) {
            console.error('Error setting counter location:', error);
            this.showToastMessage('Failed to update counter location', 'error');
          }
        },

        async addToCart(productId) {
          if (this.isProcessing) return;
          this.isProcessing = true;

          try {
            const response = await fetch('{{ route("admin.pos.add-to-cart", "__PRODUCT_ID__") }}'.replace('__PRODUCT_ID__', productId), {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json'
              }
            });

            const data = await response.json();

            if (data.success) {
              this.cart = data.cart;
              this.cartTotal = data.cartTotal;
              this.showToastMessage(data.message, 'success');
            } else {
              this.showToastMessage(data.message || 'Failed to add product', 'error');
            }
          } catch (error) {
            console.error('Error adding to cart:', error);
            this.showToastMessage('Failed to add product to cart', 'error');
          } finally {
            this.isProcessing = false;
          }
        },

        async updateCartQuantity(productId, action) {
          if (this.isProcessing) return;
          this.isProcessing = true;

          try {
            const response = await fetch('{{ route("admin.pos.update-cart", "__PRODUCT_ID__") }}'.replace('__PRODUCT_ID__', productId), {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json'
              },
              body: JSON.stringify({ action })
            });

            const data = await response.json();

            if (data.success) {
              this.cart = data.cart;
              this.cartTotal = data.cartTotal;
            } else {
              this.showToastMessage(data.message || 'Failed to update cart', 'error');
            }
          } catch (error) {
            console.error('Error updating cart:', error);
            this.showToastMessage('Failed to update cart', 'error');
          } finally {
            this.isProcessing = false;
          }
        },

        async removeFromCart(productId) {
          if (this.isProcessing) return;
          this.isProcessing = true;

          try {
            const response = await fetch('{{ route("admin.pos.remove-from-cart", "__PRODUCT_ID__") }}'.replace('__PRODUCT_ID__', productId), {
              method: 'DELETE',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json'
              }
            });

            const data = await response.json();

            if (data.success) {
              this.cart = data.cart;
              this.cartTotal = data.cartTotal;
              this.showToastMessage(data.message, 'success');
            } else {
              this.showToastMessage(data.message || 'Failed to remove item', 'error');
            }
          } catch (error) {
            console.error('Error removing from cart:', error);
            this.showToastMessage('Failed to remove item from cart', 'error');
          } finally {
            this.isProcessing = false;
          }
        },

        async clearCart() {
          if (this.isProcessing) return;
          this.isProcessing = true;

          try {
            const response = await fetch('{{ route("admin.pos.clear-cart") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json'
              }
            });

            const data = await response.json();

            if (data.success) {
              this.cart = [];
              this.cartTotal = 0;
              this.showToastMessage(data.message, 'success');
            } else {
              this.showToastMessage(data.message || 'Failed to clear cart', 'error');
            }
          } catch (error) {
            console.error('Error clearing cart:', error);
            this.showToastMessage('Failed to clear cart', 'error');
          } finally {
            this.isProcessing = false;
          }
        },

        openCustomerTypeModal() {
          if (this.cart.length === 0) {
            this.showToastMessage('Keranjang kosong!', 'error');
            return;
          }
          this.showCustomerTypeModal = true;
        },

        selectCustomerType(type) {
          this.checkoutForm.customer_type = type;
          this.showCustomerTypeModal = false;
          this.showCheckoutModal = true;
        },

        handleCustomerChange() {
          if (this.checkoutForm.customer_type === 'booking' && this.checkoutForm.customer_user_id) {
            const select = document.querySelector('select[name="customer_user_id"]');
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption) {
              this.checkoutForm.table_id = selectedOption.dataset.tableId || '';
              const areaName = selectedOption.dataset.areaName || 'N/A';
              const tableNumber = selectedOption.dataset.tableNumber || 'N/A';
              this.checkoutForm.table_display = `${areaName} - Meja ${tableNumber}`;
            }
          } else {
            this.checkoutForm.table_id = '';
            this.checkoutForm.table_display = '';
          }
        },

        async submitCheckout() {
          if (this.isProcessing) return;
          this.isProcessing = true;

          try {
            const response = await fetch('{{ route("admin.pos.checkout") }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json'
              },
              body: JSON.stringify(this.checkoutForm)
            });

            const data = await response.json();

            if (data.success) {
              this.cart = [];
              this.cartTotal = 0;
              this.showCheckoutModal = false;
              this.showToastMessage(data.message, 'success');

              // Reset form
              this.checkoutForm = {
                customer_type: '',
                customer_user_id: '',
                table_id: '',
                table_display: '',
              };
            } else {
              this.showToastMessage(data.message || 'Checkout failed', 'error');
            }
          } catch (error) {
            console.error('Error during checkout:', error);
            this.showToastMessage('Terjadi kesalahan saat checkout', 'error');
          } finally {
            this.isProcessing = false;
          }
        },

        showToastMessage(message, type = 'success') {
          this.toastMessage = message;
          this.toastType = type;
          this.showToast = true;
          setTimeout(() => {
            this.showToast = false;
          }, 3000);
        },
      };
    }
  </script>
</x-app-layout>
