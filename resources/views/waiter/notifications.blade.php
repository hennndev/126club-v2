<x-waiter-mobile-layout>
  <div class="px-5 pt-5 pb-5">
    <!-- Header -->
    <div class="mb-5">
      <h1 class="text-xl font-bold">Notifikasi</h1>
      <p class="text-slate-700 text-xs mt-0.5">Reservasi & check-in terbaru</p>
    </div>

    <!-- Pending Check-ins -->
    <div class="mb-6">
      <div class="flex items-center justify-between mb-3">
        <h2 class="font-semibold text-base text-slate-900">Menunggu Check-in</h2>
        @if ($pendingCheckIns->isNotEmpty())
          <span class="inline-flex items-center gap-1 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
            <span class="w-1.5 h-1.5 rounded-full bg-white opacity-80 animate-pulse"></span>
            {{ $pendingCheckIns->count() }} menunggu
          </span>
        @endif
      </div>

      @if ($pendingCheckIns->isEmpty())
        <div class="bg-white rounded-2xl p-6 text-center border border-slate-100">
          <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-slate-700"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <p class="text-slate-700 text-sm">Tidak ada tamu yang menunggu check-in.</p>
        </div>
      @else
        <div class="space-y-3">
          @foreach ($pendingCheckIns as $reservation)
            <div class="bg-white rounded-2xl p-4 border border-slate-100">
              <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                  <!-- Status badge -->
                  <div class="mb-2">
                    <span class="inline-flex items-center gap-1 bg-red-50 border border-red-200 text-red-600 text-xs font-semibold px-2 py-0.5 rounded-full">
                      <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                      Menunggu Check-in
                    </span>
                  </div>
                  <p class="font-semibold text-sm text-slate-900">
                    {{ $reservation->customer?->name ?? ($reservation->customer_name ?? 'Tamu') }}
                  </p>
                  @if ($reservation->table)
                    <p class="text-slate-700 text-xs mt-0.5">
                      Meja {{ $reservation->table->table_number }}
                      @if ($reservation->table->area)
                        · {{ $reservation->table->area->name }}
                      @endif
                    </p>
                  @endif
                  <p class="text-slate-700 text-xs mt-1">
                    {{ \Carbon\Carbon::parse($reservation->reservation_date ?? $reservation->created_at)->timezone('Asia/Jakarta')->translatedFormat('D, d M Y H:i') }}
                  </p>
                  @if ($reservation->notes)
                    <p class="text-slate-700 text-xs mt-1 italic">"{{ Str::limit($reservation->notes, 60) }}"</p>
                  @endif
                </div>
                <a href="{{ route('waiter.scanner') }}"
                   class="flex-shrink-0 flex items-center gap-1.5 bg-teal-600 text-white px-3 py-2 rounded-xl text-xs font-semibold">
                  <svg class="w-3.5 h-3.5"
                       fill="none"
                       stroke="currentColor"
                       viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 3.5a.5.5 0 11-1 0 .5.5 0 011 0zM6 5.5a.5.5 0 11-1 0 .5.5 0 011 0zm-.5 8.5a.5.5 0 100 1 .5.5 0 000-1zm11-8.5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                  </svg>
                  Scan
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <!-- Divider -->
    <div class="border-t border-slate-200 mb-6"></div>

    <!-- Recent Check-ins Today -->
    <div>
      <div class="flex items-center justify-between mb-3">
        <h2 class="font-semibold text-base text-slate-900">Check-in Hari Ini</h2>
        @if ($recentCheckIns->isNotEmpty())
          <span class="inline-flex items-center gap-1 bg-teal-50 border border-teal-200 text-teal-700 text-xs font-semibold px-2.5 py-1 rounded-full">
            <svg class="w-3 h-3"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7" />
            </svg>
            {{ $recentCheckIns->count() }} check-in
          </span>
        @endif
      </div>

      @if ($recentCheckIns->isEmpty())
        <div class="bg-white rounded-2xl p-6 text-center border border-slate-100">
          <p class="text-slate-700 text-sm">Belum ada check-in hari ini.</p>
        </div>
      @else
        <div class="space-y-3">
          @foreach ($recentCheckIns as $checkIn)
            <div class="bg-white rounded-2xl p-4 flex items-center gap-3 border border-slate-100">
              <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-green-600"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                  <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div class="min-w-0 flex-1">
                <p class="font-semibold text-sm text-slate-900">
                  {{ $checkIn->customer?->name ?? 'Tamu' }}
                </p>
                <p class="text-slate-700 text-xs mt-0.5">
                  @if ($checkIn->table)
                    Meja {{ $checkIn->table->table_number }}
                    @if ($checkIn->table->area)
                      · {{ $checkIn->table->area->name }}
                    @endif
                    ·
                  @endif
                  {{ \Carbon\Carbon::parse($checkIn->started_at ?? $checkIn->created_at)->timezone('Asia/Jakarta')->format('H:i') }}
                </p>
              </div>
              <span class="text-xs text-green-600 font-medium flex-shrink-0">Check-in</span>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</x-waiter-mobile-layout>
