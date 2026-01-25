<x-guest-layout>
  {{-- Brand Header --}}
  <div class="mb-8">
    <h1 class="text-gray-800 font-black text-6xl tracking-wider mb-2">126</h1>
    <p class="text-gray-500 text-xs tracking-[0.3em] uppercase">One • Two • Six</p>
  </div>

  {{-- Page Title --}}
  <div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-2">Pilih Database</h2>
    <p class="text-gray-500 text-sm">Pilih database Accurate untuk melanjutkan</p>
  </div>

  {{-- Error Message --}}
  @if (session('error'))
    <div class="mb-4 p-4 text-sm rounded-lg bg-red-50 text-red-700 border border-red-200">
      {{ session('error') }}
    </div>
  @endif

  <form method="POST"
        action="{{ route('database.select') }}"
        class="space-y-5">
    @csrf

    {{-- Database Selection --}}
    <div>
      <select id="database_selection"
              name="selected_db_json"
              class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        <option selected
                disabled>-- Pilih Database Accurate --</option>

        @if (isset($databases))
          @foreach ($databases as $db)
            <option value="{{ json_encode($db) }}">{{ $db['alias'] }}</option>
          @endforeach
        @endif
      </select>
    </div>

    {{-- Submit Button --}}
    <div class="pt-2">
      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
        Lanjutkan
      </button>
    </div>
  </form>
</x-guest-layout>
