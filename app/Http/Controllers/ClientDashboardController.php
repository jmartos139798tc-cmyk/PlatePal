<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CatererProfile;
use App\Models\Message;
use App\Models\Review;
use App\Models\SavedCaterer;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        $bookingsQuery = Booking::with('catererProfile')
            ->where('client_id', $userId);

        $activeBookingsCount = (clone $bookingsQuery)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $completedEventsCount = (clone $bookingsQuery)
            ->where('status', 'completed')
            ->count();

        $upcomingBookings = (clone $bookingsQuery)
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->take(3)
            ->get();

        $savedCaterersCount = SavedCaterer::where('user_id', $userId)->count();
        $unreadMessagesCount = Message::where('receiver_id', $userId)->where('is_read', false)->count();
        $allConversations = $this->buildRecentConversations($userId);
        $messageThreadsCount = $allConversations->count();
        $recentConversations = $allConversations->take(3);

        $featuredCaterers = CatererProfile::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('is_featured')
            ->latest()
            ->take(3)
            ->get();

        return view('client.dashboard', compact(
            'user',
            'activeBookingsCount',
            'savedCaterersCount',
            'messageThreadsCount',
            'unreadMessagesCount',
            'completedEventsCount',
            'upcomingBookings',
            'recentConversations',
            'featuredCaterers'
        ));
    }

    public function bookings()
    {
        return view('client.bookings');
    }

    public function saved()
    {
        return view('client.saved');
    }

    public function messages()
    {
        return view('client.messages');
    }

    public function myReviews()
    {
        $reviews = Review::with('catererProfile')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('client.reviews', compact('reviews'));
    }

    public function settings()
    {
        return view('client.settings', ['user' => Auth::user()]);
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update($data);

        return back()->with('success', 'Settings updated successfully.');
    }

    private function buildRecentConversations(int $userId): Collection
    {
        $threadedMessages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(fn ($message) => $message->sender_id === $userId ? $message->receiver_id : $message->sender_id);

        if ($threadedMessages->isEmpty()) {
            return collect();
        }

        $partners = User::with('catererProfile')
            ->whereIn('id', $threadedMessages->keys())
            ->get()
            ->keyBy('id');

        return $threadedMessages->map(function ($messages, $partnerId) use ($partners, $userId) {
            $partner = $partners->get((int) $partnerId);

            if (! $partner) {
                return null;
            }

            $latest = $messages->first();

            return [
                'id' => $partner->id,
                'name' => optional($partner->catererProfile)->business_name ?? $partner->name,
                'excerpt' => $latest->body,
                'time' => $latest->created_at->diffForHumans(short: true),
                'unread_count' => $messages->where('receiver_id', $userId)->where('is_read', false)->count(),
            ];
        })->filter()->values();
    }
}
