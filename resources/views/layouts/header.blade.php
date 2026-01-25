<header class="bg-white border-b border-gray-200 px-6 py-4">
  <div class="flex items-center justify-between">
    <div class="flex items-center space-x-3">
      <div class="bg-slate-800 rounded-lg p-2">
        <svg class="w-5 h-5 text-white"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
      </div>
      <h1 class="text-xl font-bold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
    </div>

    <div class="flex items-center space-x-4">
      <!-- User Menu -->
      <div class="relative">
        <button class="flex items-center space-x-3 hover:bg-gray-100 rounded-lg px-3 py-2">
          <div class="text-right">
            <p class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500">Administrator</p>
          </div>
          <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
            <span class="text-white font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
          </div>
        </button>
      </div>

      <!-- Logout -->
      <form method="POST"
            action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg"
                title="Logout">
          <svg class="w-5 h-5"
               fill="none"
               stroke="currentColor"
               viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
        </button>
      </form>
    </div>
  </div>
</header>
