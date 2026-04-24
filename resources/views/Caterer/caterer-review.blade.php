{{-- Resources/views/Caterer/caterer-review.blade.php --}}

<x-layouts.app :title="'Reviews - PlatePal'">

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
                        <div class="stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                        <strong>{{ number_format($caterer->avg_rating ?? 4.8, 1) }}</strong>
                        <span class="text-muted">({{ $caterer->reviews_count ?? 127 }} reviews)</span>
                    </div>
                    <span class="text-primary fw-bold">₱{{ $caterer->price_min ?? 300 }}–{{ $caterer->price_max ?? 500 }}/head</span>
                </div>
                <p class="profile-hero-desc">{{ $caterer->description ?? "Lola Maria's Kitchen has been serving authentic Filipino cuisine in Tagum City for over 15 years. We specialize in traditional recipes passed down through generations, with our famous Native Chicken as the star of every celebration." }}</p>
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
        <a href="{{ route('caterer.reviews', $caterer->id ?? 1) }}" class="profile-tab active">Reviews ({{ $caterer->reviews_count ?? 127 }})</a>
        <a href="{{ route('caterer.gallery', $caterer->id ?? 1) }}" class="profile-tab">Gallery</a>
        <a href="{{ route('caterer.about', $caterer->id ?? 1) }}" class="profile-tab">About</a>
    </div>

    <div style="background:white;border-radius:0 0 var(--radius-lg) var(--radius-lg);padding:24px;box-shadow:var(--shadow);">
        <div class="d-flex justify-between align-center mb-3">
            <h2 style="font-size:20px;font-weight:700;">Customer Reviews</h2>
            <a href="{{ route('reviews.create', $caterer->id ?? 1) }}" class="btn btn-primary btn-sm">Write a Review</a>
        </div>

        @php
        $reviews = $caterer->reviews ?? [
            ['name'=>'Maria Santos','event'=>'Birthday Party • March 15, 2026','rating'=>5,'text'=>'Absolutely amazing! The native chicken was so tender and flavorful. All our guests kept asking where we got the caterer. Highly recommended!','helpful'=>241,'date'=>'March 15, 2026'],
            ['name'=>'Juan Dela Cruz','event'=>'Wedding Reception • March 10, 2026','rating'=>5,'text'=>'Lola Maria\'s Kitchen made our special day even more memorable. Professional service, delicious food, and great value for money. Thank you!','helpful'=>130,'date'=>'March 10, 2026'],
            ['name'=>'Anna Reyes','event'=>'Corporate Event • March 5, 2026','rating'=>4,'text'=>'Very good food quality and presentation. The team was professional and arrived on time. Would definitely book again!','helpful'=>95,'date'=>'March 5, 2026'],
        ];
        @endphp

        @foreach($reviews as $review)
        @php $isArr = is_array($review); @endphp
        <div class="review-card">
            <div class="review-header">
                <div>
                    <div class="reviewer-name">{{ $isArr ? $review['name'] : $review->user->name }}</div>
                    <div class="reviewer-event text-muted">{{ $isArr ? $review['event'] : ($review->event_type . ' • ' . $review->created_at->format('F j, Y')) }}</div>
                </div>
                <div class="rating">
                    @for($i = 0; $i < ($isArr ? $review['rating'] : $review->rating); $i++)
                    <i class="fas fa-star" style="color:var(--warning);font-size:13px;"></i>
                    @endfor
                </div>
            </div>
            <p class="review-text">{{ $isArr ? $review['text'] : $review->comment }}</p>
            <div class="review-helpful">
                <button>👍 Helpful ({{ $isArr ? $review['helpful'] : $review->helpful_count }})</button>
            </div>
        </div>
         @endforeach
     </div>
 </div>

</x-layouts.app>
