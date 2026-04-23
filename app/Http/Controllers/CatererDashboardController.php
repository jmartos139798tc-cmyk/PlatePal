<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Message;
use App\Models\Package;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CatererDashboardController extends Controller
{
    private function catererProfile()
    {
        return Auth::user()->catererProfile;
    }

    public function index()
    {
        $profile = $this->catererProfile();
        $user = Auth::user();

        $stats = [
            'total_bookings' => Booking::where('caterer_profile_id', $profile->id)->count(),
            'pending_requests' => Booking::where('caterer_profile_id', $profile->id)->where('status', 'pending')->count(),
            'messages' => Message::where('receiver_id', $user->id)->where('is_read', false)->count(),
            'avg_rating' => Review::where('caterer_profile_id', $profile->id)->avg('rating') ?? 0,
            'prev_month_bookings' => Booking::where('caterer_profile_id', $profile->id)
                ->whereMonth('created_at', now()->subMonth()->month)->count(),
            'curr_month_bookings' => Booking::where('caterer_profile_id', $profile->id)
                ->whereMonth('created_at', now()->month)->count(),
        ];

        $upcomingBookings = Booking::with('client')
            ->where('caterer_profile_id', $profile->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->orderBy('event_date')
            ->take(3)
            ->get();

        $earnings = [
            'revenue' => Booking::where('caterer_profile_id', $profile->id)->where('status', 'completed')->sum('total_amount'),
            'satisfaction' => round(Review::where('caterer_profile_id', $profile->id)->avg('rating') * 20),
            'avg_response' => 2.1,
        ];

        $topPackages = Package::where('caterer_profile_id', $profile->id)
            ->withCount([
                'bookings',
                'bookings as active_bookings_count' => fn ($query) => $query->whereIn('status', ['pending', 'confirmed', 'completed']),
            ])
            ->withSum([
                'bookings as revenue_total' => fn ($query) => $query->where('status', '!=', 'cancelled'),
            ], 'total_amount')
            ->orderByDesc('bookings_count')
            ->take(3)
            ->get();

        return view('caterer.dashboard', compact('stats', 'upcomingBookings', 'earnings', 'topPackages', 'profile', 'user'));
    }

    public function bookings()
    {
        $profile = $this->catererProfile();
        $bookings = Booking::with('client')
            ->where('caterer_profile_id', $profile->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('caterer.bookings', compact('bookings'));
    }

    public function confirmBooking($id)
    {
        $profile = $this->catererProfile();
        $booking = Booking::where('caterer_profile_id', $profile->id)->findOrFail($id);
        $booking->update(['status' => 'confirmed']);

        return back()->with('success', 'Booking confirmed.');
    }

    public function rejectBooking($id)
    {
        $profile = $this->catererProfile();
        $booking = Booking::where('caterer_profile_id', $profile->id)->findOrFail($id);
        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking rejected.');
    }

    public function menu()
    {
        $profile = $this->catererProfile();
        $packages = Package::where('caterer_profile_id', $profile->id)->get();

        return view('caterer.menu', compact('profile', 'packages'));
    }

    public function updateMenu(Request $request)
    {
        $profile = $this->catererProfile();
        $data = $request->validate([
            'price_min' => 'required|integer|min:0',
            'price_max' => 'required|integer|min:0',
            'cuisine_type' => 'required|string|max:100',
            'min_guests' => 'required|integer|min:1',
            'max_guests' => 'required|integer|min:1',
        ]);

        $profile->update($data);

        return back()->with('success', 'Menu updated successfully.');
    }

    public function messages()
    {
        $user = Auth::user();
        $conversations = Message::with(['sender', 'receiver'])
            ->where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderByDesc('created_at')
            ->get()
            ->unique(fn ($m) => collect([$m->sender_id, $m->receiver_id])->sort()->implode('-'));

        return view('caterer.messages', compact('conversations'));
    }

    public function reviews()
    {
        $profile = $this->catererProfile();
        $reviews = Review::with('user')
            ->where('caterer_profile_id', $profile->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('caterer.reviews', compact('reviews'));
    }

    public function earnings()
    {
        $profile = $this->catererProfile();
        $monthlyRevenue = Booking::where('caterer_profile_id', $profile->id)
            ->where('status', 'completed')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        return view('caterer.earnings', compact('monthlyRevenue'));
    }

    public function profileSettings()
    {
        $profile = $this->catererProfile();

        return view('caterer.profile-settings', compact('profile'));
    }

    public function updateProfile(Request $request)
    {
        $profile = $this->catererProfile();
        $data = $request->validate([
            'business_name' => 'required|string|max:255',
            'barangay' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string',
            'cuisine_type' => 'nullable|string|max:100',
            'price_min' => 'nullable|integer',
            'price_max' => 'nullable|integer',
            'min_guests' => 'nullable|integer',
            'max_guests' => 'nullable|integer',
            'cover_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_photo')) {
            if ($profile->cover_photo) {
                Storage::delete('public/'.$profile->cover_photo);
            }
            $data['cover_photo'] = $request->file('cover_photo')->store('caterers', 'public');
        }

        $profile->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}
