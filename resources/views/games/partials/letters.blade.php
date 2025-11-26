<div class="relative w-[95%] max-w-6xl mx-auto flex flex-col lg:flex-row gap-8 py-6">

    <!-- Game Area -->
    <div id="gameArea"
         class="relative w-full lg:w-3/4 h-[80vh] max-h-[650px]
                bg-gradient-to-b from-sky-200 via-sky-100 to-sky-300 
                dark:from-gray-800 dark:via-gray-900 dark:to-gray-950 
                overflow-hidden rounded-3xl border-4 border-sky-400 dark:border-gray-700 
                shadow-[inset_0_0_40px_rgba(255,255,255,0.4),0_0_40px_rgba(0,0,0,0.5)]
                flex items-center justify-center transition-all duration-700 ease-in-out z-[1]">

{{-- 🔙 زر الرجوع --}}
        <a href="{{ url('/games') }}"
           class="absolute top-4 left-4 bg-amber-600 hover:bg-amber-700 text-white 
                  px-4 py-2 rounded-xl shadow-md text-sm font-semibold transition-transform hover:scale-105">
            ⬅️ رجوع للألعاب
        </a>


        <!-- Loading Text -->
        <p id="loadingText" 
           class="text-4xl md:text-5xl font-extrabold text-gray-700 dark:text-gray-200 animate-pulse drop-shadow-lg select-none">
            ⏳ جارٍ بدء اللعبة...
        </p>

        <!-- Time Progress -->
        <div id="progressBarContainer" 
             class="absolute bottom-6 left-1/2 -translate-x-1/2 w-[80%] h-4 
                    bg-gray-300 dark:bg-gray-600 rounded-full overflow-hidden shadow-inner z-[5]">
            <div id="progressBar" 
                 class="h-full bg-green-500 rounded-full transition-all duration-200 ease-linear" 
                 style="width: 100%;"></div>
        </div>
    </div>

    <!-- Side Panel -->
    <aside class="w-full lg:w-1/4 bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-6 
                   border border-gray-100 dark:border-gray-700 flex flex-col items-center gap-6 text-center relative z-[2]">
        <h2 class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-300">
            🎈 لعبة الحروف العربية
        </h2>
        <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-sm">
            اضغط على الحرف داخل البالون قبل أن يختفي! كل تفجير يمنحك نقطة 💥
        </p>

        <div class="text-lg font-bold text-gray-800 dark:text-gray-200">
            👤 <span id="playerNameDisplay">اللاعب</span>
        </div>

        <div class="mt-3 text-5xl font-extrabold text-green-600 dark:text-green-400 drop-shadow-sm">
            <span id="score">0</span>
            <p class="text-base text-gray-500 dark:text-gray-400 mt-1">النقاط</p>
        </div>

        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 drop-shadow-sm">
            <span id="timeLeft">60</span> ثانية
        </div>

        <button id="saveBtn"
                class="hidden mt-6 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600
                       text-white px-8 py-3 rounded-2xl font-semibold text-lg shadow-md hover:shadow-lg 
                       transition-all duration-300 transform hover:scale-105">
            💾 حفظ النتيجة
        </button>
    </aside>
</div>

<!-- Pop Sound -->
<audio id="popSound" preload="auto">
  <source src="{{ asset('sounds/pop.wav') }}" type="audio/wav">
</audio>

<script>
// ====================================
// Simple Arabic Letters Game (fixed)
// ====================================
const arabicLetters = 'أبجدهوزحطيكلمنسعفصقرشتثخذضظغ'.split('');
let score = 0, timeLeft = 60, started = false, running = false;
let spawnInterval, timerInterval;
let activeBalloons = [];

document.addEventListener('DOMContentLoaded', () => {
    const playerName = localStorage.getItem('playerName') || 'لاعب';
    document.getElementById('playerNameDisplay').textContent = playerName;
    // start directly after small delay
    setTimeout(() => startGame(), 600);
});

// also auto-start even if DOMContentLoaded missed (for AJAX)
setTimeout(() => {
    if (!started) startGame();
}, 1500);


// Start Game
function startGame() {
    if (started) return;
    started = true;
    const loading = document.getElementById('loadingText');
    if (loading) loading.remove();
    startCountdown(() => {
        running = true;
        spawnInterval = setInterval(createBalloon, 900);
        createBalloon();
        startTimer();
    });
}

// Countdown
function startCountdown(callback) {
    const area = document.getElementById('gameArea');
    const cd = document.createElement('div');
    cd.id = 'countdown';
    cd.className = 'absolute text-[8rem] font-extrabold text-white select-none drop-shadow-[0_0_40px_rgba(0,0,0,0.8)]';
    cd.style.top = '50%'; cd.style.left = '50%'; cd.style.transform = 'translate(-50%,-50%)';
    area.appendChild(cd);
    let count = 3;
    const timer = setInterval(() => {
        cd.textContent = count > 0 ? count : 'ابدأ!';
        if (count === 0) cd.classList.add('text-green-400');
        if (count < 0) {
            clearInterval(timer);
            cd.remove();
            callback();
        }
        count--;
    }, 1000);
}

