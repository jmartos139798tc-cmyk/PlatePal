<x-layouts.app :title="'All Caterers - Admin'">

<div class="min-h-screen bg-bg py-8">
    <div class="max-w-7xl mx-auto px-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-dark">All Caterers</h1>
                <p class="text-text-muted mt-1">Manage all caterer profiles and their marketplace visibility</p>
            </div>
            <a href="{{ route('admin.pending.caterers') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                <i class="fas fa-clock"></i> Pending Approvals
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Filter Tabs -->
        <div class="bg-white rounded-xl shadow-sm mb-6 overflow-hidden">
            <div class="flex border-b border-border">
                <a href="?status=all" class="px-6 py-3 text-sm font-semibold {{ request('status', 'all') === 'all' ? 'text-primary border-b-2 border-primary' : 'text-text-muted hover:text-text' }} transition-colors">
                    All ({{ $caterers->total() }})
                </a>
                <a href="?status=approved" class="px-6 py-3 text-sm font-semibold {{ request('status') === 'approved' ? 'text-primary border-b-2 border-primary' : 'text-text-muted hover:text-text' }} transition-colors">
                    <i class="fas fa-check-circle text-success"></i> Approved
                </a>
                <a href="?status=pending" class="px-6 py-3 text-sm font-semibold {{ request('status') === 'pending' ? 'text-primary border-b-2 border-primary' : 'text-text-muted hover:text-text' }} transition-colors">
                    <i class="fas fa-clock text-warning"></i> Pending
                </a>
                <a href="?status=incomplete" class="px-6 py-3 text-sm font-semibold {{ request('status') === 'incomplete' ? 'text-primary border-b-2 border-primary' : 'text-text-muted hover:text-text' }} transition-colors">
                    <i class="fas fa-exclamation-circle text-text-muted"></i> Incomplete
                </a>
                <a href="?status=rejected" class="px-6 py-3 text-sm font-semibold {{ request('status') === 'rejected' ? 'text-primary border-b-2 border-primary' : 'text-text-muted hover:text-text' }} transition-colors">
                    <i class="fas fa-times-circle text-danger"></i> Rejected
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($caterers->isEmpty())
            <div class="p-12 text-center">
                <i class="fas fa-users text-6xl text-text-muted mb-4"></i>
                <h3 class="text-xl font-bold text-dark mb-2">No Caterers Found</h3>
                <p class="text-text-muted">No caterers match the selected filter.</p>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-border">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-text-muted uppercase">Business</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-text-muted uppercase">Owner</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-text-muted uppercase">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-text-muted uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-text-muted uppercase">Rating</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-text-muted uppercase">Featured</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-text-muted uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($caterers as $caterer)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($caterer->cover_photo)
                                    <img src="{{ Storage::url($caterer->cover_photo) }}" alt="{{ $caterer->business_name }}" class="w-12 h-12 rounded-lg object-cover">
                                    @else
                                    <div class="w-12 h-12 rounded-lg bg-primary-light flex items-center justify-center">
                                        <i class="fas fa-utensils text-primary"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <div class="font-semibold text-dark">{{ $caterer->business_name }}</div>
                                        <div class="text-xs text-text-muted">{{ $caterer->cuisine_type ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-text">{{ $caterer->user->name }}</div>
                                <div class="text-xs text-text-muted">{{ $caterer->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-text">{{ $caterer->barangay ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @if($caterer->profile_status === 'approved')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 text-success text-xs font-semibold rounded-full">
                                    <i class="fas fa-check-circle"></i> Approved
                                </span>
                                @elseif($caterer->profile_status === 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-yellow-100 text-warning text-xs font-semibold rounded-full">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                                @elseif($caterer->profile_status === 'rejected')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-100 text-danger text-xs font-semibold rounded-full">
                                    <i class="fas fa-times-circle"></i> Rejected
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-text-muted text-xs font-semibold rounded-full">
                                    <i class="fas fa-exclamation-circle"></i> Incomplete
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($caterer->reviews_count > 0)
                                <div class="flex items-center gap-1 text-sm">
                                    <i class="fas fa-star text-warning text-xs"></i>
                                    <span class="font-semibold">{{ number_format($caterer->reviews_avg_rating, 1) }}</span>
                                    <span class="text-text-muted">({{ $caterer->reviews_count }})</span>
                                </div>
                                @else
                                <span class="text-sm text-text-muted">No reviews</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.caterers.toggle.featured', $caterer->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-2xl {{ $caterer->is_featured ? 'text-warning' : 'text-gray-300' }} hover:scale-110 transition-transform">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('caterer.show', $caterer->id) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-primary hover:bg-primary-light rounded-lg transition-colors">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    
                                    @if($caterer->profile_status === 'pending')
                                    <form action="{{ route('admin.caterers.approve', $caterer->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-white bg-success hover:bg-green-600 rounded-lg transition-colors">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.caterers.reject', $caterer->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Reject this caterer?')" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-white bg-danger hover:bg-red-600 rounded-lg transition-colors">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                    @elseif($caterer->profile_status === 'rejected')
                                    <form action="{{ route('admin.caterers.approve', $caterer->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-white bg-success hover:bg-green-600 rounded-lg transition-colors">
                                            <i class="fas fa-redo"></i> Re-approve
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($caterers->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $caterers->links() }}
            </div>
            @endif
            @endif
        </div>
    </div>
</div>

</x-layouts.app>
