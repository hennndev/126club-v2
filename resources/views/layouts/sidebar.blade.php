<aside class="w-64 bg-slate-800 text-white flex flex-col">
  <!-- Logo -->
  <div class="p-6 border-b border-slate-700">
    <div class="flex items-center space-x-3">
      <div class="bg-blue-600 rounded-lg p-2">
        <svg class="w-6 h-6 text-white"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
      </div>
      <div>
        <h1 class="text-lg font-bold">126 Club</h1>
        <p class="text-xs text-slate-400">Premium Management</p>
      </div>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="flex-1 overflow-y-auto py-4 px-3">
    <!-- OPERATIONS -->
    <div class="mb-6">
      <h3 class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Operations</h3>
      <x-nav-link href="{{ route('admin.dashboard') }}"
                  :active="request()->routeIs('admin.dashboard')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </x-slot>
        Dashboard
      </x-nav-link>
      <x-nav-link href="{{ route('admin.tables.index') }}"
                  :active="request()->routeIs('admin.tables.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
          </svg>
        </x-slot>
        Meja
      </x-nav-link>
      <x-nav-link href="{{ route('admin.active-tables.index') }}"
                  :active="request()->routeIs('admin.active-tables.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </x-slot>
        Active Tables
      </x-nav-link>
      <x-nav-link href="{{ route('admin.table-scanner.index') }}"
                  :active="request()->routeIs('admin.table-scanner.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
          </svg>
        </x-slot>
        Table Scanner
      </x-nav-link>
      <x-nav-link href="{{ route('admin.events.index') }}"
                  :active="request()->routeIs('admin.events.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </x-slot>
        Acara
      </x-nav-link>
    </div>

    <!-- TRANSACTION -->
    <div class="mb-6">
      <h3 class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Transaction</h3>
      <x-nav-link href="{{ route('admin.pos.index') }}"
                  :active="request()->routeIs('admin.pos.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
        </x-slot>
        Point of Sale
      </x-nav-link>
      <x-nav-link href="{{ route('admin.bookings.index') }}"
                  :active="request()->routeIs('admin.bookings.*')"
                  :badge="3">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </x-slot>
        Booking
      </x-nav-link>
      <x-nav-link href="#"
                  :active="false">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </x-slot>
        Riwayat Transaksi
      </x-nav-link>
      <x-nav-link href="#"
                  :active="false"
                  :badge="13">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
          </svg>
        </x-slot>
        Transaction Checker
      </x-nav-link>
    </div>

    <!-- PRODUCTION -->
    <div class="mb-6">
      <h3 class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Production</h3>
      <x-nav-link href="{{ route('admin.inventory.index') }}"
                  :active="request()->routeIs('admin.inventory.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
          </svg>
        </x-slot>
        Warehouse
      </x-nav-link>
      <x-nav-link href="{{ route('admin.kitchen.index') }}"
                  :active="request()->routeIs('admin.kitchen.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
        </x-slot>
        Kitchen
      </x-nav-link>
      <x-nav-link href="{{ route('admin.bar.index') }}"
                  :active="request()->routeIs('admin.bar.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </x-slot>
        Bar
      </x-nav-link>
      <x-nav-link href="{{ route('admin.bom.index') }}"
                  :active="request()->routeIs('admin.bom.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </x-slot>
        BOM
      </x-nav-link>
    </div>

    <!-- CUSTOMER MANAGEMENT -->
    <div class="mb-6">
      <h3 class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Customer Management</h3>
      <x-nav-link href="{{ route('admin.customers.index') }}"
                  :active="request()->routeIs('admin.customers.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </x-slot>
        Customer
      </x-nav-link>
      <x-nav-link href="#"
                  :active="false">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </x-slot>
        Customer Keep
      </x-nav-link>
      <x-nav-link href="#"
                  :active="false">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </x-slot>
        Reward
      </x-nav-link>
      <x-nav-link href="{{ route('admin.song-requests.index') }}"
                  :active="request()->routeIs('admin.song-requests.*')"
                  :badge="6">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
          </svg>
        </x-slot>
        Song Request
      </x-nav-link>
      <x-nav-link href="{{ route('admin.display-messages.index') }}"
                  :active="request()->routeIs('admin.display-messages.*')"
                  :badge="4">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
          </svg>
        </x-slot>
        Display Message
      </x-nav-link>
    </div>

    <!-- USER MANAGEMENT -->
    <div class="mb-6">
      <h3 class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">User Management</h3>
      <x-nav-link href="{{ route('admin.users.index') }}"
                  :active="request()->routeIs('admin.users.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </x-slot>
        User Management
      </x-nav-link>
      <x-nav-link href="{{ route('admin.roles.index') }}"
                  :active="request()->routeIs('admin.roles.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
        </x-slot>
        Role Management
      </x-nav-link>
      <x-nav-link href="{{ route('admin.areas.index') }}"
                  :active="request()->routeIs('admin.areas.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </x-slot>
        Area
      </x-nav-link>
      <x-nav-link href="{{ route('admin.printers.index') }}"
                  :active="request()->routeIs('admin.printers.*')">
        <x-slot name="icon">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
          </svg>
        </x-slot>
        Printer
      </x-nav-link>
    </div>
  </nav>

  <!-- Footer -->
  <div class="p-4 border-t border-slate-700">
    <div class="flex items-center justify-between px-3">
      <div class="flex items-center space-x-2">
        <div class="bg-slate-700 rounded p-1.5">
          <span class="text-xs font-bold">126</span>
        </div>
        <div class="text-xs">
          <p class="font-semibold">126 Club</p>
          <p class="text-slate-400">v1.0.0</p>
        </div>
      </div>
      <button class="p-1.5 hover:bg-slate-700 rounded">
        <svg class="w-4 h-4"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
      </button>
    </div>
  </div>
</aside>
