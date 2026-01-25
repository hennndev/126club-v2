<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
  <div class="bg-gradient-to-br from-teal-50 to-teal-100 border-2 border-teal-200 rounded-xl p-6">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-teal-700 mb-1">Total Area</p>
        <p class="text-4xl font-bold text-teal-900">{{ $areas->count() }}</p>
      </div>
      <div class="bg-teal-600 p-4 rounded-lg">
        <svg class="w-8 h-8 text-white"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
        </svg>
      </div>
    </div>
  </div>
  <div class="bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200 rounded-xl p-6">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-green-700 mb-1">Active</p>
        <p class="text-4xl font-bold text-green-900">{{ $areas->where('is_active', true)->count() }}</p>
      </div>
      <div class="bg-green-600 p-4 rounded-lg">
        <svg class="w-8 h-8 text-white"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
          <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
    </div>
  </div>
</div>
