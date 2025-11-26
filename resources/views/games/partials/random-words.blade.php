@extends('layouts.game')
@section('title', '🎯 اختبار الكلمات العشوائية')

@section('content')
<div class="flex flex-col lg:flex-row gap-8 items-start justify-center px-6 py-8">

 
    <div id="wordRaceArea"
         class="relative w-full lg:w-3/4 h-[70vh] max-h-[600px]
                bg-gradient-to-b from-purple-50 via-indigo-100 to-purple-100
                dark:from-gray-800 dark:via-gray-900 dark:to-gray-950
                rounded-3xl border-4 border-purple-400 dark:border-gray-700
                shadow-[inset_0_0_40px_rgba(255,255,255,0.4),0_0_40px_rgba(0,0,0,0.5)]
                flex flex-col items-center justify-center transition-all duration-700 ease-in-out z-[1] overflow-hidden">

   
        <button id="backBtn"
           class="absolute top-4 left-4 bg-purple-600 hover:bg-purple-700 text-white 
                  px-4 py-2 rounded-xl shadow-md text-sm font-semibold transition-transform hover:scale-105">
            ⬅️ رجوع للألعاب
        </button>

       
        <div id="countdown"
             class="hidden absolute inset-0 flex items-center justify-center text-[9rem]
                    font-extrabold text-white drop-shadow-[0_0_40px_rgba(0,0,0,0.8)] select-none z-20">
        </div>

      
        <h1 id="currentWord"
            class="text-5xl font-extrabold text-purple-700 dark:text-purple-300 select-none drop-shadow-md mb-8 transition-all duration-200">
            تحميل...
        </h1>

     
        <input type="text" id="wordInput" placeholder="اكتب الكلمة هنا..." 
               disabled
               class="w-full max-w-md px-6 py-3 text-center border-2 border-purple-300 dark:border-gray-700 
                      rounded-xl text-lg font-semibold text-gray-800 dark:text-gray-100
                      bg-white/70 dark:bg-gray-800/80 backdrop-blur-sm focus:ring-4 focus:ring-purple-400 outline-none
                      transition-all duration-300 shadow-md">

        {{-- 💾 زر الحفظ --}}
        <button id="saveBtn"
                class="hidden mt-8 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600
                       text-white px-8 py-3 rounded-2xl font-semibold text-lg shadow-md hover:shadow-lg 
                       transition-all duration-300 transform hover:scale-105">
            💾 حفظ النتيجة
        </button>

      
        <canvas id="sparkCanvas" class="absolute inset-0 pointer-events-none"></canvas>
    </div>

  
    <aside class="w-full lg:w-1/4 bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-6 
                   border border-gray-100 dark:border-gray-700 flex flex-col items-center gap-6 text-center relative z-[2]">
        <h2 class="text-2xl font-extrabold text-purple-700 dark:text-purple-400">
            🎲 اختبار الكلمات
        </h2>
        <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-sm">
            اكتب الكلمة الظاهرة بسرعة ودقة خلال الوقت المحدد ⏱️
        </p>

        <div class="mt-2 text-5xl font-extrabold text-green-600 dark:text-green-400 drop-shadow-sm">
            <span id="score">0</span>
            <p class="text-base text-gray-500 dark:text-gray-400 mt-1">النقاط</p>
        </div>

        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 drop-shadow-sm">
            <span id="timeLeft">30</span> ثانية
        </div>
    </aside>
</div>

{{-- 🔊 أصوات --}}
<audio id="correctSound" preload="auto">
  <source src="{{ asset('sounds/notify.mp3') }}" type="audio/mpeg">
</audio>
<audio id="endSound" preload="auto">
  <source src="{{ asset('sounds/end.mp3') }}" type="audio/mpeg">
</audio>

<script>

// ================================
const words = [
    "جامعة", "برمجة", "مهارة", "خوارزمية", "ذكاء", "حاسوب", "نظام",
    "تعليم", "تفوق", "ابداع", "طالب", "معلومة", "كتابة", "سرعة",
    "تحدي", "علم", "لغة", "مشروع", "ابتكار", "اختبار"
];

// Elements
const currentWordEl = document.getElementById('currentWord');
const wordInput = document.getElementById('wordInput');
const scoreEl = document.getElementById('score');
const timeLeftEl = document.getElementById('timeLeft');
const countdownEl = document.getElementById('countdown');
const saveBtn = document.getElementById('saveBtn');
const sparkCanvas = document.getElementById('sparkCanvas');
const backBtn = document.getElementById('backBtn');
const ctx = sparkCanvas.getContext('2d');
let width, height;

// Variables
let currentWord = "";
let score = 0;
let timeLeft = 30;
let timerInterval, started = false;
let sparks = [];

// ================ Back button ================
backBtn.onclick = () => {
  if (window.parent && window.parent.location) {
    window.parent.location.href = "{{ url('/games') }}";
  } else {
    window.location.href = "{{ url('/games') }}";
  }
};

