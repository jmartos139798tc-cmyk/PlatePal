<x-layouts.app :title="'Caterer Dashboard - PlatePal'">

@php
    $user = $user ?? auth()->user();
    $profile = $profile ?? $user?->catererProfile;

    $stats = array_merge([
        'total_bookings' => 0,
        'pending_requests' => 0,
        'messages' => 0,
        'avg_rating' => 0,
        'prev_month_bookings' => 0,
        'curr_month_bookings' => 0,
    ], $stats ?? []);

    $earnings = array_merge([
        'revenue' => 0,
        'satisfaction' => 0,
        'avg_response' => 2.1,
    ], $earnings ?? []);

    $fallbackBookings = collect([
        [
            'event_name' => 'Birthday Party',
            'event_date' => now()->addDays(2),
            'guest_count' => 90,
            'status' => 'confirmed',
            'client_name' => 'Judy Cruz',
        ],
        [
            'event_name' => 'Wedding Reception',
            'event_date' => now()->addDays(11),
            'guest_count' => 120,
            'status' => 'pending',
            'client_name' => 'Mark Lopez',
        ],
        [
            'event_name' => 'Corporate Event',
            'event_date' => now()->addDays(18),
            'guest_count' => 60,
            'status' => 'confirmed',
            'client_name' => 'Ava Garcia',
        ],
    ]);

    $fallbackPackages = collect([
        [
            'name' => 'Premium Fiesta',
            'description' => 'Party package with grilled favorites and complete sides.',
            'bookings_count' => 12,
            'revenue_total' => 72000,
            'price' => 32000,
            'share' => 54,
        ],
        [
            'name' => 'Classic Filipino',
            'description' => 'Crowd-pleasing Filipino staples for family gatherings.',
            'bookings_count' => 8,
            'revenue_total' => 42000,
            'price' => 24000,
            'share' => 31,
        ],
        [
            'name' => 'Budget-Friendly',
            'description' => 'Affordable package for intimate celebrations and office meals.',
            'bookings_count' => 6,
            'revenue_total' => 27000,
            'price' => 17000,
            'share' => 24,
        ],
    ]);

    $displayUpcomingBookings = isset($upcomingBookings) && $upcomingBookings->isNotEmpty()
        ? $upcomingBookings
        : $fallbackBookings;

    $displayTopPackages = isset($topPackages) && $topPackages->isNotEmpty()
        ? $topPackages
        : $fallbackPackages;

    $businessName = $profile->business_name ?? 'Lola Maria\'s Kitchen';
    $businessLocation = $profile->barangay ?? 'Magugpo Poblacion';
    $initials = collect(explode(' ', $businessName))
        ->filter()
        ->map(fn ($segment) => strtoupper(substr($segment, 0, 1)))
        ->take(2)
        ->implode('');

    $prevMonthBookings = (int) $stats['prev_month_bookings'];
    $currMonthBookings = (int) $stats['curr_month_bookings'];
    $growthRate = $prevMonthBookings > 0
        ? (($currMonthBookings - $prevMonthBookings) / $prevMonthBookings) * 100
        : ($currMonthBookings > 0 ? 100 : 0);
    $growthPositive = $currMonthBookings >= $prevMonthBookings;
    $growthClasses = $growthPositive ? 'bg-[#E7F8EC] text-[#2E905B]' : 'bg-[#FEECEC] text-[#D14343]';
    $growthPrefix = $growthPositive ? '+' : '';

    $previousTrendValues = $prevMonthBookings > 0
        ? [
            max(1, (int) ceil($prevMonthBookings * 0.4)),
            max(1, (int) ceil($prevMonthBookings * 0.62)),
            max(1, (int) ceil($prevMonthBookings * 0.78)),
            max(1, $prevMonthBookings),
        ]
        : [2, 3, 2, 2];

    $currentTrendValues = $currMonthBookings > 0
        ? [
            max(1, (int) ceil($currMonthBookings * 0.32)),
            max(1, (int) ceil($currMonthBookings * 0.55)),
            max(1, (int) ceil($currMonthBookings * 0.95)),
            max(1, (int) ceil($currMonthBookings * 0.82)),
        ]
        : [3, 4, 6, 5];

    $chartWidth = 250;
    $chartHeight = 128;
    $chartPaddingX = 18;
    $chartPaddingY = 16;
    $maxTrendValue = max(8, max(array_merge($previousTrendValues, $currentTrendValues)));

    $buildSeries = function (array $values) use ($chartWidth, $chartHeight, $chartPaddingX, $chartPaddingY, $maxTrendValue) {
        $count = max(count($values) - 1, 1);

        return collect($values)->values()->map(function ($value, $index) use ($chartWidth, $chartHeight, $chartPaddingX, $chartPaddingY, $maxTrendValue, $count) {
            $x = $chartPaddingX + (($chartWidth - ($chartPaddingX * 2)) * ($index / $count));
            $normalized = $maxTrendValue > 0 ? ($value / $maxTrendValue) : 0;
            $y = $chartHeight - $chartPaddingY - (($chartHeight - ($chartPaddingY * 2)) * $normalized);

            return [
                'x' => round($x, 2),
                'y' => round($y, 2),
            ];
        })->all();
    };

    $previousTrendSeries = $buildSeries($previousTrendValues);
    $currentTrendSeries = $buildSeries($currentTrendValues);
    $previousTrendPoints = collect($previousTrendSeries)->map(fn ($point) => $point['x'].','.$point['y'])->implode(' ');
    $currentTrendPoints = collect($currentTrendSeries)->map(fn ($point) => $point['x'].','.$point['y'])->implode(' ');

    $kpiCards = [
        [
            'label' => 'Revenue',
            'value' => '&#8369;'.number_format((float) $earnings['revenue'], 0),
            'icon' => 'fa-peso-sign',
            'accent' => 'bg-[#FFF2E8] text-[#F27A35]',
            'pill' => $growthPrefix.number_format(abs($growthRate), 1).'%',
            'pillClass' => $growthClasses,
        ],
        [
            'label' => 'Customer Satisfaction',
            'value' => number_format((float) $earnings['satisfaction'], 0).'%',
            'icon' => 'fa-star',
            'accent' => 'bg-[#EEF4FF] text-[#5479E6]',
            'pill' => '+'.number_format(max(0, (float) $earnings['satisfaction'] / 20), 1).' pts',
            'pillClass' => 'bg-[#E8F7EE] text-[#2E905B]',
        ],
        [
            'label' => 'Avg. Response Time',
            'value' => number_format((float) $earnings['avg_response'], 1).' hrs',
            'icon' => 'fa-clock',
            'accent' => 'bg-[#FFF1F7] text-[#E04E93]',
            'pill' => number_format((float) $earnings['avg_response'] - 0.5, 1).' hrs',
            'pillClass' => 'bg-[#E8F7EE] text-[#2E905B]',
        ],
    ];

    $menuItems = [
        ['label' => 'Overview', 'route' => route('caterer.dashboard'), 'icon' => 'fa-house-chimney', 'badge' => null, 'active' => true],
        ['label' => 'Bookings', 'route' => route('caterer.bookings'), 'icon' => 'fa-calendar-days', 'badge' => $stats['pending_requests'] > 0 ? $stats['pending_requests'] : null, 'active' => false],
        ['label' => 'Menu & Pricing', 'route' => route('caterer.menu'), 'icon' => 'fa-utensils', 'badge' => null, 'active' => false],
        ['label' => 'Messages', 'route' => route('caterer.messages'), 'icon' => 'fa-message', 'badge' => $stats['messages'] > 0 ? $stats['messages'] : null, 'active' => false],
        ['label' => 'Reviews', 'route' => route('caterer.dashboard.reviews'), 'icon' => 'fa-star', 'badge' => null, 'active' => false],
        ['label' => 'Earnings', 'route' => route('caterer.earnings'), 'icon' => 'fa-chart-line', 'badge' => null, 'active' => false],
    ];
    $overviewRoute = route('caterer.dashboard');
    $marketplaceRoute = route('browse');
