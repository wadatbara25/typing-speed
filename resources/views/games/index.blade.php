@extends('layouts.app')
@section('title', '🎮 الألعاب التعليمية')

@section('content')
<main class="space-y-10" dir="rtl">

    <!-- Title -->
    <section class="text-center">
        <h1 class="text-4xl font-extrabold text-indigo-700 dark:text-indigo-300 flex items-center justify-center gap-2">
            <i data-lucide="gamepad-2" class="w-8 h-8 text-indigo-600 dark:text-indigo-400"></i>
            الألعاب التعليمية
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">
            تعلم الطباعة بطريقة ممتعة ومليئة بالتحديات.  
            أدخل اسمك مرة واحدة ثم اختر اللعبة لتبدأ فورًا!
        </p>
    </section>

    <!-- Player name -->
    <section id="name-section"
        class="text-center max-w-md mx-auto bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg 
               border border-gray-100 dark:border-gray-700 transition-all duration-500 hover:shadow-xl fade-in">
        <label for="playerName" class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
            أدخل اسمك للبدء في اللعب
        </label>
        <input type="text" id="playerName" placeholder="اكتب اسمك هنا..." autocomplete="off"
            class="w-full px-4 py-2 border rounded-lg text-center text-gray-800 dark:text-gray-100 
                   dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 outline-none transition">
        <button onclick="saveName()"
            class="mt-4 w-full bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600
                   text-white font-semibold px-6 py-2 rounded-xl shadow-md transition-all duration-300 transform hover:scale-105">
            ابدأ الآن
        </button>
    </section>

    <!-- Player info -->
    <div id="player-info" 
        class="hidden fade-hidden text-center bg-indigo-50 dark:bg-gray-800 border border-indigo-100 dark:border-gray-700 
               rounded-xl py-4 mb-8 shadow-sm max-w-md mx-auto flex flex-col sm:flex-row items-center 
               justify-between gap-3 px-5 transition-all duration-500">
        <div class="text-start">
            <span class="font-semibold text-indigo-700 dark:text-indigo-300 text-lg">
                اللاعب: <span id="currentPlayerName" class="font-bold"></span>
            </span>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                أهلاً بك! نتمنى لك تجربة ممتعة ومفيدة 🎯
            </p>
        </div>

        <!-- Player Rank -->
        <div id="playerRankBox" class="hidden bg-gradient-to-r from-indigo-600 to-blue-500 text-white font-semibold 
             px-4 py-1.5 rounded-full shadow-md text-sm">
            ترتيبك الحالي: <span id="currentPlayerRank" class="font-bold">#1</span>
        </div>

        <button onclick="changeName()" aria-label="Change name"
            class="text-sm bg-yellow-400 hover:bg-yellow-300 text-indigo-900 font-semibold 
                   px-3 py-1.5 rounded-lg shadow transition-transform hover:scale-105">
            تغيير الاسم
        </button>
    </div>

    <!-- Games list -->
    <section id="games-section" class="hidden fade-hidden transition-all duration-500">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <x-game-card icon="zap" color="indigo" title="اختبار السرعة"
                         desc="اختبر سرعتك في الكتابة بدقة وبدون أخطاء!" type="speed" />

            <x-game-card icon="flag" color="amber" title="سباق الطباعة"
                         desc="اكتب النص بسرعة لتصل بسيارتك إلى خط النهاية" type="race" />

            <x-game-card icon="keyboard" color="green" title="لعبة الحروف"
                         desc="اضغط الحروف التي تظهر قبل أن تختفي" type="letters" />

            <x-game-card icon="type" color="purple" title="اختبار الكلمات العشوائية"
                         desc="اكتب أكبر عدد ممكن من الكلمات الصحيحة خلال 30 ثانية" type="random-words" />

            <x-game-card icon="type" color="blue" title="اختبار سرعة الكتابة بالعربية"
                         desc="لعبة HTML5 جاهزة لتدريب سرعة الكتابة باللغة العربية" type="arabic-typing" />

        </div>
    </section>
</main>

<!-- Leaderboard -->
<section id="leaderboard-section"
         class="mt-12 text-center bg-white dark:bg-gray-800 rounded-2xl shadow p-6 
                border border-gray-100 dark:border-gray-700 max-w-4xl mx-auto transition-all duration-500 hover:shadow-lg">
    <h3 class="text-2xl font-bold text-indigo-700 dark:text-indigo-300 mb-6 flex justify-center items-center gap-2">
        <i data-lucide="trophy" class="w-6 h-6 text-yellow-400"></i>
        لوحة المتصدرين
    </h3>
    <div id="leaderboard-content">
        <p class="text-gray-500">جارٍ تحميل المتصدرين...</p>
    </div>
    <button onclick="showAllPlayers()"
        class="mt-5 bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 
               text-white font-semibold px-6 py-2 rounded-xl shadow-md transition-all duration-300 hover:scale-105">
        عرض جميع اللاعبين
    </button>
</section>

