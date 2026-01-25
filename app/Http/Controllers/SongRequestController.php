<?php

namespace App\Http\Controllers;

use App\Models\SongRequest;
use App\Models\CustomerUser;
use Illuminate\Http\Request;

class SongRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = SongRequest::with(['customerUser.user.profile']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('song_title', 'like', "%{$search}%")
                    ->orWhere('artist', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('customerUser.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhereHas('profile', function ($profileQuery) use ($search) {
                                $profileQuery->where('phone', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $songRequests = $query->latest()->get();
        
        $totalRequests = SongRequest::count();
        $pendingRequests = SongRequest::where('status', 'pending')->count();
        $playedRequests = SongRequest::where('status', 'played')->count();

        $customerUsers = CustomerUser::with('user.profile')->whereHas('user')->get();

        return view('song-requests.index', compact(
            'songRequests',
            'totalRequests',
            'pendingRequests',
            'playedRequests',
            'customerUsers'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_user_id' => 'required|exists:customer_users,id',
            'song_title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'tip' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,played,rejected',
        ]);

        try {
            SongRequest::create($validated);
            return redirect()->route('admin.song-requests.index')
                ->with('success', 'Song request berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menambahkan song request: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, SongRequest $songRequest)
    {
        $validated = $request->validate([
            'customer_user_id' => 'required|exists:customer_users,id',
            'song_title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'tip' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,played,rejected',
        ]);

        try {
            $songRequest->update($validated);
            return redirect()->route('admin.song-requests.index')
                ->with('success', 'Song request berhasil diupdate');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengupdate song request: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(SongRequest $songRequest)
    {
        try {
            $songRequest->delete();
            return redirect()->route('admin.song-requests.index')
                ->with('success', 'Song request berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus song request: ' . $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, SongRequest $songRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,played,rejected',
        ]);

        try {
            $songRequest->update(['status' => $validated['status']]);
            
            $statusMessages = [
                'played' => 'Song berhasil dimainkan',
                'rejected' => 'Song request berhasil ditolak',
                'pending' => 'Song dikembalikan ke pending',
            ];
            
            $message = $statusMessages[$validated['status']] ?? 'Status song request berhasil diupdate';
            
            return redirect()->route('admin.song-requests.index')->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengupdate status: ' . $e->getMessage()]);
        }
    }
}
