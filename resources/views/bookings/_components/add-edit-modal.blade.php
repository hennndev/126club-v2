<div id="bookingModal"
     class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
    <div class="p-6 border-b border-gray-200">
      <h3 id="modalTitle"
          class="text-xl font-bold text-gray-900">Booking Baru</h3>
    </div>
    <form id="bookingForm"
          method="POST"
          action="{{ route('admin.bookings.store') }}"
          class="p-6">
      @csrf
      <input type="hidden"
             name="_method"
             value="POST"
             id="formMethod">

      <div class="grid grid-cols-2 gap-4">
        <!-- Customer -->
        <div class="col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-2">Customer <span class="text-red-500">*</span></label>
          <select name="customer_id"
                  id="customer_id"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
            <option value="">Pilih Customer</option>
            @foreach ($customers as $customer)
              <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->email }}</option>
            @endforeach
          </select>
        </div>

        <!-- Table -->
        <div class="col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-2">Meja <span class="text-red-500">*</span></label>
          <select name="table_id"
                  id="table_id"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
            <option value="">Pilih Meja</option>
            @foreach ($tables as $table)
              <option value="{{ $table->id }}">{{ $table->table_number }} - {{ $table->area->name }} ({{ $table->capacity }} seats)</option>
            @endforeach
          </select>
        </div>

        <!-- Reservation Date -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Reservasi <span class="text-red-500">*</span></label>
          <input type="date"
                 name="reservation_date"
                 id="reservation_date"
                 required
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
        </div>

        <!-- Reservation Time -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Reservasi <span class="text-red-500">*</span></label>
          <input type="time"
                 name="reservation_time"
                 id="reservation_time"
                 required
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
        </div>

        <!-- Status -->
        <div class="col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
          <select name="status"
                  id="status"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="checked_in">Checked-in</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>

        <!-- Note -->
        <div class="col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
          <textarea name="note"
                    id="note"
                    rows="3"
                    placeholder="Catatan untuk customer (opsional)"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent resize-none"></textarea>
          <p class="text-xs text-gray-500 mt-1">Maksimal 1000 karakter</p>
        </div>
      </div>

      <div class="flex justify-end gap-3 mt-6">
        <button type="button"
                onclick="closeModal()"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
          Batal
        </button>
        <button type="submit"
                class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>
