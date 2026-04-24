{{-- Resources/views/Caterer/caterer-about.blade.php --}}

<x-layouts.app :title="'About - PlatePal'">

<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar-brand">
        <div class="logo-icon"><i class="fas fa-utensils"></i></div>
        <div><div>PLATEPAL</div></div>
    </a>
    <div class="navbar-nav">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('browse') }}">Browse Caterers</a>
    </div>
    <div class="navbar-actions">
        <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Sign In</a>
    </div>
</nav>

<div class="section">
    <a href="{{ route('browse') }}" class="text-muted fs-sm d-flex align-center gap-1 mb-2">
        <i class="fas fa-chevron-left"></i> Back to Browse
    </a>

    <div class="profile-hero">
        <div class="profile-hero-image">
            <img src="{{ $caterer->cover_photo ?? 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=900&h=280&fit=crop' }}" alt="">
        </div>
        <div class="profile-hero-body">
            <div class="profile-hero-info">
                <h1 class="profile-hero-name">{{ $caterer->business_name ?? "Lola Maria's Kitchen" }}</h1>
                <div class="profile-hero-location"><i class="fas fa-map-marker-alt"></i> {{ $caterer->barangay ?? 'Magugpo Poblacion' }}, Tagum City</div>
                <div class="profile-hero-meta">
                    <div class="rating">
                        <div class="stars"><i class="fas fa-star"></i></div>
                        <strong>{{ number_format($caterer->avg_rating ?? 4.8, 1) }}</strong>
                        <span class="text-muted">({{ $caterer->reviews_count ?? 127 }} reviews)</span>
                    </div>
                    <span class="text-primary fw-bold">₱{{ $caterer->price_min ?? 300 }}–{{ $caterer->price_max ?? 500 }}/head</span>
                </div>
                <p class="profile-hero-desc">{{ $caterer->description ?? "Lola Maria's Kitchen has been serving authentic Filipino cuisine in Tagum City for over 15 years." }}</p>
                <div class="profile-hero-contact">
                    <span><i class="fas fa-phone"></i> {{ $caterer->phone ?? '+63 912 345 6789' }}</span>
                    <span><i class="fas fa-envelope"></i> {{ $caterer->email ?? 'lolamaria@kitchen.com' }}</span>
                    <span><i class="fas fa-users"></i> Serving: {{ $caterer->min_guests ?? 20 }}–{{ $caterer->max_guests ?? 500 }} guests</span>
                </div>
            </div>
            <div class="profile-hero-actions">
                <a href="{{ route('booking.create', $caterer->id ?? 1) }}" class="btn btn-primary">Book Now</a>
                <a href="{{ route('messages.show', $caterer->user_id ?? 1) }}" class="btn btn-outline">Send Message</a>
            </div>
        </div>
    </div>

    <div class="profile-tabs">
        <a href="{{ route('caterer.show', $caterer->id ?? 1) }}" class="profile-tab">Menu & Packages</a>
        <a href="{{ route('caterer.reviews', $caterer->id ?? 1) }}" class="profile-tab">Reviews ({{ $caterer->reviews_count ?? 127 }})</a>
        <a href="{{ route('caterer.gallery', $caterer->id ?? 1) }}" class="profile-tab">Gallery</a>
        <a href="{{ route('caterer.about', $caterer->id ?? 1) }}" class="profile-tab active">About</a>
    </div>

    <div style="background:white;border-radius:0 0 var(--radius-lg) var(--radius-lg);padding:28px;box-shadow:var(--shadow);">
        <div class="about-section">
            <h3>About Us</h3>
            <div class="about-section">
                <h4 style="font-size:15px;font-weight:700;margin-bottom:10px;">Our Story</h4>
                <p class="text-muted" style="font-size:14px;line-height:1.8;">
                    {{ $caterer->story ?? "Lola Maria's Kitchen has been serving authentic Filipino cuisine in Tagum City for over 15 years. We specialize in traditional recipes passed down through generations, with our famous Native Chicken as the star of every celebration. We pride ourselves on using only the freshest local ingredients and maintaining the authentic flavors that have made us a household name in Tagum City." }}
                </p>
            </div>
            <div class="about-section">
                <h4 style="font-size:15px;font-weight:700;margin-bottom:10px;">What Makes Us Special</h4>
                <ul class="about-checklist">
                    @php
                    $highlights = $caterer->highlights ?? [
                        'Over 15 years of catering experience in Tagum City',
                        'Traditional family recipes passed down through generations',
                        'Farm-to-table approach with locally sourced ingredients',
                        'Professional and courteous service staff',
                        'Customizable menus to suit your preferences and budget',
                    ];
                    @endphp
                    @foreach($highlights as $item)
                    <li><i class="fas fa-check"></i> {{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="about-section">
            <h3>Services Offered</h3>
            <div class="services-grid">
                @php
                $services = $caterer->services ?? ['Weddings & Receptions','Birthday Parties','Corporate Events','Baptisms & Communions','Graduations','Family Gatherings'];
                @endphp
                @foreach($services as $service)
                <div class="service-item">{{ $service }}</div>
                @endforeach
            </div>
             </div>
         </div>
     </div>

</x-layouts.app>
