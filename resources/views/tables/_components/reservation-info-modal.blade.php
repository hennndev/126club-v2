<div id="reservationModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
    <div class="p-6 border-b border-gray-200">
      <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold text-gray-900">Detail Reservasi Meja</h3>
        <button onclick="closeReservationModal()"
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
    </div>

    <div class="p-6 space-y-6">
      <!-- Table Info -->
      <div class="bg-slate-50 rounded-lg p-4">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 bg-slate-800 rounded-xl flex items-center justify-center">
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
          <div class="flex-1">
            <h4 class="text-lg font-bold text-gray-900"
                id="resTableNumber">-</h4>
            <p class="text-sm text-gray-500"
               id="resAreaName">-</p>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-500">Kapasitas</p>
            <p class="text-lg font-semibold text-slate-800"
               id="resCapacity">-</p>
          </div>
        </div>
      </div>

      <!-- Booking Info -->
      <div>
        <h5 class="text-sm font-semibold text-gray-700 mb-3">Informasi Booking</h5>
        <div class="grid grid-cols-2 gap-4">
          <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-2">
              <svg class="w-4 h-4 text-gray-400"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
              </svg>
              <span class="text-xs text-gray-500">Booking Code</span>
            </div>
            <p class="text-sm font-semibold text-gray-900"
               id="resBookingCode">-</p>
          </div>

          <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-2">
              <svg class="w-4 h-4 text-gray-400"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="text-xs text-gray-500">Status</span>
            </div>
            <div id="resStatusBadge">-</div>
          </div>

          <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-2">
              <svg class="w-4 h-4 text-gray-400"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span class="text-xs text-gray-500">Tanggal</span>
            </div>
            <p class="text-sm font-semibold text-gray-900"
               id="resDate">-</p>
          </div>

          <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-2">
              <svg class="w-4 h-4 text-gray-400"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="text-xs text-gray-500">Waktu</span>
            </div>
            <p class="text-sm font-semibold text-gray-900"
               id="resTime">-</p>
          </div>
        </div>
      </div>

      <!-- Customer Info -->
      <div>
        <h5 class="text-sm font-semibold text-gray-700 mb-3">Informasi Customer</h5>
        <div class="bg-white border border-gray-200 rounded-lg p-4">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-white"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div class="flex-1">
              <p class="font-semibold text-gray-900"
                 id="resCustomerName">-</p>
              <div class="mt-2 space-y-1">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                  <svg class="w-4 h-4 text-gray-400"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  <span id="resCustomerEmail">-</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                  <svg class="w-4 h-4 text-gray-400"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  <span id="resCustomerPhone">-</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                  <span class="px-2 py-1 text-xs font-medium rounded bg-purple-100 text-purple-700"
                        id="resCustomerLevel">-</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Note (if any) -->
      <div id="resNoteSection"
           class="hidden">
        <h5 class="text-sm font-semibold text-gray-700 mb-3">Catatan</h5>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
          <p class="text-sm text-gray-700"
             id="resNote">-</p>
        </div>
      </div>

      <!-- Minimum Charge Info -->
      <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-orange-700 font-medium">Minimum Charge</p>
            <p class="text-xs text-orange-600 mt-1">Per meja untuk area ini</p>
          </div>
          <div class="text-right">
            <p class="text-2xl font-bold text-orange-700"
               id="resMinCharge">-</p>
          </div>
        </div>
      </div>
    </div>

    <div class="p-6 border-t border-gray-200 flex justify-end gap-3">
      <button type="button"
              onclick="closeReservationModal()"
              class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
        Tutup
      </button>
      <a href="#"
         id="resEditButton"
         class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">
        Edit Booking
      </a>
    </div>
  </div>
</div>
