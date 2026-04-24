{{-- Resources/views/shared/messages.blade.php --}}

<x-layouts.app :title="'Messages - PlatePal'">

<!-- Navbar -->
<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar-brand">
        <div class="logo-icon"><i class="fas fa-utensils"></i></div>
        <div><div>PLATEPAL</div></div>
    </a>
    <div class="navbar-nav">
        <a href="{{ route('browse') }}">Browse caterers</a>
        <a href="{{ route('client.messages') }}" class="active" style="color:var(--primary);border-bottom:2px solid var(--primary);padding-bottom:4px;">Messages</a>
        <a href="{{ route('client.saved') }}">Saved caterers</a>
    </div>
    <div class="navbar-actions" style="gap:14px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:14px;">
                {{ substr(Auth::user()->name ?? 'J', 0, 1) }}
            </div>
            <div style="line-height:1.3;">
                <div style="font-size:13px;font-weight:700;">{{ Auth::user()->name ?? 'Juan Dela Cruz' }}</div>
                <div style="font-size:11px;color:var(--text-muted);">Client Account</div>
            </div>
        </div>
        <a href="{{ route('logout') }}" class="btn btn-outline btn-sm"
           onclick="event.preventDefault();document.getElementById('logout-msg').submit();">Sign Out</a>
        <form id="logout-msg" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>
</nav>

<!-- Messaging Layout -->
<div class="messaging-wrapper">
    <div class="messaging-container" id="messagingContainer">

        <!-- Left: Conversation List -->
        <div class="conv-list">
            <div class="conv-list-header">
                <span class="conv-list-title">Messages</span>
                <button class="icon-btn"><i class="fas fa-search"></i></button>
            </div>

            <div class="conv-items" id="convItems">
                @php
                $conversations = $conversations ?? [
                    ['id'=>1, 'name'=>"Nanay Cora's Kitchen", 'preview'=>'Great! Your preferred menu selection is also noted...', 'time'=>'Just now', 'unread'=>true,  'initials'=>'NC', 'online'=>true],
                    ['id'=>2, 'name'=>"Ate Belle's Catering",  'preview'=>'We have special packages for graduations!',            'time'=>'2h ago',   'unread'=>false, 'initials'=>'AB', 'online'=>false],
                    ['id'=>3, 'name'=>'Aloha Packed',           'preview'=>'Thank you for your inquiry!',                          'time'=>'1d ago',   'unread'=>false, 'initials'=>'AP', 'online'=>false],
                ];
                $activeId = $activeConversation ?? 1;
                @endphp

                @foreach($conversations as $conv)
                @php $isArr = is_array($conv); $cid = $isArr ? $conv['id'] : $conv->id; @endphp
                <a href="{{ route('messages.show', $cid) }}"
                   class="conv-item {{ $cid == $activeId ? 'active' : '' }}"
                   data-id="{{ $cid }}">
                    <div class="conv-avatar-wrap">
                        <div class="conv-avatar">{{ $isArr ? $conv['initials'] : strtoupper(substr($conv->name, 0, 2)) }}</div>
                        @if($isArr ? $conv['online'] : ($conv->is_online ?? false))
                        <span class="online-dot"></span>
                        @endif
                    </div>
                    <div class="conv-info">
                        <div class="conv-name">{{ $isArr ? $conv['name'] : $conv->name }}</div>
                        <div class="conv-preview {{ ($isArr ? $conv['unread'] : ($conv->unread ?? false)) ? 'unread' : '' }}" id="preview-{{ $cid }}">
                            {{ $isArr ? $conv['preview'] : $conv->preview }}
                        </div>
                    </div>
                    <div class="conv-meta">
                        <div class="conv-time" id="time-{{ $cid }}">{{ $isArr ? $conv['time'] : $conv->time }}</div>
                        @if($isArr ? $conv['unread'] : ($conv->unread ?? false))
                        <span class="unread-dot" id="dot-{{ $cid }}"></span>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Right: Chat Window -->
        <div class="chat-window">
            <!-- Chat Header -->
            <div class="chat-header">
                <div class="chat-header-left">
                    <button class="icon-btn back-btn" id="backBtn" style="display:none;">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div class="conv-avatar" style="width:42px;height:42px;font-size:15px;flex-shrink:0;">
                        {{ $activeInitials ?? 'NC' }}
                    </div>
                    <div>
                        <div class="chat-name">{{ $activeName ?? "Nanay Cora's Kitchen" }}</div>
                        <div class="chat-status" id="chatStatus">
                            <span class="online-dot" style="display:inline-block;position:static;border:none;margin-right:5px;"></span>
                            Online &middot; Usually replies in 1&ndash;2 hours
                        </div>
                    </div>
                </div>
                <div class="chat-header-actions">
                    <a href="{{ route('caterer.show', $activeCatererId ?? 1) }}" class="btn btn-outline btn-sm">View menu</a>
                    <button class="icon-btn"><i class="fas fa-phone"></i></button>
                    <button class="icon-btn"><i class="fas fa-video"></i></button>
                    <button class="icon-btn"><i class="fas fa-ellipsis-v"></i></button>
                </div>
            </div>

            <!-- Messages -->
            <div class="chat-messages" id="chatMessages">
                @php
                $messages = $messages ?? [
                    ['from'=>'them', 'body'=>'Hello! We received your booking request. Thank you for choosing us!',                                                                                        'time'=>'10:30 AM'],
                    ['from'=>'them', 'body'=>'Let me just verify the details, this is for 50 guests, correct?',                                                                                          'time'=>'10:31 AM'],
                    ['from'=>'me',   'body'=>"Yes, that's right. It's a small birthday celebration at my home.",                                                                                         'time'=>'10:35 AM'],
                    ['from'=>'them', 'body'=>'Great! Your preferred menu selection is also noted. Everything looks good on our end. To officially confirm your booking, we require a 50% down payment.', 'time'=>'10:38 AM'],
                    ['from'=>'me',   'body'=>'Okay, that sounds fine. How can I send the payment?',                                                                                                      'time'=>'10:42 AM'],
                ];
                @endphp

                @foreach($messages as $msg)
                @php
                    $isArr = is_array($msg);
                    $from  = $isArr ? $msg['from'] : ($msg->sender_id === Auth::id() ? 'me' : 'them');
                    $body  = $isArr ? $msg['body'] : $msg->body;
                    $time  = $isArr ? $msg['time'] : $msg->created_at->format('g:i A');
                @endphp
                <div class="msg-row msg-row--{{ $from }}">
                    <div class="msg-bubble msg-bubble--{{ $from }}">
                        <p>{{ $body }}</p>
                        <span class="msg-time">{{ $time }}</span>
                    </div>
                </div>
                @endforeach

                <!-- Typing indicator (hidden by default) -->
                <div class="msg-row msg-row--them" id="typingIndicator" style="display:none;">
                    <div class="msg-bubble msg-bubble--them typing-bubble">
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
            </div>
    </div>
