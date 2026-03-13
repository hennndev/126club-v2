<x-app-layout>
  <div class="p-6"
       x-data="menuForm()">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
      <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center">
        <svg class="w-5 h-5 text-white"
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
        <h1 class="text-2xl font-bold text-gray-900">Buat Menu</h1>
        <p class="text-sm text-gray-500">Data menu akan disimpan ke Accurate</p>
      </div>
    </div>

    <!-- Notification -->
    <div x-show="notification.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         :class="notification.type === 'success' ? 'bg-green-50 border-green-400 text-green-800' : 'bg-red-50 border-red-400 text-red-800'"
         class="mb-5 px-4 py-3 border rounded-lg text-sm"
         style="display: none;">
      <span x-text="notification.message"></span>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">

      <!-- No & Name -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode Item <span class="text-red-500">*</span></label>
          <input type="text"
                 x-model="form.no"
                 placeholder="Contoh: MENU-001"
                 class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-slate-500 focus:border-transparent">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Menu <span class="text-red-500">*</span></label>
          <input type="text"
                 x-model="form.name"
                 placeholder="Contoh: Nasi Goreng Spesial"
                 class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-slate-500 focus:border-transparent">
        </div>
      </div>

      <!-- Category, Unit, Price -->
      <div class="grid grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
          <select x-model="form.category_type"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-slate-500 focus:border-transparent">
            <option value="">Pilih kategori</option>
            @foreach ($categoryTypes as $cat)
              <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Satuan <span class="text-red-500">*</span></label>
          <input type="text"
                 x-model="form.unit"
                 placeholder="Contoh: porsi"
                 class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-slate-500 focus:border-transparent">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Jual (Rp) <span class="text-red-500">*</span></label>
          <input type="number"
                 x-model="form.selling_price"
                 placeholder="0"
                 min="0"
                 class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-slate-500 focus:border-transparent">
        </div>
      </div>

      <!-- Ingredients -->
      <div>
        <div class="flex items-center justify-between mb-2">
          <label class="text-sm font-medium text-gray-700">Bahan-bahan (Detail Group)</label>
          <button type="button"
                  @click="addIngredient()"
                  class="flex items-center gap-1 text-xs font-medium text-slate-700 border border-slate-300 rounded-lg px-2.5 py-1.5 hover:bg-slate-50 transition">
            <svg class="w-3.5 h-3.5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>
            Tambah Bahan
          </button>
        </div>

        <!-- Table header -->
        <div x-show="form.detail_group.length > 0"
             class="grid grid-cols-12 gap-2 px-1 mb-1.5">
          <div class="col-span-7 text-xs font-medium text-gray-500">Bahan</div>
          <div class="col-span-4 text-xs font-medium text-gray-500 text-center">Jumlah</div>
          <div class="col-span-1"></div>
        </div>

        <div class="space-y-2">
          <template x-for="(row, index) in form.detail_group"
                    :key="index">
            <div class="grid grid-cols-12 gap-2 items-center">
              <div class="col-span-7">
                <select x-model="row.inventory_item_id"
                        @change="onRowItemChange(index)"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-500 focus:border-transparent bg-white">
                  <option value="">-- Pilih Bahan --</option>
                  <template x-for="item in inventoryItems"
                            :key="item.id">
                    <option :value="item.id"
                            x-text="item.name + (item.unit ? ' (' + item.unit + ')' : '')"></option>
                  </template>
                </select>
              </div>
              <div class="col-span-4">
                <input type="number"
                       x-model="row.quantity"
                       placeholder="1"
                       min="0.001"
                       step="0.001"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-500 focus:border-transparent text-center">
              </div>
              <div class="col-span-1 flex justify-center">
                <button type="button"
                        @click="removeRow(index)"
                        class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
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
            </div>
          </template>

          <div x-show="form.detail_group.length === 0"
               class="text-center py-5 text-sm text-gray-400 border border-dashed border-gray-300 rounded-lg">
            Belum ada bahan. Klik "Tambah Bahan" untuk menambahkan.
          </div>
        </div>
      </div>

      <!-- Error -->
      <p x-show="formError"
         x-text="formError"
         class="text-sm text-red-600"
         style="display: none;"></p>
    </div>

    <!-- Footer Actions -->
    <div class="mt-5 flex items-center justify-between">
      <button type="button"
              @click="resetForm()"
              class="px-4 py-2 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
        Reset
      </button>
      <button type="button"
              @click="submitForm()"
              :disabled="saving"
              class="flex items-center gap-2 px-5 py-2 text-sm font-medium text-white bg-slate-800 hover:bg-slate-900 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
        <svg x-show="saving"
             class="w-4 h-4 animate-spin"
             fill="none"
             viewBox="0 0 24 24"
             style="display: none;">
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
        <span x-text="saving ? 'Menyimpan ke Accurate...' : 'Simpan ke Accurate'"></span>
      </button>
    </div>

  </div>

  @push('scripts')
    <script>
      const inventoryItems = @json($inventoryItems);

      function menuForm() {
        return {
          inventoryItems,
          saving: false,
          formError: '',
          notification: {
            show: false,
            type: 'success',
            message: ''
          },
          form: {
            no: '',
            name: '',
            category_type: '',
            unit: '',
            selling_price: '',
            detail_group: [],
          },

          showNotification(type, message) {
            this.notification = {
              show: true,
              type,
              message
            };
            setTimeout(() => {
              this.notification.show = false;
            }, 5000);
          },

          addIngredient() {
            this.form.detail_group.push({
              inventory_item_id: '',
              item_no: '',
              detail_name: '',
              quantity: 1
            });
          },

          onRowItemChange(index) {
            const row = this.form.detail_group[index];
            const item = this.inventoryItems.find(i => i.id == row.inventory_item_id);
            if (item) {
              row.item_no = item.code;
              row.detail_name = item.name;
            } else {
              row.item_no = '';
              row.detail_name = '';
            }
          },

          removeRow(index) {
            this.form.detail_group.splice(index, 1);
          },

          resetForm() {
            this.form = {
              no: '',
              name: '',
              category_type: '',
              unit: '',
              selling_price: '',
              detail_group: [],
            };
            this.formError = '';
          },

          async submitForm() {
            if (!this.form.no.trim()) {
              this.formError = 'Kode item wajib diisi.';
              return;
            }
            if (!this.form.name.trim()) {
              this.formError = 'Nama menu wajib diisi.';
              return;
            }
            if (!this.form.unit.trim()) {
              this.formError = 'Satuan wajib diisi.';
              return;
            }
            if (this.form.selling_price === '' || this.form.selling_price === null) {
              this.formError = 'Harga jual wajib diisi.';
              return;
            }

            this.formError = '';
            this.saving = true;

            try {
              const response = await fetch('{{ route('admin.menus.store') }}', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                  'Accept': 'application/json',
                },
                body: JSON.stringify({
                  no: this.form.no,
                  name: this.form.name,
                  category_type: this.form.category_type,
                  unit: this.form.unit,
                  selling_price: this.form.selling_price,
                  detail_group: this.form.detail_group.filter(r => r.item_no.trim()),
                }),
              });

              const data = await response.json();

              if (data.success) {
                this.showNotification('success', 'Menu "' + this.form.name + '" berhasil disimpan ke Accurate.');
                this.resetForm();
              } else {
                if (data.errors) {
                  this.formError = Object.values(data.errors).flat().join(' ');
                } else {
                  this.formError = data.message || 'Gagal menyimpan ke Accurate.';
                }
              }
            } catch (e) {
              this.formError = 'Terjadi kesalahan jaringan.';
            } finally {
              this.saving = false;
            }
          },
        };
      }
    </script>
  @endpush
</x-app-layout>
