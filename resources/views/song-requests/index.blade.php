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
                  d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Song Request</h1>
          <p class="text-sm text-gray-500">Kelola permintaan lagu dari customer untuk DJ</p>
        </div>
      </div>
      <button onclick="openModal('add')"
              class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition flex items-center gap-2">
        <svg class="w-5 h-5"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4v16m8-8H4" />
        </svg>
        Request Baru
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 gap-4 mb-6">
      <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-yellow-700 font-medium">Pending</p>
            <p class="text-2xl font-bold text-yellow-900">{{ $pendingRequests }}</p>
          </div>
        </div>
      </div>

      <div class="bg-green-50 border border-green-200 rounded-xl p-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-green-700 font-medium">Played</p>
            <p class="text-2xl font-bold text-green-900">{{ $playedRequests }}</p>
          </div>
        </div>
      </div>

      <div class="bg-pink-50 border border-pink-200 rounded-xl p-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-pink-500 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-pink-700 font-medium">Total Requests</p>
            <p class="text-2xl font-bold text-pink-900">{{ $totalRequests }}</p>
          </div>
        </div>
      </div>
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
                   placeholder="Cari song request (nama, lagu, artist, ID)..."
                   class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
          </div>
        </div>
        <select id="statusFilter"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="played">Played</option>
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
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Song Details</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200"
                 id="songTableBody">
            @foreach ($songRequests as $request)
              <tr class="hover:bg-gray-50 transition song-row"
                  data-status="{{ $request->status }}">
                <td class="px-6 py-4 whitespace-nowrap">
                  @if ($request->status === 'pending')
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
                  @elseif($request->status === 'played')
                    <span class="px-3 py-1 text-xs font-medium rounded bg-green-100 text-green-700 flex items-center gap-1 w-fit">
                      <svg class="w-3 h-3"
                           fill="currentColor"
                           viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd" />
                      </svg>
                      Played
                    </span>
                  @else
                    <span class="px-3 py-1 text-xs font-medium rounded bg-red-100 text-red-700 flex items-center gap-1 w-fit">
                      <svg class="w-3 h-3"
                           fill="currentColor"
                           viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                              clip-rule="evenodd" />
                      </svg>
                      Rejected
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">SONG-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-pink-500 rounded-full flex items-center justify-center text-white font-semibold">
                      {{ strtoupper(substr($request->customerUser->user->name, 0, 1)) }}
                    </div>
                    <div>
                      <div class="text-sm font-medium text-gray-900">{{ $request->customerUser->user->name }}</div>
                      <div class="text-xs text-gray-500">{{ $request->customerUser->user->profile->phone ?? '-' }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="max-w-md">
                    <div class="flex items-start gap-2">
                      <div class="w-8 h-8 bg-pink-500 rounded flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                          <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                      </div>
                      <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $request->song_title }}</p>
                        <p class="text-xs text-gray-500 mt-1">🎤 {{ $request->artist }}</p>
                        @if ($request->tip)
                          <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded bg-yellow-400 text-gray-900">
                            💰 Tips: Rp {{ number_format($request->tip, 0, ',', '.') }}
                          </span>
                        @endif
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ $request->created_at->format('d M Y') }}
                  </div>
                  <div class="text-xs text-gray-500">{{ $request->created_at->format('H:i') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-wrap gap-2">
                    @if ($request->status === 'pending')
                      <button onclick="updateStatus({{ $request->id }}, 'played')"
                              class="px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600 transition flex items-center gap-1">
                        <svg class="w-3 h-3"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                          <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Played
                      </button>
                      <button onclick="updateStatus({{ $request->id }}, 'rejected')"
                              class="px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600 transition flex items-center gap-1">
                        <svg class="w-3 h-3"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                          <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Tolak
                      </button>
                    @endif
                    <button onclick="editSongRequest({{ $request->id }})"
                            class="px-3 py-1 text-xs border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition">
                      Edit
                    </button>
                    <button onclick="deleteSongRequest({{ $request->id }})"
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
  @include('song-requests._components.add-edit-modal')

  <!-- Delete Modal -->
  @include('song-requests._components.delete-modal-confirmation')

  @push('scripts')
    <script>
      const songRequests = @json($songRequests);

      function openModal(mode, requestId = null) {
        const modal = document.getElementById('songModal');
        const form = document.getElementById('songForm');
        const modalTitle = document.getElementById('modalTitle');
        const formMethod = document.getElementById('formMethod');

        if (mode === 'add') {
          modalTitle.textContent = 'Request Baru';
          form.action = '{{ route('admin.song-requests.store') }}';
          formMethod.value = 'POST';
          form.reset();
          document.getElementById('status').value = 'pending';
        } else if (mode === 'edit' && requestId) {
          const request = songRequests.find(r => r.id === requestId);
          if (request) {
            modalTitle.textContent = 'Edit Song Request';
            form.action = `/admin/song-requests/${requestId}`;
            formMethod.value = 'PUT';

            document.getElementById('customer_user_id').value = request.customer_user_id;
            document.getElementById('song_title').value = request.song_title;
            document.getElementById('artist').value = request.artist;
            document.getElementById('tip').value = request.tip || '';
            document.getElementById('status').value = request.status;
          }
        }

        modal.classList.remove('hidden');
      }

      function closeModal() {
        document.getElementById('songModal').classList.add('hidden');
      }

      function editSongRequest(requestId) {
        openModal('edit', requestId);
      }

      function deleteSongRequest(requestId) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/song-requests/${requestId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
      }

      function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
      }

      function updateStatus(requestId, status) {
        if (confirm('Apakah Anda yakin ingin mengubah status song request ini?')) {
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = `/admin/song-requests/${requestId}/status`;

          const csrfToken = document.createElement('input');
          csrfToken.type = 'hidden';
          csrfToken.name = '_token';
          csrfToken.value = '{{ csrf_token() }}';

          const methodField = document.createElement('input');
          methodField.type = 'hidden';
          methodField.name = '_method';
          methodField.value = 'PATCH';

          const statusField = document.createElement('input');
          statusField.type = 'hidden';
          statusField.name = 'status';
          statusField.value = status;

          form.appendChild(csrfToken);
          form.appendChild(methodField);
          form.appendChild(statusField);

          document.body.appendChild(form);
          form.submit();
        }
      }

      // Search functionality
      document.getElementById('searchInput').addEventListener('input', function(e) {
        filterSongs();
      });

      document.getElementById('statusFilter').addEventListener('change', function(e) {
        filterSongs();
      });

      function filterSongs() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.song-row');

        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          const matchesSearch = text.includes(searchTerm);
          const matchesStatus = !statusFilter || row.dataset.status == statusFilter;

          row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
      }

      // Close modals on Escape key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          closeModal();
          closeDeleteModal();
        }
      });

      // Close modals on outside click
      document.getElementById('songModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
      });

      document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
      });
    </script>
  @endpush
</x-app-layout>
