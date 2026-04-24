<x-layouts.app :title="'Pending Caterers - Admin'">

<div class="min-h-screen bg-bg">
    <!-- Header -->
    <div class="bg-white border-b border-border">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-primary">PlatePal</a>
                    <span class="px-3 py-1 bg-danger text-white text-xs font-bold rounded-full">ADMIN</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-primary hover:text-primary-dark font-semibold">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-danger hover:text-red-700 font-semibold">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Page Title -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-dark">Pending Caterer Approvals</h1>
                <p class="text-text-muted mt-1">Review and approve caterer profiles for marketplace visibility</p>
            </div>
            <a href="{{ route('admin.all.caterers') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                <i class="fas fa-users"></i> All Caterers
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if($caterers->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-success text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-dark mb-2">All Caught Up!</h3>
            <p class="text-text-muted mb-6">There are no pending caterer approvals at the moment.</p>
            <a href="{{ route('admin.all.caterers') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                <i class="fas fa-users"></i> View All Caterers
            </a>
        </div>
        @else
        <div class="space-y-6">
            @foreach($caterers as $caterer)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-border hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-start gap-6">
                        <!-- Cover Photo -->
                        <div class="flex-shrink-0">
                            @if($caterer->cover_photo)
                            <img src="{{ Storage::url($caterer->cover_photo) }}" alt="{{ $caterer->business_name }}" class="w-32 h-32 rounded-xl object-cover">
                            @else
                            <div class="w-32 h-32 rounded-xl bg-primary-light flex items-center justify-center">
                                <i class="fas fa-utensils text-primary text-4xl"></i>
                            </div>
                            @endif
                        </div>

                        <!-- Caterer Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-dark mb-1">{{ $caterer->business_name }}</h3>
                                    <div class="flex items-center gap-4 text-sm text-text-muted">
                                        <span><i class="fas fa-user"></i> {{ $caterer->user->name }}</span>
                                        <span><i class="fas fa-envelope"></i> {{ $caterer->user->email }}</span>
                                        <span><i class="fas fa-phone"></i> {{ $caterer->phone }}</span>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-yellow-100 text-warning text-xs font-bold rounded-full">
                                    <i class="fas fa-clock"></i> PENDING
                                </span>
                            </div>

                            <!-- Details Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-text-muted font-semibold mb-1">Location</p>
                                    <p class="text-sm font-semibold text-dark">{{ $caterer->barangay ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-text-muted font-semibold mb-1">Cuisine Type</p>
                                    <p class="text-sm font-semibold text-dark">{{ $caterer->cuisine_type ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-text-muted font-semibold mb-1">Price Range</p>
                                    <p class="text-sm font-semibold text-dark">
                                        @if($caterer->price_min && $caterer->price_max)
                                        ₱{{ number_format($caterer->price_min) }} - ₱{{ number_format($caterer->price_max) }}
                                        @else
                                        N/A
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-text-muted font-semibold mb-1">Guest Capacity</p>
                                    <p class="text-sm font-semibold text-dark">
                                        @if($caterer->min_guests && $caterer->max_guests)
                                        {{ $caterer->min_guests }} - {{ $caterer->max_guests }}
                                        @else
                                        N/A
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($caterer->description)
                            <div class="mb-4">
                                <p class="text-xs text-text-muted font-semibold mb-1">Description</p>
                                <p class="text-sm text-text line-clamp-3">{{ $caterer->description }}</p>
                            </div>
                            @endif

                            <!-- Packages -->
                            @if($caterer->packages->isNotEmpty())
                            <div class="mb-4">
                                <p class="text-xs text-text-muted font-semibold mb-2">Packages ({{ $caterer->packages->count() }})</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($caterer->packages->take(3) as $package)
                                    <span class="px-3 py-1 bg-primary-light text-primary text-xs font-semibold rounded-full">
                                        {{ $package->name }} - ₱{{ number_format($package->price) }}
                                    </span>
                                    @endforeach
                                    @if($caterer->packages->count() > 3)
                                    <span class="px-3 py-1 bg-gray-100 text-text-muted text-xs font-semibold rounded-full">
                                        +{{ $caterer->packages->count() - 3 }} more
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- Metadata -->
                            <div class="flex items-center gap-4 text-xs text-text-muted mb-4">
                                <span><i class="fas fa-calendar"></i> Registered {{ $caterer->created_at->format('M d, Y') }}</span>
                                <span><i class="fas fa-clock"></i> Updated {{ $caterer->updated_at->diffForHumans() }}</span>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-3">
                                <a href="{{ route('caterer.show', $caterer->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-text hover:bg-gray-200 rounded-lg font-semibold transition-colors">
                                    <i class="fas fa-eye"></i> Preview Profile
                                </a>
                                
                                <form action="{{ route('admin.caterers.approve', $caterer->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-success text-white rounded-lg font-semibold hover:bg-green-600 transition-colors">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>

                                <form action="{{ route('admin.caterers.reject', $caterer->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to reject {{ $caterer->business_name }}? They will need to resubmit their profile.')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-danger text-white rounded-lg font-semibold hover:bg-red-600 transition-colors">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($caterers->hasPages())
        <div class="mt-6">
            {{ $caterers->links() }}
        </div>
        @endif
        @endif
    </div>
</div>

</x-layouts.app>
