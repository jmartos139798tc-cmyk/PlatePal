{{-- Resources/views/LandingPage/home.blade.php --}}

<x-layouts.app :title="'Find Authentic Home Cooking'">

@php
    $displayCaterers = collect();
    $heroPancit = asset('assets/Pancit.png');
    $heroGuisado = asset('assets/Guisado.png');

    if (isset($caterers) && $caterers instanceof \Illuminate\Support\Collection && $caterers->isNotEmpty()) {
        $displayCaterers = $caterers;
    } elseif (isset($featuredCaterers) && $featuredCaterers instanceof \Illuminate\Support\Collection && $featuredCaterers->isNotEmpty()) {
        $displayCaterers = $featuredCaterers;
    }

    $fallbackCaterers = [
        ['name' => "Lola Maria's Kitchen", 'loc' => 'Magugpo Poblacion', 'cuisine' => 'Authentic Tagum Native Chicken', 'rating' => '4.8', 'reviews' => '127', 'price' => '300-500', 'img' => 'Pusit.jpg'],
        ['name' => "Kusina ni Aling Nena", 'loc' => 'Apokon', 'cuisine' => 'Mindanao Fusion Cuisine', 'rating' => '4.9', 'reviews' => '96', 'price' => '400-600', 'img' => 'Kusina ni Aling Nena.jpg'],
        ['name' => 'TasteBuds Catering', 'loc' => 'Visayan Village', 'cuisine' => 'Party Packages & Event Buffet', 'rating' => '4.7', 'reviews' => '155', 'price' => '350-550', 'img' => 'TasteBuds Catering.jpg'],
        ['name' => 'Bahay Kubo Kitchen', 'loc' => '8 Barangays', 'cuisine' => 'Decent Farm-to-Table Dishes', 'rating' => '4.8', 'reviews' => '88', 'price' => '250-450', 'img' => 'Bahay Kubo Kitchen.jpg'],
        ['name' => 'Sarap Pinoy Express', 'loc' => '9 New Balandiran', 'cuisine' => 'Mindanao Party Trays', 'rating' => '4.5', 'reviews' => '61', 'price' => '200-380', 'img' => 'Sarap Pinoy Express.jpg'],
        ['name' => 'DeliciaHaus Catering', 'loc' => '9 Pagsabangan', 'cuisine' => 'Premium Seafood Flavors', 'rating' => '4.8', 'reviews' => '73', 'price' => '400-700', 'img' => 'DeliciaHaus Catering.jpg'],
    ];
@endphp

