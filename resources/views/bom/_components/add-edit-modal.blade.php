<div id="recipeModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
    <div class="p-6 border-b border-gray-200">
      <h3 id="modalTitle"
          class="text-xl font-bold text-gray-900">Tambah Recipe</h3>
    </div>
    <form id="recipeForm"
          method="POST"
          action="{{ route('admin.bom.store') }}"
          class="p-6">
      @csrf
      <input type="hidden"
             name="_method"
             value="POST"
             id="formMethod">

      <div class="space-y-4">
        <!-- Inventory Item (Final Product) -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Produk Akhir <span class="text-red-500">*</span></label>
          <select name="inventory_item_id"
                  id="inventory_item_id"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            <option value="">Pilih Produk Akhir</option>
            @foreach ($inventoryItems as $item)
              @if (in_array(strtolower($item->category_type), ['food', 'bar']))
                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code }})</option>
              @endif
            @endforeach
          </select>
          <p class="text-xs text-gray-500 mt-1">Produk yang akan dihasilkan dari recipe ini (Food/Beverage)</p>
        </div>

        <!-- Quantity -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Produksi <span class="text-red-500">*</span></label>
          <input type="number"
                 name="quantity"
                 id="quantity"
                 required
                 min="1"
                 value="1"
                 placeholder="1"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
          <p class="text-xs text-gray-500 mt-1">Berapa unit yang dihasilkan dari recipe ini</p>
        </div>

        <!-- Type -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tipe <span class="text-red-500">*</span></label>
          <div class="flex gap-4">
            <label class="flex-1 cursor-pointer">
              <input type="radio"
                     name="type"
                     value="food"
                     required
                     class="peer sr-only">
              <div class="px-4 py-3 border-2 border-gray-300 rounded-lg peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-700 transition text-center font-medium">
                🍽️ Food
              </div>
            </label>
            <label class="flex-1 cursor-pointer">
              <input type="radio"
                     name="type"
                     value="bar"
                     required
                     class="peer sr-only">
              <div class="px-4 py-3 border-2 border-gray-300 rounded-lg peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-700 transition text-center font-medium">
                🍹 Bar
              </div>
            </label>
          </div>
        </div>

        <!-- Description -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
          <textarea name="description"
                    id="description"
                    rows="2"
                    placeholder="Deskripsi singkat tentang recipe ini..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"></textarea>
        </div>

        <!-- Selling Price -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Harga Jual <span class="text-red-500">*</span></label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
            <input type="number"
                   name="selling_price"
                   id="selling_price"
                   required
                   min="0"
                   step="100"
                   placeholder="0"
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
          </div>
        </div>

        <!-- Ingredients -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Bahan-bahan <span class="text-red-500">*</span></label>
          <div id="ingredientsList"
               class="space-y-2 mb-3">
            <!-- Ingredients will be added here -->
          </div>
          <button type="button"
                  onclick="addIngredient()"
                  class="w-full px-4 py-2 border-2 border-dashed border-gray-300 text-gray-600 rounded-lg hover:border-teal-500 hover:text-teal-600 transition">
            + Tambah Bahan
          </button>
        </div>
      </div>

      <div class="flex justify-end gap-3 mt-6">
        <button type="button"
                onclick="closeModal()"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
          Batal
        </button>
        <button type="submit"
                class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>
