<x-app-layout>
  <div class="flex w-full h-[calc(100vh-6rem)]">
    <div class="flex-1 flex flex-col"
         id="posContainer">

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

                  <!-- Stock Badge (Only for Inventory Items with Drink category) -->
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
                      <form action="{{ route('admin.pos.add-to-cart', $product['id']) }}"
                            method="POST">
                        @csrf
                        <button type="submit"
                                class="w-8 h-8 bg-gray-800 hover:bg-gray-900 text-white rounded-lg flex items-center justify-center transition disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ isset($product['type']) && $product['type'] === 'item' && $product['stock'] <= 0 ? 'disabled' : '' }}>
                          <svg class="w-4 h-4"
                               fill="none"
                               stroke="currentColor"
                               viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 4v16m8-8H4" />
                          </svg>
                        </button>
                      </form>
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
      <div id="customerTypeModal"
           style="display: none;"
           class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60]">
        <div class="bg-white rounded-2xl p-6 max-w-lg w-full mx-4"
             onclick="event.stopPropagation()">
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
            <button onclick="closeCustomerTypeModal()"
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
            <button onclick="selectCustomerType('booking')"
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
                3 Booking Aktif
              </div>
            </button>

            <!-- Walk-in Option -->
            <button onclick="selectCustomerType('walk-in')"
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
                12 Pelanggan Terdaftar
              </div>
            </button>
          </div>
        </div>
      </div>

      <!-- Checkout Modal -->
      <div id="checkoutModal"
           style="display: none;"
           class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60]">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4"
             onclick="event.stopPropagation()">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Checkout Order</h3>
            <button onclick="closeCheckoutModal()"
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

          <form action="{{ route('admin.pos.checkout') }}"
                method="POST"
                class="space-y-4">
            @csrf

            <!-- Customer Type (Hidden) -->
            <input type="hidden"
                   name="customer_type"
                   id="customerTypeInput"
                   value="">

            <!-- Customer Selection -->
            <div id="customerSelectionDiv">
              <label class="block text-sm font-medium text-gray-700 mb-2">Customer</label>
              <select name="customer_user_id"
                      id="customerSelect"
                      required
                      onchange="handleCustomerChange()"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Pilih Customer</option>
              </select>
            </div>

            <!-- Table Selection -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Meja</label>
              <input type="text"
                     id="tableDisplay"
                     readonly
                     class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                     placeholder="Pilih customer terlebih dahulu">
              <input type="hidden"
                     name="table_id"
                     id="tableIdInput">
            </div>

            <!-- Payment Method -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
              <input type="text"
                     name="payment_method"
                     value="Transfer Manual"
                     readonly
                     class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
              <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal ({{ $cartItems->count() }} items)</span>
                <span>Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
              </div>
              <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-gray-200">
                <span>Total</span>
                <span>Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4">
              <button type="button"
                      onclick="closeCheckoutModal()"
                      class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition">
                Batal
              </button>
              <button type="submit"
                      class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                Proses Order
              </button>
            </div>
          </form>
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
              <p class="text-gray-500 text-sm">{{ $cartItems->count() }} item</p>
            </div>
          </div>
          @if ($cartItems->isNotEmpty())
            <form action="{{ route('admin.pos.clear-cart') }}"
                  method="POST">
              @csrf
              <button type="submit"
                      class="text-gray-400 hover:text-gray-600"
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
            </form>
          @endif
        </div>

        <!-- Cart Items -->
        <div class="space-y-3 max-h-[500px] mb-6 flex-1 overflow-y-auto">
          @forelse($cartItems as $item)
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 hover:bg-gray-100 transition">
              <div class="flex items-start gap-3">
                <div class="bg-blue-600 rounded-lg p-2 flex-shrink-0">
                  <div class="text-2xl">🍸</div>
                </div>
                <div class="flex-1 min-w-0">
                  <h4 class="text-gray-900 font-semibold text-sm truncate">{{ $item['name'] }}</h4>
                  <p class="text-gray-500 text-xs">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>

                  <div class="flex items-center justify-between mt-3">
                    <div class="flex items-center gap-2">
                      <form action="{{ route('admin.pos.update-cart', $item['id']) }}"
                            method="POST"
                            class="inline">
                        @csrf
                        <input type="hidden"
                               name="action"
                               value="decrease">
                        <button type="submit"
                                class="w-6 h-6 bg-white border border-gray-300 hover:bg-gray-100 rounded text-gray-700 flex items-center justify-center text-sm font-bold">-</button>
                      </form>
                      <span class="text-gray-900 font-medium w-8 text-center">{{ $item['quantity'] }}</span>
                      <form action="{{ route('admin.pos.update-cart', $item['id']) }}"
                            method="POST"
                            class="inline">
                        @csrf
                        <input type="hidden"
                               name="action"
                               value="increase">
                        <button type="submit"
                                class="w-6 h-6 bg-white border border-gray-300 hover:bg-gray-100 rounded text-gray-700 flex items-center justify-center text-sm font-bold">+</button>
                      </form>
                    </div>
                    <form action="{{ route('admin.pos.remove-from-cart', $item['id']) }}"
                          method="POST"
                          class="inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                              class="text-red-500 hover:text-red-700">
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
                    </form>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="text-center py-12">
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
          @endforelse
        </div>

        <!-- Cart Total -->
        @if ($cartItems->isNotEmpty())
          <div class="border-t border-gray-200 pt-4 space-y-3 flex-shrink-0">
            <div class="flex justify-between text-gray-600">
              <span>Subtotal</span>
              <span>Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-900 text-lg font-bold">
              <span>Total</span>
              <span>Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
            </div>
            <button onclick="openCustomerTypeModal()"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl flex items-center justify-center gap-2 transition">
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
        @endif
      </div>
    </div>
  </div>

  <script>
    // Vanilla JavaScript for POS modals
    let customerType = '';

    // Table sessions data from Laravel
    const tableSessions = @json($tableSessions);

    function openCustomerTypeModal() {
      document.getElementById('customerTypeModal').style.display = 'flex';
    }

    function closeCustomerTypeModal() {
      document.getElementById('customerTypeModal').style.display = 'none';
    }

    function selectCustomerType(type) {
      customerType = type;
      document.getElementById('customerTypeInput').value = type;
      closeCustomerTypeModal();
      populateCustomerSelect(type);
      openCheckoutModal();
    }

    function populateCustomerSelect(type) {
      const customerSelect = document.getElementById('customerSelect');
      customerSelect.innerHTML = '<option value="">Pilih Customer</option>';

      if (type === 'booking') {
        // Populate with table sessions
        tableSessions.forEach(session => {
          const option = document.createElement('option');
          option.value = session.customer_id;
          option.dataset.tableId = session.table_id;
          option.dataset.tableName = session.table?.table_number || 'N/A';
          option.dataset.areaName = session.table?.area?.name || 'N/A';
          option.textContent = `${session.customer?.name || 'Unknown'} - ${session.table?.area?.name || 'N/A'} - Meja ${session.table?.table_number || 'N/A'}`;
          customerSelect.appendChild(option);
        });
      } else {
        // For walk-in, you can add manual customer selection here
        // For now, just placeholder
        const option = document.createElement('option');
        option.value = 'walk-in';
        option.textContent = 'Walk-in Customer';
        customerSelect.appendChild(option);
      }
    }

    function handleCustomerChange() {
      const customerSelect = document.getElementById('customerSelect');
      const selectedOption = customerSelect.options[customerSelect.selectedIndex];

      if (customerType === 'booking' && selectedOption.value) {
        const tableId = selectedOption.dataset.tableId;
        const areaName = selectedOption.dataset.areaName;
        const tableName = selectedOption.dataset.tableName;

        document.getElementById('tableIdInput').value = tableId;
        document.getElementById('tableDisplay').value = `${areaName} - Meja ${tableName}`;
      } else {
        document.getElementById('tableIdInput').value = '';
        document.getElementById('tableDisplay').value = '';
      }
    }

    function openCheckoutModal() {
      document.getElementById('checkoutModal').style.display = 'flex';
    }

    function closeCheckoutModal() {
      document.getElementById('checkoutModal').style.display = 'none';
    }

    // Close modals when clicking outside
    document.getElementById('customerTypeModal')?.addEventListener('click', function(e) {
      if (e.target === this) {
        closeCustomerTypeModal();
      }
    });

    document.getElementById('checkoutModal')?.addEventListener('click', function(e) {
      if (e.target === this) {
        closeCheckoutModal();
      }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeCustomerTypeModal();
        closeCheckoutModal();
      }
    });
  </script>
</x-app-layout>