<!-- Navbar -->
<nav class="sticky top-0 z-[100] border-b border-[#E8E0D5] bg-white/95 backdrop-blur shadow-sm">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 sm:py-5 lg:h-16 lg:flex-row lg:items-center lg:justify-between lg:py-0">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center justify-center gap-2 font-extrabold text-xl text-[#F54900] sm:justify-start">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#F54900] shadow-sm">
                <i class="fas fa-utensils text-sm text-white"></i>
            </div>
            <span>PLATEPAL</span>
        </a>

        <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-center sm:gap-x-6 lg:flex-nowrap lg:justify-start lg:gap-8">
            <a href="{{ auth()->check() ? route('browse') : route('login') }}" class="text-sm font-medium text-[#8B7E70] transition-colors hover:text-[#F54900]">Browse Caterers</a>
            <a href="#" class="text-sm font-medium text-[#8B7E70] transition-colors hover:text-[#F54900]">How It Works</a>
            <a href="#" class="text-sm font-medium text-[#8B7E70] transition-colors hover:text-[#F54900]">For Caterers</a>
        </div>

        <div class="flex w-full items-center justify-center gap-3 sm:w-auto lg:justify-end">
            <a href="{{ route('login') }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border-2 border-[#F54900] bg-transparent px-4 py-2 text-[13px] font-semibold text-[#F54900] transition-all hover:bg-[#F54900] hover:text-white sm:flex-none sm:py-1.5">
                Sign In
            </a>
            <a href="{{ auth()->check() ? route('register') : route('login') }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-[#F54900] px-4 py-2 text-[13px] font-semibold text-white transition-all hover:-translate-y-0.5 hover:bg-[#B08D4B] sm:flex-none sm:py-1.5">
                Get Started
            </a>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="relative overflow-hidden bg-[linear-gradient(180deg,#E7BF8E_0%,#EFD0A5_52%,#F5E2C7_100%)] py-12 text-center sm:py-16 lg:py-20">
    <div class="pointer-events-none absolute inset-x-0 top-0 h-28 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.42),transparent_72%)]"></div>
    <div class="pointer-events-none absolute left-1/2 top-[-150px] h-[330px] w-[330px] -translate-x-1/2 rounded-full bg-white/20 blur-3xl"></div>
    <img
        src="{{ $heroPancit }}"
        alt=""
        aria-hidden="true"
        class="pointer-events-none absolute left-[-82px] top-[210px] z-[1] w-[180px] drop-shadow-[0_16px_28px_rgba(69,38,14,0.28)] sm:left-[-96px] sm:top-[235px] sm:w-[230px] lg:left-[-74px] lg:top-[182px] lg:w-[320px]"
    >
    <img
        src="{{ $heroGuisado }}"
        alt=""
        aria-hidden="true"
        class="pointer-events-none absolute right-[-88px] top-[24px] z-[1] w-[175px] drop-shadow-[0_18px_30px_rgba(69,38,14,0.24)] sm:right-[-92px] sm:top-[18px] sm:w-[230px] lg:right-[-70px] lg:top-0 lg:w-[330px]"
    >

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[740px]">
            <div class="mb-5 inline-flex max-w-full items-center justify-center rounded-full border border-white/50 bg-[#FFF4EA]/85 px-3.5 py-1.5 text-center text-[10px] font-semibold uppercase tracking-[0.16em] text-[#F54900] shadow-[0_8px_18px_rgba(168,114,54,0.08)] sm:mb-6 sm:px-4 sm:text-xs">
                Tagum City's Home Kitchen Marketplace
            </div>

            <h1 class="mx-auto mb-4 max-w-[620px] text-[34px] font-extrabold leading-[1.08] text-[#20160F] sm:text-[44px] sm:leading-tight lg:text-[54px]">
                Find <span class="text-[#F54900]">authentic</span> home cooking for your next event
            </h1>

            <p class="mx-auto mb-8 max-w-[520px] text-sm leading-7 text-[#7C6552] sm:mb-10 sm:text-[16px]">
                Connect with talented home-based caterers right in your barangay for real food, kept simple and unforgettable.
            </p>
        </div>

        <div class="mx-auto mb-10 w-full max-w-[860px] rounded-[22px] border border-white/60 bg-white/95 p-3 shadow-[0_20px_55px_rgba(61,61,61,0.14)] sm:mb-12 sm:p-4">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                <div class="flex min-w-0 flex-1 items-center rounded-2xl bg-[#FFF7EF] px-4 py-3 text-left">
                    <i class="fas fa-magnifying-glass mr-3 text-[12px] text-[#C4A487]"></i>
                    <input type="text" placeholder="Search native chicken, lechon, kare-kare..." class="w-full border-none bg-transparent text-sm text-[#4A4A4A] placeholder:text-[#AF9A86] outline-none">
                </div>

                <div class="flex min-w-0 items-center rounded-2xl border border-[#F0E2D4] bg-white px-4 py-3 lg:w-[190px]">
                    <select class="w-full appearance-none bg-transparent pr-5 text-sm text-[#6E6257] outline-none">
                        <option>All Category</option>
                        <option>Chicken Dishes</option>
                        <option>Party Trays</option>
                        <option>Seafood</option>
                    </select>
                    <i class="fas fa-chevron-down text-[11px] text-[#9F8A76]"></i>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row lg:ml-auto lg:items-center">
                    <div class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#EEF6E8] px-4 py-3 text-sm font-semibold text-[#668152]">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#7BB36B]/20">
                            <i class="fas fa-location-dot text-[10px] text-[#7BB36B]"></i>
                        </span>
                        <span>Tagum City</span>
                    </div>

                    <a href="{{ auth()->check() ? route('browse') : route('login') }}" class="inline-flex items-center justify-center rounded-2xl bg-[#F54900] px-5 py-3 text-sm font-semibold text-white transition-all hover:-translate-y-0.5 hover:bg-[#D95A11]">
                        Find Caterers
                    </a>
                </div>
            </div>
        </div>

        <div class="mx-auto grid max-w-[510px] grid-cols-3 gap-4 sm:gap-8 lg:gap-12">
            <div class="text-center">
                <div class="text-[24px] font-extrabold text-[#F54900] sm:text-[28px]">48+</div>
                <div class="text-[12px] text-[#7C6552] sm:text-[13px]">Home Caterers</div>
            </div>
            <div class="text-center">
                <div class="text-[24px] font-extrabold text-[#F54900] sm:text-[28px]">12</div>
                <div class="text-[12px] text-[#7C6552] sm:text-[13px]">Barangays Covered</div>
            </div>
            <div class="text-center">
                <div class="text-[24px] font-extrabold text-[#F54900] sm:text-[28px]">320+</div>
                <div class="text-[12px] text-[#7C6552] sm:text-[13px]">Events Served</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Caterers -->
