<x-waiter-mobile-layout>
  <div x-data="waiterPos()"
       x-init="init()">

    <!-- Header -->
    <div class="px-5 pt-5 pb-3 sticky top-0 bg-slate-50 z-20">
      <div class="flex items-center justify-between mb-3">
        <div>
          <h1 class="text-xl font-bold">POS</h1>
          <p class="text-slate-700 text-xs mt-0.5">Pilih meja & tambah pesanan</p>
        </div>
        <!-- Cart badge -->
        <button @click="showCart = true"
                class="relative w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm">
          <svg class="w-5 h-5 text-slate-700"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <span x-show="cartCount > 0"
                x-text="cartCount"
                class="absolute -top-1 -right-1 bg-teal-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center leading-none"></span>
        </button>
      </div>

      <!-- Session Selector -->
      <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
        <button @click="selectSession(null)"
                :class="selectedSession === null ? 'bg-teal-500 text-white' : 'bg-white text-slate-600 border border-slate-200'"
                class="flex-shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold transition whitespace-nowrap">
          Tanpa Meja
        </button>
        @foreach ($activeSessions as $session)
          <button @click="selectSession('{{ $session->id }}')"
                  :class="selectedSession === '{{ $session->id }}' ? 'bg-teal-500 text-white' : 'bg-white text-slate-600 border border-slate-200'"
                  class="flex-shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold transition whitespace-nowrap">
            Meja {{ $session->table?->table_number ?? '?' }}
            @if ($session->customer)
              · {{ Str::limit($session->customer->name, 10) }}
            @endif
          </button>
        @endforeach
      </div>
    </div>

    <!-- Search + Category Filter -->
    <div class="px-5 py-3 bg-slate-50 sticky top-[116px] z-10">
      <div class="relative mb-3">
        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-700"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input type="text"
               x-model="search"
               placeholder="Cari menu..."
               class="w-full bg-white text-slate-900 border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-teal-400" />
      </div>

      <!-- Category pills -->
      <div class="flex gap-2 overflow-x-auto scrollbar-hide">
        <button @click="categoryFilter = ''"
                :class="categoryFilter === '' ? 'bg-teal-500 text-white' : 'bg-white text-slate-600 border border-slate-200'"
                class="flex-shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold transition">
          Semua
        </button>
        <button @click="categoryFilter = 'food'"
                :class="categoryFilter === 'food' ? 'bg-teal-500 text-white' : 'bg-white text-slate-600 border border-slate-200'"
                class="flex-shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold transition">
          Food
        </button>
        <button @click="categoryFilter = 'bar'"
                :class="categoryFilter === 'bar' ? 'bg-teal-500 text-white' : 'bg-white text-slate-600 border border-slate-200'"
                class="flex-shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold transition">
          Bar
        </button>
        <button @click="categoryFilter = 'beverage'"
                :class="categoryFilter === 'beverage' ? 'bg-teal-500 text-white' : 'bg-white text-slate-600 border border-slate-200'"
                class="flex-shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold transition">
          Beverage
        </button>
      </div>
    </div>

    <!-- Products Grid -->
    <div class="px-5 pb-5">
      <div x-show="filteredProducts().length === 0"
           class="py-8 text-center text-slate-700 text-sm">
        Tidak ada produk ditemukan.
      </div>
      <div class="grid grid-cols-2 gap-3">
        <template x-for="product in filteredProducts()"
                  :key="product.id">
          <button @click="addToCart(product)"
                  :disabled="addingToCart === product.id"
                  class="bg-white rounded-2xl p-4 text-left relative active:scale-95 transition-transform disabled:opacity-50 border border-slate-100 shadow-sm">
            <!-- Category badge -->
            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium mb-2"
                  :class="{
                      'bg-orange-100 text-orange-700': product.category === 'food',
                      'bg-purple-100 text-purple-700': product.category === 'bar',
                      'bg-blue-100 text-blue-700': product.category === 'beverage',
                  }"
                  x-text="product.category.charAt(0).toUpperCase() + product.category.slice(1)"></span>
            <p class="font-semibold text-sm leading-tight mb-2 text-slate-900"
               x-text="product.name"></p>
            <p class="text-teal-600 font-bold text-sm"
               x-text="'Rp ' + product.price.toLocaleString('id-ID')"></p>
            <!-- Cart qty indicator -->
            <span x-show="getCartQty(product.id) > 0"
                  x-text="getCartQty(product.id)"
                  class="absolute top-3 right-3 bg-teal-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center leading-none"></span>
            <!-- Loading spinner -->
            <span x-show="addingToCart === product.id"
                  class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-80 rounded-2xl">
              <svg class="animate-spin w-5 h-5 text-teal-400"
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
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
            </span>
          </button>
        </template>
      </div>
    </div>

    <!-- Cart Slideup Overlay -->
    <div x-show="showCart"
         x-transition.opacity
         style="display: none;"
         class="fixed inset-0 bg-black bg-opacity-60 z-40"
         @click="showCart = false">
    </div>
    <div x-show="showCart"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="transform translate-y-full"
         x-transition:enter-end="transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="transform translate-y-0"
         x-transition:leave-end="transform translate-y-full"
         style="display: none;"
         class="fixed bottom-0 left-0 right-0 bg-white rounded-t-3xl z-50 max-h-[80vh] flex flex-col shadow-2xl border-t border-slate-200"
         @click.stop>

      <div class="flex items-center justify-between px-5 pt-5 pb-3">
        <h2 class="font-bold text-lg text-slate-900">Keranjang</h2>
        <button @click="showCart = false"
                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
          <svg class="w-4 h-4"
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

      <!-- Cart items -->
      <div class="flex-1 overflow-y-auto px-5">
        <div x-show="Object.keys(cart).length === 0"
             class="py-8 text-center text-slate-700 text-sm">
          Keranjang kosong.
        </div>
        <div class="space-y-3">
          <template x-for="(item, pid) in cart"
                    :key="pid">
            <div class="flex items-center gap-3">
              <div class="flex-1 min-w-0">
                <p class="font-medium text-sm text-slate-900 truncate"
                   x-text="item.name"></p>
                <p class="text-teal-600 text-xs"
                   x-text="'Rp ' + item.price.toLocaleString('id-ID')"></p>
              </div>
              <div class="flex items-center gap-2">
                <button @click="updateQty(pid, item.qty - 1)"
                        class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-sm font-bold">
                  −
                </button>
                <span class="text-sm font-semibold w-6 text-center"
                      x-text="item.qty"></span>
                <button @click="addToCartById(pid)"
                        class="w-7 h-7 rounded-full bg-teal-600 flex items-center justify-center text-sm font-bold">
                  +
                </button>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- Cart Footer -->
      <div class="px-5 py-4 border-t border-slate-200">
        <div class="flex items-center justify-between mb-3">
          <span class="text-slate-700 font-medium">Total</span>
          <span class="font-bold text-lg text-slate-900"
                x-text="'Rp ' + cartTotal.toLocaleString('id-ID')"></span>
        </div>
        <div x-show="selectedSession === null"
             class="mb-2 px-3 py-2 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-700 text-xs">
          Pilih meja terlebih dahulu sebelum checkout.
        </div>
        <button @click="checkout()"
                :disabled="checkingOut || selectedSession === null || Object.keys(cart).length === 0"
                class="w-full bg-teal-500 text-white py-4 rounded-full font-bold text-sm disabled:opacity-40 flex items-center justify-center gap-2">
          <svg x-show="checkingOut"
               class="animate-spin w-4 h-4"
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
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
          <span x-text="checkingOut ? 'Memproses...' : 'Checkout'"></span>
        </button>
      </div>
    </div>

    <!-- Checkout Success Toast -->
    <div x-show="toastMsg"
         x-transition
         style="display: none;"
         class="fixed top-5 left-5 right-5 z-50 flex items-center gap-3 px-4 py-4 rounded-2xl shadow-xl"
         :class="toastSuccess ? 'bg-green-700' : 'bg-red-700'">
      <svg class="w-5 h-5 flex-shrink-0"
           fill="none"
           stroke="currentColor"
           viewBox="0 0 24 24">
        <path x-show="toastSuccess"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 13l4 4L19 7" />
        <path x-show="!toastSuccess"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12" />
      </svg>
      <p class="text-sm font-medium"
         x-text="toastMsg"></p>
    </div>

  </div>

  @push('scripts')
    <script>
      function waiterPos() {
        return {
          products: @json($products),
          search: '',
          categoryFilter: '',
          cart: @json(collect($cart ?? [])->mapWithKeys(fn($item, $key) => [$key => $item])),
          selectedSession: @json($selectedCounter),
          showCart: false,
          addingToCart: null,
          checkingOut: false,
          toastMsg: '',
          toastSuccess: true,

          init() {},

          get cartCount() {
            return Object.values(this.cart).reduce((s, i) => s + (i.qty || 0), 0);
          },

          get cartTotal() {
            return Object.values(this.cart).reduce((s, i) => s + (i.price * (i.qty || 0)), 0);
          },

          filteredProducts() {
            return this.products.filter(p => {
              const matchSearch = !this.search ||
                p.name.toLowerCase().includes(this.search.toLowerCase());
              const matchCat = !this.categoryFilter || p.category === this.categoryFilter;
              return matchSearch && matchCat;
            });
          },

          getCartQty(productId) {
            return this.cart[productId]?.qty ?? 0;
          },

          async selectSession(sessionId) {
            this.selectedSession = sessionId;
            try {
              await fetch('{{ route('admin.pos.select-counter') }}', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                  counter_id: sessionId
                }),
              });
            } catch (_) {}
          },

          async addToCart(product) {
            this.addingToCart = product.id;
            try {
              const res = await fetch(`{{ url('/admin/pos') }}/${product.id}/add-to-cart`, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({}),
              });
              const data = await res.json();
              if (data.success) {
                this.cart = data.cart ?? this.cart;
                if (!this.cart[product.id]) {
                  this.cart[product.id] = {
                    name: product.name,
                    price: product.price,
                    qty: 1
                  };
                }
              }
            } catch (_) {
              // Optimistic UI fallback
              if (this.cart[product.id]) {
                this.cart[product.id].qty++;
              } else {
                this.cart[product.id] = {
                  name: product.name,
                  price: product.price,
                  qty: 1
                };
              }
            } finally {
              this.addingToCart = null;
            }
          },

          async addToCartById(productId) {
            const product = this.products.find(p => p.id === productId);
            if (product) {
              await this.addToCart(product);
            }
          },

          async updateQty(productId, newQty) {
            if (newQty <= 0) {
              try {
                const res = await fetch(`{{ url('/admin/pos') }}/${productId}/remove-from-cart`, {
                  method: 'DELETE',
                  headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                  },
                });
                const data = await res.json();
                if (data.success) {
                  this.cart = data.cart ?? {};
                }
              } catch (_) {
                delete this.cart[productId];
              }
              return;
            }
            try {
              const res = await fetch(`{{ url('/admin/pos') }}/${productId}/update-cart`, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                  quantity: newQty
                }),
              });
              const data = await res.json();
              if (data.success) {
                this.cart = data.cart ?? this.cart;
              }
            } catch (_) {
              if (this.cart[productId]) {
                this.cart[productId].qty = newQty;
              }
            }
          },

          async checkout() {
            if (!this.selectedSession || Object.keys(this.cart).length === 0) {
              return;
            }
            this.checkingOut = true;
            try {
              const res = await fetch('{{ route('admin.pos.checkout') }}', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                  counter_id: this.selectedSession
                }),
              });
              const data = await res.json();
              if (data.success) {
                this.cart = {};
                this.showCart = false;
                this.flash('Pesanan berhasil dikirim ke dapur/bar!', true);
              } else {
                this.flash(data.message || 'Checkout gagal.', false);
              }
            } catch (_) {
              this.flash('Terjadi kesalahan jaringan.', false);
            } finally {
              this.checkingOut = false;
            }
          },

          flash(msg, success = true) {
            this.toastMsg = msg;
            this.toastSuccess = success;
            setTimeout(() => {
              this.toastMsg = '';
            }, 3500);
          },
        };
      }
    </script>
  @endpush
</x-waiter-mobile-layout>
