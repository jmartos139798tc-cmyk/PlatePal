{{-- Resources/views/client/messages.blade.php --}}

<x-layouts.app :title="'My Messages - PlatePal'">
    <div style="max-width:1200px;margin:40px auto;padding:0 20px;">
        <h1 style="font-size:28px;color:var(--dark);margin-bottom:24px;">Your Messages</h1>

        @php
            $userId = Auth::id();
            $messages = \App\Models\Message::where('sender_id', $userId)
                ->orWhere('receiver_id', $userId)
                ->orderByDesc('created_at')
                ->get()
                ->groupBy(fn ($m) => $m->sender_id === $userId ? $m->receiver_id : $m->sender_id);
        @endphp

        @if($messages->isEmpty())
            <div style="background:var(--white);padding:40px;border-radius:12px;text-align:center;color:var(--text-muted);">
                <i class="far fa-comments" style="font-size:48px;margin-bottom:16px;"></i>
                <p>No conversations yet. Start a conversation with a caterer from their profile page!</p>
                <a href="{{ route('browse') }}" class="btn btn-primary" style="margin-top:12px;">Browse Caterers</a>
            </div>
        @else
            <div class="caterer-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;">
                @foreach($messages as $partnerId => $msgs)
                    @php
                        $partner = \App\Models\User::find($partnerId);
                        if (!$partner) continue;
                        $latest = $msgs->first();
                        $unread = $msgs->where('receiver_id', $userId)->where('is_read', false)->count();
                    @endphp
                    <a href="{{ route('messages.show', $partner->id) }}"
                       class="caterer-card"
                       style="display:block;background:var(--white);border-radius:12px;overflow:hidden;box-shadow:var(--shadow);transition:transform 0.2s,box-shadow 0.2s;text-decoration:none;color:inherit;">
                        <div class="caterer-card-image" style="height:160px;background:#eee;">
                            <img src="{{ $partner->cover_photo ?? '/Assets/Pusit.jpg' }}"
                                 alt="{{ $partner->name }}"
                                 style="width:100%;height:100%;object-fit:cover;">
                            @if($unread > 0)
                                <span style="position:absolute;top:10px;right:10px;background:var(--primary);color:white;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">{{ $unread }}</span>
                            @endif
                        </div>
                        <div class="caterer-card-body" style="padding:16px;">
                            <div class="caterer-card-name" style="font-size:16px;font-weight:600;margin-bottom:4px;">{{ $partner->name }}</div>
                            <div class="caterer-card-location" style="font-size:13px;color:var(--text-muted);margin-bottom:8px;">
                                <i class="fas fa-map-marker-alt"></i> {{ $partner->barangay ?? 'Tagum City' }}
                            </div>
                            <div class="conv-preview" style="font-size:14px;color:var(--text);display:flex;align-items:center;gap:6px;">
                                @if($latest->sender_id === $userId)
                                    <span style="color:var(--text-muted);font-size:12px;">You:</span>
                                @endif
                                {{ Str::limit($latest->body, 60) }}
                            </div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:8px;">{{ $latest->created_at->diffForHumans() }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