<section class="py-14 sm:py-16" style="background: linear-gradient(#F1DEC5 45%, #FFFFFF 100%)">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold text-[#3D3D3D]">Featured Local Caterers</h2>
            <a href="{{ auth()->check() ? route('browse') : route('login') }}" class="text-sm font-semibold text-[#F54900] hover:underline">View All &rarr;</a>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">
            @if($displayCaterers->isEmpty())
                @foreach($fallbackCaterers as $c)
                    <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative h-[220px] overflow-hidden sm:h-[240px] lg:h-[200px]">
                            <img src="{{ asset('assets/'.$c['img']) }}" alt="{{ $c['name'] }}" class="h-full w-full object-cover">
                            <span class="absolute left-3 top-3 rounded-full bg-[#F54900] px-2.5 py-0.5 text-[11px] font-semibold text-white">Featured</span>
                            <button class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm transition-colors hover:bg-gray-50" type="button">
                                <i class="far fa-heart text-sm text-[#8B7E70]"></i>
                            </button>
                            <span class="absolute bottom-3 right-3 rounded-full bg-black/70 px-2.5 py-0.5 text-xs font-semibold text-white">&#8369;{{ $c['price'] }}/head</span>
                        </div>

                        <div class="flex flex-1 flex-col p-4">
                            <div class="mb-2 flex flex-wrap items-start justify-between gap-2">
                                <h3 class="min-w-0 flex-1 text-base font-bold leading-tight text-[#3D3D3D]">{{ $c['name'] }}</h3>
                                <div class="flex shrink-0 items-center gap-1 text-[13px] font-semibold">
                                    <i class="fas fa-star text-xs text-[#FFC107]"></i>
                                    <span>{{ $c['rating'] }}</span>
                                    <span class="font-normal text-[#8B7E70]">({{ $c['reviews'] }})</span>
                                </div>
                            </div>

                            <div class="mb-2 flex items-center gap-1 text-xs text-[#8B7E70]">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $c['loc'] }}</span>
                            </div>

                            <p class="mt-auto text-[13px] leading-relaxed text-[#8B7E70]">{{ $c['cuisine'] }}</p>
                        </div>
                    </article>
                @endforeach
            @else
                @foreach($displayCaterers as $caterer)
                    <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative h-[220px] overflow-hidden sm:h-[240px] lg:h-[200px]">
                            <img src="{{ $caterer->cover_photo ?? asset('assets/Pusit.jpg') }}" alt="{{ $caterer->business_name }}" class="h-full w-full object-cover">
                            @if($caterer->is_featured ?? false)
                                <span class="absolute left-3 top-3 rounded-full bg-[#F54900] px-2.5 py-0.5 text-[11px] font-semibold text-white">Featured</span>
                            @endif
                            <button class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm transition-colors hover:bg-gray-50" type="button">
                                <i class="far fa-heart text-sm text-[#8B7E70]"></i>
                            </button>
                            <span class="absolute bottom-3 right-3 rounded-full bg-black/70 px-2.5 py-0.5 text-xs font-semibold text-white">&#8369;{{ $caterer->price_min ?? '300' }}-{{ $caterer->price_max ?? '500' }}/head</span>
                        </div>

                        <div class="flex flex-1 flex-col p-4">
                            <div class="mb-2 flex flex-wrap items-start justify-between gap-2">
                                <h3 class="min-w-0 flex-1 text-base font-bold leading-tight text-[#3D3D3D]">{{ $caterer->business_name }}</h3>
                                <div class="flex shrink-0 items-center gap-1 text-[13px] font-semibold">
                                    <i class="fas fa-star text-xs text-[#FFC107]"></i>
                                    <span>{{ number_format($caterer->avg_rating ?? 4.8, 1) }}</span>
                                    <span class="font-normal text-[#8B7E70]">({{ $caterer->reviews_count ?? 127 }})</span>
                                </div>
                            </div>

                            <div class="mb-2 flex items-center gap-1 text-xs text-[#8B7E70]">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $caterer->barangay ?? 'Tagum City' }}</span>
                            </div>

                            <p class="mt-auto text-[13px] leading-relaxed text-[#8B7E70]">{{ $caterer->cuisine_type ?? 'Filipino Cuisine' }}</p>
                        </div>
                    </article>
                @endforeach
            @endif
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-[#3D3D3D] py-12 text-white/70">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10 grid grid-cols-1 gap-8 md:grid-cols-[1.5fr_1fr_1fr] md:gap-12">
            <div>
                <div class="mb-3 flex items-center gap-2 text-xl font-extrabold text-white">
                    <i class="fas fa-utensils text-[#F54900]"></i> PLATEPAL
                </div>
                <p class="text-[13px] leading-relaxed">Connecting Tagum City's best home-based caterers with the community since 2024.</p>
            </div>

            <div>
                <h4 class="mb-4 text-sm font-bold text-white">For Clients</h4>
                <ul class="list-none space-y-2.5">
                    <li><a href="{{ auth()->check() ? route('browse') : route('login') }}" class="text-[13px] text-white/60 transition-colors hover:text-[#F54900]">Browse Caterers</a></li>
                    <li><a href="#" class="text-[13px] text-white/60 transition-colors hover:text-[#F54900]">How It Works</a></li>
                    <li><a href="{{ route('login') }}" class="text-[13px] text-white/60 transition-colors hover:text-[#F54900]">My Events</a></li>
                    <li><a href="#" class="text-[13px] text-white/60 transition-colors hover:text-[#F54900]">Client Reviews</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 text-sm font-bold text-white">For Caterers</h4>
                <ul class="list-none space-y-2.5">
                    <li><a href="{{ auth()->check() ? route('caterer.register') : route('login') }}" class="text-[13px] text-white/60 transition-colors hover:text-[#F54900]">Join as Caterer</a></li>
                    <li><a href="#" class="text-[13px] text-white/60 transition-colors hover:text-[#F54900]">Pricing</a></li>
                    <li><a href="#" class="text-[13px] text-white/60 transition-colors hover:text-[#F54900]">Success Stories</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 pt-6 text-center text-[13px]">
            <p>&copy; 2026 PlatePal Tagum City. All rights reserved.</p>
        </div>
    </div>
</footer>

</x-layouts.app>
