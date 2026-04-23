<x-layouts.app :title="'Browse Caterers in Tagum City'">

@php
    $currentUser = auth()->user();
    $overviewRoute = null;
    $accountName = null;

    if ($currentUser?->role === 'client') {
        $overviewRoute = route('client.dashboard');
        $accountName = $currentUser->name;
    } elseif ($currentUser?->role === 'caterer') {
        $overviewRoute = route('caterer.dashboard');
        $accountName = optional($currentUser->catererProfile)->business_name ?: $currentUser->name;
    }

    $accountInitial = $accountName ? strtoupper(substr($accountName, 0, 1)) : null;
    $locationOptions = collect($availableBarangays ?? [])->filter()->values();
    $cuisineOptions = collect($availableCuisines ?? [])->filter()->values();

    if ($cuisineOptions->isEmpty()) {
        $cuisineOptions = collect(['Filipino', 'Native Chicken', 'Seafood', 'Fusion']);
    }

    $selectedCuisineFilters = collect($selectedCuisines ?? request()->input('cuisine', []))
        ->filter()
        ->values();

    $browseTotal = method_exists($caterers, 'total') ? $caterers->total() : $caterers->count();
    $currentPage = method_exists($caterers, 'currentPage') ? $caterers->currentPage() : 1;
    $lastPage = method_exists($caterers, 'lastPage') ? $caterers->lastPage() : 1;
    $pageStart = max(1, $currentPage - 1);
    $pageEnd = min($lastPage, $currentPage + 1);
    $hasActiveFilters = request()->filled('search')
        || request()->filled('location')
        || request()->filled('price')
        || request()->filled('rating')
        || $selectedCuisineFilters->isNotEmpty();
@endphp

