<x-waiter-mobile-layout>
  <div class="p-5">

    <!-- Header -->
    <div class="mb-5">
      <h1 class="text-2xl font-bold">Transaksi Saya</h1>
      <p class="text-slate-700 text-sm mt-0.5">Sesi meja yang kamu tangani</p>
    </div>

    <!-- Tabs -->
    <div class="flex bg-slate-200 rounded-full p-1 mb-5">
      <a href="{{ route('waiter.transactions', ['tab' => 'active']) }}"
         class="flex-1 py-2.5 rounded-full flex items-center justify-center gap-2 font-semibold text-sm transition-all duration-200 {{ $tab === 'active' ? 'bg-white text-gray-900 shadow-sm' : 'text-slate-700' }}">
        Aktif
        @if ($activeCount > 0)
          <span class="bg-teal-500 text-white text-xs font-bold rounded-full px-2 py-0.5 leading-none">{{ $activeCount }}</span>
        @endif
      </a>
      <a href="{{ route('waiter.transactions', ['tab' => 'history']) }}"
         class="flex-1 py-2.5 rounded-full flex items-center justify-center gap-2 font-semibold text-sm transition-all duration-200 {{ $tab === 'history' ? 'bg-white text-gray-900 shadow-sm' : 'text-slate-700' }}">
        Riwayat
        @if ($historyCount > 0)
          <span class="bg-slate-500 text-white text-xs font-bold rounded-full px-2 py-0.5 leading-none">{{ $historyCount }}</span>
        @endif
      </a>
    </div>

    @if ($sessions->isEmpty())
      <div class="bg-white rounded-2xl p-10 text-center shadow-sm border border-slate-100">
        <svg class="w-12 h-12 mx-auto mb-3 text-slate-400"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p class="text-slate-700 font-medium">
          {{ $tab === 'active' ? 'Tidak ada meja aktif' : 'Belum ada riwayat transaksi' }}
        </p>
        <p class="text-slate-500 text-sm mt-1">
          {{ $tab === 'active' ? 'Kamu belum di-assign ke meja manapun' : 'Transaksi selesai akan muncul di sini' }}
        </p>
      </div>
    @else
      <div class="space-y-3">
        @foreach ($sessions as $session)
          @php
            $checkedInAt = $session->checked_in_at ? \Carbon\Carbon::parse($session->checked_in_at)->setTimezone('Asia/Jakarta') : null;
            $checkedOutAt = $session->checked_out_at ? \Carbon\Carbon::parse($session->checked_out_at)->setTimezone('Asia/Jakarta') : null;
            $duration = $checkedInAt ? ($checkedOutAt ? $checkedInAt->diff($checkedOutAt)->format('%Hj %Im') : $checkedInAt->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE)) : '—';
            $isActive = $session->status === 'active';
          @endphp

          <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">

            <!-- Top row: table + status + billing status -->
            <div class="flex items-start justify-between mb-3">
              <div>
                <div class="flex items-center gap-2">
                  <span class="font-bold text-lg text-slate-900">Meja {{ $session->table?->table_number ?? '?' }}</span>
                  @if ($isActive)
                    <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                  @elseif ($session->status === 'completed')
                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Selesai</span>
                  @else
                    <span class="px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">Force Closed</span>
                  @endif
                </div>
                <p class="text-slate-500 text-xs mt-0.5">{{ $session->table?->area?->name ?? '—' }}</p>
              </div>
              <div class="text-right">
                <p class="text-xs text-slate-500">Durasi</p>
                <p class="text-sm font-semibold text-slate-900">{{ $duration }}</p>
              </div>
            </div>

            <!-- Customer -->
            <div class="flex items-center gap-3 mb-3">
              <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-slate-500"
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
                <p class="font-medium text-sm text-slate-900">{{ $session->customer?->name ?? 'Tamu' }}</p>
                <p class="text-slate-500 text-xs">
                  Check-in {{ $checkedInAt ? $checkedInAt->format('d/m H:i') : '—' }}
                  @if ($checkedOutAt)
                    &bull; Check-out {{ $checkedOutAt->format('H:i') }}
                  @endif
                </p>
              </div>
            </div>

            <!-- Session code -->
            <div class="flex items-center gap-2 mb-3">
              <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
              </svg>
              <span class="text-xs text-slate-500 font-mono">{{ $session->session_code }}</span>
            </div>

            <!-- Billing info -->
            @if ($session->billing)
              <div class="border-t border-slate-100 pt-3 space-y-1.5">
                @if ($session->billing->minimum_charge > 0)
                  <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Min. Charge</span>
                    <span class="text-slate-700">Rp {{ number_format($session->billing->minimum_charge, 0, ',', '.') }}</span>
                  </div>
                @endif
                @if ($session->billing->orders_total > 0)
                  <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Total Order</span>
                    <span class="text-slate-700">Rp {{ number_format($session->billing->orders_total, 0, ',', '.') }}</span>
                  </div>
                @endif
                @if ($session->billing->discount_amount > 0)
                  <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Diskon</span>
                    <span class="text-red-500">-Rp {{ number_format($session->billing->discount_amount, 0, ',', '.') }}</span>
                  </div>
                @endif
                <div class="flex justify-between text-sm font-semibold pt-1 border-t border-slate-100">
                  <span class="text-slate-900">Grand Total</span>
                  <span class="text-slate-900">Rp {{ number_format($session->billing->grand_total, 0, ',', '.') }}</span>
                </div>
                @if ($session->billing->billing_status)
                  <div class="flex justify-end mt-1">
                    @php
                      $billingStatus = $session->billing->billing_status;
                      $statusClass = match ($billingStatus) {
                          'paid' => 'bg-green-100 text-green-700',
                          'unpaid' => 'bg-yellow-100 text-yellow-700',
                          'partial' => 'bg-orange-100 text-orange-700',
                          default => 'bg-slate-100 text-slate-600',
                      };
                    @endphp
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }} capitalize">
                      {{ $billingStatus }}
                    </span>
                  </div>
                @endif
              </div>
            @else
              <div class="border-t border-slate-100 pt-3">
                <p class="text-xs text-slate-400 text-center">Belum ada tagihan</p>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @endif

  </div>
</x-waiter-mobile-layout>
