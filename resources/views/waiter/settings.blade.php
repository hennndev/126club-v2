<x-waiter-mobile-layout>
  <div class="px-5 pt-5 pb-5">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-xl font-bold">Pengaturan</h1>
      <p class="text-slate-600 text-xs mt-0.5">Profil & akun</p>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-2xl p-5 mb-4 border border-slate-100 shadow-sm">
      <!-- Avatar -->
      <div class="flex items-center gap-4">
        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-teal-600 to-indigo-700 flex items-center justify-center flex-shrink-0">
          <span class="text-2xl font-bold text-white">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
          </span>
        </div>
        <div class="min-w-0">
          <p class="font-bold text-lg leading-tight">{{ auth()->user()->name }}</p>
          <p class="text-slate-600 text-sm mt-0.5 truncate">{{ auth()->user()->email }}</p>
          <span class="inline-block mt-2 bg-teal-900 text-teal-300 text-xs font-semibold px-2.5 py-0.5 rounded-full">
            Waiter / Server
          </span>
        </div>
      </div>
    </div>

    <!-- Info rows -->
    <div class="bg-white rounded-2xl mb-4 divide-y divide-slate-100 border border-slate-100 shadow-sm">
      <div class="px-4 py-3.5 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-slate-700"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <span class="text-sm text-slate-600">Nama</span>
        </div>
        <span class="text-sm font-medium text-right max-w-[55%] truncate">{{ auth()->user()->name }}</span>
      </div>
      <div class="px-4 py-3.5 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-slate-700"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <span class="text-sm text-slate-600">Email</span>
        </div>
        <span class="text-sm font-medium text-right max-w-[55%] truncate">{{ auth()->user()->email }}</span>
      </div>
      <div class="px-4 py-3.5 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-slate-700"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <span class="text-sm text-slate-600">Role</span>
        </div>
        <span class="text-sm font-medium text-teal-400">Waiter / Server</span>
      </div>
    </div>

    <!-- Logout -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
      <form method="POST"
            action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-4 text-red-400 active:bg-red-900/20 rounded-2xl transition">
          <div class="w-8 h-8 rounded-lg bg-red-900/40 flex items-center justify-center">
            <svg class="w-4 h-4 text-red-400"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
              <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
          </div>
          <span class="font-semibold text-sm">Logout</span>
        </button>
      </form>
    </div>

    <!-- App version -->
    <p class="text-center text-slate-700 text-xs mt-6">
      126 Club &middot; Waiter App
    </p>
  </div>
</x-waiter-mobile-layout>