<!-- All players modal -->
<div id="allPlayersModal" role="dialog" aria-modal="true"
     class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50 opacity-0 transition-opacity duration-500">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl p-6 w-[95%] md:w-[80%] max-h-[90vh] overflow-y-auto relative transform scale-95 transition-all duration-500">
        <button onclick="closeAllPlayers()" aria-label="Close modal"
            class="absolute top-3 left-3 bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm font-bold transition-transform hover:scale-110">
            إغلاق
        </button>
        <h2 class="text-2xl font-bold text-indigo-600 mb-4 text-center">جميع اللاعبين ونتائجهم</h2>
        <div id="allPlayersContent" class="overflow-x-auto">
            <p class="text-gray-500 text-center">جارٍ التحميل...</p>
        </div>
    </div>
</div>

@include('games.scripts')

<style>
.fade-in { opacity: 1 !important; transform: translateY(0) !important; }
.fade-hidden { opacity: 0; transform: translateY(20px); transition: all 0.5s ease; }
#allPlayersModal.show { opacity: 1 !important; }
.game-card { transition: all 0.3s ease-in-out; }
.game-card:hover {
  background: linear-gradient(to bottom right, #eef2ff, #e0e7ff);
  transform: translateY(-4px) scale(1.02);
  box-shadow: 0 10px 25px rgba(99, 102, 241, 0.25);
}
.dark .game-card:hover {
  background: linear-gradient(to bottom right, #1f2937, #111827);
  box-shadow: 0 10px 25px rgba(147, 197, 253, 0.15);
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    const userName = "{{ auth()->user()->name ?? '' }}";

    if (isAuthenticated && userName) {
        document.getElementById('name-section').classList.add('hidden');
        showSections(userName, false);
        return;
    }

    const savedName = localStorage.getItem('playerName');
    if (savedName) {
        document.getElementById('name-section').classList.add('hidden');
        showSections(savedName, true);
    }
});

/* Show player info + games */
function showSections(name, allowChange = true) {
    const playerInfo = document.getElementById('player-info');
    const gamesSection = document.getElementById('games-section');
    document.getElementById('currentPlayerName').textContent = name;

    if (!allowChange) {
        const changeBtn = document.querySelector('#player-info button');
        if (changeBtn) changeBtn.classList.add('hidden');
    }

    [playerInfo, gamesSection].forEach(el => {
        el.classList.remove('hidden', 'fade-hidden');
        setTimeout(() => el.classList.add('fade-in'), 50);
    });
}

/* Update player rank (called after save) */
function updatePlayerRank(rank) {
    const rankBox = document.getElementById('playerRankBox');
    const rankSpan = document.getElementById('currentPlayerRank');
    rankSpan.textContent = `#${rank}`;
    rankBox.classList.remove('hidden');
}


async function showAllPlayers() {
    const modal = document.getElementById('allPlayersModal');
    const content = document.getElementById('allPlayersContent');

  
    modal.classList.remove('hidden');
    setTimeout(() => modal.classList.add('show'), 50);

    
    content.innerHTML = `<p class="text-gray-500 text-center">⏳ جارٍ تحميل النتائج...</p>`;

    try {
        const res = await fetch("{{ url('/games/all') }}");
        const players = await res.json();

        if (!players.length) {
            content.innerHTML = `<p class="text-gray-500 text-center">لا توجد بيانات بعد. ✨</p>`;
            return;
        }

     
        players.sort((a, b) => {
            if (b.wpm === a.wpm) return b.accuracy - a.accuracy;
            return b.wpm - a.wpm;
        });

        // ✅ إنشاء جدول اللاعبين
        let html = `
        <table class="w-full text-center border-separate border-spacing-y-2 text-gray-700 dark:text-gray-200">
            <thead class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white text-sm">
                <tr>
                    <th class="py-2 px-3">#</th>
                    <th class="py-2 px-3">اللاعب</th>
                    <th class="py-2 px-3">السرعة (WPM)</th>
                    <th class="py-2 px-3">الدقة (%)</th>
                    <th class="py-2 px-3">اللعبة</th>
                    <th class="py-2 px-3">📅 التاريخ</th>
                </tr>
            </thead>
            <tbody>`;

        players.forEach((p, i) => {
            const date = new Date(p.created_at).toLocaleDateString('ar-EG');
            html += `
            <tr class="bg-gray-50 dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-gray-700 transition">
                <td class="font-bold text-indigo-600">${i + 1}</td>
                <td>${p.player_name}</td>
                <td class="text-green-600 font-semibold">${p.wpm}</td>
                <td>${p.accuracy}%</td>
                <td>${translateGame(p.game_type)}</td>
                <td class="text-sm text-gray-400">${date}</td>
            </tr>`;
        });

        html += "</tbody></table>";
        content.innerHTML = html;

    } catch (error) {
        console.error(error);
        content.innerHTML = `<p class="text-red-500 text-center">⚠️ حدث خطأ أثناء تحميل النتائج.</p>`;
    }
}


function closeAllPlayers() {
    const modal = document.getElementById('allPlayersModal');
    modal.classList.remove('show');
    setTimeout(() => modal.classList.add('hidden'), 300);
}

</script>

@endsection
