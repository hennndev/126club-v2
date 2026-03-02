<div id="assignWaiterModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
    <div class="p-6 border-b border-gray-200">
      <h3 class="text-xl font-bold text-gray-900">Assign Waiter</h3>
      <p class="text-sm text-gray-500 mt-1">Pilih waiter yang akan menangani customer ini</p>
    </div>
    <form id="assignWaiterForm"
          method="POST"
          class="p-6">
      @csrf
      <div class="mb-5">
        <label class="block text-sm font-medium text-gray-700 mb-2">Waiter</label>
        <select name="waiter_id"
                id="assignWaiterSelect"
                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
          <option value="">— Tidak ada (unassign) —</option>
          @foreach ($waiters as $waiter)
            @php
              $waiterDisplayName = $waiter->profile?->name ?? $waiter->name;
            @endphp
            <option value="{{ $waiter->id }}">{{ $waiterDisplayName }}</option>
          @endforeach
        </select>
      </div>

      <div class="flex gap-3 justify-end">
        <button type="button"
                onclick="closeAssignWaiterModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
          Batal
        </button>
        <button type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>