@endphp

<div class="min-h-screen bg-[#FDF7F0] text-[#2B2118]">
    <header class="border-b border-[#EFE2D6] bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-[1320px] flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ $overviewRoute }}" class="flex items-center gap-3 text-[#221F2D] transition-opacity hover:opacity-80">
                    <span class="leading-none">
                        <span class="block text-[16px] font-extrabold tracking-[0.18em] text-[#F7661B]">PLATEPAL</span>
                    </span>
                </a>
            </div>

            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                <nav class="flex items-center gap-2 text-[13px] font-medium text-[#7D6A59]">
                    <a href="{{ $overviewRoute }}" class="rounded-full bg-[#FFF3EA] px-3 py-2 text-[#F7661B]">Overview</a>
                    <a href="{{ $marketplaceRoute }}" class="rounded-full px-3 py-2 transition-colors hover:bg-[#FFF3EA] hover:text-[#F7661B]">Marketplace</a>
                    <a href="#" class="rounded-full px-3 py-2 transition-colors hover:bg-[#FFF3EA] hover:text-[#F7661B]">Help</a>
                </nav>

                <div class="flex items-center gap-3 rounded-full border border-[#F0E2D4] bg-[#FFF8F2] px-3 py-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#F7661B] text-[11px] font-bold text-white">
                        {{ $initials ?: 'PP' }}
                    </span>
                    <span class="leading-none">
                        <span class="block text-[12px] font-semibold text-[#3C2F24]">{{ $businessName }}</span>
                        <span class="mt-1 block text-[10px] text-[#A08D7D]">{{ $businessLocation }}</span>
                    </span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center rounded-full px-4 py-2.5 text-[12px] font-semibold text-[#8A7868] transition-colors hover:bg-[#FFF3EA] hover:text-[#F7661B]">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="mx-auto grid max-w-[1320px] gap-5 px-4 py-5 sm:px-6 lg:grid-cols-[220px_minmax(0,1fr)]">
        <aside class="overflow-hidden rounded-[28px] border border-[#F0E2D6] bg-[linear-gradient(180deg,#FFF6ED_0%,#FFF0E0_100%)] shadow-[0_12px_30px_rgba(131,95,62,0.08)] lg:sticky lg:top-6 lg:self-start">
            <div class="flex flex-col lg:min-h-[calc(100vh-145px)]">
                <div class="px-6 pt-6">
                    <div class="text-[11px] font-semibold uppercase tracking-[0.24em] text-[#D07C48]">Caterer Menu</div>
                </div>

                <nav class="mt-4 space-y-2 px-4 pb-5">
                    @foreach ($menuItems as $item)
                        <a
                            href="{{ $item['route'] }}"
                            class="{{ $item['active'] ? 'bg-[#FF7A3E] text-white shadow-[0_14px_28px_rgba(245,96,30,0.24)]' : 'text-[#8B6748] hover:bg-white/70 hover:text-[#F7661B]' }} flex items-center justify-between rounded-[18px] px-4 py-3 text-[14px] font-medium transition-all"
                        >
                            <span class="flex items-center gap-3">
                                <i class="fas {{ $item['icon'] }} text-[13px]"></i>
                                <span>{{ $item['label'] }}</span>
                            </span>

                            @if ($item['badge'])
                                <span class="{{ $item['active'] ? 'bg-white/20 text-white' : 'bg-[#F7661B] text-white' }} rounded-full px-2 py-0.5 text-[10px] font-bold">
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                <div class="border-t border-[#EFDCCB] px-4 py-5 lg:mt-auto">
                    <a href="{{ route('caterer.profile.settings') }}" class="flex items-center gap-3 rounded-[18px] px-4 py-3 text-[14px] font-medium text-[#8B6748] transition-colors hover:bg-white/70 hover:text-[#F7661B]">
                        <i class="fas fa-user-gear text-[13px]"></i>
                        <span>Profile Settings</span>
                    </a>
                </div>
            </div>
        </aside>

        <main class="min-w-0 space-y-5">
            <section class="grid grid-cols-2 gap-4 xl:grid-cols-4">
                <article class="rounded-[22px] border border-[#F1E3D4] bg-white px-5 py-4 shadow-[0_10px_24px_rgba(125,95,56,0.08)]">
                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-[16px] bg-[#FFF1E6] text-[#F7661B]">
                        <i class="fas fa-calendar-check text-[16px]"></i>
                    </div>
                    <div class="text-[38px] font-extrabold leading-none text-[#2A2018]">{{ $stats['total_bookings'] }}</div>
                    <p class="mt-2 text-[12px] font-medium text-[#8A7868]">Total Bookings</p>
                </article>

                <article class="rounded-[22px] border border-[#F1E3D4] bg-white px-5 py-4 shadow-[0_10px_24px_rgba(125,95,56,0.08)]">
                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-[16px] bg-[#FFF3EC] text-[#F27A35]">
                        <i class="fas fa-clock text-[16px]"></i>
                    </div>
                    <div class="text-[38px] font-extrabold leading-none text-[#2A2018]">{{ $stats['pending_requests'] }}</div>
                    <p class="mt-2 text-[12px] font-medium text-[#8A7868]">Pending Requests</p>
                </article>

                <article class="rounded-[22px] border border-[#F1E3D4] bg-white px-5 py-4 shadow-[0_10px_24px_rgba(125,95,56,0.08)]">
                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-[16px] bg-[#F3F4F6] text-[#5A6472]">
                        <i class="fas fa-message text-[16px]"></i>
                    </div>
                    <div class="text-[38px] font-extrabold leading-none text-[#2A2018]">{{ $stats['messages'] }}</div>
                    <p class="mt-2 text-[12px] font-medium text-[#8A7868]">Messages</p>
                </article>

                <article class="rounded-[22px] border border-[#F1E3D4] bg-white px-5 py-4 shadow-[0_10px_24px_rgba(125,95,56,0.08)]">
                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-[16px] bg-[#FFF8E8] text-[#D39A09]">
                        <i class="fas fa-star text-[16px]"></i>
                    </div>
                    <div class="text-[38px] font-extrabold leading-none text-[#2A2018]">{{ number_format((float) $stats['avg_rating'], 1) }}</div>
                    <p class="mt-2 text-[12px] font-medium text-[#8A7868]">Average Rating</p>
                </article>
            </section>

            <section class="grid grid-cols-1 gap-5 xl:grid-cols-[1.05fr,0.95fr]">
                <article class="rounded-[24px] border border-[#F1E3D4] bg-white p-5 shadow-[0_10px_24px_rgba(125,95,56,0.08)]">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-[16px] font-bold text-[#2D241D]">Upcoming Bookings</h2>
                            <p class="mt-1 text-[11px] text-[#9D8873]">Confirmed and pending events on your calendar.</p>
                        </div>
                        <a href="{{ route('caterer.bookings') }}" class="text-[12px] font-semibold text-[#F7661B] transition-colors hover:text-[#DE570F]">View All</a>
                    </div>

                    <div class="space-y-4">
                        @foreach ($displayUpcomingBookings as $booking)
                            @php
                                $isArrayBooking = is_array($booking);
                                $status = $isArrayBooking ? $booking['status'] : $booking->status;
                                $eventName = $isArrayBooking ? $booking['event_name'] : $booking->event_name;
                                $eventDate = $isArrayBooking ? $booking['event_date'] : $booking->event_date;
                                $guestCount = $isArrayBooking ? $booking['guest_count'] : $booking->guest_count;
                                $clientName = $isArrayBooking ? $booking['client_name'] : (optional($booking->client)->name ?? 'PlatePal Client');
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
                                        <h3 class="text-[15px] font-semibold text-[#2D241D]">{{ $eventName }}</h3>
                                        <p class="mt-1 text-[12px] text-[#8A7868]">{{ $clientName }}</p>
                                    </div>

                                    <span class="{{ $statusClasses }} rounded-full px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.08em]">
                                        {{ $status }}
                                    </span>
                                </div>

                                <div class="mt-3 flex flex-wrap gap-x-4 gap-y-2 text-[11px] text-[#8A7868]">
                                    <span><i class="fas fa-calendar mr-1.5 text-[10px] text-[#D07C48]"></i>{{ $eventDate instanceof \Carbon\CarbonInterface ? $eventDate->format('M j, Y') : $eventDate }}</span>
                                    <span><i class="fas fa-user-group mr-1.5 text-[10px] text-[#D07C48]"></i>{{ $guestCount }} guests</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="rounded-[24px] border border-[#F1E3D4] bg-white p-5 shadow-[0_10px_24px_rgba(125,95,56,0.08)]">
                    <div class="mb-4 flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-[16px] font-bold text-[#2D241D]">Business Trends</h2>
                            <p class="mt-1 text-[11px] text-[#9D8873]">Booking momentum compared with last month.</p>
                        </div>
                        <span class="{{ $growthClasses }} inline-flex rounded-full px-3 py-1 text-[11px] font-semibold">
                            {{ $growthPrefix }}{{ number_format(abs($growthRate), 1) }}% growth
                        </span>
                    </div>

                    <div class="rounded-[20px] border border-[#F6E7D6] bg-[#FFFCF9] px-4 py-4">
                        <div class="mb-3 flex items-center justify-end gap-4 text-[10px] font-medium text-[#9D8873]">
                            <span class="inline-flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-[#D5C4B7]"></span>
                                Previous Month
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-[#F7661B]"></span>
                                Current Month
                            </span>
                        </div>

                        <div class="grid grid-cols-[26px_minmax(0,1fr)] gap-3">
                            <div class="flex h-[128px] flex-col justify-between text-[10px] text-[#B29B87]">
                                <span>{{ $maxTrendValue }}</span>
                                <span>{{ (int) round($maxTrendValue / 2) }}</span>
                                <span>0</span>
                            </div>

                            <div class="min-w-0">
                                <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" class="h-[128px] w-full">
                                    <line x1="{{ $chartPaddingX }}" y1="{{ $chartPaddingY }}" x2="{{ $chartWidth - $chartPaddingX }}" y2="{{ $chartPaddingY }}" stroke="#F0E2D4" stroke-width="1" />
                                    <line x1="{{ $chartPaddingX }}" y1="{{ $chartHeight / 2 }}" x2="{{ $chartWidth - $chartPaddingX }}" y2="{{ $chartHeight / 2 }}" stroke="#F0E2D4" stroke-width="1" />
                                    <line x1="{{ $chartPaddingX }}" y1="{{ $chartHeight - $chartPaddingY }}" x2="{{ $chartWidth - $chartPaddingX }}" y2="{{ $chartHeight - $chartPaddingY }}" stroke="#F0E2D4" stroke-width="1" />

                                    <polyline fill="none" stroke="#D2C0B4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" points="{{ $previousTrendPoints }}" />
                                    <polyline fill="none" stroke="#F7661B" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" points="{{ $currentTrendPoints }}" />

                                    @foreach ($previousTrendSeries as $point)
                                        <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="3.5" fill="#FFFFFF" stroke="#D2C0B4" stroke-width="2" />
                                    @endforeach

                                    @foreach ($currentTrendSeries as $point)
                                        <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="4" fill="#FFFFFF" stroke="#F7661B" stroke-width="2.5" />
                                    @endforeach
                                </svg>

                                <div class="mt-2 grid grid-cols-4 text-center text-[10px] text-[#B29B87]">
                                    <span>Week 1</span>
                                    <span>Week 2</span>
                                    <span>Week 3</span>
                                    <span>Week 4</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-[18px] border border-[#F6E7D6] bg-[#FFFCF9] px-4 py-4">
                            <span class="text-[11px] font-medium text-[#B19B87]">Previous Month</span>
                            <div class="mt-2 text-[34px] font-extrabold leading-none text-[#2A2018]">{{ $prevMonthBookings }}</div>
                            <p class="mt-2 text-[11px] text-[#8A7868]">Total bookings</p>
                        </div>

                        <div class="rounded-[18px] bg-[#FFF2E8] px-4 py-4">
                            <span class="text-[11px] font-medium text-[#D07C48]">Current Month</span>
                            <div class="mt-2 text-[34px] font-extrabold leading-none text-[#F7661B]">{{ $currMonthBookings }}</div>
                            <p class="mt-2 text-[11px] text-[#B66F3E]">Total bookings</p>
                        </div>
                    </div>
                </article>
            </section>

            <section class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($kpiCards as $card)
                    <article class="rounded-[22px] border border-[#F1E3D4] bg-white px-5 py-4 shadow-[0_10px_24px_rgba(125,95,56,0.08)]">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-[14px] {{ $card['accent'] }}">
                                <i class="fas {{ $card['icon'] }} text-[14px]"></i>
                            </span>
                            <span class="{{ $card['pillClass'] }} rounded-full px-2.5 py-1 text-[10px] font-semibold">{{ $card['pill'] }}</span>
                        </div>

                        <p class="text-[12px] font-medium text-[#8A7868]">{{ $card['label'] }}</p>
                        <div class="mt-2 text-[34px] font-extrabold leading-none text-[#2A2018]">{!! $card['value'] !!}</div>
                    </article>
                @endforeach
            </section>

            <section class="overflow-hidden rounded-[28px] bg-[linear-gradient(180deg,#FF9966_0%,#FF7A3E_100%)] p-5 shadow-[0_18px_36px_rgba(245,96,30,0.22)]">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-[18px] font-bold text-white">Top Performing Packages</h2>
                        <p class="mt-1 text-[12px] text-white/85">Your strongest offers based on bookings and revenue activity.</p>
                    </div>

                    <span class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-[11px] font-semibold text-white">
                        {{ $stats['total_bookings'] }} Total Bookings
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                    @foreach ($displayTopPackages as $package)
                        @php
                            $isArrayPackage = is_array($package);
                            $packageName = $isArrayPackage ? $package['name'] : $package->name;
                            $packageDescription = $isArrayPackage ? $package['description'] : ($package->description ?: 'Catering package');
                            $packageBookings = (int) ($isArrayPackage ? $package['bookings_count'] : ($package->bookings_count ?? 0));
                            $packageRevenue = (float) ($isArrayPackage ? $package['revenue_total'] : ($package->revenue_total ?? 0));
                            $packagePrice = (float) ($isArrayPackage ? $package['price'] : ($package->price ?? 0));
                            $packageShare = (int) ($isArrayPackage
                                ? $package['share']
                                : ($stats['total_bookings'] > 0 ? round(($packageBookings / max($stats['total_bookings'], 1)) * 100) : 0));

                            if ($packageRevenue <= 0 && $packageBookings > 0 && $packagePrice > 0) {
                                $packageRevenue = $packageBookings * $packagePrice;
                            }
                        @endphp

                        <article class="rounded-[22px] border border-white/35 bg-white px-4 py-4 shadow-[0_14px_28px_rgba(69,38,14,0.16)]">
                            <div class="mb-4 flex items-start justify-between gap-3">
                                <span class="inline-flex items-center gap-2 rounded-full bg-[#FFF3EA] px-2.5 py-1 text-[10px] font-semibold text-[#F7661B]">
                                    <i class="fas fa-fire text-[10px]"></i>
                                    Top {{ $loop->iteration }}
                                </span>

                                <span class="inline-flex items-center gap-1 rounded-full bg-[#F7F8FA] px-2.5 py-1 text-[10px] font-semibold text-[#6D7380]">
                                    {{ $packageBookings }} bookings
                                </span>
                            </div>

                            <h3 class="text-[15px] font-bold text-[#2D241D]">{{ $packageName }}</h3>
                            <p class="mt-1 text-[12px] leading-5 text-[#8A7868]">{{ \Illuminate\Support\Str::limit($packageDescription, 68) }}</p>

                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div class="rounded-[16px] bg-[#FFF8F2] px-3 py-3">
                                    <span class="text-[10px] font-medium uppercase tracking-[0.08em] text-[#B19B87]">Revenue</span>
                                    <div class="mt-2 text-[18px] font-extrabold text-[#2A2018]">&#8369;{{ number_format($packageRevenue, 0) }}</div>
                                </div>
                                <div class="rounded-[16px] bg-[#FFF8F2] px-3 py-3">
                                    <span class="text-[10px] font-medium uppercase tracking-[0.08em] text-[#B19B87]">From Price</span>
                                    <div class="mt-2 text-[18px] font-extrabold text-[#2A2018]">&#8369;{{ number_format($packagePrice, 0) }}</div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="mb-2 flex items-center justify-between text-[11px] font-medium text-[#8A7868]">
                                    <span>Booking Share</span>
                                    <span>{{ $packageShare }}%</span>
                                </div>
                                <div class="h-2 rounded-full bg-[#F6E7D6]">
                                    <div class="h-2 rounded-full bg-[#F7661B]" style="width: {{ max(8, min(100, $packageShare)) }}%"></div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </main>
    </div>
</div>

</x-layouts.app>
