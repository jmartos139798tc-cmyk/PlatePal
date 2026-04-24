<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CatererProfile;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_caterers' => CatererProfile::count(),
            'pending_caterers' => CatererProfile::where('profile_status', 'pending')->count(),
            'approved_caterers' => CatererProfile::where('profile_status', 'approved')->count(),
            'total_bookings' => Booking::count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_reviews' => Review::count(),
            'avg_rating' => Review::avg('rating') ?? 0,
        ];

        $recentCaterers = CatererProfile::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentCaterers'));
    }

    public function pendingCaterers()
    {
        $caterers = CatererProfile::with(['user', 'packages'])
            ->where('profile_status', 'pending')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('admin.pending-caterers', compact('caterers'));
    }

    public function approveCaterer($id)
    {
        $caterer = CatererProfile::findOrFail($id);
        
        $caterer->update([
            'profile_status' => 'approved',
            'is_approved' => true,
        ]);

        return back()->with('success', "Caterer '{$caterer->business_name}' has been approved and is now visible in the marketplace.");
    }

    public function rejectCaterer(Request $request, $id)
    {
        $caterer = CatererProfile::findOrFail($id);
        
        $caterer->update([
            'profile_status' => 'rejected',
            'is_approved' => false,
        ]);

        return back()->with('success', "Caterer '{$caterer->business_name}' has been rejected.");
    }

    public function allCaterers(Request $request)
    {
        $query = CatererProfile::with('user')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('profile_status', $request->status);
        }

        $caterers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.all-caterers', compact('caterers'));
    }

    public function toggleFeatured($id)
    {
        $caterer = CatererProfile::findOrFail($id);
        
        $caterer->update([
            'is_featured' => !$caterer->is_featured,
        ]);

        $status = $caterer->is_featured ? 'featured' : 'unfeatured';
        return back()->with('success', "Caterer '{$caterer->business_name}' has been {$status}.");
    }
}
