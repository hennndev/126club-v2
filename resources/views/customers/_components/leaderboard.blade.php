<div id="leaderboardContent"
     class="hidden">
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Top 10 Spenders</h3>
    <div class="space-y-4">
      @foreach ($leaderboard as $index => $customer)
        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
          <div class="flex-shrink-0">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white font-bold">
              #{{ $index + 1 }}
            </div>
          </div>
          <div class="flex-1">
            <div class="font-medium text-gray-900">{{ $customer->user->name }}</div>
            <div class="text-xs text-gray-500">{{ $customer->customer_code }} • {{ $customer->total_visits }} visits</div>
          </div>
          <div class="text-right">
            <div class="font-bold text-green-600">Rp {{ number_format($customer->lifetime_spending / 1000000, 1) }}jt</div>
            <div class="text-xs text-orange-600">{{ number_format($customer->points, 0, ',', '.') }} points</div>
          </div>
          <span class="px-3 py-1 text-xs font-medium rounded-full {{ $customer->membership_tier === 'Untouchable' ? 'bg-yellow-100 text-yellow-700' : 'bg-purple-100 text-purple-700' }}">
            {{ $customer->membership_tier }}
          </span>
        </div>
      @endforeach
    </div>
  </div>
</div>
