{{-- Resources/views/Caterer/caterer-gallery.blade.php --}}

<x-layouts.app :title="'Gallery - PlatePal'">

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
        <a href="{{ route('caterer.gallery', $caterer->id ?? 1) }}" class="profile-tab active">Gallery</a>
        <a href="{{ route('caterer.about', $caterer->id ?? 1) }}" class="profile-tab">About</a>
    </div>

    <div style="background:white;border-radius:0 0 var(--radius-lg) var(--radius-lg);padding:24px;box-shadow:var(--shadow);">
        <h2 style="font-size:20px;font-weight:700;margin-bottom:20px;">Photo Gallery</h2>
        <div class="photo-gallery">
            @php
            $photos = $caterer->gallery ?? [
                'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=400&h=300&fit=crop',
                'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=400&h=300&fit=crop',
            ];
            @endphp
            @foreach($photos as $photo)
            <img src="{{ is_string($photo) ? $photo : $photo->url }}" alt="Gallery photo" onclick="openLightbox(this.src)">
            @endforeach
        </div>
     </div>
 </div>

 {{-- Inline script for lightbox --}}
 <script>
 function openLightbox(src) {
     document.getElementById('lightboxImg').src = src;
     document.getElementById('lightbox').style.display = 'flex';
 }
 </script>

</x-layouts.app>
