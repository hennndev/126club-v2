<div id="qrModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
      <h3 class="text-xl font-bold text-gray-900">QR Code Meja</h3>
      <button onclick="closeQRModal()"
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
    <div class="p-6"
         id="qrContent">
      <div class="text-center mb-4">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-800 rounded-xl mb-3">
          <svg class="w-8 h-8 text-white"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
          </svg>
        </div>
        <h4 id="qrTableName"
            class="text-xl font-bold text-gray-900 mb-1"></h4>
        <p id="qrAreaName"
           class="text-sm text-gray-500"></p>
        <p id="qrTableInfo"
           class="text-xs text-gray-400 mt-1"></p>
      </div>

      <div class="flex justify-center mb-4">
        <div id="qrcodeContainer"
             class="p-3 bg-white border-2 border-gray-200 rounded-lg"></div>
      </div>

      <div class="bg-slate-50 rounded-lg p-4 mb-4">
        <div class="grid grid-cols-2 gap-3 text-sm">
          <div>
            <p class="text-gray-500 mb-1">Kapasitas</p>
            <p id="qrCapacity"
               class="font-semibold text-gray-900"></p>
          </div>
          <div>
            <p class="text-gray-500 mb-1">Min. Charge</p>
            <p id="qrMinCharge"
               class="font-semibold text-gray-900"></p>
          </div>
        </div>
      </div>

      <div class="text-center">
        <p id="qrCodeText"
           class="text-xs text-gray-400 font-mono mb-4"></p>
        <button onclick="printQRCode()"
                class="w-full px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition flex items-center justify-center gap-2">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
          </svg>
          Print QR Code
        </button>
      </div>
    </div>
  </div>
</div>
