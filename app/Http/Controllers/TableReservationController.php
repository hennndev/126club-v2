<?php

namespace App\Http\Controllers;

use App\Models\TableReservation;
use App\Models\Tabel;
use App\Models\User;
use Illuminate\Http\Request;

class TableReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = TableReservation::with(['table.area', 'customer.profile', 'customer.customerUser']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhereHas('profile', function ($profileQuery) use ($search) {
                                $profileQuery->where('phone', 'like', "%{$search}%");
                            });
                    })
                    ->orWhereHas('table', function ($tableQuery) use ($search) {
                        $tableQuery->where('table_number', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('category') && $request->category != '') {
            $query->whereHas('table.area', function ($areaQuery) use ($request) {
                $areaQuery->where('id', $request->category);
            });
        }

        $bookings = $query->latest('reservation_date')->latest('reservation_time')->get();
        
        $totalBookings = TableReservation::count();
        $pendingBookings = TableReservation::where('status', 'pending')->count();
        $confirmedBookings = TableReservation::where('status', 'confirmed')->count();
        $checkedInBookings = TableReservation::where('status', 'checked_in')->count();

        $tables = Tabel::with('area')->where('is_active', true)->orderBy('table_number')->get();
        $customers = User::whereHas('customerUser')->with('profile')->orderBy('name')->get();
        $areas = \App\Models\Area::where('is_active', true)->orderBy('sort_order')->get();

        return view('bookings.index', compact(
            'bookings',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'checkedInBookings',
            'tables',
            'customers',
            'areas'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_id' => 'required|exists:users,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'status' => 'required|in:pending,confirmed,checked_in,completed,cancelled,rejected',
            'note' => 'nullable|string|max:1000',
        ]);

        try {
            // Check if table is already reserved by another customer
            if (in_array($validated['status'], ['confirmed', 'checked_in'])) {
                $existingBooking = TableReservation::where('table_id', $validated['table_id'])
                    ->whereIn('status', ['confirmed', 'checked_in'])
                    ->where('reservation_date', $validated['reservation_date'])
                    ->first();
                
                if ($existingBooking) {
                    $table = Tabel::with('area')->find($validated['table_id']);
                    $customerName = $existingBooking->customer->name ?? 'Customer lain';
                    
                    return back()->withErrors([
                        'table_id' => "Meja {$table->area->name} - Nomor {$table->table_number} sudah direservasi oleh {$customerName} pada tanggal yang sama."
                    ])->withInput();
                }
            }

            // Generate unique booking code
            $lastBooking = TableReservation::latest('id')->first();
            $validated['booking_code'] = $lastBooking ? $lastBooking->booking_code + 1 : 1;
            
            $booking = TableReservation::create($validated);
            
            // Update table status based on booking status
            if ($validated['status'] === 'confirmed' || $validated['status'] === 'checked_in') {
                Tabel::where('id', $validated['table_id'])->update(['status' => 'reserved']);
            }
            
            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menambahkan booking: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, TableReservation $booking)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_id' => 'required|exists:users,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'status' => 'required|in:pending,confirmed,checked_in,completed,cancelled,rejected',
            'note' => 'nullable|string|max:1000',
        ]);

        try {
            // Check if trying to change from pending to confirmed/checked_in
            if ($booking->status === 'pending' && in_array($validated['status'], ['confirmed', 'checked_in'])) {
                // Check if table is already reserved by another customer
                $existingBooking = TableReservation::where('table_id', $validated['table_id'])
                    ->whereIn('status', ['confirmed', 'checked_in'])
                    ->where('reservation_date', $validated['reservation_date'])
                    ->where('id', '!=', $booking->id)
                    ->first();
                
                if ($existingBooking) {
                    $table = Tabel::with('area')->find($validated['table_id']);
                    $customerName = $existingBooking->customer->name ?? 'Customer lain';
                    
                    return back()->withErrors([
                        'status' => "Tidak dapat mengkonfirmasi booking. Meja {$table->area->name} - Nomor {$table->table_number} sudah direservasi oleh {$customerName} pada tanggal yang sama. Silakan ubah status ke 'Cancelled' dan tambahkan catatan untuk customer."
                    ])->withInput();
                }
            }

            $oldTableId = $booking->table_id;
            $oldStatus = $booking->status;
            
            $booking->update($validated);
            
            // Update old table status to available if table changed
            if ($oldTableId != $validated['table_id']) {
                Tabel::where('id', $oldTableId)->update(['status' => 'available']);
            }
            
            // Update new table status based on booking status
            if ($validated['status'] === 'confirmed' || $validated['status'] === 'checked_in') {
                Tabel::where('id', $validated['table_id'])->update(['status' => 'reserved']);
            } elseif ($validated['status'] === 'completed' || $validated['status'] === 'cancelled') {
                Tabel::where('id', $validated['table_id'])->update(['status' => 'available']);
            }
            
            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking berhasil diupdate');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengupdate booking: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(TableReservation $booking)
    {
        try {
            $booking->delete();
            return redirect()->route('admin.bookings.index')
                ->with('success', 'Booking berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus booking: ' . $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, TableReservation $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,checked_in,completed,cancelled,rejected',
        ]);

        try {
            // Check if trying to change from pending to confirmed/checked_in
            if ($booking->status === 'pending' && in_array($validated['status'], ['confirmed', 'checked_in'])) {
                // Check if table is already reserved by another customer
                $existingBooking = TableReservation::where('table_id', $booking->table_id)
                    ->whereIn('status', ['confirmed', 'checked_in'])
                    ->where('reservation_date', $booking->reservation_date)
                    ->where('id', '!=', $booking->id)
                    ->first();
                
                if ($existingBooking) {
                    $table = Tabel::with('area')->find($booking->table_id);
                    $customerName = $existingBooking->customer->name ?? 'Customer lain';
                    
                    return back()->withErrors([
                        'status' => "Tidak dapat mengkonfirmasi booking. Meja {$table->area->name} - Nomor {$table->table_number} sudah direservasi oleh {$customerName} pada tanggal yang sama. Silakan ubah status ke 'Cancelled' dan tambahkan catatan untuk customer."
                    ]);
                }
            }

            $booking->update(['status' => $validated['status']]);
            
            // Update table status based on booking status
            $table = Tabel::find($booking->table_id);
            if ($table) {
                if ($validated['status'] === 'confirmed' || $validated['status'] === 'checked_in') {
                    $table->update(['status' => 'reserved']);
                } elseif ($validated['status'] === 'completed' || $validated['status'] === 'cancelled' || $validated['status'] === 'rejected') {
                    $table->update(['status' => 'available']);
                }
            }
            
            $statusMessages = [
                'confirmed' => 'Booking berhasil dikonfirmasi',
                'checked_in' => 'Customer berhasil check-in',
                'completed' => 'Booking berhasil diselesaikan',
                'cancelled' => 'Booking berhasil dibatalkan',
                'rejected' => 'Booking berhasil ditolak',
            ];
            
            $message = $statusMessages[$validated['status']] ?? 'Status booking berhasil diupdate';
            
            return redirect()->route('admin.bookings.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengupdate status: ' . $e->getMessage()]);
        }
    }
}
