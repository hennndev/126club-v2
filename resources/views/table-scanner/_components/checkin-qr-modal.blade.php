<!-- Check-in QR Modal -->
<div x-data="{ showCheckInModal: false, qrData: null, timeRemaining: 300, intervalId: null }"
     @open-checkin-modal.window="
        showCheckInModal = true; 
        qrData = $event.detail; 
        timeRemaining = 300;
        if (intervalId) clearInterval(intervalId);
        intervalId = setInterval(() => { 
            if (timeRemaining > 0) {
                timeRemaining--; 
            } else {
                clearInterval(intervalId);
                showCheckInModal = false;
                alert('QR Code sudah expired. Silakan generate ulang.');
            }
        }, 1000);
     "
     @close-checkin-modal.window="showCheckInModal = false; if (intervalId) clearInterval(intervalId);"
     x-show="showCheckInModal"
     x-cloak
     class="fixed z-50 inset-0 overflow-y-auto"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <!-- Background overlay -->
    <div x-show="showCheckInModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
         aria-hidden="true"
         @click="showCheckInModal = false; if (intervalId) clearInterval(intervalId);"></div>

    <!-- Center modal -->
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
          aria-hidden="true">&#8203;</span>

    <!-- Modal panel -->
    <div x-show="showCheckInModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

      <div class="sm:flex sm:items-start">
        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
          <h3 class="text-lg leading-6 font-medium text-gray-900"
              id="modal-title">
            Check-In QR Code
          </h3>

          <div class="mt-4">
            <!-- Timer -->
            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
              <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-yellow-800">QR Code akan expired dalam:</span>
                <span class="text-lg font-bold text-yellow-900"
                      x-text="Math.floor(timeRemaining / 60) + ':' + String(timeRemaining % 60).padStart(2, '0')"></span>
              </div>
            </div>

            <!-- Instructions -->
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
              <p class="text-sm text-blue-800">
                <strong>Instruksi:</strong> Pilih salah satu cara check-in:
              </p>
              <ul class="text-sm text-blue-700 mt-2 space-y-1 ml-4 list-disc">
                <li>Tunjukkan QR Code ke customer untuk di-scan, atau</li>
                <li>Klik tombol "Check In Sekarang" untuk langsung check-in</li>
              </ul>
            </div>

            <!-- QR Code Display -->
            <div class="flex justify-center mb-4">
              <div id="checkinQrCode"
                   class="p-4 bg-white border-2 border-gray-300 rounded-lg"></div>
            </div>

            <!-- QR Code Text -->
            <div class="text-center mb-4">
              <p class="text-sm text-gray-600">QR Code:</p>
              <p class="text-lg font-mono font-bold text-gray-900"
                 x-text="qrData?.qr_code || ''"></p>
            </div>

            <!-- Customer Info -->
            <template x-if="qrData?.reservation">
              <div class="border-t pt-4">
                <h4 class="font-medium text-gray-900 mb-2">Informasi Reservasi:</h4>
                <dl class="grid grid-cols-2 gap-2 text-sm">
                  <dt class="text-gray-600">Customer:</dt>
                  <dd class="font-medium"
                      x-text="qrData.reservation.customer?.name || '-'"></dd>
                  <dt class="text-gray-600">Meja:</dt>
                  <dd class="font-medium"
                      x-text="qrData.reservation.table?.table_number || '-'"></dd>
                  <dt class="text-gray-600">Tanggal:</dt>
                  <dd class="font-medium"
                      x-text="qrData.reservation.booking_date || '-'"></dd>
                </dl>
              </div>
            </template>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="mt-5 sm:mt-4 space-y-3">
        <!-- Primary Action: Check In Now -->
        <button type="button"
                @click="
                    if (confirm('Apakah Anda yakin ingin check-in customer sekarang?')) {
                        window.dispatchEvent(new CustomEvent('process-checkin-now', { detail: qrData?.qr_code }));
                    }
                "
                class="w-full inline-flex justify-center items-center gap-2 rounded-md shadow-sm px-4 py-3 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Check In Sekarang
        </button>

        <!-- Secondary Actions -->
        <div class="flex gap-2">
          <button type="button"
                  @click="
                            if (confirm('Apakah Anda ingin generate QR Code baru?')) {
                                window.dispatchEvent(new CustomEvent('regenerate-checkin-qr', { detail: qrData.reservation.id }));
                            }
                        "
                  class="flex-1 inline-flex justify-center rounded-md border border-blue-300 shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
            Generate Ulang
          </button>
          <button type="button"
                  @click="showCheckInModal = false; if (intervalId) clearInterval(intervalId);"
                  class="flex-1 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Listen for QR modal open event
  window.addEventListener('open-checkin-modal', function(event) {
    // Clear previous QR code
    document.getElementById('checkinQrCode').innerHTML = '';

    // Generate new QR code
    if (event.detail && event.detail.qr_code) {
      new QRCode(document.getElementById('checkinQrCode'), {
        text: event.detail.qr_code,
        width: 256,
        height: 256,
        colorDark: '#000000',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
      });
    }
  });

  // Listen for regenerate event
  window.addEventListener('regenerate-checkin-qr', function(event) {
    const reservationId = event.detail;

    fetch('{{ route('admin.table-scanner.generate-checkin-qr') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          reservation_id: reservationId
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          window.dispatchEvent(new CustomEvent('open-checkin-modal', {
            detail: data.data
          }));
        } else {
          alert(data.message || 'Gagal generate QR Code');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat generate QR Code');
      });
  });

  // Listen for process check-in now event
  window.addEventListener('process-checkin-now', function(event) {
    const qrCode = event.detail;

    if (!qrCode) {
      alert('QR Code tidak ditemukan');
      return;
    }

    fetch('{{ route('admin.table-scanner.process-checkin') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          qr_code: qrCode
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Close modal
          window.dispatchEvent(new CustomEvent('close-checkin-modal'));

          // Show success message
          alert('Check-in berhasil! Customer: ' + data.data.customer + ', Meja: ' + data.data.table);

          // Refresh table info
          window.location.reload();
        } else {
          alert(data.message || 'Gagal melakukan check-in');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat melakukan check-in');
      });
  });
</script>
