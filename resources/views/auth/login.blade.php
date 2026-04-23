{{-- Resources/views/auth/login.blade.php --}}
<x-layouts.app :title="'Login - PlatePal'">

<div class="min-h-screen bg-white">
    <div class="flex min-h-screen w-full flex-col lg:flex-row">
        <section class="flex w-full bg-white lg:min-h-screen lg:w-1/2 lg:justify-end">
            <div class="w-full px-6 py-8 sm:px-10 sm:py-10 lg:max-w-[590px] lg:px-14 lg:py-14 xl:px-20">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 text-[#221F2D] transition-opacity hover:opacity-80">
                    <i class="fas fa-utensils text-[17px] text-[#F54900]"></i>
                    <span class="text-[16px] font-bold tracking-[0.08em] text-[#1E1E1E]">PLATEPAL</span>
                </a>

                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[14px] font-medium text-[#8B8B8B] transition-colors hover:text-[#F54900]">
                    <i class="fas fa-arrow-left text-[10px]"></i>
                    <span>Back to Home</span>
                </a>
            </div>

            <div class="mt-14 max-w-[360px] sm:mt-16 lg:mt-24">
                <h1 class="text-[30px] font-extrabold leading-tight text-[#1F2937] sm:text-[33px]">
                    Welcome Back, Client
                </h1>
                <p class="mt-2 text-[15px] leading-7 text-[#6B7280]">
                    Sign in to browse caterers and book your next event
                </p>

                @if (session('error'))
                    <div class="mt-5 rounded-xl border border-[#FFD4BF] bg-[#FFF4EE] px-4 py-3 text-sm text-[#B54A18]">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-5 rounded-xl border border-[#FFD4BF] bg-[#FFF4EE] px-4 py-3 text-sm text-[#B54A18]">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="mt-7 space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-[13px] font-semibold text-[#2B2B2B]">Email Address</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            placeholder="you@example.com"
                            class="w-full rounded-[7px] border border-[#E4E4E4] bg-[#F3F4F6] px-4 py-[10px] text-[13px] text-[#1F2937] outline-none transition-all placeholder:text-[#A0A0A0] focus:border-[#F54900] focus:bg-white focus:ring-2 focus:ring-[#F54900]/10"
                            required
                            autofocus
                        >
                        @error('email')
                            <p class="mt-2 text-xs font-medium text-[#D14300]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-[13px] font-semibold text-[#2B2B2B]">Password</label>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                class="w-full rounded-[7px] border border-[#E4E4E4] bg-[#F3F4F6] px-4 py-[10px] pr-14 text-[13px] text-[#1F2937] outline-none transition-all placeholder:text-[#A0A0A0] focus:border-[#F54900] focus:bg-white focus:ring-2 focus:ring-[#F54900]/10"
                                required
                            >
                            <button
                                type="button"
                                id="toggle-password"
                                class="absolute right-3 top-1/2 inline-flex -translate-y-1/2 items-center gap-1 text-[12px] font-medium text-[#8B8B8B] transition-colors hover:text-[#F54900] focus:outline-none"
                                aria-controls="password"
                                aria-label="Show password"
                                aria-pressed="false"
                            >
                                <i class="fas fa-eye text-[12px]" aria-hidden="true"></i>
                                <span>Show</span>
                            </button>
                        </div>
                        <div class="mt-2 text-right">
                            <a href="{{ route('password.request') }}" class="text-[12px] font-medium text-[#F54900] transition-colors hover:text-[#D14300]">
                                Forgot password?
                            </a>
                        </div>
                        @error('password')
                            <p class="mt-2 text-xs font-medium text-[#D14300]">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="mt-1 inline-flex w-full items-center justify-center rounded-[7px] bg-[#F54900] px-5 py-[10px] text-[14px] font-semibold text-white transition-colors hover:bg-[#D14300] focus:outline-none focus:ring-2 focus:ring-[#F54900] focus:ring-offset-2"
                    >
                        Sign In
                    </button>
                </form>

                <div class="mt-5 flex items-center gap-4">
                    <span class="h-px flex-1 bg-[#E5E7EB]"></span>
                    <span class="text-[12px] text-[#9CA3AF]">or</span>
                    <span class="h-px flex-1 bg-[#E5E7EB]"></span>
                </div>

                <div class="mt-6 space-y-2 text-center text-[15px] leading-6 text-[#6B7280]">
                    <p>
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-semibold text-[#F54900] transition-colors hover:text-[#D14300]">
                            Sign up
                        </a>
                    </p>
                    <p>
                        Are you a caterer?
                        <a href="{{ route('caterer.login') }}" class="font-semibold text-[#F54900] transition-colors hover:text-[#D14300]">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>
            </div>
        </section>

        <aside class="flex w-full bg-[linear-gradient(180deg,#FF5A08_0%,#FF7A22_100%)] text-white lg:min-h-screen lg:w-1/2 lg:items-center">
            <div class="w-full px-8 py-10 sm:px-10 lg:max-w-[590px] lg:px-16 lg:py-14 xl:px-20">
                <h2 class="text-[34px] font-extrabold leading-tight sm:text-[37px]">
                    Find Your Perfect Caterer
                </h2>
                <p class="mt-4 max-w-[350px] text-[15px] leading-8 text-white/95">
                    Access Tagum City's finest home-based caterers. Browse menus, check availability, and book with confidence.
                </p>

                <div class="mt-8 space-y-4">
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/25 text-[9px] text-white">
                            <i class="fas fa-check"></i>
                        </span>
                        <p class="text-[14px] leading-7 text-white/95">Browse verified caterers in your barangay</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/25 text-[9px] text-white">
                            <i class="fas fa-check"></i>
                        </span>
                        <p class="text-[14px] leading-7 text-white/95">View real-time availability and pricing</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/25 text-[9px] text-white">
                            <i class="fas fa-check"></i>
                        </span>
                        <p class="text-[14px] leading-7 text-white/95">Message caterers directly to customize your menu</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const toggleButton = document.getElementById('toggle-password');

        if (!passwordInput || !toggleButton) {
            return;
        }

        const icon = toggleButton.querySelector('i');
        const label = toggleButton.querySelector('span');

        toggleButton.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';

            passwordInput.type = isHidden ? 'text' : 'password';
            toggleButton.setAttribute('aria-pressed', String(isHidden));
            toggleButton.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');

            if (icon) {
                icon.className = isHidden ? 'fas fa-eye-slash text-[12px]' : 'fas fa-eye text-[12px]';
            }

            if (label) {
                label.textContent = isHidden ? 'Hide' : 'Show';
            }
        });
    });
</script>

</x-layouts.app>
