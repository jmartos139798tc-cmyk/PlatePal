{{-- Resources/views/client/dashboard.blade.php --}}
<x-layouts.app :title="'Client Dashboard - PlatePal'">

@php
    $overviewRoute = route('client.dashboard');
    $marketplaceRoute = route('browse');

    $fallbackUpcomingBookings = collect([
        [
            'event_name' => 'Birthday Party',
            'caterer_name' => "Lola Maria's Kitchen",
            'event_date' => 'Apr 20, 2026',
            'guest_count' => 50,
            'status' => 'confirmed',
        ],
        [
            'event_name' => 'Graduation Party',
            'caterer_name' => 'Kusina ni Aling Nena',
            'event_date' => 'May 5, 2026',
            'guest_count' => 80,
            'status' => 'confirmed',
        ],
        [
            'event_name' => 'Anniversary Party',
            'caterer_name' => 'TasteBuds Catering',
            'event_date' => 'May 12, 2026',
            'guest_count' => 30,
            'status' => 'pending',
        ],
    ]);

    $fallbackConversations = collect([
        [
            'name' => "Lola Maria's Kitchen",
            'excerpt' => 'Your booking for April 20 is confirmed!',
            'time' => '1h ago',
            'unread_count' => 1,
        ],
        [
            'name' => 'Kusina ni Aling Nena',
            'excerpt' => 'We can customize the menu as per your request.',
            'time' => '3h ago',
            'unread_count' => 0,
        ],
        [
            'name' => 'TasteBuds Catering',
            'excerpt' => 'Thank you for booking us!',
            'time' => '1d ago',
            'unread_count' => 0,
        ],
    ]);

    $fallbackFeaturedCaterers = collect([
        [
            'name' => "Lola Maria's Kitchen",
            'location' => 'Magugpo Poblacion',
            'cuisine' => 'Authentic Tagum Native Chicken',
            'rating' => '4.8',
            'reviews' => 127,
            'price' => '300-500',
            'image' => 'Pusit.jpg',
        ],
        [
            'name' => 'Kusina ni Aling Nena',
            'location' => 'Apokon',
            'cuisine' => 'Mindanao Fusion Cuisine',
            'rating' => '4.9',
            'reviews' => 98,
            'price' => '400-600',
            'image' => 'Kusina ni Aling Nena.jpg',
        ],
        [
            'name' => 'TasteBuds Catering',
            'location' => 'Visayan Village',
            'cuisine' => 'Party Packages & Event Buffet',
            'rating' => '4.7',
            'reviews' => 156,
            'price' => '350-550',
            'image' => 'TasteBuds Catering.jpg',
        ],
    ]);

    $displayUpcomingBookings = $upcomingBookings->isNotEmpty() ? $upcomingBookings : $fallbackUpcomingBookings;
    $displayConversations = $recentConversations->isNotEmpty() ? $recentConversations : $fallbackConversations;
    $displayFeaturedCaterers = $featuredCaterers->isNotEmpty() ? $featuredCaterers : $fallbackFeaturedCaterers;

    $dashboardStats = [
        [
            'label' => 'Active Bookings',
            'value' => $activeBookingsCount,
            'icon' => 'fa-calendar-check',
            'iconBg' => 'bg-[#FFF1E6]',
            'iconText' => 'text-[#F7661B]',
        ],
        [
            'label' => 'Saved Caterers',
            'value' => $savedCaterersCount,
            'icon' => 'fa-heart',
            'iconBg' => 'bg-[#FFF3EC]',
            'iconText' => 'text-[#F27A35]',
        ],
        [
            'label' => 'Messages',
            'value' => $messageThreadsCount,
            'icon' => 'fa-message',
            'iconBg' => 'bg-[#F3F4F6]',
            'iconText' => 'text-[#5A6472]',
        ],
        [
            'label' => 'Completed Events',
            'value' => $completedEventsCount,
            'icon' => 'fa-star',
            'iconBg' => 'bg-[#FFF8E8]',
            'iconText' => 'text-[#D39A09]',
        ],
    ];

    $menuItems = [
        [
            'label' => 'Browse Caterers',
            'href' => route('browse'),
            'icon' => 'fa-magnifying-glass',
            'badge' => null,
            'highlight' => true,
        ],
        [
            'label' => 'My Bookings',
            'href' => route('client.bookings'),
            'icon' => 'fa-calendar',
            'badge' => $activeBookingsCount > 0 ? $activeBookingsCount : null,
            'highlight' => false,
        ],
        [
            'label' => 'Saved Caterers',
            'href' => route('client.saved'),
            'icon' => 'fa-heart',
            'badge' => null,
            'highlight' => false,
        ],
        [
            'label' => 'Messages',
            'href' => route('client.messages'),
            'icon' => 'fa-message',
            'badge' => $unreadMessagesCount > 0 ? $unreadMessagesCount : null,
            'highlight' => false,
        ],
        [
            'label' => 'My Reviews',
            'href' => route('client.reviews'),
            'icon' => 'fa-star',
            'badge' => null,
            'highlight' => false,
        ],
    ];
