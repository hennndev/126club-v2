<div id="deleteModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg w-full max-w-md mx-4">
    <div class="p-6">
      <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
        <svg class="w-6 h-6 text-red-600"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
      </div>
      <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Hapus Role</h3>
      <p class="text-gray-600 text-center mb-6">Apakah Anda yakin ingin menghapus role <span id="deleteRoleName"
              class="font-semibold"></span>?</p>

      <form id="deleteForm"
            method="POST">
        @csrf
        @method('DELETE')
        <div class="flex gap-3">
          <button type="button"
                  onclick="closeDeleteModal()"
                  class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
            Batal
          </button>
          <button type="submit"
                  class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
            Hapus
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
