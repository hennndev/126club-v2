<div id="songModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
    <div class="p-6 border-b border-gray-200">
      <h3 id="modalTitle"
          class="text-xl font-bold text-gray-900">Request Baru</h3>
    </div>
    <form id="songForm"
          method="POST"
          action="{{ route('admin.song-requests.store') }}"
          class="p-6">
      @csrf
      <input type="hidden"
             name="_method"
             value="POST"
             id="formMethod">

      <div class="space-y-4">
        <!-- Customer -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Customer <span class="text-red-500">*</span></label>
          <select name="customer_user_id"
                  id="customer_user_id"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
            <option value="">Pilih Customer</option>
            @foreach ($customerUsers as $customerUser)
              <option value="{{ $customerUser->id }}">{{ $customerUser->user->name }} - {{ $customerUser->user->email }}</option>
            @endforeach
          </select>
        </div>

        <!-- Song Title -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Judul Lagu <span class="text-red-500">*</span></label>
          <input type="text"
                 name="song_title"
                 id="song_title"
                 required
                 placeholder="Contoh: Shape of You"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
        </div>

        <!-- Artist -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Artist <span class="text-red-500">*</span></label>
          <input type="text"
                 name="artist"
                 id="artist"
                 required
                 placeholder="Contoh: Ed Sheeran"
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
        </div>

        <!-- Tip -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tip (Optional)</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
            <input type="number"
                   name="tip"
                   id="tip"
                   min="0"
                   step="1000"
                   placeholder="0"
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
          </div>
        </div>

        <!-- Status -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
          <select name="status"
                  id="status"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
            <option value="pending">Pending</option>
            <option value="played">Played</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>
      </div>

      <div class="flex justify-end gap-3 mt-6">
        <button type="button"
                onclick="closeModal()"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
          Batal
        </button>
        <button type="submit"
                class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>