<div class="min-h-screen bg-[#FCF7F0] text-[#2D241D]">
    <nav class="sticky top-0 z-[100] border-b border-[#EDE1D6] bg-white/95 backdrop-blur shadow-sm">
        <div class="mx-auto flex max-w-[1320px] flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
            <a href="{{ $overviewRoute ?? route('home') }}" class="flex items-center gap-3 text-[#221F2D] transition-opacity hover:opacity-80">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#FFF1E7] text-[#F7661B]">
                    <i class="fas fa-utensils text-[14px]"></i>
                </span>

                <span class="leading-none">
                    <span class="block text-[11px] font-extrabold tracking-[0.18em] text-[#F7661B]">PLATEPAL</span>
                    <span class="mt-1 block text-[9px] font-medium tracking-[0.08em] text-[#A08D7D]">Tagum City Edition</span>
                </span>
            </a>

            <div class="flex flex-wrap items-center gap-2 text-[13px] font-medium text-[#7D6A59]">
                @if ($overviewRoute)
                    <a href="{{ $overviewRoute }}" class="rounded-full px-3 py-2 transition-colors hover:bg-[#FFF3EA] hover:text-[#F7661B]">Overview</a>
                @else
                    <a href="{{ route('home') }}" class="rounded-full px-3 py-2 transition-colors hover:bg-[#FFF3EA] hover:text-[#F7661B]">Home</a>
                @endif
                <a href="{{ route('browse') }}" class="rounded-full bg-[#FFF3EA] px-3 py-2 text-[#F7661B]">Marketplace</a>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @if ($currentUser)
                    <div class="flex items-center gap-3 rounded-full border border-[#F0E2D4] bg-[#FFF8F2] px-3 py-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#F7661B] text-[11px] font-bold text-white">
                            {{ $accountInitial ?: 'P' }}
                        </span>
                        <span class="text-[12px] font-semibold text-[#6E4B31]">{{ $accountName }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#F7661B] px-5 py-2.5 text-[12px] font-semibold text-white transition-colors hover:bg-[#DE570F]">
                            Sign Out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full border-2 border-[#F54900] px-4 py-2 text-[13px] font-semibold text-[#F54900] transition-all hover:bg-[#F54900] hover:text-white">
                        Sign In
                    </a>
                    <a href="{{ route('caterer.register') }}" class="inline-flex items-center gap-2 rounded-full bg-[#3D3D3D] px-4 py-2 text-[13px] font-semibold text-white transition-colors hover:bg-[#1F2937]">
                        For Caterers
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <div class="py-10 sm:py-12">
        <div class="mx-auto max-w-[1320px] px-4 sm:px-6">
            <h1 class="text-[30px] font-extrabold text-[#3D3D3D] sm:text-[34px]">Browse Caterers in Tagum City</h1>
            <p class="mt-2 max-w-[620px] text-[14px] text-[#8B7E70]">
                Discover trusted local caterers for birthdays, weddings, office events, and intimate family celebrations.
            </p>

            <form method="GET" action="{{ route('browse') }}" class="mt-8">
                <div class="flex flex-col gap-6 xl:flex-row xl:items-start">
                    <aside id="browse-filters" class="w-full rounded-[24px] border border-[#F1E3D5] bg-white p-5 shadow-[0_10px_24px_rgba(125,95,56,0.06)] xl:sticky xl:top-24 xl:w-[270px] xl:self-start">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-[16px] font-bold text-[#2D241D]">Filter Results</h2>
                            @if ($hasActiveFilters)
                                <a href="{{ route('browse') }}" class="text-[12px] font-semibold text-[#F7661B] transition-colors hover:text-[#DE570F]">Clear All</a>
                            @endif
                        </div>

                        <div class="mt-5 space-y-6">
                            <div>
                                <label for="location" class="mb-2 block text-[11px] font-bold uppercase tracking-[0.08em] text-[#8B7E70]">Location</label>
                                <input
                                    id="location"
                                    name="location"
                                    list="browse-locations"
                                    value="{{ request('location') }}"
                                    class="w-full rounded-[16px] border border-[#E9DED3] bg-[#FCF8F3] px-3.5 py-3 text-sm text-[#3D3128] outline-none transition-colors placeholder:text-[#B7A18E] focus:border-[#F7661B] focus:bg-white"
                                    placeholder="Enter barangay..."
                                >
                                @if ($locationOptions->isNotEmpty())
                                    <datalist id="browse-locations">
                                        @foreach ($locationOptions as $location)
                                            <option value="{{ $location }}"></option>
                                        @endforeach
                                    </datalist>
                                @endif
                            </div>

                            <div>
                                <span class="mb-2 block text-[11px] font-bold uppercase tracking-[0.08em] text-[#8B7E70]">Price Range</span>
                                <div class="space-y-2.5">
                                    @foreach ([
                                        '' => 'All Prices',
                                        'budget' => 'Budget (PHP 200-400)',
                                        'mid' => 'Mid-Range (PHP 400-600)',
                                        'premium' => 'Premium (PHP 600+)',
                                    ] as $value => $label)
                                        <label class="flex items-center gap-2.5 text-sm text-[#4A4A4A]">
                                            <input type="radio" name="price" value="{{ $value }}" {{ request('price', '') === $value ? 'checked' : '' }} class="accent-[#F7661B]">
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <span class="mb-2 block text-[11px] font-bold uppercase tracking-[0.08em] text-[#8B7E70]">Cuisine Type</span>
                                <div class="space-y-2.5">
                                    @foreach ($cuisineOptions as $cuisine)
                                        <label class="flex items-center gap-2.5 text-sm text-[#4A4A4A]">
                                            <input type="checkbox" name="cuisine[]" value="{{ $cuisine }}" {{ $selectedCuisineFilters->contains($cuisine) ? 'checked' : '' }} class="accent-[#F7661B]">
                                            <span>{{ $cuisine }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <span class="mb-2 block text-[11px] font-bold uppercase tracking-[0.08em] text-[#8B7E70]">Minimum Rating</span>
                                <div class="space-y-2.5">
                                    @foreach ([
                                        '4.5' => '4.5+ Stars',
                                        '4' => '4+ Stars',
                                        '3.5' => '3.5+ Stars',
                                        '' => 'All Ratings',
                                    ] as $value => $label)
                                        <label class="flex items-center gap-2.5 text-sm text-[#4A4A4A]">
                                            <input type="radio" name="rating" value="{{ $value }}" {{ request('rating', '') === $value ? 'checked' : '' }} class="accent-[#F7661B]">
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-[16px] bg-[#F7661B] px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#DE570F]">
                                Apply Filters
                            </button>
                        </div>
                    </aside>

                    <div class="min-w-0 flex-1">
                        <div class="rounded-[24px] border border-[#F1E3D5] bg-white p-4 shadow-[0_10px_24px_rgba(125,95,56,0.06)] sm:p-5">
                            <div class="flex flex-col gap-3 lg:flex-row">
                                <div class="relative flex-1">
                                    <i class="fas fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[12px] text-[#B7A18E]"></i>
                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="Search caterers or specialties..."
                                        class="w-full rounded-[18px] border border-[#F0E2D4] bg-[#FFF8F2] py-3.5 pl-11 pr-4 text-sm text-[#3D3128] outline-none transition-colors placeholder:text-[#B7A18E] focus:border-[#F7661B] focus:bg-white"
                                    >
                                </div>

                                <a href="#browse-filters" class="inline-flex items-center justify-center gap-2 rounded-[18px] border-2 border-[#F7661B] px-4 py-3.5 text-sm font-semibold text-[#F7661B] transition-colors hover:bg-[#FFF3EA] xl:hidden">
                                    <i class="fas fa-sliders-h text-[12px]"></i>
                                    <span>Filters</span>
                                </a>

                                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-[18px] bg-[#F7661B] px-6 py-3.5 text-sm font-semibold text-white transition-colors hover:bg-[#DE570F]">
                                    <span>Search</span>
                                </button>
                            </div>

                            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-sm text-[#8B7E70]">Showing {{ $caterers->count() }} of {{ $browseTotal }} caterers</p>

                                @if ($hasActiveFilters)
                                    <div class="flex flex-wrap gap-2">
                                        @if (request('location'))
                                            <span class="rounded-full bg-[#FFF3EA] px-3 py-1 text-[11px] font-medium text-[#C96F32]">{{ request('location') }}</span>
                                        @endif
                                        @if (request('price'))
                                            <span class="rounded-full bg-[#FFF3EA] px-3 py-1 text-[11px] font-medium text-[#C96F32]">{{ ucfirst(request('price')) }}</span>
                                        @endif
                                        @if (request('rating'))
                                            <span class="rounded-full bg-[#FFF3EA] px-3 py-1 text-[11px] font-medium text-[#C96F32]">{{ request('rating') }}+ Stars</span>
                                        @endif
                                        @foreach ($selectedCuisineFilters as $cuisine)
                                            <span class="rounded-full bg-[#FFF3EA] px-3 py-1 text-[11px] font-medium text-[#C96F32]">{{ $cuisine }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($caterers->count() > 0)
                            <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($caterers as $caterer)
                                    @php
                                        $cardImage = asset('assets/Pusit.jpg');

                                        if ($caterer->cover_photo) {
                                            if (\Illuminate\Support\Str::startsWith($caterer->cover_photo, ['http://', 'https://', '/'])) {
                                                $cardImage = $caterer->cover_photo;
                                            } else {
                                                $cardImage = asset('storage/'.$caterer->cover_photo);
                                            }
                                        }

                                        $priceMin = $caterer->price_min ?? 300;
                                        $priceMax = $caterer->price_max ?? 500;
                                        $rating = number_format($caterer->reviews_avg_rating ?? 4.8, 1);
                                        $reviewsCount = $caterer->reviews_count ?? 0;
                                    @endphp

                                    <article class="group flex flex-col overflow-hidden rounded-[22px] border border-[#F1E3D5] bg-white shadow-[0_10px_24px_rgba(125,95,56,0.08)] transition-transform duration-200 hover:-translate-y-1 hover:shadow-[0_16px_28px_rgba(125,95,56,0.12)]">
                                        <div class="relative h-[210px] overflow-hidden">
                                            <img src="{{ $cardImage }}" alt="{{ $caterer->business_name }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">

                                            <div class="absolute left-3 top-3 flex flex-wrap gap-2">
                                                @if ($caterer->is_featured)
                                                    <span class="rounded-full bg-[#F54900] px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-[0.08em] text-white">Featured</span>
                                                @endif

                                                <span class="rounded-full bg-white/95 px-2.5 py-0.5 text-[10px] font-semibold text-[#5D4B3F] shadow-sm">
                                                    &#8369;{{ $priceMin }}-{{ $priceMax }}/head
                                                </span>
                                            </div>

                                            <button type="button" class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-white/95 text-[#8B7E70] shadow-sm transition-colors hover:bg-white">
                                                <i class="far fa-heart text-[12px]"></i>
                                            </button>
                                        </div>

                                        <div class="flex flex-1 flex-col p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <h3 class="truncate text-[15px] font-bold text-[#2D241D]">{{ $caterer->business_name }}</h3>
                                                    <div class="mt-1 flex items-center gap-1.5 text-[11px] text-[#8A7868]">
                                                        <i class="fas fa-map-marker-alt text-[10px]"></i>
                                                        <span class="truncate">{{ $caterer->barangay ?? 'Tagum City' }}</span>
                                                    </div>
                                                </div>

                                                <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-[#FFF3EA] px-2.5 py-1 text-[11px] font-semibold text-[#F7661B]">
                                                    <i class="fas fa-star text-[10px] text-[#F9B400]"></i>
                                                    {{ $rating }}
                                                </span>
                                            </div>

                                            <p class="mt-3 flex-1 text-[13px] leading-6 text-[#7E6B5C]">
                                                {{ \Illuminate\Support\Str::limit($caterer->cuisine_type ?? 'Filipino Cuisine', 58) }}
                                            </p>

                                            <div class="mt-4 flex items-center justify-between gap-3 border-t border-[#F1E3D5] pt-3">
                                                <span class="text-[11px] text-[#9D8873]">{{ $reviewsCount }} reviews</span>
                                                <a href="{{ route('caterer.show', $caterer->id) }}" class="inline-flex items-center justify-center rounded-[14px] bg-[#F7661B] px-4 py-2 text-[12px] font-semibold text-white transition-colors hover:bg-[#DE570F]">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-6 rounded-[24px] border border-dashed border-[#E3D4C6] bg-white px-6 py-12 text-center shadow-[0_10px_24px_rgba(125,95,56,0.06)]">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#FFF3EA] text-[#F7661B]">
                                    <i class="fas fa-magnifying-glass text-[18px]"></i>
                                </div>
                                <h2 class="mt-4 text-[20px] font-bold text-[#2D241D]">No caterers matched your search</h2>
                                <p class="mx-auto mt-2 max-w-[460px] text-[14px] leading-6 text-[#8A7868]">
                                    Try adjusting your location, price range, cuisine type, or rating filter to broaden the results.
                                </p>
                                <a href="{{ route('browse') }}" class="mt-6 inline-flex items-center justify-center rounded-[16px] bg-[#F7661B] px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#DE570F]">
                                    Reset Browse Filters
                                </a>
                            </div>
                        @endif

                        @if ($lastPage > 1)
                            <div class="mt-8 flex items-center justify-center gap-2">
                                @if ($caterers->onFirstPage())
                                    <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-[14px] border border-[#E8E0D5] bg-white px-3 text-[12px] text-[#B8A596]">Previous</span>
                                @else
                                    <a href="{{ $caterers->previousPageUrl() }}" class="inline-flex h-10 min-w-10 items-center justify-center rounded-[14px] border border-[#E8E0D5] bg-white px-3 text-[12px] font-medium text-[#8B7E70] transition-colors hover:border-[#F7661B] hover:text-[#F7661B]">Previous</a>
                                @endif

                                @for ($page = $pageStart; $page <= $pageEnd; $page++)
                                    @if ($page === $currentPage)
                                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-[14px] bg-[#F7661B] text-[12px] font-semibold text-white">{{ $page }}</span>
                                    @else
                                        <a href="{{ $caterers->url($page) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-[14px] border border-[#E8E0D5] bg-white text-[12px] font-medium text-[#8B7E70] transition-colors hover:border-[#F7661B] hover:text-[#F7661B]">{{ $page }}</a>
                                    @endif
                                @endfor

                                @if ($caterers->hasMorePages())
                                    <a href="{{ $caterers->nextPageUrl() }}" class="inline-flex h-10 min-w-10 items-center justify-center rounded-[14px] border border-[#E8E0D5] bg-white px-3 text-[12px] font-medium text-[#8B7E70] transition-colors hover:border-[#F7661B] hover:text-[#F7661B]">Next</a>
                                @else
                                    <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-[14px] border border-[#E8E0D5] bg-white px-3 text-[12px] text-[#B8A596]">Next</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</x-layouts.app>