@endphp

<div class="min-h-screen bg-[#FCF8F3] text-[#2D241D]">
    <header class="border-b border-[#F1E3D5] bg-white">
        <div class="mx-auto flex max-w-[1240px] flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ $overviewRoute }}" class="flex items-center gap-3 text-[#221F2D] transition-opacity hover:opacity-80">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#FFF1E7] text-[#F7661B]">
                        <i class="fas fa-utensils text-[14px]"></i>
                    </span>

                    <span class="leading-none">
                        <span class="block text-[16px] font-extrabold tracking-[0.18em] text-[#111827]">PLATEPAL</span>
                    </span>
                </a>

                <span class="hidden h-6 w-px bg-[#E8DACA] lg:block"></span>
                <span class="text-[13px] font-medium text-[#8A7868]">Client Overview</span>
            </div>

            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                <nav class="flex items-center gap-2 text-[13px] font-medium text-[#7D6A59]">
                    <a href="{{ $overviewRoute }}" class="rounded-full bg-[#FFF3EA] px-3 py-2 text-[#F7661B]">Overview</a>
                    <a href="{{ $marketplaceRoute }}" class="rounded-full px-3 py-2 transition-colors hover:bg-[#FFF3EA] hover:text-[#F7661B]">Marketplace</a>
                </nav>

                <div class="flex items-center gap-3 rounded-full bg-[#FFF4EC] px-3 py-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#F7661B] text-xs font-bold text-white">
                        {{ strtoupper(substr($user->name ?? 'J', 0, 1)) }}
                    </span>
                    <span class="text-[12px] font-semibold text-[#6E4B31]">{{ $user->name ?? 'Juan Dela Cruz' }}</span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#F7661B] px-5 py-2.5 text-[12px] font-semibold text-white transition-colors hover:bg-[#DE570F]">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="mx-auto grid max-w-[1240px] gap-6 px-4 py-6 sm:px-6 lg:grid-cols-[220px_minmax(0,1fr)]">
        <aside class="overflow-hidden rounded-[28px] border border-[#F3E2D1] bg-[#FFF4EC] shadow-[0_12px_26px_rgba(125,95,56,0.06)] lg:sticky lg:top-6 lg:self-start">
            <div class="flex flex-col lg:min-h-[calc(100vh-148px)]">
                <div class="px-6 pt-6 text-[11px] font-semibold uppercase tracking-[0.28em] text-[#C77842]">Menu</div>

                <nav class="mt-5 space-y-2 px-4 pb-5">
                    @foreach ($menuItems as $item)
                        <a
                            href="{{ $item['href'] }}"
                            class="{{ $item['highlight'] ? 'bg-white text-[#F7661B] shadow-[0_12px_24px_rgba(245,102,27,0.12)]' : 'text-[#8B6748] hover:bg-white/70 hover:text-[#F7661B]' }} flex items-center justify-between rounded-[18px] px-4 py-3 text-[14px] font-medium transition-all"
                        >
                            <span class="flex items-center gap-3.5">
                                <i class="fas {{ $item['icon'] }} text-[13px]"></i>
                                <span>{{ $item['label'] }}</span>
                            </span>

                            @if ($item['badge'])
                                <span class="rounded-full bg-[#F7661B] px-2 py-0.5 text-[10px] font-bold text-white">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                <div class="border-t border-[#EFDCCB] px-4 py-5 lg:mt-auto">
                    <a href="{{ route('client.settings') }}" class="flex items-center gap-3 rounded-[18px] px-4 py-3 text-[14px] font-medium text-[#8B6748] transition-colors hover:bg-white/70 hover:text-[#F7661B]">
                        <i class="fas fa-user text-[13px]"></i>
                        <span>Account Settings</span>
                    </a>
                </div>
            </div>
        </aside>

        <main class="min-w-0 space-y-6">
            <section class="grid grid-cols-2 gap-4 xl:grid-cols-4">
                @foreach ($dashboardStats as $stat)
                    <article class="rounded-[22px] border border-[#F1E3D4] bg-white px-5 py-4 shadow-[0_10px_26px_rgba(125,95,56,0.08)]">
                        <div class="mb-5 flex h-11 w-11 items-center justify-center rounded-[16px] {{ $stat['iconBg'] }} {{ $stat['iconText'] }}">
                            <i class="fas {{ $stat['icon'] }} text-[16px]"></i>
                        </div>
                        <div class="text-[42px] font-extrabold leading-none text-[#2A2018]">{{ $stat['value'] }}</div>
                        <p class="mt-2 text-[12px] font-medium text-[#8A7868]">{{ $stat['label'] }}</p>
                    </article>
                @endforeach
            </section>

            <section class="rounded-[24px] border border-[#F1E3D4] bg-white p-5 shadow-[0_10px_26px_rgba(125,95,56,0.08)]">
                <h2 class="text-[16px] font-bold text-[#2D241D]">Find Your Perfect Caterer</h2>

                <form action="{{ route('browse') }}" method="GET" class="mt-4 flex flex-col gap-3 md:flex-row">
                    <div class="relative flex-1">
                        <i class="fas fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[12px] text-[#B7A18E]"></i>
                        <input
                            type="text"
                            name="search"
                            placeholder="Search caterers or specialties..."
                            class="w-full rounded-[18px] border border-[#F0E2D4] bg-[#FFF8F2] py-3.5 pl-11 pr-4 text-sm text-[#3D3128] outline-none transition-colors placeholder:text-[#B7A18E] focus:border-[#F7661B] focus:bg-white"
                        >
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-[18px] bg-[#F7661B] px-6 py-3.5 text-sm font-semibold text-white transition-colors hover:bg-[#DE570F]">
                        <span>Search</span>
                        <i class="fas fa-arrow-right text-[11px]"></i>
                    </button>
                </form>
            </section>

            <section class="grid grid-cols-1 gap-5 xl:grid-cols-[1.08fr,0.92fr]">
                <article class="rounded-[24px] border border-[#F1E3D4] bg-white p-5 shadow-[0_10px_26px_rgba(125,95,56,0.08)]">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <h2 class="text-[16px] font-bold text-[#2D241D]">Upcoming Events</h2>
                        <a href="{{ route('client.bookings') }}" class="text-[12px] font-semibold text-[#F7661B] transition-colors hover:text-[#DE570F]">View All</a>
                    </div>

                    <div class="space-y-4">
                        @foreach ($displayUpcomingBookings as $booking)
                            @php
                                $status = is_array($booking) ? $booking['status'] : $booking->status;
                                $statusClasses = match ($status) {
                                    'confirmed' => 'bg-[#E7F8EC] text-[#2E905B]',
                                    'completed' => 'bg-[#EEF3FF] text-[#4F46E5]',
                                    'cancelled' => 'bg-[#FEECEC] text-[#D14343]',
                                    default => 'bg-[#FFF2DB] text-[#C98B16]',
                                };
                            @endphp

                            <div class="rounded-[18px] border border-[#F6E7D6] bg-[#FFFCF9] p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-[15px] font-semibold text-[#2D241D]">
                                            {{ is_array($booking) ? $booking['event_name'] : $booking->event_name }}
                                        </h3>
                                        <p class="mt-1 text-[12px] text-[#8A7868]">
                                            {{ is_array($booking) ? $booking['caterer_name'] : (optional($booking->catererProfile)->business_name ?? 'PlatePal Caterer') }}
                                        </p>
                                    </div>

                                    <span class="{{ $statusClasses }} rounded-full px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.08em]">
                                        {{ $status }}
                                    </span>
                                </div>

                                <div class="mt-3 flex flex-wrap gap-x-4 gap-y-2 text-[11px] text-[#8A7868]">
                                    <span>{{ is_array($booking) ? $booking['event_date'] : $booking->event_date->format('M j, Y') }}</span>
                                    <span>{{ is_array($booking) ? $booking['guest_count'] : $booking->guest_count }} guests</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="rounded-[24px] border border-[#F1E3D4] bg-white p-5 shadow-[0_10px_26px_rgba(125,95,56,0.08)]">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <h2 class="text-[16px] font-bold text-[#2D241D]">Recent Messages</h2>
                        <a href="{{ route('client.messages') }}" class="text-[12px] font-semibold text-[#F7661B] transition-colors hover:text-[#DE570F]">View All</a>
                    </div>

                    <div class="space-y-4">
                        @foreach ($displayConversations as $conversation)
                            @php
                                $conversationName = data_get($conversation, 'name');
                                $conversationTime = data_get($conversation, 'time');
                                $conversationExcerpt = data_get($conversation, 'excerpt');
                                $conversationUnreadCount = data_get($conversation, 'unread_count', 0);
                                $conversationId = data_get($conversation, 'id');
                                $conversationRoute = $conversationId
                                    ? route('messages.show', $conversationId)
                                    : route('client.messages');
                            @endphp

                            <a href="{{ $conversationRoute }}" class="block rounded-[18px] border border-[#F6E7D6] bg-[#FFFCF9] p-4 transition-colors hover:border-[#F5C19B]">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="truncate text-[15px] font-semibold text-[#2D241D]">{{ $conversationName }}</h3>
                                        <p class="mt-1 text-[12px] leading-5 text-[#8A7868]">
                                            {{ \Illuminate\Support\Str::limit($conversationExcerpt, 64) }}
                                        </p>
                                    </div>

                                    <div class="flex shrink-0 flex-col items-end gap-2">
                                        <span class="text-[11px] text-[#B19B87]">{{ $conversationTime }}</span>
                                        @if ($conversationUnreadCount > 0)
                                            <span class="rounded-full bg-[#F7661B] px-2 py-0.5 text-[10px] font-bold text-white">{{ $conversationUnreadCount }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </article>
            </section>

            <section class="overflow-hidden rounded-[28px] bg-[linear-gradient(180deg,#FF935A_0%,#FF7A3E_100%)] p-5 shadow-[0_18px_36px_rgba(245,96,30,0.22)]">
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-[18px] font-bold text-white">Featured Caterers Near You</h2>
                        <p class="mt-1 text-[12px] text-white/80">Handpicked local favorites for family celebrations and intimate events.</p>
                    </div>

                    <a href="{{ route('browse') }}" class="inline-flex items-center gap-2 text-[12px] font-semibold text-white/90 transition-colors hover:text-white">
                        <span>Browse All</span>
                        <i class="fas fa-arrow-right text-[11px]"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                    @foreach ($displayFeaturedCaterers as $caterer)
                        @php
                            $isFallback = is_array($caterer);
                            $cardName = $isFallback ? $caterer['name'] : $caterer->business_name;
                            $cardLocation = $isFallback ? $caterer['location'] : ($caterer->barangay ?? 'Tagum City');
                            $cardCuisine = $isFallback ? $caterer['cuisine'] : ($caterer->cuisine_type ?? 'Filipino Cuisine');
                            $cardRating = $isFallback ? $caterer['rating'] : number_format($caterer->reviews_avg_rating ?? 4.8, 1);
                            $cardReviews = $isFallback ? $caterer['reviews'] : ($caterer->reviews_count ?? 0);
                            $cardPrice = $isFallback ? $caterer['price'] : (($caterer->price_min ?? 300).'-'.($caterer->price_max ?? 500));
                            $cardLink = $isFallback ? route('browse') : route('caterer.show', $caterer->id);
                            $cardImage = $isFallback ? asset('Assets/'.$caterer['image']) : ($caterer->cover_photo ?: asset('Assets/Pusit.jpg'));

                            if (! $isFallback && $caterer->cover_photo && ! \Illuminate\Support\Str::startsWith($caterer->cover_photo, ['http://', 'https://', '/'])) {
                                $cardImage = asset('Assets/'.$caterer->cover_photo);
                            }
                        @endphp

                        <a href="{{ $cardLink }}" class="group overflow-hidden rounded-[22px] bg-white shadow-[0_14px_28px_rgba(69,38,14,0.16)] transition-transform duration-200 hover:-translate-y-1.5">
                            <div class="relative h-[152px] overflow-hidden">
                                <img src="{{ $cardImage }}" alt="{{ $cardName }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                <span class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-white/95 text-[#F7661B] shadow-sm">
                                    <i class="far fa-heart text-[12px]"></i>
                                </span>
                            </div>

                            <div class="p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="truncate text-[14px] font-semibold text-[#2D241D]">{{ $cardName }}</h3>
                                        <div class="mt-1 flex items-center gap-2 text-[11px] text-[#8A7868]">
                                            <i class="fas fa-location-dot text-[10px]"></i>
                                            <span class="truncate">{{ $cardLocation }}</span>
                                        </div>
                                    </div>

                                    <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-[#FFF3EA] px-2.5 py-1 text-[11px] font-semibold text-[#F7661B]">
                                        <i class="fas fa-star text-[10px] text-[#F9B400]"></i>
                                        {{ $cardRating }}
                                    </span>
                                </div>

                                <div class="mt-3 text-[12px] leading-5 text-[#7E6B5C]">
                                    {{ \Illuminate\Support\Str::limit($cardCuisine, 42) }}
                                </div>

                                <div class="mt-4 flex items-center justify-between text-[11px]">
                                    <span class="text-[#9D8873]">{{ $cardReviews }} reviews</span>
                                    <span class="font-semibold text-[#F7661B]">PHP {{ $cardPrice }}/head</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>

            <section class="rounded-[24px] border border-[#F1E3D4] bg-white px-5 py-5 shadow-[0_10px_26px_rgba(125,95,56,0.08)]">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-[18px] font-bold text-[#2D241D]">Planning an Event?</h2>
                        <p class="mt-2 max-w-[560px] text-[13px] leading-6 text-[#8A7868]">
                            Browse authentic home-cooked meals from verified local caterers and find the right fit for your celebration.
                        </p>
                    </div>

                    <a href="{{ route('browse') }}" class="inline-flex items-center justify-center rounded-[18px] bg-[#F7661B] px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#DE570F]">
                        Browse Caterers
                    </a>
                </div>
            </section>
        </main>
    </div>
</div>

</x-layouts.app>
