<div id="orderHistoryModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center p-4 pt-16 overflow-y-auto">
  <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl mb-8">
    <div class="flex items-center justify-between p-5 border-b border-gray-200">
      <div>
        <h3 class="text-lg font-bold text-gray-900"
            id="orderHistoryTitle">Riwayat Order</h3>
        <p class="text-xs text-gray-400 mt-0.5">Daftar semua order dalam sesi ini</p>
      </div>
      <button onclick="closeOrderHistoryModal()"
              class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
        <svg class="w-5 h-5"
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
    <div class="p-5 max-h-[60vh] overflow-y-auto"
         id="orderHistoryBody">
      {{-- Populated by JS --}}
    </div>
  </div>
</div>