</div>

@push('styles')
<style>
/* ── Page wrapper ──────────────────────────────── */
.messaging-wrapper {
    background: #EDE5DA;
    min-height: calc(100vh - 64px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 28px;
}

.messaging-container {
    display: flex;
    width: 100%;
    max-width: 860px;
    height: calc(100vh - 148px);
    min-height: 480px;
    background: var(--white);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 40px rgba(0,0,0,0.12);
}

/* ── Conversation List ─────────────────────────── */
.conv-list {
    width: 295px;
    flex-shrink: 0;
    border-right: 1px solid #EDE5DA;
    display: flex;
    flex-direction: column;
    background: #FDFAF7;
}

.conv-list-header {
    padding: 18px 16px 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #EDE5DA;
}

.conv-list-title { font-size: 16px; font-weight: 700; color: var(--dark); }

.icon-btn {
    width: 30px; height: 30px;
    border: none; background: transparent;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: var(--text-muted);
    cursor: pointer; font-size: 13px;
    transition: background 0.15s;
}
.icon-btn:hover { background: #EDE5DA; color: var(--dark); }

.conv-items { flex: 1; overflow-y: auto; }

.conv-item {
    display: flex; align-items: center; gap: 11px;
    padding: 13px 14px;
    cursor: pointer; text-decoration: none; color: inherit;
    border-bottom: 1px solid #F5EFE8;
    transition: background 0.15s;
}
.conv-item:hover  { background: #F7F1EB; }
.conv-item.active { background: #F0E6DA; }

.conv-avatar-wrap { position: relative; flex-shrink: 0; }

.conv-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: #C9B8A8; color: #5C3D2E;
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    letter-spacing: 0.3px; text-transform: uppercase;
}

.online-dot {
    width: 9px; height: 9px;
    background: #22C55E; border-radius: 50%;
    border: 2px solid #FDFAF7;
    position: absolute; bottom: 1px; right: 1px;
}

.conv-info { flex: 1; min-width: 0; }

.conv-name {
    font-size: 13px; font-weight: 600; color: var(--dark);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

.conv-preview {
    font-size: 12px; color: var(--text-muted);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-top: 2px; transition: color 0.2s;
}
.conv-preview.unread { color: var(--dark); font-weight: 600; }

.conv-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 5px; flex-shrink: 0; }
.conv-time  { font-size: 11px; color: var(--text-muted); white-space: nowrap; }

.unread-dot {
    width: 8px; height: 8px;
    background: var(--primary); border-radius: 50%;
}

/* ── Chat Window ───────────────────────────────── */
.chat-window { flex: 1; display: flex; flex-direction: column; background: var(--white); min-width: 0; }

.chat-header {
    padding: 13px 18px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    background: var(--white); flex-shrink: 0;
}
.chat-header-left  { display: flex; align-items: center; gap: 11px; }
.chat-header-actions { display: flex; align-items: center; gap: 4px; }

.chat-name   { font-size: 15px; font-weight: 700; color: var(--dark); }
.chat-status {
    font-size: 11px; color: var(--text-muted);
    display: flex; align-items: center; gap: 4px; margin-top: 2px;
}
.chat-status .online-dot { position: static; border: none; width: 7px; height: 7px; }

/* Messages */
.chat-messages {
    flex: 1; overflow-y: auto;
    padding: 22px 20px;
    display: flex; flex-direction: column; gap: 10px;
    background: var(--white);
}

.msg-row { display: flex; max-width: 75%; }
.msg-row--me   { align-self: flex-end; }
.msg-row--them { align-self: flex-start; }

.msg-bubble {
    padding: 10px 14px; border-radius: 16px;
    font-size: 14px; line-height: 1.55;
}
.msg-bubble p { margin: 0 0 3px; }

.msg-bubble--them {
    background: #F3EDE7; color: var(--dark);
    border-bottom-left-radius: 4px;
}
.msg-bubble--me {
    background: var(--primary); color: white;
    border-bottom-right-radius: 4px;
}

.msg-time {
    display: block; font-size: 10px; opacity: 0.60;
    margin-top: 3px; text-align: right;
}
.msg-bubble--them .msg-time { text-align: left; }

/* Typing indicator */
.typing-bubble {
    display: flex; align-items: center; gap: 4px;
    padding: 12px 16px;
}
.typing-dot {
    width: 7px; height: 7px;
    background: #9CA3AF; border-radius: 50%;
    animation: typing-bounce 1.2s infinite ease-in-out;
}
.typing-dot:nth-child(2) { animation-delay: 0.2s; }
.typing-dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes typing-bounce {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
    30%            { transform: translateY(-5px); opacity: 1; }
}

/* Sending state */
.msg-bubble--me.sending { opacity: 0.65; }

/* Input */
.chat-input-bar {
    padding: 12px 16px;
    border-top: 1px solid var(--border);
    background: var(--white); flex-shrink: 0;
}
.chat-input-form {
    display: flex; align-items: center; gap: 8px;
    background: #F5F0EB; border-radius: 24px;
    padding: 8px 8px 8px 18px;
    border: 1.5px solid transparent;
    transition: border-color 0.2s, background 0.2s;
}
.chat-input-form:focus-within { border-color: var(--primary); background: var(--white); }

.chat-input {
    flex: 1; border: none; background: transparent; outline: none;
    font-size: 14px; font-family: 'Inter', sans-serif; color: var(--dark);
}
.chat-input::placeholder { color: #B8AFA8; }

.send-btn {
    width: 34px; height: 34px; border-radius: 50%; border: none;
    background: var(--primary); color: white;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 13px; flex-shrink: 0;
    transition: background 0.2s, transform 0.15s;
}
.send-btn:hover   { background: var(--primary-dark); transform: scale(1.07); }
.send-btn:disabled { background: #D1C4BB; cursor: not-allowed; transform: none; }

/* Connection status bar */
.conn-bar {
    padding: 6px 16px;
    font-size: 12px; font-weight: 600;
    text-align: center; display: none;
}
.conn-bar.offline { display: block; background: #FEF3C7; color: #92400E; }
.conn-bar.reconnecting { display: block; background: #DBEAFE; color: #1E40AF; }

/* Scrollbars */
.chat-messages::-webkit-scrollbar,
.conv-items::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-thumb,
.conv-items::-webkit-scrollbar-thumb { background: #D6C4B4; border-radius: 4px; }
.chat-messages::-webkit-scrollbar-track,
.conv-items::-webkit-scrollbar-track { background: transparent; }

/* Responsive */
@media (max-width: 620px) {
    .messaging-wrapper { padding: 0; }
    .messaging-container { border-radius: 0; height: 100vh; }
    .conv-list { width: 100%; border-right: none; }
    .chat-window { display: none; }
    .messaging-container.chat-open .conv-list  { display: none; }
    .messaging-container.chat-open .chat-window { display: flex; width: 100%; }
    #backBtn { display: flex !important; }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0-rc2/dist/web/pusher.min.js"></script>

<script>
/* ============================================================
   CONFIG — values passed from Laravel
   ============================================================ */
const AUTH_USER_ID   = {{ Auth::id() }};
const ACTIVE_USER_ID = {{ $activeUserId ?? 1 }};
const CSRF_TOKEN     = '{{ csrf_token() }}';
const SEND_URL       = '{{ route("messages.send", $activeUserId ?? 1) }}';

/* ============================================================
   DOM REFS
   ============================================================ */
const chatBox      = document.getElementById('chatMessages');
const form         = document.getElementById('msgForm');
const input        = document.getElementById('msgInput');
const sendBtn      = document.getElementById('sendBtn');
const typingEl     = document.getElementById('typingIndicator');
const chatStatus   = document.getElementById('chatStatus');
const container    = document.getElementById('messagingContainer');
const connBar      = document.createElement('div');

connBar.className = 'conn-bar';
document.querySelector('.chat-window').insertBefore(connBar, document.querySelector('.chat-header').nextSibling);

/* ============================================================
   SCROLL HELPER
   ============================================================ */
function scrollBottom(smooth = false) {
    chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: smooth ? 'smooth' : 'auto' });
}
scrollBottom();

/* ============================================================
   BUILD A BUBBLE
   ============================================================ */
function buildBubble(from, body, time, { pending = false } = {}) {
    const row = document.createElement('div');
    row.className = `msg-row msg-row--${from}`;
    row.innerHTML =
        `<div class="msg-bubble msg-bubble--${from}${pending ? ' sending' : ''}">` +
            `<p>${escHtml(body)}</p>` +
            `<span class="msg-time">${time}</span>` +
        `</div>`;
    return row;
}

function escHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function nowTime() {
    return new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
}

/* ============================================================
   PRIVATE CHANNEL  →  listen for incoming messages
   ============================================================ */
const channel = Echo.private(`chat.${AUTH_USER_ID}`);

channel.listen('.message.sent', function (e) {
    // Only show if message is from the active conversation partner
    if (e.sender_id !== ACTIVE_USER_ID) {
        // Update the sidebar preview for that conversation
        const preview = document.getElementById(`preview-${e.sender_id}`);
        const dot     = document.getElementById(`dot-${e.sender_id}`);
        const timeEl  = document.getElementById(`time-${e.sender_id}`);
        if (preview) { preview.textContent = e.body; preview.classList.add('unread'); }
        if (timeEl)  timeEl.textContent = 'Just now';
        if (dot)     dot.style.display = 'inline-block';
        else {
            // Create dot if not present
            const meta = document.querySelector(`[data-id="${e.sender_id}"] .conv-meta`);
            if (meta) {
                const newDot = document.createElement('span');
                newDot.className = 'unread-dot';
                newDot.id = `dot-${e.sender_id}`;
                meta.appendChild(newDot);
            }
        }
        return;
    }

    // Hide typing indicator
    stopTyping();

    // Append their message
    const row = buildBubble('them', e.body, e.time ?? nowTime());
    chatBox.insertBefore(row, typingEl);
    scrollBottom(true);

    // Update sidebar preview
    const preview = document.getElementById(`preview-${e.sender_id}`);
    if (preview) preview.textContent = e.body;
});

/* ============================================================
   WHISPER  →  typing indicator
   ============================================================ */
let typingTimer = null;

channel.listenForWhisper('typing', function (e) {
    if (e.sender_id !== ACTIVE_USER_ID) return;
    showTyping();
    clearTimeout(typingTimer);
    typingTimer = setTimeout(stopTyping, 3000);
});

function showTyping() {
    typingEl.style.display = 'flex';
    scrollBottom(true);
}
function stopTyping() {
    typingEl.style.display = 'none';
    clearTimeout(typingTimer);
}

/* ============================================================
   PRESENCE CHANNEL  →  online status
   ============================================================ */
Echo.join(`presence.${Math.min(AUTH_USER_ID, ACTIVE_USER_ID)}-${Math.max(AUTH_USER_ID, ACTIVE_USER_ID)}`)
    .here(function (users) {
        updateOnlineStatus(users.some(u => u.id === ACTIVE_USER_ID));
    })
    .joining(function (user) {
        if (user.id === ACTIVE_USER_ID) updateOnlineStatus(true);
    })
    .leaving(function (user) {
        if (user.id === ACTIVE_USER_ID) updateOnlineStatus(false);
    });

function updateOnlineStatus(isOnline) {
    if (!chatStatus) return;
    if (isOnline) {
        chatStatus.innerHTML =
            '<span class="online-dot" style="display:inline-block;position:static;border:none;margin-right:5px;width:7px;height:7px;"></span>' +
            'Online &middot; Usually replies in 1&ndash;2 hours';
    } else {
        chatStatus.innerHTML = '<span style="color:#9CA3AF;">● Offline</span>';
    }
}

/* ============================================================
   CONNECTION STATUS BAR
   ============================================================ */
Echo.connector.pusher.connection.bind('disconnected', function () {
    connBar.className = 'conn-bar offline';
    connBar.textContent = '⚠ Connection lost. Trying to reconnect...';
});
Echo.connector.pusher.connection.bind('connecting', function () {
    connBar.className = 'conn-bar reconnecting';
    connBar.textContent = '↻ Reconnecting...';
});
Echo.connector.pusher.connection.bind('connected', function () {
    connBar.className = 'conn-bar';
    connBar.style.display = 'none';
});

/* ============================================================
   SEND MESSAGE  (AJAX — no page reload)
   ============================================================ */
let isSending = false;

form && form.addEventListener('submit', async function (e) {
    e.preventDefault();

    const text = input.value.trim();
    if (!text || isSending) return;

    isSending = true;
    sendBtn.disabled = true;
    input.value = '';

    // Optimistic bubble
    const pendingRow = buildBubble('me', text, nowTime(), { pending: true });
    chatBox.insertBefore(pendingRow, typingEl);
    scrollBottom(true);

    try {
        const res = await fetch(SEND_URL, {
            method:  'POST',
            headers: {
                'Content-Type':     'application/json',
                'Accept':           'application/json',
                'X-CSRF-TOKEN':     CSRF_TOKEN,
            },
            body: JSON.stringify({ body: text }),
        });

        if (!res.ok) throw new Error('Send failed');

        // Confirm sent — remove "sending" dimming
        pendingRow.querySelector('.msg-bubble').classList.remove('sending');

        // Update sidebar preview
        const preview = document.getElementById(`preview-${ACTIVE_USER_ID}`);
        if (preview) { preview.textContent = text; preview.classList.remove('unread'); }

    } catch (err) {
        // Remove pending bubble and restore input on failure
        pendingRow.remove();
        input.value = text;
        alert('Failed to send. Please try again.');
    } finally {
        isSending = false;
        sendBtn.disabled = false;
        input.focus();
    }
});

/* ============================================================
   WHISPER TYPING  (emit when user types)
   ============================================================ */
let whisperTimer = null;

input && input.addEventListener('input', function () {
    clearTimeout(whisperTimer);
    // Throttle whispers to once per 1.5s
    whisperTimer = setTimeout(function () {
        channel.whisper('typing', { sender_id: AUTH_USER_ID });
    }, 400);
});

/* ============================================================
   MOBILE: back button
   ============================================================ */
const backBtn = document.getElementById('backBtn');
backBtn && backBtn.addEventListener('click', function () {
    container.classList.remove('chat-open');
});

document.querySelectorAll('.conv-item').forEach(function (item) {
    item.addEventListener('click', function () {
        container.classList.add('chat-open');
    });
}
</script>
@endpush

</x-layouts.app>
