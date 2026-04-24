{{-- Resources/views/Caterer/profile-settings.blade.php --}}

<x-layouts.app :title="'Profile Settings - PlatePal'">

<div class="min-h-screen bg-bg py-8">
    <div class="max-w-4xl mx-auto px-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-dark">Profile Settings</h1>
            <p class="text-text-muted mt-1">Complete your profile to appear in the marketplace</p>
        </div>

        <!-- Profile Status Alert -->
        @if($profile->profile_status === 'incomplete')
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-6 flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-xl"></i>
            <div>
                <h3 class="font-bold mb-1">Profile Incomplete</h3>
                <p class="text-sm">Please complete all required fields below to submit your profile for approval.</p>
            </div>
        </div>
        @elseif($profile->profile_status === 'pending')
        <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-6 flex items-start gap-3">
            <i class="fas fa-clock text-xl"></i>
            <div>
                <h3 class="font-bold mb-1">Pending Approval</h3>
                <p class="text-sm">Your profile is under review. You'll appear in the marketplace once approved by our team.</p>
            </div>
        </div>
        @elseif($profile->profile_status === 'approved')
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-start gap-3">
            <i class="fas fa-check-circle text-xl"></i>
            <div>
                <h3 class="font-bold mb-1">Profile Approved</h3>
                <p class="text-sm">Your profile is live in the marketplace! Customers can now find and book your services.</p>
            </div>
        </div>
        @elseif($profile->profile_status === 'rejected')
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-start gap-3">
            <i class="fas fa-times-circle text-xl"></i>
            <div>
                <h3 class="font-bold mb-1">Profile Rejected</h3>
                <p class="text-sm">Your profile was not approved. Please update your information and resubmit.</p>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Profile Form -->
        <form action="{{ route('caterer.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm overflow-hidden">
            @csrf

            <div class="p-6 space-y-6">
                <!-- Business Information -->
                <div>
                    <h2 class="text-lg font-bold text-dark mb-4 flex items-center gap-2">
                        <i class="fas fa-store text-primary"></i> Business Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-dark mb-2">Business Name <span class="text-danger">*</span></label>
                            <input type="text" name="business_name" value="{{ old('business_name', $profile->business_name) }}" required class="w-full py-2.5 px-3.5 border-[1.5px] border-border rounded-lg text-sm outline-none transition-colors focus:border-primary bg-white">
                            @error('business_name')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-dark mb-2">Barangay <span class="text-danger">*</span></label>
                            <input type="text" name="barangay" value="{{ old('barangay', $profile->barangay) }}" required class="w-full py-2.5 px-3.5 border-[1.5px] border-border rounded-lg text-sm outline-none transition-colors focus:border-primary bg-white">
                            @error('barangay')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-dark mb-2">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" required class="w-full py-2.5 px-3.5 border-[1.5px] border-border rounded-lg text-sm outline-none transition-colors focus:border-primary bg-white">
                            @error('phone')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-dark mb-2">Cuisine Type <span class="text-danger">*</span></label>
                            <input type="text" name="cuisine_type" value="{{ old('cuisine_type', $profile->cuisine_type) }}" placeholder="e.g., Filipino, Seafood, Fusion" class="w-full py-2.5 px-3.5 border-[1.5px] border-border rounded-lg text-sm outline-none transition-colors focus:border-primary bg-white">
                            @error('cuisine_type')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-dark mb-2">Business Description <span class="text-danger">*</span></label>
                        <textarea name="description" rows="4" placeholder="Tell customers about your catering service, specialties, and what makes you unique..." class="w-full py-2.5 px-3.5 border-[1.5px] border-border rounded-lg text-sm outline-none transition-colors focus:border-primary bg-white">{{ old('description', $profile->description) }}</textarea>
                        @error('description')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing & Capacity -->
                <div>
                    <h2 class="text-lg font-bold text-dark mb-4 flex items-center gap-2">
                        <i class="fas fa-dollar-sign text-primary"></i> Pricing & Capacity
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-dark mb-2">Minimum Price per Head (₱) <span class="text-danger">*</span></label>
                            <input type="number" name="price_min" value="{{ old('price_min', $profile->price_min) }}" min="0" class="w-full py-2.5 px-3.5 border-[1.5px] border-border rounded-lg text-sm outline-none transition-colors focus:border-primary bg-white">
                            @error('price_min')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-dark mb-2">Maximum Price per Head (₱) <span class="text-danger">*</span></label>
                            <input type="number" name="price_max" value="{{ old('price_max', $profile->price_max) }}" min="0" class="w-full py-2.5 px-3.5 border-[1.5px] border-border rounded-lg text-sm outline-none transition-colors focus:border-primary bg-white">
                            @error('price_max')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-dark mb-2">Minimum Guests</label>
                            <input type="number" name="min_guests" value="{{ old('min_guests', $profile->min_guests) }}" min="1" class="w-full py-2.5 px-3.5 border-[1.5px] border-border rounded-lg text-sm outline-none transition-colors focus:border-primary bg-white">
                            @error('min_guests')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-dark mb-2">Maximum Guests</label>
                            <input type="number" name="max_guests" value="{{ old('max_guests', $profile->max_guests) }}" min="1" class="w-full py-2.5 px-3.5 border-[1.5px] border-border rounded-lg text-sm outline-none transition-colors focus:border-primary bg-white">
                            @error('max_guests')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Cover Photo -->
                <div>
                    <h2 class="text-lg font-bold text-dark mb-4 flex items-center gap-2">
                        <i class="fas fa-image text-primary"></i> Cover Photo
                    </h2>

                    @if($profile->cover_photo)
                    <div class="mb-4">
                        <img src="{{ Storage::url($profile->cover_photo) }}" alt="Current cover photo" class="w-full max-w-md h-48 object-cover rounded-lg">
                        <p class="text-xs text-text-muted mt-2">Current cover photo</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-dark mb-2">Upload New Cover Photo</label>
                        <input type="file" name="cover_photo" accept="image/*" class="w-full py-2.5 px-3.5 border-[1.5px] border-border rounded-lg text-sm outline-none transition-colors focus:border-primary bg-white">
                        <p class="text-xs text-text-muted mt-1">Recommended: 1200x600px, Max 2MB</p>
                        @error('cover_photo')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-border flex items-center justify-between">
                <a href="{{ route('caterer.dashboard') }}" class="text-text-muted hover:text-dark font-semibold text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                    <i class="fas fa-save"></i>
                    @if($profile->profile_status === 'incomplete')
                        Submit for Approval
                    @else
                        Update Profile
                    @endif
                </button>
            </div>
        </form>

        <!-- Required Fields Notice -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-bold text-blue-900 mb-2">Required for Marketplace Approval:</h3>
            <ul class="text-sm text-blue-800 space-y-1">
                <li class="flex items-center gap-2">
                    <i class="fas fa-check-circle {{ $profile->description ? 'text-success' : 'text-gray-400' }}"></i>
                    Business Description
                </li>
                <li class="flex items-center gap-2">
                    <i class="fas fa-check-circle {{ $profile->cuisine_type ? 'text-success' : 'text-gray-400' }}"></i>
                    Cuisine Type
                </li>
                <li class="flex items-center gap-2">
                    <i class="fas fa-check-circle {{ $profile->price_min && $profile->price_max ? 'text-success' : 'text-gray-400' }}"></i>
                    Price Range
                </li>
            </ul>
        </div>
    </div>
</div>

</x-layouts.app>
