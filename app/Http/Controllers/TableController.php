<?php

namespace App\Http\Controllers;

use App\Models\Tabel;
use App\Models\Area;
use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TableController extends Controller
{
  // HALAMAN TABLE MANAGEMENT
  public function index(Request $request)
  {
    $query = Tabel::with('area');

    if ($request->has('search') && $request->search != '') {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('table_number', 'like', "%{$search}%")
          ->orWhereHas('area', function ($areaQuery) use ($search) {
            $areaQuery->where('name', 'like', "%{$search}%");
          });
      });
    }

    if ($request->has('area_id') && $request->area_id != '') {
      $query->where('area_id', $request->area_id);
    }

    if ($request->has('status') && $request->status != '') {
      $query->where('status', $request->status);
    }

    $tables = $query->orderBy('area_id')->orderBy('table_number')->get();

    $totalTables = Tabel::count();
    $availableTables = Tabel::where('status', 'available')->where('is_active', true)->count();
    $totalCapacity = Tabel::where('is_active', true)->sum('capacity');

    $areas = Area::where('is_active', true)->orderBy('sort_order')->get();
    $areaStats = Area::where('is_active', true)
      ->withCount(['tables' => function ($q) {
        $q->where('is_active', true);
      }])
      ->orderBy('sort_order')
      ->get();

    // Get active reservations for reserved tables
    $reservations = \App\Models\TableReservation::with(['customer.profile', 'customer.customerUser', 'table.area'])
      ->whereIn('status', ['confirmed', 'checked_in'])
      ->whereIn('table_id', $tables->pluck('id'))
      ->get()
      ->keyBy('table_id');

    return view('tables.index', compact(
      'tables',
      'totalTables',
      'availableTables',
      'totalCapacity',
      'areas',
      'areaStats',
      'reservations'
    ));
  } 

  // CREATE NEW TABLE
  public function store(Request $request)
  {
    $validated = $request->validate([
      'area_id' => 'required|exists:areas,id',
      'table_number' => 'required|string|max:50',
      'capacity' => 'required|integer|min:1',
      'minimum_charge' => 'nullable|numeric|min:0',
      'status' => 'required|in:available,reserved,occupied,maintenance',
      'is_active' => 'boolean',
      'notes' => 'nullable|string',
    ]);

    $validated['qr_code'] = 'QR-' . strtoupper(Str::random(12));
    $validated['is_active'] = $validated['is_active'] ?? true;
    DB::beginTransaction();
    try {
      Tabel::create($validated);
      DB::commit();
      return redirect()->route('admin.tables.index')
        ->with('success', 'Meja berhasil ditambahkan');
    } catch (\Exception $e) {
      DB::rollback();
      return back()->withErrors(['error' => 'Gagal menambahkan meja: ' . $e->getMessage()]);
    }
  }

  // UPDATE TABLE
  public function update(Request $request, Tabel $table)
  {
    $validated = $request->validate([
      'area_id' => 'required|exists:areas,id',
      'table_number' => 'required|string|max:50',
      'capacity' => 'required|integer|min:1',
      'minimum_charge' => 'nullable|numeric|min:0',
      'status' => 'required|in:available,reserved,occupied,maintenance',
      'is_active' => 'boolean',
      'notes' => 'nullable|string',
    ]);
    $validated['is_active'] = $validated['is_active'] ?? false;
    DB::beginTransaction();
    try {
      $table->update($validated);
      DB::commit();
      return redirect()->route('admin.tables.index')
        ->with('success', 'Meja berhasil diupdate');
    } catch (\Exception $e) {
      DB::rollback();
      return back()->withErrors(['error' => 'Gagal mengupdate meja: ' . $e->getMessage()]);
    }
  }

  // DELETE TABLE
  public function destroy(Tabel $table)
  {
    DB::beginTransaction();
    try {
      $table->delete();
      DB::commit();
      return redirect()->route('admin.tables.index')
        ->with('success', 'Meja berhasil dihapus');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Gagal menghapus meja: ' . $e->getMessage()]);
    }
  }

  // HALAMAN TABLE SCANNER
  public function scanner()
  {
    return view('table-scanner.index');
  }

  // SCAN QR MEJA
  public function scanQR(Request $request)
  {
    $validated = $request->validate([
      'qr_code' => 'required|string',
    ]);

    try {
      $table = Tabel::with(['area'])
        ->where('qr_code', $validated['qr_code'])
        ->first();

      if (!$table) {
        return response()->json([
          'success' => false,
          'message' => 'QR Code tidak valid atau meja tidak ditemukan'
        ], 404);
      }

      // Get active reservation for this table
      $reservation = \App\Models\TableReservation::with(['customer.profile', 'customer.customerUser'])
        ->where('table_id', $table->id)
        ->whereIn('status', ['confirmed', 'checked_in'])
        ->first();

      return response()->json([
        'success' => true,
        'data' => [
          'table' => $table,
          'reservation' => $reservation
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ], 500);
    }
  }

  public function generateCheckInQR(Request $request)
  {
    $validated = $request->validate([
      'reservation_id' => 'required|exists:table_reservations,id',
    ]);

    try {
      $reservation = \App\Models\TableReservation::with(['table', 'customer'])
        ->findOrFail($validated['reservation_id']);

      // Check if session already exists
      $session = \App\Models\TableSession::where('table_reservation_id', $reservation->id)
        ->where('status', 'pending')
        ->first();

      if (!$session) {
        // Create new session
        $session = \App\Models\TableSession::create([
          'table_reservation_id' => $reservation->id,
          'table_id' => $reservation->table_id,
          'customer_id' => $reservation->customer_id,
          'session_code' => 'SES-' . strtoupper(Str::random(10)),
          'check_in_qr_code' => 'CHECKIN-' . strtoupper(Str::random(16)),
          // 'checked_in_at' => Carbon
          'check_in_qr_expires_at' => now()->addMinutes(5), // QR valid for 5 minutes
          'status' => 'pending',
        ]);
      } else {
        // Regenerate QR if expired
        if (!$session->isQRValid()) {
          $session->update([
            'check_in_qr_code' => 'CHECKIN-' . strtoupper(Str::random(16)),
            'check_in_qr_expires_at' => now()->addMinutes(5),
          ]);
        }
      }

      return response()->json([
        'success' => true,
        'data' => [
          'session' => $session,
          'reservation' => $reservation,
          'qr_code' => $session->check_in_qr_code,
          'expires_at' => $session->check_in_qr_expires_at->format('Y-m-d H:i:s'),
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ], 500);
    }
  }

  public function processCheckIn(Request $request)
  {
    $validated = $request->validate([
      'qr_code' => 'required|string',
    ]);

    try {
      $session = \App\Models\TableSession::with(['reservation', 'table', 'customer'])
        ->where('check_in_qr_code', $validated['qr_code'])
        ->first();

      if (!$session) {
        return response()->json([
          'success' => false,
          'message' => 'QR Code tidak valid'
        ], 404);
      }

      if (!$session->isQRValid()) {
        return response()->json([
          'success' => false,
          'message' => 'QR Code sudah expired. Silakan generate ulang.'
        ], 400);
      }

      if ($session->status === 'active') {
        return response()->json([
          'success' => false,
          'message' => 'Customer sudah check-in'
        ], 400);
      }

      // Process check-in
      $session->update([
        'checked_in_at' => now(),
        'status' => 'active',
        'check_in_qr_code' => null, // Clear QR after use
        'check_in_qr_expires_at' => null,
      ]);

      // Create billing for this session
      $minimumCharge = $session->table->minimum_charge ?? 0;
      $billing = Billing::create([
        'table_session_id' => $session->id,
        'minimum_charge' => $minimumCharge,
        'orders_total' => 0,
        'subtotal' => $minimumCharge,
        'tax' => $minimumCharge * 0.10, // 10% tax
        'tax_percentage' => 10.00,
        'discount_amount' => 0,
        'grand_total' => $minimumCharge + ($minimumCharge * 0.10),
        'paid_amount' => 0,
        'billing_status' => 'draft',
      ]);

      // Assign billing_id to table_session
      $session->update([
        'billing_id' => $billing->id,
      ]);

      // Update reservation status to checked_in
      $session->reservation->update([
        'status' => 'checked_in'
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Check-in berhasil!',
        'data' => [
          'session' => $session,
          'customer' => $session->customer->name,
          'table' => $session->table->table_number,
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ], 500);
    }
  }
}
