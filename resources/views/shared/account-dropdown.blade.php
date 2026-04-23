@php
    $dropdownName = $name ?? 'PlatePal User';
    $dropdownInitials = $initials ?? strtoupper(substr($dropdownName, 0, 1));
    $dropdownSubtitle = $subtitle ?? null;
    $dropdownProfileHref = $profileHref ?? '#';
    $dropdownSettingsHref = $settingsHref ?? $dropdownProfileHref;
    $dropdownFeedbackHref = $feedbackHref ?? '#';
@endphp

<details class="group relative">
    <summary class="flex cursor-pointer list-none items-center gap-3 rounded-full border border-[#F0E2D4] bg-[#FFF8F2] px-3 py-2 text-left transition-colors hover:border-[#F6C8A9] hover:bg-white [&::-webkit-details-marker]:hidden">
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#F7661B] text-[11px] font-bold text-white">
            {{ $dropdownInitials ?: 'P' }}
        </span>

        <span class="leading-none">
            <span class="block text-[12px] font-semibold text-[#3C2F24]">{{ $dropdownName }}</span>
            @if ($dropdownSubtitle)
                <span class="mt-1 block text-[10px] text-[#A08D7D]">{{ $dropdownSubtitle }}</span>
            @endif
        </span>

        <i class="fas fa-chevron-down text-[10px] text-[#A08D7D] transition-transform group-open:rotate-180"></i>
    </summary>

    <div class="absolute right-0 top-[calc(100%+0.75rem)] z-50 w-[220px] rounded-[22px] border border-[#F1E3D4] bg-white p-2 shadow-[0_18px_42px_rgba(92,64,39,0.14)]">
        <a href="{{ $dropdownProfileHref }}" class="flex items-center gap-3 rounded-[16px] px-4 py-3 text-[13px] font-medium text-[#5F4B3B] transition-colors hover:bg-[#FFF5ED] hover:text-[#F7661B]">
            <i class="fas fa-user text-[12px]"></i>
            <span>Profile</span>
        </a>

        <a href="{{ $dropdownSettingsHref }}" class="flex items-center gap-3 rounded-[16px] px-4 py-3 text-[13px] font-medium text-[#5F4B3B] transition-colors hover:bg-[#FFF5ED] hover:text-[#F7661B]">
            <i class="fas fa-gear text-[12px]"></i>
            <span>Settings</span>
        </a>

        <a href="{{ $dropdownFeedbackHref }}" class="flex items-center gap-3 rounded-[16px] px-4 py-3 text-[13px] font-medium text-[#5F4B3B] transition-colors hover:bg-[#FFF5ED] hover:text-[#F7661B]">
            <i class="fas fa-comment-dots text-[12px]"></i>
            <span>Feedback</span>
        </a>

        <div class="my-2 h-px bg-[#F4E8DB]"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-[16px] px-4 py-3 text-[13px] font-medium text-[#D25A28] transition-colors hover:bg-[#FFF5ED] hover:text-[#B94818]">
                <i class="fas fa-right-from-bracket text-[12px]"></i>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</details>
