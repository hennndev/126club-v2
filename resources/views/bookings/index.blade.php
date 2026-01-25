<x-app-layout>
  <div class="p-6">
    @if (session('success'))
      <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center">
          <svg class="w-6 h-6 text-white"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Manajemen Booking</h1>
          <p class="text-sm text-gray-500">Kelola reservasi nightclub</p>
        </div>
      </div>
      <button onclick="openModal('add')"
              class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition flex items-center gap-2">
        <svg class="w-5 h-5"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4v16m8-8H4" />
        </svg>
        Booking Baru
      </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
      <div class="p-4 flex flex-wrap gap-4">
        <div class="flex-1 min-w-[300px]">
          <div class="relative">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text"
                   id="searchInput"
                   placeholder="Cari booking (nama, telepon, ID, layanan)..."
                   class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
          </div>
        </div>
        <select id="categoryFilter"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
          <option value="">Semua Category</option>
          @foreach ($areas as $area)
            <option value="{{ $area->id }}">{{ $area->name }}</option>
          @endforeach
        </select>
        <select id="statusFilter"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="confirmed">Confirmed</option>
          <option value="checked_in">Checked-in</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200"
                 id="bookingTableBody">
            @foreach ($bookings as $booking)
              <tr class="hover:bg-gray-50 transition booking-row"
                  data-status="{{ $booking->status }}"
                  data-category="{{ $booking->table->area_id }}">
                <td class="px-6 py-4 whitespace-nowrap">
                  @if ($booking->status === 'checked_in')
                    <span class="px-3 py-1 text-xs font-medium rounded bg-gray-100 text-gray-700 flex items-center gap-1 w-fit">
                      <svg class="w-3 h-3"
                           fill="currentColor"
                           viewBox="0 0 20 20">
                        <circle cx="10"
                                cy="10"
                                r="3" />
                      </svg>
                      Checked-in
                    </span>
                  @elseif($booking->status === 'confirmed')
                    <span class="px-3 py-1 text-xs font-medium rounded bg-blue-100 text-blue-700 flex items-center gap-1 w-fit">
                      <svg class="w-3 h-3"
                           fill="currentColor"
                           viewBox="0 0 20 20">
                        <circle cx="10"
                                cy="10"
                                r="3" />
                      </svg>
                      Confirmed
                    </span>
                  @elseif($booking->status === 'pending')
                    <span class="px-3 py-1 text-xs font-medium rounded bg-yellow-100 text-yellow-700 flex items-center gap-1 w-fit">
                      <svg class="w-3 h-3"
                           fill="currentColor"
                           viewBox="0 0 20 20">
                        <circle cx="10"
                                cy="10"
                                r="3" />
                      </svg>
                      Pending
                    </span>
                  @elseif($booking->status === 'completed')
                    <span class="px-3 py-1 text-xs font-medium rounded bg-green-100 text-green-700 flex items-center gap-1 w-fit">
                      <svg class="w-3 h-3"
                           fill="currentColor"
                           viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd" />
                      </svg>
                      Completed
                    </span>
                  @elseif($booking->status === 'cancelled')
                    <span class="px-3 py-1 text-xs font-medium rounded bg-red-100 text-red-700 flex items-center gap-1 w-fit">
                      <svg class="w-3 h-3"
                           fill="currentColor"
                           viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                              clip-rule="evenodd" />
                      </svg>
                      Cancelled
                    </span>
                  @else
                    <span class="px-3 py-1 text-xs font-medium rounded bg-orange-100 text-orange-700 flex items-center gap-1 w-fit">
                      <svg class="w-3 h-3"
                           fill="currentColor"
                           viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z"
                              clip-rule="evenodd" />
                      </svg>
                      Rejected
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">BKG-{{ $booking->booking_code }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ $booking->customer->name }}</div>
                    <div class="text-xs text-gray-500">
                      @if ($booking->customer->customerUser)
                        {{ $booking->customer->customerUser->membership_level ?? 'Regular Customer' }}
                      @else
                        Customer
                      @endif
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    <div class="flex items-center gap-1">
                      <svg class="w-4 h-4 text-gray-400"
                           fill="none"
                           stroke="currentColor"
                           viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                      </svg>
                      {{ $booking->customer->profile->phone ?? '-' }}
                    </div>
                    <div class="flex items-center gap-1 text-xs text-gray-500 mt-1">
                      <svg class="w-3 h-3"
                           fill="none"
                           stroke="currentColor"
                           viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                      {{ $booking->customer->email }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if ($booking->table->area->code === 'VIP') bg-purple-100 text-purple-700
                                    @elseif($booking->table->area->code === 'BAR') bg-blue-100 text-blue-700
                                    @elseif($booking->table->area->code === 'LOUNGE') bg-teal-100 text-teal-700
                                    @else bg-gray-100 text-gray-700 @endif">
                    {{ $booking->table->area->name }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ $booking->table->table_number }}</div>
                    <div class="text-xs text-gray-500">{{ $booking->table->capacity }} seats</div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    <div class="flex items-center gap-1">
                      <svg class="w-4 h-4 text-gray-400"
                           fill="none"
                           stroke="currentColor"
                           viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      {{ $booking->reservation_date->format('d/m/y') }}
                    </div>
                    <div class="flex items-center gap-1 text-xs text-gray-500 mt-1">
                      <svg class="w-3 h-3"
                           fill="none"
                           stroke="currentColor"
                           viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      {{ date('H:i', strtotime($booking->reservation_time)) }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  @if ($booking->note)
                    <div class="text-sm text-gray-600 max-w-xs truncate"
                         title="{{ $booking->note }}">
                      {{ $booking->note }}
                    </div>
                  @else
                    <span class="text-xs text-gray-400">-</span>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-wrap gap-2">
                    @if ($booking->status !== 'completed' && $booking->status !== 'cancelled' && $booking->status !== 'rejected')
                      <button onclick="openStatusModal({{ $booking->id }}, '{{ $booking->status }}')"
                              class="px-3 py-1 text-xs bg-slate-700 text-white rounded hover:bg-slate-800 transition">
                        Update Status
                      </button>
                    @endif
                    <button onclick="editBooking({{ $booking->id }})"
                            class="px-3 py-1 text-xs border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition">
                      Edit
                    </button>
                    <button onclick="deleteBooking({{ $booking->id }})"
                            class="px-3 py-1 text-xs text-red-600 rounded hover:bg-red-50 transition">
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Add/Edit Modal -->
  @include('bookings._components.add-edit-modal')

  <!-- Delete Modal -->
  @include('bookings._components.delete-confirmation-modal')

  <!-- Status Update Modal -->
  @include('bookings._components.status-update-modal')

  @push('scripts')
    <script>
      const bookings = @json($bookings);

      function openModal(mode, bookingId = null) {
        const modal = document.getElementById('bookingModal');
        const form = document.getElementById('bookingForm');
        const modalTitle = document.getElementById('modalTitle');
        const formMethod = document.getElementById('formMethod');

        if (mode === 'add') {
          modalTitle.textContent = 'Booking Baru';
          form.action = '{{ route('admin.bookings.store') }}';
          formMethod.value = 'POST';
          form.reset();
          document.getElementById('status').value = 'pending';
        } else if (mode === 'edit' && bookingId) {
          const booking = bookings.find(b => b.id === bookingId);
          if (booking) {
            modalTitle.textContent = 'Edit Booking';
            form.action = `/admin/bookings/${bookingId}`;
            formMethod.value = 'PUT';

            document.getElementById('customer_id').value = booking.customer_id;
            document.getElementById('table_id').value = booking.table_id;
            document.getElementById('reservation_date').value = booking.reservation_date;
            document.getElementById('reservation_time').value = booking.reservation_time;
            document.getElementById('status').value = booking.status;
            document.getElementById('note').value = booking.note || '';
          }
        }

        modal.classList.remove('hidden');
      }

      function closeModal() {
        document.getElementById('bookingModal').classList.add('hidden');
      }

      function editBooking(bookingId) {
        openModal('edit', bookingId);
      }

      function deleteBooking(bookingId) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/bookings/${bookingId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
      }

      function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
      }

      function openStatusModal(bookingId, currentStatus) {
        const modal = document.getElementById('statusModal');
        const form = document.getElementById('statusForm');
        form.action = `/admin/bookings/${bookingId}/status`;

        // Uncheck all radio buttons first
        const radios = form.querySelectorAll('input[name="status"]');
        radios.forEach(radio => {
          radio.checked = false;
          // Disable current status
          if (radio.value === currentStatus) {
            radio.checked = true;
            radio.parentElement.classList.add('bg-slate-50', 'opacity-60');
          } else {
            radio.parentElement.classList.remove('bg-slate-50', 'opacity-60');
          }
        });

        modal.classList.remove('hidden');
      }

      function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
      }

      // Search functionality
      document.getElementById('searchInput').addEventListener('input', function(e) {
        filterBookings();
      });

      document.getElementById('categoryFilter').addEventListener('change', function(e) {
        filterBookings();
      });

      document.getElementById('statusFilter').addEventListener('change', function(e) {
        filterBookings();
      });

      function filterBookings() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const categoryFilter = document.getElementById('categoryFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.booking-row');

        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          const matchesSearch = text.includes(searchTerm);
          const matchesCategory = !categoryFilter || row.dataset.category == categoryFilter;
          const matchesStatus = !statusFilter || row.dataset.status == statusFilter;

          row.style.display = matchesSearch && matchesCategory && matchesStatus ? '' : 'none';
        });
      }

      // Close modals on Escape key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          closeModal();
          closeDeleteModal();
          closeStatusModal();
        }
      });

      // Close modals on outside click
      document.getElementById('bookingModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
      });

      document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
      });

      document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) closeStatusModal();
      });
    </script>
  @endpush
</x-app-layout>
