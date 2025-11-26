@extends('layouts.game')
@section('title', '🏁 سباق السيارات - اختبار الطباعة')

@section('content')
<div class="flex flex-col lg:flex-row gap-8 items-start justify-center px-6 py-8">

  
    <div id="raceArea"
         class="relative w-full lg:w-3/4 h-[70vh] max-h-[600px]
                bg-gradient-to-b from-amber-50 via-yellow-100 to-orange-100 
                dark:from-gray-800 dark:via-gray-900 dark:to-gray-950 
                rounded-3xl border-4 border-amber-400 dark:border-gray-700 
                shadow-[inset_0_0_40px_rgba(255,255,255,0.4),0_0_40px_rgba(0,0,0,0.5)]
                p-8 flex flex-col justify-center transition-all duration-700 ease-in-out z-[1]">

     
        <a href="{{ url('/games') }}"
           class="absolute top-4 left-4 bg-amber-600 hover:bg-amber-700 text-white 
                  px-4 py-2 rounded-xl shadow-md text-sm font-semibold transition-transform hover:scale-105">
            ⬅️ رجوع للألعاب
        </a>

       
        <div id="countdown"
             class="hidden absolute inset-0 flex items-center justify-center text-[10rem] 
                    font-extrabold text-white drop-shadow-[0_0_40px_rgba(0,0,0,0.8)] select-none z-20">
        </div>

        
        <div class="relative bg-gray-300 dark:bg-gray-700 h-6 rounded-full overflow-hidden mb-6 mt-8 shadow-inner">
            <div class="absolute left-0 top-0 w-full h-full bg-[url('/images/track-pattern.png')] opacity-10"></div>
        </div>

       
        <div id="cars" class="space-y-4 mb-8">
            <div class="relative h-10">
                <img src="{{ asset('images/car-red.png') }}" id="playerCar" class="absolute left-0 top-0 w-12 transition-all duration-300">
                <div class="absolute right-0 top-2 text-sm font-semibold text-gray-700 dark:text-gray-300">🚗 أنت</div>
            </div>
            <div class="relative h-10">
                <img src="{{ asset('images/car-blue.png') }}" id="botCar1" class="absolute left-0 top-0 w-12 transition-all duration-300">
                <div class="absolute right-0 top-2 text-sm text-gray-500 dark:text-gray-400">المنافس 1</div>
            </div>
            <div class="relative h-10">
                <img src="{{ asset('images/car-green.png') }}" id="botCar2" class="absolute left-0 top-0 w-12 transition-all duration-300">
                <div class="absolute right-0 top-2 text-sm text-gray-500 dark:text-gray-400">المنافس 2</div>
            </div>
        </div>

     
        <p id="raceText"
           class="text-xl md:text-2xl font-semibold text-gray-800 dark:text-gray-200 text-center mb-6 leading-relaxed">
            السباق سيبدأ بعد العد التنازلي، استعد للانطلاق! 🏎️
        </p>

       
        <input type="text" id="raceInput" placeholder="ابدأ الكتابة هنا بعد الإشارة..."
               disabled
               class="w-full px-5 py-3 text-center border-2 border-amber-300 dark:border-gray-700 
                      rounded-xl text-lg font-semibold text-gray-800 dark:text-gray-100
                      bg-white/70 dark:bg-gray-800/80 backdrop-blur-sm focus:ring-4 focus:ring-amber-400 outline-none
                      transition-all duration-300 shadow-md">

     
        <button id="saveBtn"
                class="hidden mt-6 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600
                       text-white px-8 py-3 rounded-2xl font-semibold text-lg shadow-md hover:shadow-lg 
                       transition-all duration-300 transform hover:scale-105 mx-auto">
            💾 حفظ النتيجة
        </button>
    </div>

    {{-- 📊 لوحة البيانات (يسار) --}}
    <aside class="w-full lg:w-1/4 bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-6 
                   border border-gray-100 dark:border-gray-700 flex flex-col items-center gap-6 text-center relative z-[2]">
        <h2 class="text-2xl font-extrabold text-amber-600 dark:text-amber-400">
            🏁 سباق السيارات
        </h2>
        <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-sm">
            اكتب النص بأسرع ما يمكن لتتفوّق على خصومك قبل انتهاء الوقت! ⏱️
        </p>

        <div class="mt-2 text-5xl font-extrabold text-green-600 dark:text-green-400 drop-shadow-sm">
            <span id="score">0</span>
            <p class="text-base text-gray-500 dark:text-gray-400 mt-1">عدد الأحرف الصحيحة</p>
        </div>

        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 drop-shadow-sm">
            <span id="timeLeft">60</span> ثانية
        </div>
    </aside>
</div>

<script>



// DOM Elements
const raceTextEl = document.getElementById('raceText');
const raceInput = document.getElementById('raceInput');
const playerCar = document.getElementById('playerCar');
const botCar1 = document.getElementById('botCar1');
const botCar2 = document.getElementById('botCar2');
const saveBtn = document.getElementById('saveBtn');
const countdown = document.getElementById('countdown');
const scoreEl = document.getElementById('score');
const timeLeftEl = document.getElementById('timeLeft');

