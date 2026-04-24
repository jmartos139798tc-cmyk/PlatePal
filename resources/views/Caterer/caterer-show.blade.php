{{-- Resources/views/Caterer/caterer-show.blade.php --}}

<x-layouts.app :title="($caterer->business_name ?? 'Lola Maria\'s Kitchen') . ' - PlatePal'">

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

    <!-- Profile Hero -->
    <div class="profile-hero">
        <div class="profile-hero-image">
            <img src="{{ $caterer->cover_photo ?? 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=900&h=280&fit=crop' }}" alt="Cover">
        </div>
        <div class="profile-hero-body">
            <div class="profile-hero-info">
                <h1 class="profile-hero-name">{{ $caterer->business_name ?? "Lola Maria's Kitchen" }}</h1>
                <div class="profile-hero-location">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $caterer->barangay ?? 'Magugpo Poblacion' }}, Tagum City
                </div>
                <div class="profile-hero-meta">
                    <div class="rating">
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                        <strong>{{ number_format($caterer->avg_rating ?? 4.8, 1) }}</strong>
                        <span class="text-muted">({{ $caterer->reviews_count ?? 127 }} reviews)</span>
                    </div>
                    <span class="text-primary fw-bold">₱{{ $caterer->price_min ?? 300 }}–{{ $caterer->price_max ?? 500 }}/head</span>
                </div>
                <p class="profile-hero-desc">
                    {{ $caterer->description ?? "Lola Maria's Kitchen has been serving authentic Filipino cuisine in Tagum City for over 15 years. We specialize in traditional recipes passed down through generations, with our famous Native Chicken as the star of every celebration." }}
                </p>
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

    <!-- Tabs -->
    <div class="profile-tabs">
        <a href="{{ route('caterer.show', $caterer->id ?? 1) }}" class="profile-tab {{ request()->routeIs('caterer.show') ? 'active' : '' }}">Menu & Packages</a>
        <a href="{{ route('caterer.reviews', $caterer->id ?? 1) }}" class="profile-tab {{ request()->routeIs('caterer.reviews') ? 'active' : '' }}">Reviews ({{ $caterer->reviews_count ?? 127 }})</a>
        <a href="{{ route('caterer.gallery', $caterer->id ?? 1) }}" class="profile-tab {{ request()->routeIs('caterer.gallery') ? 'active' : '' }}">Gallery</a>
        <a href="{{ route('caterer.about', $caterer->id ?? 1) }}" class="profile-tab {{ request()->routeIs('caterer.about') ? 'active' : '' }}">About</a>
    </div>

    <!-- Packages -->
    <div style="background:white;border-radius:0 0 var(--radius-lg) var(--radius-lg);padding:24px;box-shadow:var(--shadow);">
        <h2 style="font-size:20px;font-weight:700;margin-bottom:6px;">Our Packages</h2>
        <p class="text-muted fs-sm mb-3">Choose the perfect package for your upcoming celebration</p>

        @php
        $packages = $caterer->packages ?? [
            ['name'=>'Classic Filipino Package','desc'=>'Traditional Filipino menu for intimate celebrations','price'=>'350','price_note'=>'Min 30 guests','items'=>['Lechon Manok or Chicken Inasal','3 Main Dish Choices','Crispy Pata Lechon Kawali','Fresh Lumpia','Buko Salad','Steamed Rice','Fruit Tea'],'serving'=>'20–100 guests'],
            ['name'=>'Premium Fiesta Package','desc'=>'Our flagship package for big celebrations and events','price'=>'500','price_note'=>'Min 50 guests','items'=>['Whole Roasted Native Chicken','Grilled Sea Bata Party','Beef Caldereta','Kare-Kare (beef/oxtail)','Special Pancit Palabok','Fresh Fruit Platter','Steamed Rice & Drinks'],'serving'=>'50–300 guests'],
            ['name'=>'Budget-Friendly Package','desc'=>'Affordable without compromising quality','price'=>'300','price_note'=>'Min 25 guests','items'=>['Chicken Inasal','Sinigang na Baboy','1 Company Shanghai','Pancit Bihon','Fresh Vegetables','Steamed Rice','Juice'],'serving'=>'20–80 guests'],
        ];
        @endphp

        @foreach($packages as $pkg)
        @php $isArr = is_array($pkg); @endphp
        <div class="package-card">
            <div class="package-header">
                <div>
                    <div class="package-name">{{ $isArr ? $pkg['name'] : $pkg->name }}</div>
                    <div class="package-desc text-muted fs-sm">{{ $isArr ? $pkg['desc'] : $pkg->description }}</div>
                </div>
                <div style="text-align:right;">
                    <div class="package-price">₱{{ $isArr ? $pkg['price'] : $pkg->price }}/head</div>
                    <div class="package-price-sub text-muted">{{ $isArr ? $pkg['price_note'] : $pkg->price_note }}</div>
                </div>
            </div>
            <div class="package-includes">
                <h4>Package Inclusions:</h4>
                <ul>
                    @foreach(($isArr ? $pkg['items'] : $pkg->items) as $item)
                    <li><i class="fas fa-check-circle"></i> {{ $item }}</li>
                    @endforeach
                </ul>
                <p class="text-muted fs-xs mt-1"><i class="fas fa-users"></i> Serving: {{ $isArr ? $pkg['serving'] : $pkg->serving }}</p>
            </div>
            <div class="package-actions">
                <button class="btn btn-primary" style="flex:1;">Select Package</button>
                <button class="btn btn-outline">Customize</button>
            </div>
        </div>
         @endforeach
     </div>
 </div>

</x-layouts.app>
