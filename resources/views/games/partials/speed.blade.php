@extends('layouts.game')
@section('title', '⚡ اختبار السرعة')

@section('content')
<div class="flex flex-col lg:flex-row gap-8 items-start justify-center px-6 py-8">

   
    <div id="speedArea"
         class="relative w-full lg:w-3/4 h-[70vh] max-h-[600px]
                bg-gradient-to-b from-indigo-50 via-blue-100 to-indigo-100 
                dark:from-gray-800 dark:via-gray-900 dark:to-gray-950 
                rounded-3xl border-4 border-indigo-400 dark:border-gray-700 
                shadow-[inset_0_0_40px_rgba(255,255,255,0.4),0_0_40px_rgba(0,0,0,0.5)]
                flex flex-col items-center justify-center transition-all duration-700 ease-in-out z-[1] overflow-hidden">

        
        <button id="backBtn"
           class="absolute top-4 left-4 bg-indigo-600 hover:bg-indigo-700 text-white 
                  px-4 py-2 rounded-xl shadow-md text-sm font-semibold transition-transform hover:scale-105">
            ⬅️ رجوع للألعاب
        </button>

       
        <h2 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-300 mb-3">⚡ اختبار السرعة</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-3 text-center text-lg">
            اكتب الجملة التالية بأسرع ما يمكنك ثم احفظ نتيجتك!
        </p>

     
        <p id="quote"
           class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-100 mb-6 text-center select-none leading-relaxed opacity-0 transition-opacity duration-700">
           تحميل الجملة...
        </p>

       
        <div id="coloredText"
             class="text-2xl font-bold text-center mb-6 min-h-[3rem] text-gray-800 dark:text-gray-100 tracking-wide leading-relaxed transition-all duration-150"></div>

      
        <input type="text" id="userInput" placeholder="ابدأ الكتابة هنا..."
               class="w-full max-w-md px-5 py-3 text-center border-2 border-indigo-300 dark:border-gray-700 
                      rounded-xl text-lg font-semibold text-gray-800 dark:text-gray-100
                      bg-white/70 dark:bg-gray-800/80 backdrop-blur-sm focus:ring-4 focus:ring-indigo-400 outline-none
                      transition-all duration-300 shadow-md">

        {{-- ⏱️ الإحصاءات --}}
        <div class="mt-4 text-gray-700 dark:text-gray-300 text-lg">
            ⏱️ الوقت: <span id="timer" class="font-bold text-indigo-600 dark:text-indigo-400">0</span> ث — 
            ⚡ السرعة: <span id="wpm" class="font-bold text-green-600 dark:text-green-400">0</span> كلمة/د
        </div>

       
        <button id="saveBtn"
                class="hidden mt-6 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600
                       text-white px-8 py-3 rounded-2xl font-semibold text-lg shadow-md hover:shadow-lg 
                       transition-all duration-300 transform hover:scale-105">
            💾 حفظ النتيجة
        </button>

      
        <canvas id="confettiCanvas" class="absolute inset-0 pointer-events-none"></canvas>
    </div>

   
    <aside class="w-full lg:w-1/4 bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-6 
                   border border-gray-100 dark:border-gray-700 flex flex-col items-center gap-6 text-center relative z-[2]">
        <h2 class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-400">
            ⚙️ تفاصيل المحاولة
        </h2>
        <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-sm">
            كل مرة ستظهر جملة جديدة لقياس سرعة الكتابة والدقة!
        </p>

        <div class="mt-2 text-5xl font-extrabold text-green-600 dark:text-green-400 drop-shadow-sm">
            <span id="finalWPM">0</span>
            <p class="text-base text-gray-500 dark:text-gray-400 mt-1">كلمة/دقيقة</p>
        </div>
    </aside>
</div>

<script>

// ===============================
const quotes = [
  "التدريب يصنع الإتقان.",
  "العلم نور والجهل ظلام.",
  "النجاح لا يأتي صدفة بل بالاجتهاد والمثابرة.",
  "من جد وجد ومن زرع حصد.",
  "الحياة مغامرة جريئة أو لا شيء.",
  "كل بداية صعبة لكن الإصرار يصنع الفارق.",
  "القوة الحقيقية في ضبط النفس وليس في البطش.",
  "الأمل هو الحلم الذي يصنع المستقبل.",
  "الوقت كالسيف إن لم تقطعه قطعك.",
  "ابدأ من حيث أنت بما تملك لتصل لما تريد."
];

const quoteEl = document.getElementById('quote');
const coloredText = document.getElementById('coloredText');
const input = document.getElementById('userInput');
const timerEl = document.getElementById('timer');
const wpmEl = document.getElementById('wpm');
const saveBtn = document.getElementById('saveBtn');
const finalWPM = document.getElementById('finalWPM');
const canvas = document.getElementById('confettiCanvas');
const ctx = canvas.getContext('2d');
const backBtn = document.getElementById('backBtn');

let startTime = null;
let finished = false;
let timerInterval;
let selectedQuote = "";
let confetti = [];

// ============ Back button ============
backBtn.onclick = () => {
  if (window.parent && window.parent.location) {
    window.parent.location.href = "{{ url('/games') }}";
  } else {
    window.location.href = "{{ url('/games') }}";
  }
};

// ============ Canvas setup ============
function resizeCanvas() {
  canvas.width = canvas.offsetWidth;
  canvas.height = canvas.offsetHeight;
}
window.addEventListener('resize', resizeCanvas);

