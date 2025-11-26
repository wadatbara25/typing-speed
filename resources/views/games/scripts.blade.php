<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Notification sound -->
<audio id="notifySound" preload="auto">
  <source src="{{ asset('sounds/notify.mp3') }}" type="audio/mpeg">
</audio>

<script>
/* =======================================================
   🎮 Al-Khwarizmi Game System (Updated with Rank)
   Arabic UI — Simple English comments
======================================================= */

document.addEventListener("DOMContentLoaded", () => {
    // Init icons
    if (typeof lucide !== 'undefined') lucide.createIcons();

    // Load player name
    const name = localStorage.getItem('playerName');
    if (name) {
        fadeOut(document.getElementById('name-section'));
        fadeIn(document.getElementById('games-section'));
        fadeIn(document.getElementById('player-info'));
        document.getElementById('currentPlayerName').textContent = name;
    }

    // Load leaderboard automatically
    loadLeaderboard();
});

/* ============================
   Fade helpers
============================ */
function fadeIn(el) {
    el.classList.remove('hidden', 'opacity-0');
    el.classList.add('opacity-100', 'transition-opacity', 'duration-500');
}
function fadeOut(el) {
    el.classList.add('opacity-0');
    setTimeout(() => el.classList.add('hidden'), 400);
}

/* ============================
   Save player name
============================ */
function saveName() {
    const name = document.getElementById('playerName').value.trim();
    if (!name) {
        Swal.fire('تنبيه', 'يرجى إدخال اسمك أولاً قبل البدء باللعب.', 'warning');
        return;
    }

    localStorage.setItem('playerName', name);
    fadeOut(document.getElementById('name-section'));
    fadeIn(document.getElementById('games-section'));
    fadeIn(document.getElementById('player-info'));
    document.getElementById('currentPlayerName').textContent = name;
    document.getElementById('notifySound').play().catch(() => {});
}

/* ============================
   Change player name
============================ */
function changeName() {
    Swal.fire({
        title: 'تغيير الاسم؟',
        text: 'سيتم حذف الاسم الحالي من المتصفح.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'نعم',
        cancelButtonText: 'إلغاء',
        confirmButtonColor: '#facc15',
        cancelButtonColor: '#6b7280'
    }).then(r => {
        if (r.isConfirmed) {
            localStorage.removeItem('playerName');
            location.reload();
        }
    });
}

/* ============================
   Open game in fullscreen
============================ */
function openGame(type) {
    const name = localStorage.getItem('playerName');
    if (!name) return Swal.fire('تنبيه', 'يرجى إدخال اسمك أولاً.', 'warning');

    const overlay = document.createElement('div');
    overlay.id = 'gameFullscreen';
    overlay.className = 'fixed inset-0 z-[9999] bg-black flex items-center justify-center transition-opacity duration-500 opacity-0';
    overlay.innerHTML = `
        <iframe id="gameFrame" src="/games/${type}" class="w-full h-full border-0 bg-black"></iframe>
        <button onclick="closeGame()" 
            class="absolute top-4 right-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full px-4 py-2 text-sm shadow-lg transition">
            إغلاق
        </button>
    `;
    document.body.appendChild(overlay);
    setTimeout(() => overlay.classList.add('opacity-100'), 50);
    localStorage.setItem('lastGame', type);
    document.getElementById('notifySound').play().catch(() => {});
}

/* ============================
   Close game fullscreen
============================ */
function closeGame() {
    const overlay = document.getElementById('gameFullscreen');
    if (!overlay) return;
    overlay.classList.remove('opacity-100');
    setTimeout(() => overlay.remove(), 300);
}

/* ============================
   Load leaderboard
============================ */
async function loadLeaderboard() {
    try {
        const res = await fetch("{{ url('/games/leaderboard') }}");
        const data = await res.json();
        renderLeaderboard(data);
    } catch {
        document.getElementById('leaderboard-content').innerHTML =
            '<p class="text-red-500">حدث خطأ أثناء تحميل المتصدرين.</p>';
    }
}

/* ============================
   Render leaderboard table
============================ */
function renderLeaderboard(players) {
    if (!players.length) {
        document.getElementById('leaderboard-content').innerHTML =
            '<p class="text-gray-500">لا توجد نتائج بعد. كن أول من يبدأ!</p>';
        return;
    }

    let html = `
    <table class="w-full text-center border-separate border-spacing-y-2 text-gray-700 dark:text-gray-200">
        <thead class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white">
            <tr><th>#</th><th>اللاعب</th><th>السرعة</th><th>الدقة</th><th>اللعبة</th></tr>
        </thead>
        <tbody>`;
    players.forEach((p, i) => {
        html += `
        <tr class="bg-gray-50 dark:bg-gray-700 hover:bg-indigo-50 dark:hover:bg-gray-600 transition">
            <td>${i + 1}</td>
            <td>${p.player_name}</td>
            <td class="text-green-600 dark:text-green-400 font-semibold">${p.wpm}</td>
            <td>${p.accuracy}%</td>
            <td>${translateGame(p.game_type)}</td>
        </tr>`;
    });
    html += "</tbody></table>";
    document.getElementById('leaderboard-content').innerHTML = html;
}

/* ============================
   Translate game names
============================ */
function translateGame(type) {
    switch (type) {
        case 'speed': return 'اختبار السرعة';
        case 'race': return 'سباق الطباعة';
        case 'letters': return 'لعبة الحروف';
        case 'random-words': return 'الكلمات العشوائية';
        case 'arabic-typing': return 'الكتابة بالعربية';
        default: return 'غير معروف';
    }
}

/* ============================
   Save game result (with rank)
============================ */
async function saveResult(type, wpm, accuracy) {
    const name = localStorage.getItem('playerName') || 'لاعب';
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrf) return Swal.fire('خطأ', 'رمز الأمان (CSRF) غير موجود.', 'error');

    try {
        const res = await fetch("{{ url('/games/store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrf
            },
            body: JSON.stringify({
                player_name: name,
                wpm: Math.round(wpm),
                accuracy: parseFloat(accuracy).toFixed(1),
                game_type: type
            }),
        });

        const data = await res.json();
        if (!res.ok || !data.success) throw new Error(data.message || "فشل الحفظ");

        // Success alert with current rank
        document.getElementById('notifySound').play().catch(() => {});
        Swal.fire({
            icon: 'success',
            title: 'تم حفظ النتيجة بنجاح',
            html: `
                <p>الاسم: <b>${name}</b></p>
                <p>السرعة: <b class="text-green-600">${wpm}</b> كلمة/دقيقة</p>
                <p>ترتيبك الحالي: <b class="text-indigo-600">#${data.rank}</b></p>
            `,
            confirmButtonText: 'عرض المتصدرين',
            showCancelButton: true,
            cancelButtonText: 'إغلاق',
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#6b7280'
        }).then(r => { if (r.isConfirmed) loadLeaderboard(); });

    } catch (e) {
        console.error('Save error:', e);
        Swal.fire('خطأ', 'تعذر حفظ النتيجة، حاول مرة أخرى.', 'error');
    }
}

/* ============================
   Listen for postMessage from game
============================ */
window.addEventListener("message", e => {
    if (e.data && e.data.type === "saveGameResult") {
        const { gameType, wpm, accuracy } = e.data;
        saveResult(gameType, wpm, accuracy);
    }
});
</script>
