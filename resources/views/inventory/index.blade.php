<x-app-layout>
  <div class="p-6">
    @if (session('success'))
      <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Inventory Management</h1>
        <p class="text-sm text-gray-500">Kelola daftar produk dan stok gudang</p>
      </div>
      <button data-sync-btn
              onclick="syncFromAccurate()"
              class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
        <svg class="w-5 h-5"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        <span data-sync-text>Sync dari Accurate</span>
      </button>
      {{-- <button onclick="openModal('add')"
              class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition flex items-center gap-2">
        <svg class="w-5 h-5"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4v16m8-8H4" />
        </svg>
        Tambah Produk
      </button> --}}
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 gap-4 mb-6">
      <div class="bg-white border border-gray-200 rounded-xl p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500 font-medium">Total Produk</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalItems }}</p>
          </div>
          <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-slate-600"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white border border-gray-200 rounded-xl p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500 font-medium">Total Nilai Stok</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</p>
          </div>
          <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-slate-600"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-yellow-700 font-medium">Stok Rendah</p>
            <p class="text-2xl font-bold text-yellow-900">{{ $lowStockCount }}</p>
          </div>
          <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-yellow-600"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-slate-800 text-white rounded-xl p-4 mb-6 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <svg class="w-5 h-5"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
        </svg>
        <span class="font-medium">Semua Produk ({{ $totalItems }})</span>
      </div>
      <button onclick="toggleStockFilter()"
              id="lowStockBtn"
              class="px-4 py-2 bg-yellow-500 text-gray-900 rounded-lg hover:bg-yellow-600 transition flex items-center gap-2">
        <svg class="w-4 h-4"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        Low Stock ({{ $lowStockCount }})
      </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
      <div class="p-4">
        <div class="relative">
          <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input type="text"
                 id="searchInput"
                 placeholder="Cari produk berdasarkan nama atau kategori..."
                 class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
        </div>
      </div>
    </div>

    <!-- Actions Bar -->
    <div class="flex items-center justify-end mb-4">
      <button onclick="openThresholdModal()"
              class="px-4 py-2 text-yellow-600 border border-yellow-300 rounded-lg hover:bg-yellow-50 transition flex items-center gap-2">
        <svg class="w-4 h-4"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
        </svg>
        Edit Threshold Sekaligus
      </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">⚠ Threshold</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200"
                 id="itemTableBody">
            @foreach ($items as $item)
              <tr class="hover:bg-gray-50 transition item-row"
                  data-category="{{ $item->category_type }}"
                  data-low-stock="{{ $item->isLowStock() ? '1' : '0' }}">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-xs font-mono text-gray-600">{{ $item->code }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-medium rounded 
                                    @if (strtolower($item->category_type) === 'spices') bg-orange-100 text-orange-700
                                    @elseif(strtolower($item->category_type) === 'spirits') bg-purple-100 text-purple-700
                                    @elseif(strtolower($item->category_type) === 'beverage') bg-blue-100 text-blue-700
                                    @elseif(strtolower($item->category_type) === 'dairy') bg-green-100 text-green-700
                                    @elseif(strtolower($item->category_type) === 'condiments') bg-yellow-100 text-yellow-700
                                    @else bg-gray-100 text-gray-700 @endif">
                    {{ ucfirst($item->category_type) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm">
                    <span class="@if ($item->isLowStock()) text-red-600 font-bold @else text-green-600 font-medium @endif">
                      {{ $item->stock_quantity }} {{ $item->unit }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm">
                    <span class="px-2 py-1 text-xs font-medium rounded bg-red-100 text-red-700">
                      {{ $item->threshold }} {{ $item->unit }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @if ($item->is_active)
                    <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-700">Active</span>
                  @else
                    <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-700">Inactive</span>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex gap-2">
                    <button onclick="editItem({{ $item->id }})"
                            class="p-1 text-gray-600 hover:text-blue-600 transition">
                      <svg class="w-5 h-5"
                           fill="none"
                           stroke="currentColor"
                           viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button onclick="deleteItem({{ $item->id }})"
                            class="p-1 text-gray-600 hover:text-red-600 transition">
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
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Add/Edit Modal -->
  <div id="itemModal"
       class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-6 border-b border-gray-200">
        <h3 id="modalTitle"
            class="text-xl font-bold text-gray-900">Tambah Produk</h3>
      </div>
      <form id="itemForm"
            method="POST"
            action="{{ route('admin.inventory.store') }}"
            class="p-6">
        @csrf
        <input type="hidden"
               name="_method"
               value="POST"
               id="formMethod">

        <div class="grid grid-cols-2 gap-4">
          <!-- Name -->
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
            <input type="text"
                   name="name"
                   id="name"
                   required
                   placeholder="Contoh: Black Pepper, Olive Oil"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
          </div>

          <!-- Code -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Produk <span class="text-red-500">*</span></label>
            <input type="text"
                   name="code"
                   id="code"
                   required
                   placeholder="Contoh: BP001"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
          </div>

          <!-- Category Type -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
            <select name="category_type"
                    id="category_type"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
              <option value="">Pilih Kategori</option>
              <option value="spices">Spices</option>
              <option value="condiments">Condiments</option>
              <option value="dairy">Dairy</option>
              <option value="beverage">Beverage</option>
              <option value="spirits">Spirits</option>
            </select>
          </div>

          <!-- Unit -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Satuan <span class="text-red-500">*</span></label>
            <select name="unit"
                    id="unit"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
              <option value="unit">Unit</option>
              <option value="bottle">Bottle</option>
              <option value="kg">Kg</option>
              <option value="liter">Liter</option>
              <option value="pack">Pack</option>
            </select>
          </div>

          <!-- Price -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Harga <span class="text-red-500">*</span></label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
              <input type="number"
                     name="price"
                     id="price"
                     required
                     min="0"
                     step="100"
                     placeholder="0"
                     class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
            </div>
          </div>

          <!-- Stock Quantity -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok <span class="text-red-500">*</span></label>
            <input type="number"
                   name="stock_quantity"
                   id="stock_quantity"
                   required
                   min="0"
                   placeholder="0"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
          </div>

          <!-- Threshold -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Threshold (Batas Minimum) <span class="text-red-500">*</span></label>
            <input type="number"
                   name="threshold"
                   id="threshold"
                   required
                   min="0"
                   placeholder="10"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
          </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
          <button type="button"
                  onclick="closeModal()"
                  class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
            Batal
          </button>
          <button type="submit"
                  class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Modal -->
  <div id="deleteModal"
       class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
      <div class="p-6">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
          <svg class="w-6 h-6 text-red-600"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Hapus Item</h3>
        <p class="text-sm text-gray-500 text-center mb-6">Apakah Anda yakin ingin menghapus item ini?</p>
        <form id="deleteForm"
              method="POST"
              class="flex gap-3">
          @csrf
          @method('DELETE')
          <button type="button"
                  onclick="closeDeleteModal()"
                  class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
            Batal
          </button>
          <button type="submit"
                  class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
            Hapus
          </button>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      const items = @json($items);
      let lowStockFilterActive = false;

      function syncFromAccurate() {
        const btn = document.querySelector('[data-sync-btn]'); // atau gunakan ID/class spesifik
        const text = document.querySelector('[data-sync-text]'); // atau gunakan ID/class spesifik

        // Disable button
        btn.disabled = true;
        text.innerHTML = '<span class="animate-spin inline-block mr-2">⚙️</span> Syncing...';

        fetch('/admin/accurate/sync/items', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          })
          .then(res => res.json())
          .then(data => {
            btn.disabled = false;
            text.textContent = 'Sync dari Accurate';
            window.location.reload()
          })
          .catch(err => {
            btn.disabled = false;
            text.textContent = 'Sync dari Accurate';
            alert('❌ Error: ' + err.message);
          });
      }

      function openModal(mode, itemId = null) {
        const modal = document.getElementById('itemModal');
        const form = document.getElementById('itemForm');
        const modalTitle = document.getElementById('modalTitle');
        const formMethod = document.getElementById('formMethod');

        if (mode === 'add') {
          modalTitle.textContent = 'Tambah Produk';
          form.action = '{{ route('admin.inventory.store') }}';
          formMethod.value = 'POST';
          form.reset();
        } else if (mode === 'edit' && itemId) {
          const item = items.find(i => i.id === itemId);
          if (item) {
            modalTitle.textContent = 'Edit Produk';
            form.action = `/admin/inventory/${itemId}`;
            formMethod.value = 'PUT';

            document.getElementById('name').value = item.name;
            document.getElementById('code').value = item.code;
            document.getElementById('category_type').value = item.category_type;
            document.getElementById('price').value = item.price;
            document.getElementById('stock_quantity').value = item.stock_quantity;
            document.getElementById('threshold').value = item.threshold;
            document.getElementById('unit').value = item.unit;
          }
        }

        modal.classList.remove('hidden');
      }

      function closeModal() {
        document.getElementById('itemModal').classList.add('hidden');
      }

      function editItem(itemId) {
        openModal('edit', itemId);
      }

      function deleteItem(itemId) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/inventory/${itemId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
      }

      function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
      }

      function toggleStockFilter() {
        lowStockFilterActive = !lowStockFilterActive;
        const btn = document.getElementById('lowStockBtn');

        if (lowStockFilterActive) {
          btn.classList.remove('bg-yellow-500', 'hover:bg-yellow-600');
          btn.classList.add('bg-yellow-600', 'hover:bg-yellow-700', 'ring-2', 'ring-yellow-300');
        } else {
          btn.classList.add('bg-yellow-500', 'hover:bg-yellow-600');
          btn.classList.remove('bg-yellow-600', 'hover:bg-yellow-700', 'ring-2', 'ring-yellow-300');
        }

        filterItems();
      }

      // Search functionality
      document.getElementById('searchInput').addEventListener('input', function(e) {
        filterItems();
      });

      function filterItems() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.item-row');

        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          const matchesSearch = text.includes(searchTerm);
          const isLowStock = row.dataset.lowStock === '1';
          const matchesFilter = !lowStockFilterActive || isLowStock;

          row.style.display = matchesSearch && matchesFilter ? '' : 'none';
        });
      }

      // Close modals on Escape key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          closeModal();
          closeDeleteModal();
        }
      });

      // Close modals on outside click
      document.getElementById('itemModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
      });

      document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
      });
    </script>
  @endpush
</x-app-layout>