// Text samples for typing
const sampleTexts = [
    "السرعة في الطباعة تجعلك تتفوق في السباق نحو النجاح.",
    "كن الأسرع في الكتابة لتحصل على المركز الأول في السباق.",
    "اكتب النص بسرعة ودقة لتصل إلى خط النهاية قبل الجميع!"
];

// Game variables
let selectedText = sampleTexts[Math.floor(Math.random() * sampleTexts.length)];
let score = 0, timeLeft = 60, timerInterval, started = false;
let bots = { bot1: 0, bot2: 0 };


// ===============================
function startCountdown() {
    let count = 3;
    countdown.textContent = count;
    countdown.classList.remove('hidden');
    const timer = setInterval(() => {
        count--;
        countdown.textContent = count > 0 ? count : 'انطلق!';
        if (count < 0) {
            clearInterval(timer);
            countdown.classList.add('hidden');
            startRace();
        }
    }, 1000);
}


// ===============================
function startRace() {
    raceTextEl.textContent = selectedText;
    raceInput.removeAttribute('disabled');
    raceInput.focus();
    started = true;
    startTimer();
    moveBots();
}


// ===============================
function startTimer() {
    timerInterval = setInterval(() => {
        timeLeft--;
        timeLeftEl.textContent = timeLeft;
        if (timeLeft <= 0) finishRace();
    }, 1000);
}


function moveBots() {
    const bot1Interval = setInterval(() => {
        if (timeLeft <= 0) return clearInterval(bot1Interval);
        bots.bot1 = Math.min(bots.bot1 + Math.random() * 5, 100);
        botCar1.style.left = bots.bot1 + "%";
    }, 900);

    const bot2Interval = setInterval(() => {
        if (timeLeft <= 0) return clearInterval(bot2Interval);
        bots.bot2 = Math.min(bots.bot2 + Math.random() * 4.5, 100);
        botCar2.style.left = bots.bot2 + "%";
    }, 1000);
}


raceInput.addEventListener('input', () => {
    if (!started) return;
    const typed = raceInput.value;
    const correct = typed.split('').filter((c, i) => c === selectedText[i]).length;
    score = correct;
    const progress = Math.min((correct / selectedText.length) * 100, 100);
    playerCar.style.left = progress + "%";
    scoreEl.textContent = correct;
    if (progress >= 100) finishRace();
});


function finishRace() {
    clearInterval(timerInterval);
    raceInput.setAttribute('disabled', true);
    saveBtn.classList.remove('hidden');

   
    let position = "الأول!";
    const playerPos = parseFloat(playerCar.style.left) || 0;
    if (playerPos < bots.bot1 && playerPos < bots.bot2) position = "الثالث";
    else if (playerPos < Math.max(bots.bot1, bots.bot2)) position = "الثاني";

    
    const elapsed = 60 - timeLeft; 
    const totalChars = score; 
    const words = totalChars / 5; 
    const wpm = Math.round(words / (elapsed / 60)) || 0; 
    const accuracy = Math.floor((score / selectedText.length) * 100);

  
    Swal.fire({
        icon: 'success',
        title: 'انتهى السباق! 🏁',
        html: `
            <b>عدد الأحرف الصحيحة:</b> ${score}<br>
            <b>الدقة:</b> ${accuracy}%<br>
            <b>السرعة:</b> ${wpm} كلمة/دقيقة<br>
            <b>ترتيبك:</b> ${position}
        `,
        confirmButtonText: 'تم',
        confirmButtonColor: '#22c55e'
    });

    
    window.parent.postMessage({
        type: "saveGameResult",
        gameType: "race",
        wpm: wpm,
        accuracy: accuracy
    }, "*");
}


// ===============================
// 💾 Manual save button
// ===============================
saveBtn.onclick = () => {
    const accuracy = Math.floor((score / selectedText.length) * 100);
    const speed = Math.floor(score / 5);
    Swal.fire({
        icon: 'question',
        title: 'هل ترغب بحفظ نتيجتك؟',
        showCancelButton: true,
        confirmButtonText: 'نعم، احفظها',
        cancelButtonText: 'لاحقًا',
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#9ca3af'
    }).then(result => {
        if (result.isConfirmed) {
            // Use global saveResult from games.scripts
            if (typeof saveResult === 'function') {
                saveResult('race', speed, accuracy);
            } else {
                window.parent.postMessage({
                    type: "saveGameResult",
                    gameType: "race",
                    wpm: speed,
                    accuracy: accuracy
                }, "*");
            }
        }
    });
};

// ===============================
// 🚀 Start countdown after load
// ===============================
setTimeout(startCountdown, 1000);
</script>


<style>
#raceArea {
  background: linear-gradient(180deg, #fff8e1 0%, #ffeab6 50%, #ffd580 100%);
  box-shadow: 0 0 50px rgba(0,0,0,0.25) inset;
  border-radius: 1rem;
}
#playerCar, #botCar1, #botCar2 {
  transition: left 0.3s linear;
}
#countdown {
  font-size: 9rem;
  color: white;
  text-shadow: 0 0 30px rgba(0,0,0,0.6);
}
</style>
@endsection
