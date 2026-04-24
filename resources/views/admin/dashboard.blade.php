<x-layouts.app :title="'Admin Dashboard'">

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
                    <span class="text-sm text-text-muted">{{ Auth::user()->name }}</span>
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
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-dark">Admin Dashboard</h1>
            <p class="text-text-muted mt-1">Manage caterers, approvals, and marketplace settings</p>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Caterers -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-text-muted text-sm font-semibold mb-1">Total Caterers</p>
                        <p class="text-3xl font-extrabold text-dark">{{ $stats['total_caterers'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-light rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-primary text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Approvals -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-warning">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-text-muted text-sm font-semibold mb-1">Pending Approvals</p>
                        <p class="text-3xl font-extrabold text-dark">{{ $stats['pending_caterers'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-warning text-xl"></i>
                    </div>
                </div>
                @if($stats['pending_caterers'] > 0)
                <a href="{{ route('admin.pending.caterers') }}" class="text-xs text-warning hover:text-yellow-700 font-semibold mt-2 inline-block">
                    Review now →
                </a>
                @endif
            </div>

            <!-- Approved Caterers -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-success">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-text-muted text-sm font-semibold mb-1">Approved</p>
                        <p class="text-3xl font-extrabold text-dark">{{ $stats['approved_caterers'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-success text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Bookings -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-text-muted text-sm font-semibold mb-1">Total Bookings</p>
                        <p class="text-3xl font-extrabold text-dark">{{ $stats['total_bookings'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('admin.pending.caterers') }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow border border-border">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-warning bg-opacity-10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-clock text-warning text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-dark text-lg">Pending Approvals</h3>
                        <p class="text-text-muted text-sm">Review caterer applications</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.all.caterers') }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow border border-border">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-utensils text-primary text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-dark text-lg">All Caterers</h3>
                        <p class="text-text-muted text-sm">Manage all caterer profiles</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.all.caterers', ['status' => 'approved']) }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow border border-border">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-success bg-opacity-10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-warning text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-dark text-lg">Featured Caterers</h3>
                        <p class="text-text-muted text-sm">Manage featured listings</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Caterers -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-border">
                    <h2 class="text-lg font-bold text-dark">Recent Caterers</h2>
                </div>
                <div class="divide-y divide-border">
                    @forelse($recentCaterers as $caterer)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                @if($caterer->cover_photo)
                                <img src="{{ Storage::url($caterer->cover_photo) }}" alt="{{ $caterer->business_name }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                <div class="w-10 h-10 rounded-lg bg-primary-light flex items-center justify-center">
                                    <i class="fas fa-utensils text-primary text-sm"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="font-semibold text-dark text-sm">{{ $caterer->business_name }}</div>
                                    <div class="text-xs text-text-muted">{{ $caterer->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @if($caterer->profile_status === 'approved')
                            <span class="px-2 py-1 bg-green-100 text-success text-xs font-semibold rounded-full">
                                <i class="fas fa-check"></i> Approved
                            </span>
                            @elseif($caterer->profile_status === 'pending')
                            <span class="px-2 py-1 bg-yellow-100 text-warning text-xs font-semibold rounded-full">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                            @else
                            <span class="px-2 py-1 bg-gray-100 text-text-muted text-xs font-semibold rounded-full">
                                {{ ucfirst($caterer->profile_status) }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-text-muted">
                        <i class="fas fa-inbox text-3xl mb-2"></i>
                        <p class="text-sm">No recent caterers</p>
                    </div>
                    @endforelse
                </div>
                <div class="px-6 py-3 bg-gray-50 border-t border-border">
                    <a href="{{ route('admin.all.caterers') }}" class="text-sm text-primary hover:text-primary-dark font-semibold">
                        View all caterers →
                    </a>
                </div>
            </div>

            <!-- Platform Stats -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-border">
                    <h2 class="text-lg font-bold text-dark">Platform Statistics</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-border">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-blue-500"></i>
                            </div>
                            <span class="text-sm font-semibold text-text">Total Users</span>
                        </div>
                        <span class="text-lg font-bold text-dark">{{ $stats['total_users'] }}</span>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-border">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-tie text-purple-500"></i>
                            </div>
                            <span class="text-sm font-semibold text-text">Clients</span>
                        </div>
                        <span class="text-lg font-bold text-dark">{{ $stats['total_clients'] }}</span>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-border">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-check text-success"></i>
                            </div>
                            <span class="text-sm font-semibold text-text">Completed Bookings</span>
                        </div>
                        <span class="text-lg font-bold text-dark">{{ $stats['completed_bookings'] }}</span>
                    </div>

                    <div class="flex items-center justify-between py-3 border-b border-border">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <span class="text-sm font-semibold text-text">Total Reviews</span>
                        </div>
                        <span class="text-lg font-bold text-dark">{{ $stats['total_reviews'] }}</span>
                    </div>

                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-star-half-alt text-warning"></i>
                            </div>
                            <span class="text-sm font-semibold text-text">Average Rating</span>
                        </div>
                        <span class="text-lg font-bold text-dark">{{ number_format($stats['avg_rating'], 1) }} / 5.0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layouts.app>
