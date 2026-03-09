<div id="statusModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
    <div class="p-6 border-b border-gray-200">
      <h3 class="text-xl font-bold text-gray-900">Update Status Booking</h3>
    </div>
    <form id="statusForm"
          method="POST"
          class="p-6">
      @csrf
      @method('PATCH')
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Status Baru</label>
        <div class="space-y-2">
          <!-- Pending -->
          <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
            <input type="radio"
                   name="status"
                   value="pending"
                   class="w-4 h-4 text-slate-600 focus:ring-slate-500">
            <div class="ml-3 flex-1">
              <div class="flex items-center gap-2">
                <span class="px-2 py-1 text-xs font-medium rounded bg-yellow-100 text-yellow-700">Pending</span>
                <span class="text-sm text-gray-700">Menunggu konfirmasi</span>
              </div>
            </div>
          </label>

          <!-- Confirmed -->
          <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
            <input type="radio"
                   name="status"
                   value="confirmed"
                   class="w-4 h-4 text-slate-600 focus:ring-slate-500">
            <div class="ml-3 flex-1">
              <div class="flex items-center gap-2">
                <span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-700">Confirmed</span>
                <span class="text-sm text-gray-700">Booking dikonfirmasi</span>
              </div>
            </div>
          </label>

          <!-- Checked-in -->
          <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
            <input type="radio"
                   name="status"
                   value="checked_in"
                   class="w-4 h-4 text-slate-600 focus:ring-slate-500">
            <div class="ml-3 flex-1">
              <div class="flex items-center gap-2">
                <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-700">Checked-in</span>
                <span class="text-sm text-gray-700">Customer sudah datang</span>
              </div>
            </div>
          </label>

          <!-- Cancelled -->
          <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
            <input type="radio"
                   name="status"
                   value="cancelled"
                   class="w-4 h-4 text-slate-600 focus:ring-slate-500">
            <div class="ml-3 flex-1">
              <div class="flex items-center gap-2">
                <span class="px-2 py-1 text-xs font-medium rounded bg-red-100 text-red-700">Cancelled</span>
                <span class="text-sm text-gray-700">Booking dibatalkan</span>
              </div>
            </div>
          </label>

          <!-- Rejected -->
          <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
            <input type="radio"
                   name="status"
                   value="rejected"
                   class="w-4 h-4 text-slate-600 focus:ring-slate-500">
            <div class="ml-3 flex-1">
              <div class="flex items-center gap-2">
                <span class="px-2 py-1 text-xs font-medium rounded bg-orange-100 text-orange-700">Rejected</span>
                <span class="text-sm text-gray-700">Booking ditolak</span>
              </div>
            </div>
          </label>
        </div>
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
        <button type="button"
                onclick="closeStatusModal()"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
          Batal
        </button>
        <button type="submit"
                class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">
          Update Status
        </button>
      </div>
    </form>
  </div>
</div>