// ============ New quote ============
function newQuote() {
  quoteEl.classList.remove('opacity-100');
  quoteEl.classList.add('opacity-0');
  setTimeout(() => {
    selectedQuote = quotes[Math.floor(Math.random() * quotes.length)];
    quoteEl.textContent = selectedQuote;
    quoteEl.classList.remove('opacity-0');
    quoteEl.classList.add('opacity-100');
    resetGame();
  }, 300);
}

// ============ Reset ============
function resetGame() {
  finished = false;
  startTime = null;
  clearInterval(timerInterval);
  timerEl.textContent = 0;
  wpmEl.textContent = 0;
  finalWPM.textContent = 0;
  saveBtn.classList.add('hidden');
  input.value = "";
  input.removeAttribute('disabled');
  coloredText.innerHTML = "";
  confetti = [];
  resizeCanvas();
}

// ============ Timer ============
input.addEventListener('focus', () => {
  if (startTime) return;
  startTime = Date.now();
  timerInterval = setInterval(updateTimer, 1000);
});

function updateTimer() {
  const elapsed = (Date.now() - startTime) / 1000;
  timerEl.textContent = Math.round(elapsed);
  const words = input.value.trim().split(/\s+/).filter(Boolean).length;
  const wpm = Math.round(words / (elapsed / 60)) || 0;
  wpmEl.textContent = wpm;
  finalWPM.textContent = wpm;
}

// ============ Typing ============
input.addEventListener('input', () => {
  if (finished) return;
  const typed = input.value;
  const target = selectedQuote;
  let colored = "";
  for (let i = 0; i < target.length; i++) {
    if (i < typed.length) {
      if (typed[i] === target[i]) {
        colored += `<span class='text-green-600 dark:text-green-400'>${target[i]}</span>`;
      } else {
        colored += `<span class='text-red-500 dark:text-red-400 underline decoration-red-400'>${target[i]}</span>`;
      }
    } else {
      colored += `<span class='text-gray-500 dark:text-gray-400 opacity-70'>${target[i]}</span>`;
    }
  }
  coloredText.innerHTML = colored;
  if (typed.trim() === target.trim()) finishGame();
});

// ============ Confetti ============
function triggerConfetti() {
  confetti = Array.from({ length: 80 }, () => ({
    x: Math.random() * canvas.width,
    y: Math.random() * canvas.height / 2,
    size: Math.random() * 6 + 4,
    color: `hsl(${Math.random() * 360}, 80%, 60%)`,
    speedX: (Math.random() - 0.5) * 6,
    speedY: Math.random() * -8 - 4,
    gravity: 0.2,
    alpha: 1
  }));
  animateConfetti();
}

function animateConfetti() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  confetti.forEach(c => {
    c.x += c.speedX;
    c.y += c.speedY;
    c.speedY += c.gravity;
    c.alpha -= 0.01;
    ctx.globalAlpha = c.alpha;
    ctx.fillStyle = c.color;
    ctx.beginPath();
    ctx.arc(c.x, c.y, c.size, 0, Math.PI * 2);
    ctx.fill();
  });
  confetti = confetti.filter(c => c.alpha > 0);
  if (confetti.length) requestAnimationFrame(animateConfetti);
}

// ============ Finish (Fixed Zero WPM) ============
function finishGame() {
  finished = true;
  clearInterval(timerInterval);
  input.setAttribute('disabled', true);

  if (!startTime) startTime = Date.now();
  let elapsed = (Date.now() - startTime) / 1000;

  
  if (elapsed < 3) elapsed = 3;

  const typed = input.value.trim();
  const target = selectedQuote;

  
  let correctChars = 0;
  for (let i = 0; i < typed.length; i++) {
    if (typed[i] === target[i]) correctChars++;
  }


  const wpm = correctChars > 10
    ? Math.round((correctChars / 5) / (elapsed / 60))
    : 0;

  
  const accuracy = target.length > 0
    ? Math.round((correctChars / target.length) * 100)
    : 0;

  wpmEl.textContent = wpm;
  finalWPM.textContent = wpm;
  saveBtn.classList.remove('hidden');
  triggerConfetti();

  Swal.fire({
    icon: 'success',
    title: '🎉 أحسنت!',
    html: `
      أنهيت الجملة في <b>${elapsed.toFixed(1)}</b> ثانية.<br>
      السرعة: <b>${wpm}</b> كلمة/دقيقة<br>
      الدقة: <b>${accuracy}%</b>
    `,
    showCancelButton: true,
    confirmButtonText: '💾 حفظ النتيجة',
    cancelButtonText: '🔁 جملة جديدة',
    confirmButtonColor: '#22c55e',
    cancelButtonColor: '#3b82f6'
  }).then(result => {
    if (result.isConfirmed) saveResult('speed', wpm, accuracy);
    else if (result.dismiss === Swal.DismissReason.cancel) newQuote();
  });
}




// ============ Save Result ============
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
        html: `<b>${playerName}</b><br>سرعتك: ${wpm} كلمة/دقيقة${rankMsg}`,
        confirmButtonText: 'رائع!',
        confirmButtonColor: '#22c55e'
      });
    }
  })
  .catch(() => Swal.fire('⚠️ خطأ', 'تعذر حفظ النتيجة.', 'error'));
}

// ============ Auto start ============
document.addEventListener('DOMContentLoaded', () => {
  resizeCanvas();
  newQuote();
});
setTimeout(() => { if (!selectedQuote) newQuote(); }, 1500);
</script>

<style>
#speedArea {
  background: linear-gradient(180deg, #eef2ff 0%, #e0e7ff 50%, #c7d2fe 100%);
}
</style>
@endsection
