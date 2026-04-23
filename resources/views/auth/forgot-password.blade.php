{{-- Resources/views/auth/forgot-password.blade.php --}}
<x-layouts.app :title="'Forgot Password - PlatePal'">

<div class="min-h-screen bg-[#414141] px-4 py-6 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-[680px] rounded-[24px] bg-white p-8 shadow-[0_24px_70px_rgba(0,0,0,0.22)] sm:p-10">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-[#221F2D] transition-opacity hover:opacity-80">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#FFF1E7] text-[#F54900]">
                    <i class="fas fa-utensils text-[13px]"></i>
                </span>
                <span class="text-[11px] font-extrabold tracking-[0.18em]">PLATEPAL</span>
            </a>

            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-medium text-[#8C8C8C] transition-colors hover:text-[#F54900]">
                <i class="fas fa-arrow-left text-[10px]"></i>
                <span>Back to Home</span>
            </a>
        </div>

        <div class="mt-10">
            <h1 class="text-[32px] font-extrabold leading-tight text-[#1F2937]">Forgot your password?</h1>
            <p class="mt-3 text-[15px] leading-7 text-[#6B7280]">
                Self-service password recovery has not been set up yet. If you cannot access your account, please contact the PlatePal team for help resetting it.
            </p>
        </div>

        <div class="mt-8 rounded-2xl border border-[#FFD4BF] bg-[#FFF4EE] px-5 py-4 text-sm leading-7 text-[#B54A18]">
            Need access right away? Reach out to support, or return to the correct sign-in page below.
        </div>

        <div class="mt-8 grid gap-3 sm:grid-cols-2">
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-[#F54900] px-5 py-3 text-sm font-semibold text-white transition-all hover:-translate-y-0.5 hover:bg-[#D14300]">
                Client Sign In
            </a>
            <a href="{{ route('caterer.login') }}" class="inline-flex items-center justify-center rounded-xl bg-[#111827] px-5 py-3 text-sm font-semibold text-white transition-all hover:-translate-y-0.5 hover:bg-[#1F2937]">
                Caterer Sign In
            </a>
        </div>

        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl border border-[#F54900] px-5 py-3 text-sm font-semibold text-[#F54900] transition-all hover:bg-[#FFF1E7]">
                Create Client Account
            </a>
            <a href="{{ route('caterer.register') }}" class="inline-flex items-center justify-center rounded-xl border border-[#111827] px-5 py-3 text-sm font-semibold text-[#111827] transition-all hover:bg-[#F3F4F6]">
                Create Caterer Account
            </a>
        </div>
    </div>
</div>

</x-layouts.app>