// ================ Canvas setup ================
function resizeCanvas() {
    width = sparkCanvas.width = sparkCanvas.offsetWidth;
    height = sparkCanvas.height = sparkCanvas.offsetHeight;
}
window.addEventListener('resize', resizeCanvas);

// ================ Countdown ================
function startCountdown() {
    let count = 3;
    countdownEl.textContent = count;
    countdownEl.classList.remove('hidden');
    const timer = setInterval(() => {
        count--;
        countdownEl.textContent = count > 0 ? count : 'ابدأ!';
        if (count < 0) {
            clearInterval(timer);
            countdownEl.classList.add('hidden');
            startGame();
        }
    }, 1000);
}

// ================ Start Game ================
function startGame() {
    started = true;
    score = 0;
    scoreEl.textContent = 0;
    timeLeft = 30;
    nextWord();
    wordInput.removeAttribute('disabled');
    wordInput.focus();
    resizeCanvas();

    timerInterval = setInterval(() => {
        timeLeft--;
        timeLeftEl.textContent = timeLeft;
        if (timeLeft <= 0) endGame();
    }, 1000);
}

// ================ New Word ================
function nextWord() {
    currentWord = words[Math.floor(Math.random() * words.length)];
    currentWordEl.textContent = currentWord;
    currentWordEl.classList.add('scale-110');
    setTimeout(() => currentWordEl.classList.remove('scale-110'), 150);
}

// ================ Sparks Effect ================
function triggerSparks() {
    const x = Math.random() * width;
    const y = Math.random() * (height / 2) + height / 4;
    for (let i = 0; i < 30; i++) {
        sparks.push({
            x, y,
            size: Math.random() * 4 + 2,
            color: `hsl(${Math.random() * 360}, 80%, 60%)`,
            vx: (Math.random() - 0.5) * 6,
            vy: (Math.random() - 0.5) * 6,
            alpha: 1
        });
    }
}

function animateSparks() {
    ctx.clearRect(0, 0, width, height);
    sparks.forEach(s => {
        s.x += s.vx;
        s.y += s.vy;
        s.alpha -= 0.02;
        ctx.globalAlpha = s.alpha;
        ctx.fillStyle = s.color;
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.size, 0, Math.PI * 2);
        ctx.fill();
    });
    sparks = sparks.filter(s => s.alpha > 0);
    requestAnimationFrame(animateSparks);
}

// ================ Typing Input ================
wordInput.addEventListener('input', () => {
    if (!started) return;
    const typed = wordInput.value.trim();
    if (typed === currentWord) {
        score++;
        scoreEl.textContent = score;
        wordInput.value = "";
        nextWord();
        triggerSparks();
        const sound = document.getElementById('correctSound');
        sound.play().catch(() => {});
    }
});

// ================ End Game (Unified & Fixed) ================
function endGame() {
    started = false;
    clearInterval(timerInterval);
    wordInput.setAttribute('disabled', true);
    saveBtn.classList.remove('hidden');
    document.getElementById('endSound').play().catch(() => {});


    const totalTime = 30; 
    const totalChars = score * 5; 
    const words = totalChars / 5;
    const wpm = Math.round(words / (totalTime / 60)) || 0; 
    const accuracy = Math.min(100, Math.round((score / words.length) * 100) || 100);

   
    Swal.fire({
        icon: 'success',
        title: '🎉 انتهت اللعبة!',
        html: `
            عدد الكلمات الصحيحة: <b>${score}</b><br>
            السرعة: <b>${wpm}</b> كلمة/دقيقة<br>
            الدقة: <b>${accuracy}%</b>
        `,
        confirmButtonText: '💾 حفظ النتيجة',
        confirmButtonColor: '#9333ea'
    }).then(res => {
        if (res.isConfirmed) {
            saveResult('random-words', wpm, accuracy);
        }
    });
}



// ================ Save Result ================
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
            const rankMsg = data.rank ? `<br>🏆 ترتيبك الحالي: <b>${data.rank}</b>` : '';
            Swal.fire({
                icon: 'success',
                title: '✅ تم حفظ النتيجة بنجاح!',
                html: `<b>${playerName}</b><br>سرعتك: ${wpm} كلمة/دقيقة<br>دقتك: ${accuracy}%${rankMsg}`,
                confirmButtonText: 'رائع!',
                confirmButtonColor: '#22c55e'
            });
        }
    })
    .catch(() => Swal.fire('⚠️ خطأ', 'تعذر حفظ النتيجة.', 'error'));
}

// ================ Auto Start ================
animateSparks();
document.addEventListener('DOMContentLoaded', () => setTimeout(startCountdown, 600));
setTimeout(() => { if (!started) startCountdown(); }, 1500);
</script>

<style>
#wordRaceArea {
  background: linear-gradient(180deg, #f5e1ff 0%, #ede9fe 50%, #ddd6fe 100%);
}
#countdown {
  font-size: 9rem;
  color: white;
  text-shadow: 0 0 30px rgba(0,0,0,0.6);
}
.scale-110 { transform: scale(1.1); transition: transform 0.15s; }
</style>
@endsection
