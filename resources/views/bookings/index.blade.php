<x-app-layout>
  @php
    $tablesJson = $tables->map(
        fn($t) => [
            'id' => $t->id,
            'table_number' => $t->table_number,
            'capacity' => $t->capacity,
            'minimum_charge' => $t->minimum_charge,
            'area_id' => $t->area_id,
            'area_name' => $t->area->name ?? '',
            'area_code' => $t->area->code ?? '',
            'notes' => $t->notes ?? '',
        ],
    );
  @endphp

  <div class="p-6"
       x-data="bookingPage(@js($tablesJson), @js($activeBookingsByTable->keys()->values()), @js(collect()))">

    @if (session('success'))
      <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded-lg text-sm">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-4 px-4 py-3 bg-red-100 border border-red-400 text-red-700 rounded-lg text-sm">
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
      <button @click="openModal(null)"
              class="flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-lg font-medium transition text-sm">
        <svg class="w-4 h-4"
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

    <!-- Tabs -->
    <div class="flex items-center gap-1 mb-6">
      <a href="{{ route('admin.bookings.index', ['tab' => 'all']) }}"
         class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition
                {{ $tab === 'all' || ($tab !== 'active' && $tab !== 'pending' && $tab !== 'history') ? 'bg-slate-800 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-100' }}">
        <svg class="w-4 h-4"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        Booking
      </a>
      <a href="{{ route('admin.bookings.index', ['tab' => 'pending']) }}"
         class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition
                {{ $tab === 'pending' ? 'bg-yellow-500 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-100' }}">
        <svg class="w-4 h-4"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Pending
        @if ($pendingBookings > 0)
          <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full text-xs font-bold
                       {{ $tab === 'pending' ? 'bg-white text-yellow-600' : 'bg-yellow-500 text-white' }}">
            {{ $pendingBookings }}
          </span>
        @endif
      </a>
      <a href="{{ route('admin.bookings.index', ['tab' => 'active']) }}"
         class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition
                {{ $tab === 'active' ? 'bg-slate-800 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-100' }}">
        <svg class="w-4 h-4"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Active Tables
        @if ($activeSessions->count() > 0)
          <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full text-xs font-bold
                       {{ $tab === 'active' ? 'bg-white text-slate-700' : 'bg-blue-500 text-white' }}">
            {{ $activeSessions->count() }}
          </span>
        @endif
      </a>
      <a href="{{ route('admin.bookings.index', ['tab' => 'history']) }}"
         class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition
                {{ $tab === 'history' ? 'bg-slate-800 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-100' }}">
        <svg class="w-4 h-4"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        History
      </a>
    </div>

    @if ($tab === 'pending')
      {{-- PENDING TAB --}}

      {{-- Top bar: stats + date filter --}}
      <div class="flex items-start justify-between gap-4 mb-5 flex-wrap">
        <div class="flex items-center gap-3">
          <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-5 py-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-yellow-100 rounded-lg flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-yellow-600"
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
              <div class="text-2xl font-bold text-yellow-700">{{ $pendingBookings }}</div>
              <div class="text-sm text-yellow-600">Total Pending</div>
            </div>
          </div>
          @if (!empty($conflictingPendingKeys))
            <div class="bg-orange-50 border border-orange-200 rounded-xl px-5 py-4 flex items-center gap-3">
              <div class="w-9 h-9 bg-orange-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-orange-600"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div>
                <div class="text-xl font-bold text-orange-700">{{ count($conflictingPendingKeys) }}</div>
                <div class="text-sm text-orange-600">Konflik Meja</div>
              </div>
            </div>
          @endif
        </div>

        {{-- Date range filter --}}
        <form method="GET"
              action="{{ route('admin.bookings.index') }}"
              class="flex items-center gap-2 flex-wrap">
          <input type="hidden"
                 name="tab"
                 value="pending">
          <input type="date"
                 name="date_from"
                 value="{{ request('date_from') }}"
                 class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 bg-white text-gray-700">
          <span class="text-gray-400 font-medium text-sm">–</span>
          <input type="date"
                 name="date_to"
                 value="{{ request('date_to') }}"
                 class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 bg-white text-gray-700">
          <button type="submit"
                  class="px-3 py-2 text-xs font-medium bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">Filter</button>
          @if (request('date_from') || request('date_to'))
            <a href="{{ route('admin.bookings.index', ['tab' => 'pending']) }}"
               class="px-3 py-2 text-xs font-medium text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50 transition">Reset</a>
          @endif
        </form>
      </div>

      {{-- Conflict / blocked notices --}}
      @if (!empty($conflictingPendingKeys))
        <div class="flex items-center gap-3 px-4 py-3 bg-orange-50 border border-orange-200 rounded-xl mb-4 text-sm text-orange-800">
          <svg class="w-4 h-4 text-orange-500 shrink-0"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <span>Terdapat <strong>{{ count($conflictingPendingKeys) }} slot meja</strong> yang dipesan oleh lebih dari satu customer. Baris bertanda
            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-orange-200 text-orange-800 rounded text-xs font-semibold">⚠ Konflik</span>
            hanya bisa dikonfirmasi salah satunya — yang lain akan otomatis diblokir.
          </span>
        </div>
      @endif

      {{-- Pending bookings table --}}
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        @if ($bookings->isEmpty())
          <div class="flex flex-col items-center justify-center py-16 text-gray-400">
            <svg class="w-12 h-12 mb-3 text-gray-300"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm font-medium">Tidak ada booking pending</p>
            <p class="text-xs text-gray-400 mt-1">Semua booking sudah dikonfirmasi atau diselesaikan</p>
          </div>
        @else
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Meja</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</th>
                  <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @foreach ($bookings as $booking)
                  @php
                    $slotKey = $booking->table_id . '_' . $booking->reservation_date;
                    $isConflict = in_array($slotKey, $conflictingPendingKeys);
                    $isBlocked = in_array($slotKey, $blockedPendingKeys);
                    $customerName = $booking->customer->profile->name ?? ($booking->customer->customerUser->name ?? ($booking->customer->name ?? '-'));
                    $areaName = $booking->table?->area?->name ?? '';
                    $areaBadge = match (true) {
                        str_contains(strtolower($areaName), 'room') || str_contains(strtolower($areaName), 'vip') => 'bg-purple-100 text-purple-700',
                        str_contains(strtolower($areaName), 'balcony') => 'bg-violet-100 text-violet-700',
                        str_contains(strtolower($areaName), 'lounge') => 'bg-cyan-100 text-cyan-700',
                        strlen($areaName) > 0 => 'bg-gray-100 text-gray-600',
                        default => 'bg-gray-100 text-gray-500',
                    };
                  @endphp
                  <tr class="hover:bg-gray-50 transition-colors {{ $isConflict ? 'bg-orange-50 hover:bg-orange-100' : ($isBlocked ? 'bg-red-50 hover:bg-red-100' : '') }}">

                    {{-- ID --}}
                    <td class="px-4 py-4 whitespace-nowrap">
                      <span class="text-xs font-mono font-semibold text-gray-500">
                        #{{ $booking->booking_code_formatted }}
                      </span>
                    </td>

                    {{-- Customer --}}
                    <td class="px-4 py-4">
                      <div class="font-semibold text-gray-900">{{ $customerName }}</div>
                      @php
                        $phone = $booking->customer->profile?->phone ?? null;
                      @endphp
                      @if ($phone)
                        <div class="text-xs text-gray-400 mt-0.5">{{ $phone }}</div>
                      @endif
                    </td>

                    {{-- Meja --}}
                    <td class="px-4 py-4 whitespace-nowrap">
                      <div class="font-medium text-gray-900">{{ $booking->table?->table_number ?? '-' }}</div>
                      @if ($areaName)
                        <span class="inline-block mt-1 text-xs font-medium px-2 py-0.5 rounded-full {{ $areaBadge }}">
                          {{ $areaName }}
                        </span>
                      @endif
                      {{-- Conflict badge --}}
                      @if ($isBlocked)
                        <div class="mt-1.5">
                          <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 bg-red-100 text-red-700 rounded-full">
                            <svg class="w-3 h-3"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                              <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            Sudah dikonfirmasi customer lain
                          </span>
                        </div>
                      @elseif ($isConflict)
                        <div class="mt-1.5">
                          <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full">
                            <svg class="w-3 h-3"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                              <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            ⚠ Konflik
                          </span>
                        </div>
                      @endif
                    </td>

                    {{-- Date & Time --}}
                    <td class="px-4 py-4 whitespace-nowrap">
                      <div class="font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($booking->reservation_date)->format('d M Y') }}
                      </div>
                      <div class="text-xs text-gray-400 mt-0.5">
                        {{ $booking->reservation_time ? \Carbon\Carbon::parse($booking->reservation_time)->format('H:i') : '-' }}
                      </div>
                    </td>

                    {{-- Note --}}
                    <td class="px-4 py-4 max-w-[180px]">
                      @if ($booking->note)
                        <p class="text-xs text-gray-500 line-clamp-2">{{ $booking->note }}</p>
                      @else
                        <span class="text-xs text-gray-300">—</span>
                      @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-4 py-4 whitespace-nowrap">
                      <div class="flex items-center justify-end gap-2">
                        @if (!$isBlocked)
                          {{-- Confirm --}}
                          <form method="POST"
                                action="{{ route('admin.bookings.updateStatus', $booking) }}"
                                class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden"
                                   name="status"
                                   value="confirmed">
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-green-600 text-white hover:bg-green-700 transition"
                                    onclick="return confirm('Konfirmasi booking ini? Meja akan ditandai sebagai Booked.')">
                              <svg class="w-3.5 h-3.5"
                                   fill="none"
                                   stroke="currentColor"
                                   viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5 13l4 4L19 7" />
                              </svg>
                              Konfirmasi
                            </button>
                          </form>
                        @endif

                        {{-- Reject --}}
                        <form method="POST"
                              action="{{ route('admin.bookings.updateStatus', $booking) }}"
                              class="inline">
                          @csrf
                          @method('PATCH')
                          <input type="hidden"
                                 name="status"
                                 value="rejected">
                          <button type="submit"
                                  class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition"
                                  onclick="return confirm('Tolak booking ini?')">
                            <svg class="w-3.5 h-3.5"
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
                        </form>

                        {{-- Edit / ubah status --}}
                        <button onclick="openStatusModal({{ $booking->id }}, 'pending')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                          <svg class="w-3.5 h-3.5"
                               fill="none"
                               stroke="currentColor"
                               viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                          </svg>
                          Ubah
                        </button>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    @elseif ($tab === 'active')
      {{-- ACTIVE TABLES TAB --}}

      {{-- Stats row --}}
      <div class="grid grid-cols-3 gap-4 mb-5">
        <div class="bg-blue-50 border border-blue-100 rounded-xl px-5 py-4 flex items-center gap-4">
          <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-blue-500"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
          <div>
            <div class="text-2xl font-bold text-blue-700">{{ $activeSessions->count() }}</div>
            <div class="text-sm text-blue-500">Meja Aktif</div>
          </div>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-xl px-5 py-4 flex items-center gap-4">
          <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-green-500"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <div class="text-lg font-bold text-green-700">
              Rp {{ number_format($activeSessions->sum(fn($s) => $s->billing?->grand_total ?? 0), 0, ',', '.') }}
            </div>
            <div class="text-sm text-green-500">Total Tagihan Berjalan</div>
          </div>
        </div>
        <div class="bg-amber-50 border border-amber-100 rounded-xl px-5 py-4 flex items-center gap-4">
          <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-amber-500"
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
            <div class="text-lg font-bold text-amber-700">
              @php
                $avgMin = $activeSessions->count() > 0 ? (int) round($activeSessions->avg(fn($s) => $s->checked_in_at ? abs(now()->diffInMinutes($s->checked_in_at)) : 0)) : null;
              @endphp
              @if ($avgMin !== null)
                {{ $avgMin >= 60 ? floor($avgMin / 60) . 'j ' . $avgMin % 60 . 'm' : $avgMin . ' mnt' }}
              @else
                —
              @endif
            </div>
            <div class="text-sm text-amber-500">Rata-rata Durasi</div>
          </div>
        </div>
      </div>

      {{-- Sessions table --}}
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        @if ($activeSessions->isEmpty())
          <div class="flex flex-col items-center justify-center py-16 text-gray-400">
            <svg class="w-12 h-12 mb-3 text-gray-300"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <p class="text-sm font-medium">Tidak ada sesi aktif</p>
            <p class="text-xs text-gray-400 mt-1">Belum ada customer yang check-in</p>
          </div>
        @else
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Session</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Meja</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waiter</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Check-in</th>
                  <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Min. Charge</th>
                  <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Orders</th>
                  <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total (Live)</th>
                  <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @foreach ($activeSessions as $session)
                  @php
                    $checkedInAt = $session->checked_in_at ? \Carbon\Carbon::parse($session->checked_in_at)->setTimezone('Asia/Jakarta') : null;

                    $billing = $session->billing;
                    $reservation = $session->reservation;
                    $customerName = $reservation?->customer?->profile?->name ?? ($reservation?->customer?->customerUser?->name ?? ($reservation?->customer?->name ?? ($session->customer?->profile?->name ?? ($session->customer?->name ?? 'Tamu'))));
                    $phone = $reservation?->customer?->profile?->phone ?? ($session->customer?->profile?->phone ?? null);
                    $areaName = $session->table?->area?->name ?? '';
                    $areaBadge = match (true) {
                        str_contains(strtolower($areaName), 'room') || str_contains(strtolower($areaName), 'vip') => 'bg-purple-100 text-purple-700',
                        str_contains(strtolower($areaName), 'balcony') => 'bg-violet-100 text-violet-700',
                        str_contains(strtolower($areaName), 'lounge') => 'bg-cyan-100 text-cyan-700',
                        strlen($areaName) > 0 => 'bg-gray-100 text-gray-600',
                        default => 'bg-gray-100 text-gray-500',
                    };

                    // Billing state
                    $canClose = $billing && in_array($billing->billing_status, ['draft', 'finalized']) && $billing->orders_total >= $billing->minimum_charge;
                    $belowMinCharge = $billing && in_array($billing->billing_status, ['draft', 'finalized']) && $billing->orders_total < $billing->minimum_charge;

                    // Waiter
                    $waiterDisplayName = $session->waiter?->profile?->name ?? ($session->waiter?->name ?? null);
                    $waiterId = $session->waiter_id;

                    // Compute live total: only orders + tax - discount (min_charge applied at close)
                    $ordersSubtotal = (float) ($billing?->orders_total ?? 0);
                    $computedGrandTotal = $ordersSubtotal + $ordersSubtotal * (($billing?->tax_percentage ?? 0) / 100) - (float) ($billing?->discount_amount ?? 0);
                  @endphp
                  <tr class="hover:bg-gray-50 transition-colors">

                    {{-- Session code --}}
                    <td class="px-5 py-4 whitespace-nowrap">
                      <div class="font-mono text-xs font-semibold text-gray-500">{{ $session->session_code }}</div>
                      @if ($reservation)
                        <div class="text-xs text-gray-400 mt-0.5">#{{ $reservation->booking_code_formatted }}</div>
                      @endif
                    </td>

                    {{-- Table --}}
                    <td class="px-5 py-4 whitespace-nowrap">
                      <div class="font-semibold text-gray-900">{{ $session->table?->table_number ?? '—' }}</div>
                      @if ($areaName)
                        <span class="inline-block mt-1 text-xs font-medium px-2 py-0.5 rounded-full {{ $areaBadge }}">
                          {{ $areaName }}
                        </span>
                      @endif
                    </td>

                    {{-- Customer --}}
                    <td class="px-5 py-4">
                      <div class="font-medium text-gray-900">{{ $customerName }}</div>
                      @if ($phone)
                        <div class="text-xs text-gray-400 mt-0.5">{{ $phone }}</div>
                      @endif
                    </td>

                    {{-- Waiter --}}
                    <td class="px-5 py-4 whitespace-nowrap">
                      @if ($waiterDisplayName)
                        <div class="font-medium text-gray-900 text-sm">{{ $waiterDisplayName }}</div>
                      @else
                        <span class="text-xs text-gray-400 italic">Belum di-assign</span>
                      @endif
                      @if ($reservation)
                        <button onclick="openAssignWaiterModal({{ $reservation->id }}, {{ $waiterId ?? 'null' }})"
                                class="mt-1 text-xs text-blue-600 hover:underline">
                          {{ $waiterDisplayName ? 'Ganti' : 'Assign' }}
                        </button>
                      @endif
                    </td>

                    {{-- Check-in --}}
                    <td class="px-5 py-4 whitespace-nowrap">
                      <div class="font-medium text-gray-900">
                        {{ $checkedInAt ? $checkedInAt->format('d M Y') : '—' }}
                      </div>
                      <div class="text-xs text-gray-400 mt-0.5">
                        {{ $checkedInAt ? $checkedInAt->format('H:i') : '' }}
                      </div>
                    </td>

                    {{-- Min charge --}}
                    <td class="px-5 py-4 whitespace-nowrap text-right">
                      <div class="text-sm text-gray-700">
                        Rp {{ number_format($billing?->minimum_charge ?? 0, 0, ',', '.') }}
                      </div>
                    </td>

                    {{-- Orders total --}}
                    <td class="px-5 py-4 whitespace-nowrap text-right">
                      <div class="text-sm text-gray-700">
                        Rp {{ number_format($billing?->orders_total ?? 0, 0, ',', '.') }}
                      </div>
                    </td>

                    {{-- Live total --}}
                    <td class="px-5 py-4 whitespace-nowrap text-right">
                      <div class="font-bold text-gray-900">
                        Rp {{ number_format($computedGrandTotal, 0, ',', '.') }}
                      </div>
                      @if ($belowMinCharge)
                        <div class="text-xs text-amber-600 mt-0.5">
                          Min. Rp {{ number_format($billing->minimum_charge, 0, ',', '.') }}
                        </div>
                      @elseif ($billing?->billing_status === 'paid')
                        <div class="text-xs text-green-600 mt-0.5">Lunas</div>
                      @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-5 py-4 whitespace-nowrap">
                      <div class="flex items-center justify-end gap-2">
                        <button onclick="openOrderHistoryModal('{{ $session->session_code }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                          <svg class="w-3.5 h-3.5"
                               fill="none"
                               stroke="currentColor"
                               viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                          </svg>
                          Orders
                        </button>
                        @if ($reservation)
                          @if ($canClose)
                            <button onclick="openCloseBillingModal({{ $reservation->id }}, {{ (float) $billing->minimum_charge }}, {{ (float) $billing->orders_total }}, {{ (float) $billing->discount_amount }}, {{ (float) $computedGrandTotal }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
                              <svg class="w-3.5 h-3.5"
                                   fill="none"
                                   stroke="currentColor"
                                   viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm0 0h10" />
                              </svg>
                              Tutup Billing
                            </button>
                          @endif
                          <button onclick="openStatusModal({{ $reservation->id }}, '{{ $reservation->status }}')"
                                  class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                            <svg class="w-3.5 h-3.5"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                              <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Status
                          </button>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    @elseif ($tab !== 'history')
      <!-- Stats Row -->
      <div class="grid grid-cols-3 gap-4 mb-5">
        <!-- Available -->
        <div class="bg-white border border-gray-200 rounded-xl px-5 py-4 flex items-center gap-4">
          <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-gray-500"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 10h11M9 21V3m12 10h-5m-5 4v2m5-6v6" />
            </svg>
          </div>
          <div>
            <div class="text-2xl font-bold text-gray-900">{{ $availableTablesCount }}</div>
            <div class="text-sm text-gray-500">Available</div>
          </div>
        </div>

        <!-- Booked -->
        <div class="bg-red-50 border border-red-100 rounded-xl px-5 py-4 flex items-center gap-4">
          <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-red-500"
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
            <div class="text-2xl font-bold text-red-600">{{ $bookedTablesCount }}</div>
            <div class="text-sm text-red-400">Booked</div>
          </div>
        </div>

        <!-- Checked-in -->
        <div class="bg-blue-50 border border-blue-100 rounded-xl px-5 py-4 flex items-center gap-4">
          <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-500"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <div>
            <div class="text-2xl font-bold text-blue-600">{{ $checkedInTablesCount }}</div>
            <div class="text-sm text-blue-400">Checked-in</div>
          </div>
        </div>
      </div>

      <!-- Real-time Status Legend -->
      <div class="bg-blue-50 border border-blue-100 rounded-xl px-5 py-3 mb-5 flex items-center gap-3 flex-wrap">
        <div class="flex items-center gap-2">
          <svg class="w-4 h-4 text-blue-500"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
          </svg>
          <span class="text-xs font-semibold text-blue-700">Status Meja Real-Time</span>
        </div>
        <div class="flex items-center gap-4 text-xs text-gray-500">
          <span class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded border border-gray-300 bg-white inline-block"></span>
            Available - Meja tersedia
          </span>
          <span class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded bg-red-500 inline-block"></span>
            Booked - Ada booking, belum check-in
          </span>
          <span class="flex items-center gap-1.5">
            <span class="w-3 h-3 rounded bg-blue-500 inline-block"></span>
            Checked-in - Customer sudah datang
          </span>
        </div>
      </div>

      <!-- Category Filter Cards -->
      @php
        $areaTableCounts = $tables->groupBy('area_id');
        $areaIcons = [
            'room' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
            'vip' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
            'balcony' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />',
            'lounge' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />',
        ];
        $areaColors = [
            'room' => ['bg' => 'bg-purple-50 border-purple-200', 'icon' => 'bg-purple-100 text-purple-600', 'text' => 'text-purple-700', 'count' => 'text-purple-900'],
            'vip' => ['bg' => 'bg-purple-50 border-purple-200', 'icon' => 'bg-purple-100 text-purple-600', 'text' => 'text-purple-700', 'count' => 'text-purple-900'],
            'balcony' => ['bg' => 'bg-violet-50 border-violet-200', 'icon' => 'bg-violet-100 text-violet-600', 'text' => 'text-violet-700', 'count' => 'text-violet-900'],
            'lounge' => ['bg' => 'bg-cyan-50 border-cyan-200', 'icon' => 'bg-cyan-100 text-cyan-600', 'text' => 'text-cyan-700', 'count' => 'text-cyan-900'],
        ];
        $defaultAreaColor = ['bg' => 'bg-gray-50 border-gray-200', 'icon' => 'bg-gray-100 text-gray-600', 'text' => 'text-gray-700', 'count' => 'text-gray-900'];
      @endphp

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
        <!-- Semua -->
        <button @click="selectedCategory = null"
                :class="selectedCategory === null ? 'bg-slate-800 border-slate-800 text-white' : 'bg-white border-gray-200 text-gray-700 hover:border-gray-300'"
                class="relative flex flex-col items-center gap-2 py-4 px-3 rounded-xl border font-medium transition-all">
          <svg class="w-6 h-6"
               :class="selectedCategory === null ? 'text-white' : 'text-gray-500'"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
          </svg>
          <span class="text-sm font-semibold"
                :class="selectedCategory === null ? 'text-white' : 'text-gray-800'">Semua</span>
          <span class="text-lg font-bold"
                :class="selectedCategory === null ? 'text-white' : 'text-gray-900'">{{ $tables->count() }}</span>
        </button>

        @foreach ($areas as $area)
          @php
            $areaKey = strtolower($area->code ?? $area->name);
            $areaKey = str_contains($areaKey, 'room') || str_contains($areaKey, 'vip') ? 'room' : $areaKey;
            $areaKey = str_contains($areaKey, 'balcony') ? 'balcony' : $areaKey;
            $areaKey = str_contains($areaKey, 'lounge') ? 'lounge' : $areaKey;
            $color = $areaColors[$areaKey] ?? $defaultAreaColor;
            $icon = $areaIcons[$areaKey] ?? $areaIcons['lounge'];
            $count = $areaTableCounts->get($area->id)?->count() ?? 0;
          @endphp
          <button @click="selectedCategory = {{ $area->id }}"
                  :class="selectedCategory === {{ $area->id }} ? 'ring-2 ring-offset-1 ring-slate-500 border-transparent shadow-md' : 'hover:shadow-sm'"
                  class="relative flex flex-col items-center gap-2 py-4 px-3 rounded-xl border {{ $color['bg'] }} font-medium transition-all">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $color['icon'] }}">
              <svg class="w-4 h-4"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">{!! $icon !!}</svg>
            </div>
            <span class="text-sm font-semibold {{ $color['text'] }}">{{ $area->name }}</span>
            <span class="text-lg font-bold {{ $color['count'] }}">{{ $count }}</span>
          </button>
        @endforeach
      </div>

      <!-- Table Cards Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($tables as $table)
          @php
            $isBooked = $table->status === 'reserved';
            $isCheckedIn = $table->status === 'occupied';
            $tableBooking = $activeBookingsByTable[$table->id] ?? null;
            $tableAreaKey = strtolower($table->area->code ?? ($table->area->name ?? ''));
            $tableAreaKey = str_contains($tableAreaKey, 'room') || str_contains($tableAreaKey, 'vip') ? 'room' : $tableAreaKey;
            $tableAreaKey = str_contains($tableAreaKey, 'balcony') ? 'balcony' : $tableAreaKey;
            $tableAreaKey = str_contains($tableAreaKey, 'lounge') ? 'lounge' : $tableAreaKey;
            $tableColor = $areaColors[$tableAreaKey] ?? $defaultAreaColor;
          @endphp
          <div x-show="selectedCategory === null || selectedCategory === {{ $table->area_id }}"
               class="bg-white border rounded-xl p-4 transition-all
                      {{ $isCheckedIn ? 'border-blue-200 bg-blue-50/30' : ($isBooked ? 'border-red-200 bg-red-50/20' : 'border-gray-200 hover:border-gray-300 hover:shadow-md') }}
                      {{ ($isBooked || $isCheckedIn) && $tableBooking ? 'cursor-pointer hover:shadow-md' : (!$isBooked && !$isCheckedIn ? 'cursor-pointer' : '') }}"
               @if (!$isBooked && !$isCheckedIn) @click="openModal({{ $table->id }})"
               @elseif ($tableBooking) onclick="openStatusModal({{ $tableBooking->id }}, '{{ $tableBooking->status }}')" @endif>

            <!-- Card Header -->
            <div class="flex items-start justify-between mb-3">
              <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 10h18M3 14h18M10 4v16M14 4v16" />
                </svg>
                <span class="font-semibold text-gray-900 text-sm">{{ $table->table_number }}</span>
              </div>
              @if ($isCheckedIn)
                <span class="flex items-center gap-1 text-xs font-medium text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full">
                  <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                  Checked-in
                </span>
              @elseif ($isBooked)
                <span class="flex items-center gap-1 text-xs font-medium text-red-600 bg-red-100 px-2 py-0.5 rounded-full">
                  <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                  Booked
                </span>
              @else
                <span class="flex items-center gap-1 text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                  <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                  Available
                </span>
              @endif
            </div>

            <!-- Area Badge -->
            <div class="mb-3">
              <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ str_contains(strtolower($table->area->name ?? ''), 'room') || str_contains(strtolower($table->area->name ?? ''), 'vip') ? 'bg-purple-100 text-purple-700' : (str_contains(strtolower($table->area->name ?? ''), 'balcony') ? 'bg-violet-100 text-violet-700' : (str_contains(strtolower($table->area->name ?? ''), 'lounge') ? 'bg-cyan-100 text-cyan-700' : 'bg-gray-100 text-gray-600')) }}">
                {{ $table->area->name ?? '-' }}
              </span>
            </div>

            <!-- Capacity + Min Charge -->
            <div class="flex items-center gap-4 text-xs text-gray-500 mb-3">
              <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ $table->capacity }} seats
              </span>
              @if ($table->minimum_charge)
                <span class="flex items-center gap-1">
                  <svg class="w-3.5 h-3.5"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Min: Rp {{ number_format($table->minimum_charge, 0, ',', '.') }}
                </span>
              @endif
            </div>

            <!-- Footer text -->
            <div class="border-t border-gray-100 pt-2.5 mt-1">
              @if ($isCheckedIn)
                @if ($tableBooking)
                  @php $billing = $tableBooking->tableSession?->billing; @endphp
                  <p class="text-xs font-semibold text-blue-700 text-center truncate">
                    {{ $tableBooking->customer->profile->name ?? ($tableBooking->customer->customerUser->name ?? '-') }}
                  </p>
                  <p class="text-xs text-blue-500 text-center mt-0.5">Klik untuk ubah status</p>
                  @if ($billing && in_array($billing->billing_status, ['draft', 'finalized']))
                    @if ($billing->orders_total >= $billing->minimum_charge)
                      <button onclick="event.stopPropagation(); openCloseBillingModal({{ $tableBooking->id }}, {{ (float) $billing->minimum_charge }}, {{ (float) $billing->orders_total }}, {{ (float) $billing->discount_amount }}, {{ (float) ($billing->minimum_charge + $billing->orders_total + ($billing->minimum_charge + $billing->orders_total) * ($billing->tax_percentage / 100) - $billing->discount_amount) }})"
                              class="mt-2 w-full text-xs font-semibold px-3 py-1.5 rounded-lg bg-green-600 text-white hover:bg-green-500 transition">
                        Tutup Billing
                      </button>
                    @else
                      <p class="mt-2 text-xs text-amber-600 text-center font-medium">
                        Min. charge belum terpenuhi
                        (Rp {{ number_format($billing->minimum_charge - $billing->orders_total, 0, ',', '.') }} kurang)
                      </p>
                    @endif
                  @endif
                @else
                  <p class="text-xs text-blue-500 text-center">Customer sudah check-in</p>
                @endif
              @elseif ($isBooked)
                @if ($tableBooking)
                  <p class="text-xs font-semibold text-red-700 text-center truncate">
                    {{ $tableBooking->customer->profile->name ?? ($tableBooking->customer->customerUser->name ?? '-') }}
                  </p>
                  <p class="text-xs text-red-400 text-center mt-0.5">Klik untuk ubah status</p>
                @else
                  <p class="text-xs text-red-400 text-center">Sudah ada booking hari ini</p>
                @endif
              @else
                <p class="text-xs text-gray-400 text-center">Meja tersedia untuk booking</p>
                <p class="text-xs text-blue-500 font-medium text-center mt-0.5">Klik untuk booking</p>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      <!-- Pending Bookings Section -->
      @if ($todayPendingBookings->isNotEmpty())
        <div class="mt-6">
          <div class="flex items-center gap-2 mb-3">
            <span class="w-2 h-2 rounded-full bg-yellow-400 inline-block"></span>
            <h3 class="text-sm font-semibold text-gray-700">Pending Bookings ({{ $todayPendingBookings->count() }})</h3>
            <span class="text-xs text-gray-400">— belum dikonfirmasi, tidak tampil di peta meja</span>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach ($todayPendingBookings as $pendingBooking)
              <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-center justify-between gap-3">
                <div class="min-w-0">
                  <p class="text-sm font-semibold text-gray-900 truncate">
                    {{ $pendingBooking->customer->profile->name ?? ($pendingBooking->customer->customerUser->name ?? '-') }}
                  </p>
                  <p class="text-xs text-gray-500 mt-0.5">
                    {{ $pendingBooking->table?->table_number ?? '-' }} &middot;
                    {{ \Carbon\Carbon::parse($pendingBooking->reservation_date)->format('d M Y') }} &middot;
                    {{ $pendingBooking->reservation_time ? \Carbon\Carbon::parse($pendingBooking->reservation_time)->format('H:i') : '-' }}
                  </p>
                </div>
                <button onclick="openStatusModal({{ $pendingBooking->id }}, 'pending')"
                        class="flex-shrink-0 text-xs font-medium px-3 py-1.5 rounded-lg bg-yellow-200 text-yellow-800 hover:bg-yellow-300 transition">
                  Ubah Status
                </button>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    @else
      {{-- HISTORY TAB --}}

      {{-- Search & Filters row --}}
      <div class="flex items-center gap-3 mb-5">
        <div class="flex-1 relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
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
                 placeholder="Cari booking (nama, telepon, ID)..."
                 class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-400 bg-white">
        </div>
        <select id="categoryFilter"
                class="px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-400 bg-white">
          <option value="">Semua Category</option>
          @foreach ($areas as $area)
            <option value="{{ $area->id }}">{{ $area->name }}</option>
          @endforeach
        </select>
        <select id="statusFilter"
                class="px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-400 bg-white">
          <option value="">Semua Status</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>

      {{-- 4 Stat cards --}}
      <div class="grid grid-cols-4 gap-4 mb-5">
        {{-- Total Booking --}}
        <div class="bg-white border border-gray-200 rounded-xl px-5 py-4 flex items-center gap-4">
          <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-gray-500"
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
            <div class="text-2xl font-bold text-gray-900">{{ $historyTotalCount }}</div>
            <div class="text-sm text-gray-500">Total Booking</div>
          </div>
        </div>

        {{-- Completed --}}
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-100 rounded-xl px-5 py-4 flex items-center gap-4">
          <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-green-600"
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
            <div class="text-2xl font-bold text-green-700">{{ $historyCompletedCount }}</div>
            <div class="text-sm text-green-600">Completed</div>
          </div>
        </div>

        {{-- Avg Spending --}}
        <div class="bg-gradient-to-br from-blue-50 to-sky-50 border border-blue-100 rounded-xl px-5 py-4 flex items-center gap-4">
          <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-blue-600"
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
            <div class="text-lg font-bold text-blue-700">Rp {{ number_format($historyAvgSpending, 0, ',', '.') }}</div>
            <div class="text-sm text-blue-500">Avg Spending</div>
          </div>
        </div>

        {{-- Total Revenue --}}
        <div class="bg-gradient-to-br from-amber-50 to-yellow-50 border border-amber-100 rounded-xl px-5 py-4 flex items-center gap-4">
          <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-amber-600"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <div class="text-lg font-bold text-amber-700">Rp {{ number_format($historyTotalRevenue, 0, ',', '.') }}</div>
            <div class="text-sm text-amber-500">Total Revenue</div>
          </div>
        </div>
      </div>

      {{-- Table card --}}
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- Date range filter --}}
        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100">
          <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
            <svg class="w-4 h-4 text-gray-400"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
            </svg>
            Filter Tanggal:
          </div>
          <form method="GET"
                action="{{ route('admin.bookings.index') }}"
                class="flex items-center gap-2">
            <input type="hidden"
                   name="tab"
                   value="history">
            @if (request('search'))
              <input type="hidden"
                     name="search"
                     value="{{ request('search') }}">
            @endif
            <div class="flex items-center gap-1.5">
              <svg class="w-4 h-4 text-gray-400"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <input type="date"
                     name="date_from"
                     value="{{ request('date_from') }}"
                     placeholder="Tanggal Mulai"
                     class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-400 bg-white text-gray-700">
            </div>
            <span class="text-gray-400 font-medium">-</span>
            <div class="flex items-center gap-1.5">
              <svg class="w-4 h-4 text-gray-400"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <input type="date"
                     name="date_to"
                     value="{{ request('date_to') }}"
                     placeholder="Tanggal Akhir"
                     class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-400 bg-white text-gray-700">
            </div>
            <button type="submit"
                    class="px-3 py-1.5 text-xs font-medium bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">
              Filter
            </button>
            @if (request('date_from') || request('date_to'))
              <a href="{{ route('admin.bookings.index', ['tab' => 'history']) }}"
                 class="px-3 py-1.5 text-xs font-medium text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                Reset
              </a>
            @endif
          </form>
        </div>

        @if ($bookings->isEmpty())
          <div class="flex flex-col items-center justify-center py-16 text-gray-400">
            <svg class="w-12 h-12 mb-3 text-gray-300"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-sm font-medium">Tidak ada riwayat booking</p>
          </div>
        @else
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Table</th>
                  <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date/Time</th>
                  <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Spent</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100"
                     id="bookingTableBody">
                @foreach ($bookings as $booking)
                  @php
                    $totalSpent = $booking->tableSession?->billing?->grand_total;
                    $histAreaName = $booking->table?->area?->name ?? '';
                    $histAreaKey = strtolower($histAreaName);
                    $histAreaBadge = match (true) {
                        str_contains($histAreaKey, 'room') || str_contains($histAreaKey, 'vip') => 'bg-purple-100 text-purple-700',
                        str_contains($histAreaKey, 'balcony') => 'bg-violet-100 text-violet-700',
                        str_contains($histAreaKey, 'lounge') => 'bg-cyan-100 text-cyan-700',
                        strlen($histAreaName) > 0 => 'bg-gray-100 text-gray-600',
                        default => '',
                    };
                  @endphp
                  <tr class="hover:bg-gray-50 transition-colors booking-row"
                      data-status="{{ $booking->status }}"
                      data-category="{{ $booking->table?->area_id }}">
                    <td class="px-5 py-4 whitespace-nowrap">
                      @php
                        $sc = match ($booking->status) {
                            'completed' => ['bg-green-100 text-green-700', 'Completed'],
                            'cancelled' => ['bg-red-100 text-red-700', 'Cancelled'],
                            'rejected' => ['bg-orange-100 text-orange-700', 'Rejected'],
                            default => ['bg-gray-100 text-gray-600', ucfirst($booking->status)],
                        };
                      @endphp
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $sc[0] }}">
                        @if ($booking->status === 'completed')
                          <svg class="w-3 h-3"
                               fill="none"
                               stroke="currentColor"
                               viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2.5"
                                  d="M9 12l2 2 4-4" />
                          </svg>
                        @else
                          <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        @endif
                        {{ $sc[1] }}
                      </span>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                      <span class="font-mono font-semibold text-gray-700 text-xs">BKG-{{ $booking->booking_code }}</span>
                    </td>
                    <td class="px-5 py-4">
                      <div class="font-semibold text-gray-900">{{ $booking->customer->name }}</div>
                      @if ($booking->note)
                        <div class="text-xs text-gray-400 mt-0.5">{{ $booking->note }}</div>
                      @endif
                    </td>
                    <td class="px-5 py-4">
                      <div class="flex items-center gap-1.5 text-xs text-gray-700">
                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                          <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        {{ $booking->customer->profile?->phone ?? '-' }}
                      </div>
                      <div class="flex items-center gap-1.5 text-xs text-gray-400 mt-1">
                        <svg class="w-3.5 h-3.5 shrink-0"
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
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                      @if ($histAreaBadge)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $histAreaBadge }}">
                          {{ $histAreaName }}
                        </span>
                      @else
                        <span class="text-gray-400 text-xs">-</span>
                      @endif
                    </td>
                    <td class="px-5 py-4">
                      @if ($booking->table)
                        <div class="text-sm font-medium text-gray-800">{{ $booking->table->table_number }}</div>
                        <div class="text-xs text-gray-400">{{ $booking->table->capacity }} seats</div>
                      @else
                        <span class="text-gray-400 text-xs">No table</span>
                      @endif
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                      @if ($booking->reservation_date)
                        <div class="flex items-center gap-1.5 text-xs font-medium text-gray-700">
                          <svg class="w-3.5 h-3.5 text-gray-400"
                               fill="none"
                               stroke="currentColor"
                               viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                          </svg>
                          {{ $booking->reservation_date->format('d M Y') }}
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-gray-400 mt-1">
                          <svg class="w-3.5 h-3.5"
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
                      @else
                        <span class="text-gray-400 text-xs">-</span>
                      @endif
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap text-right">
                      @if ($totalSpent)
                        <span class="text-sm font-semibold text-gray-900">
                          Rp {{ number_format($totalSpent, 0, ',', '.') }}
                        </span>
                      @else
                        <span class="text-gray-300 text-sm">-</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    @endif

    <!-- Add/Edit Modal -->
    @include('bookings._components.add-edit-modal')

    <!-- Delete Modal -->
    @include('bookings._components.delete-confirmation-modal')

    <!-- Status Update Modal -->
    @include('bookings._components.status-update-modal')

    <!-- Close Billing Modal -->
    @include('bookings._components.close-billing-modal')

    <!-- Assign Waiter Modal -->
    @include('bookings._components.assign-waiter-modal')

    <!-- Order History Modal -->
    @include('bookings._components.order-history-modal')
  </div>

  @push('scripts')
    <script>
      const allBookings = @json($bookings);

      function bookingPage(tables, bookedIds, checkedInIds) {
        return {
          selectedCategory: null,
          selectedTableId: null,
          modalOpen: false,
          tables: tables,
          bookedIds: bookedIds,
          checkedInIds: checkedInIds,

          openModal(tableId) {
            this.selectedTableId = tableId;
            const modal = document.getElementById('bookingModal');
            if (modal) {
              modal.classList.remove('hidden');
              // Pre-fill table selection if given
              if (tableId) {
                const table = this.tables.find(t => t.id === tableId);
                if (table) {
                  // update table_id hidden input
                  const tableInput = document.getElementById('table_id');
                  if (tableInput) {
                    tableInput.value = tableId;
                  }
                  // fire custom event to let the modal update its preview
                  document.dispatchEvent(new CustomEvent('table-selected', {
                    detail: table
                  }));
                }
              }
            }
          },
        };
      }

      function closeModal() {
        document.getElementById('bookingModal')?.classList.add('hidden');
      }

      function editBooking(bookingId) {
        const booking = allBookings.find(b => b.id === bookingId);
        if (!booking) return;
        const modal = document.getElementById('bookingModal');
        const form = document.getElementById('bookingForm');
        document.getElementById('modalTitle').textContent = 'Edit Booking';
        form.action = `/admin/bookings/${bookingId}`;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('table_id').value = booking.table_id;
        document.getElementById('customer_id').value = booking.customer_id;
        document.getElementById('reservation_date').value = booking.reservation_date;
        document.getElementById('reservation_time').value = booking.reservation_time;
        document.getElementById('status').value = booking.status;
        document.getElementById('note').value = booking.note || '';
        modal?.classList.remove('hidden');
      }

      function deleteBooking(bookingId) {
        document.getElementById('deleteForm').action = `/admin/bookings/${bookingId}`;
        document.getElementById('deleteModal')?.classList.remove('hidden');
      }

      function closeDeleteModal() {
        document.getElementById('deleteModal')?.classList.add('hidden');
      }

      function openStatusModal(bookingId, currentStatus) {
        const modal = document.getElementById('statusModal');
        const form = document.getElementById('statusForm');
        form.action = `/admin/bookings/${bookingId}/status`;
        const radios = form.querySelectorAll('input[name="status"]');
        radios.forEach(r => {
          r.checked = r.value === currentStatus;
        });
        modal?.classList.remove('hidden');
      }

      function closeStatusModal() {
        document.getElementById('statusModal')?.classList.add('hidden');
      }

      function openAssignWaiterModal(bookingId, currentWaiterId) {
        const modal = document.getElementById('assignWaiterModal');
        const form = document.getElementById('assignWaiterForm');
        form.action = `/admin/bookings/${bookingId}/assign-waiter`;
        const select = document.getElementById('assignWaiterSelect');
        if (select) {
          select.value = currentWaiterId ?? '';
        }
        modal?.classList.remove('hidden');
      }

      function closeAssignWaiterModal() {
        document.getElementById('assignWaiterModal')?.classList.add('hidden');
      }

      @php
        $sessionOrdersJson = $activeSessions->keyBy('session_code')->map(function ($s) {
            return [
                'customer' => $s->reservation?->customer?->profile?->name ?? ($s->reservation?->customer?->customerUser?->name ?? ($s->reservation?->customer?->name ?? 'Tamu')),
                'table' => $s->table?->table_number ?? '—',
                'orders' => $s->orders
                    ->map(function ($o) {
                        return [
                            'order_number' => $o->order_number,
                            'ordered_at' => $o->ordered_at?->setTimezone('Asia/Jakarta')->format('H:i'),
                            'status' => $o->status,
                            'total' => (float) $o->total,
                            'items' => $o->items
                                ->map(function ($i) {
                                    return [
                                        'item_name' => $i->item_name,
                                        'quantity' => $i->quantity,
                                        'price' => (float) $i->price,
                                        'subtotal' => (float) $i->subtotal,
                                    ];
                                })
                                ->values(),
                        ];
                    })
                    ->values(),
            ];
        });
      @endphp
      const sessionOrdersData = @json($sessionOrdersJson);

      function openOrderHistoryModal(sessionCode) {
        const data = sessionOrdersData[sessionCode];
        if (!data) return;

        document.getElementById('orderHistoryTitle').textContent =
          data.customer + ' — Meja ' + data.table;

        const container = document.getElementById('orderHistoryBody');
        if (data.orders.length === 0) {
          container.innerHTML = '<p class="text-sm text-gray-400 text-center py-6">Belum ada order.</p>';
        } else {
          container.innerHTML = data.orders.map(order => {
            const statusClass = {
              pending: 'bg-yellow-100 text-yellow-700',
              processing: 'bg-blue-100 text-blue-700',
              completed: 'bg-green-100 text-green-700',
              cancelled: 'bg-red-100 text-red-700',
            } [order.status] || 'bg-gray-100 text-gray-600';

            const itemRows = order.items.map(item =>
              `<tr class="border-b border-gray-50 last:border-0">
                <td class="py-1.5 pr-3 text-sm text-gray-700">${item.item_name}</td>
                <td class="py-1.5 px-3 text-sm text-gray-500 text-center">${item.quantity}</td>
                <td class="py-1.5 pl-3 text-sm text-gray-500 text-right">Rp ${item.price.toLocaleString('id-ID')}</td>
                <td class="py-1.5 pl-3 text-sm font-medium text-gray-700 text-right">Rp ${item.subtotal.toLocaleString('id-ID')}</td>
              </tr>`
            ).join('');

            return `<div class="mb-4 border border-gray-200 rounded-lg overflow-hidden">
              <div class="flex items-center justify-between bg-gray-50 px-4 py-2.5">
                <div class="flex items-center gap-2">
                  <span class="text-xs font-mono font-semibold text-gray-600">${order.order_number}</span>
                  <span class="text-xs text-gray-400">${order.ordered_at ?? ''}</span>
                </div>
                <div class="flex items-center gap-3">
                  <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium ${statusClass}">${order.status}</span>
                  <span class="text-sm font-bold text-gray-900">Rp ${order.total.toLocaleString('id-ID')}</span>
                </div>
              </div>
              <table class="w-full px-4">
                <thead><tr class="bg-white">
                  <th class="px-4 py-1.5 text-left text-xs text-gray-400 font-medium">Item</th>
                  <th class="px-3 py-1.5 text-center text-xs text-gray-400 font-medium">Qty</th>
                  <th class="px-3 py-1.5 text-right text-xs text-gray-400 font-medium">Harga</th>
                  <th class="px-3 py-1.5 text-right text-xs text-gray-400 font-medium">Subtotal</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50 px-4">${itemRows}</tbody>
              </table>
            </div>`;
          }).join('');
        }

        document.getElementById('orderHistoryModal')?.classList.remove('hidden');
      }

      function closeOrderHistoryModal() {
        document.getElementById('orderHistoryModal')?.classList.add('hidden');
      }

      // History tab client-side filter
      ['searchInput', 'categoryFilter', 'statusFilter'].forEach(id => {
        document.getElementById(id)?.addEventListener(id === 'searchInput' ? 'input' : 'change', filterHistory);
      });

      function filterHistory() {
        const search = (document.getElementById('searchInput')?.value || '').toLowerCase();
        const category = document.getElementById('categoryFilter')?.value || '';
        const status = document.getElementById('statusFilter')?.value || '';
        document.querySelectorAll('.booking-row').forEach(row => {
          const ms = !search || row.textContent.toLowerCase().includes(search);
          const mc = !category || row.dataset.category == category;
          const mst = !status || row.dataset.status == status;
          row.style.display = ms && mc && mst ? '' : 'none';
        });
      }

      document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
          closeModal();
          closeDeleteModal();
          closeStatusModal();
        }
      });

      document.getElementById('bookingModal')?.addEventListener('click', e => {
        if (e.target === e.currentTarget) closeModal();
      });
      document.getElementById('deleteModal')?.addEventListener('click', e => {
        if (e.target === e.currentTarget) closeDeleteModal();
      });
      document.getElementById('statusModal')?.addEventListener('click', e => {
        if (e.target === e.currentTarget) closeStatusModal();
      });
    </script>
  @endpush
</x-app-layout>