// Create balloon
function createBalloon() {
    if (!running) return;
    const letter = arabicLetters[Math.floor(Math.random() * arabicLetters.length)];
    const balloon = document.createElement('div');
    balloon.innerHTML = `<span>${letter}</span>`;
    balloon.className = 'balloon flex items-center justify-center text-5xl font-extrabold text-white select-none';
    const hue = Math.floor(Math.random() * 360);
    balloon.style.background = `radial-gradient(circle at 30% 30%, hsl(${hue}, 90%, 70%), hsl(${hue}, 90%, 50%))`;
    balloon.style.width = balloon.style.height = `${Math.random() * 70 + 100}px`;
    balloon.style.left = `${Math.random() * (document.getElementById('gameArea').clientWidth - 120)}px`;
    balloon.style.bottom = `-130px`;
    balloon.style.position = 'absolute';
    balloon.style.borderRadius = '50%';
    document.getElementById('gameArea').appendChild(balloon);
    activeBalloons.push(balloon);

    const drift = Math.random() * 100 - 50;
    balloon.animate(
        [{ transform: `translate(0,0)` }, { transform: `translate(${drift}px,-900px)` }],
        { duration: 7000, easing: 'ease-in-out', fill: 'forwards' }
    );

    setTimeout(() => {
        if (document.body.contains(balloon)) {
            balloon.remove();
            activeBalloons = activeBalloons.filter(b => b !== balloon);
        }
    }, 7000);
}

// Pop balloon
function popBalloon(balloon) {
    const sound = document.getElementById('popSound');
    sound.currentTime = 0;
    sound.play().catch(() => {});
    balloon.remove();
    activeBalloons = activeBalloons.filter(b => b !== balloon);
    score++;
    document.getElementById('score').textContent = score;
}

// Keyboard input
document.addEventListener('keydown', e => {
    if (!running) return;
    const pressed = e.key.toUpperCase();
    for (const balloon of activeBalloons) {
        const letter = balloon.textContent.toUpperCase();
        if (pressed === letter) {
            popBalloon(balloon);
            break;
        }
    }
});

// Click / Touch
document.getElementById('gameArea').addEventListener('click', e => {
    if (e.target.classList.contains('balloon')) popBalloon(e.target);
});
document.getElementById('gameArea').addEventListener('touchstart', e => {
    if (e.target.classList.contains('balloon')) popBalloon(e.target);
});

// Timer
function startTimer() {
    const progress = document.getElementById('progressBar');
    timerInterval = setInterval(() => {
        timeLeft--;
        document.getElementById('timeLeft').textContent = timeLeft;
        progress.style.width = `${(timeLeft / 60) * 100}%`;
        if (timeLeft <= 0) endGame();
    }, 1000);
}

// End Game
function endGame() {
    if (!running) return;
    running = false;
    clearInterval(spawnInterval);
    clearInterval(timerInterval);
    document.querySelectorAll('.balloon').forEach(b => b.remove());

 
    const totalTime = 60 - timeLeft; 
    const totalLetters = score; 
    const totalChars = totalLetters * 5; 
    const wpm = Math.round((totalChars / 5) / (totalTime / 60)) || 0; 
    const accuracy = 95; 

    Swal.fire({
        icon: 'success',
        title: '🎉 انتهت اللعبة!',
        html: `
            <h3>مجموع نقاطك: <b>${score}</b></h3>
            <p>السرعة: <b>${wpm}</b> كلمة/دقيقة</p>
            <p>الدقة: <b>${accuracy}%</b></p>
        `,
        confirmButtonText: '💾 حفظ النتيجة',
        cancelButtonText: '🔁 إعادة اللعب',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#6366f1'
    }).then(res => {
        if (res.isConfirmed) saveResult('letters', wpm, accuracy);
        else window.location.reload();
    });
}


// Save result (same logic as race)
function saveResult(type, wpm, accuracy) {
    const playerName = localStorage.getItem('playerName') || 'لاعب';
    fetch("{{ route('games.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content
        },
        body: JSON.stringify({ player_name: playerName, wpm, accuracy, game_type: type })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '✅ تم الحفظ بنجاح',
                html: `<b>${playerName}</b> — نتيجتك ${wpm / 10} نقطة`,
                confirmButtonText: 'رائع!',
                confirmButtonColor: '#22c55e'
            });
        } else {
            Swal.fire('⚠️ خطأ', 'لم يتم حفظ النتيجة.', 'error');
        }
    })
    .catch(() => Swal.fire('⚠️ خطأ', 'تعذر الاتصال بالخادم.', 'error'));
}
</script>

<style>
.balloon span { text-shadow: 0 2px 8px rgba(0,0,0,0.25); }
#countdown { transition: transform 0.4s ease-in-out; }
</style>
