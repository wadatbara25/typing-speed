<div class="flex flex-col lg:flex-row gap-8 items-start justify-center px-6 py-8">

    <!-- Typing Area -->
    <div id="typingArea"
         class="relative w-full lg:w-3/4 h-[75vh] max-h-[620px]
                bg-gradient-to-b from-sky-50 via-cyan-100 to-blue-100 
                dark:from-gray-800 dark:via-gray-900 dark:to-gray-950 
                rounded-3xl border-4 border-sky-400 dark:border-gray-700 
                shadow-[inset_0_0_40px_rgba(255,255,255,0.4),0_0_40px_rgba(0,0,0,0.5)]
                flex flex-col items-center justify-center p-8 overflow-hidden">

        <!-- Back Button -->
        <button id="backBtn"
           class="absolute top-4 left-4 bg-sky-600 hover:bg-sky-700 text-white 
                  px-4 py-2 rounded-xl shadow-md text-sm font-semibold transition-transform hover:scale-105 z-50">
            ⬅️ رجوع للألعاب
        </button>

        <!-- Title -->
        <h2 class="text-3xl font-extrabold text-sky-700 dark:text-sky-300 mb-4">
            ⌨️ اختبار سرعة الكتابة بالعربية
        </h2>

        <p class="text-gray-600 dark:text-gray-400 mb-3 text-center text-lg">
            بعد العد التنازلي ⏳ اكتب النص بسرعة وبدقة لتحقق أفضل نتيجة 🎯
        </p>

        <!-- Text to type -->
        <div id="paragraph"
             class="text-xl md:text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6 
                    bg-white/60 dark:bg-gray-800/70 rounded-2xl px-6 py-4 shadow-inner text-center leading-relaxed 
                    select-none max-w-3xl transition-all duration-500 min-h-[120px]">
            تحميل النص...
        </div>

        <!-- Input -->
        <textarea id="userInput" rows="5" placeholder="ابدأ الكتابة هنا..."
                  disabled
                  class="w-full max-w-3xl px-5 py-3 text-center border-2 border-sky-300 dark:border-gray-700 
                         rounded-xl text-lg font-semibold text-gray-800 dark:text-gray-100
                         bg-white/70 dark:bg-gray-800/80 backdrop-blur-sm focus:ring-4 focus:ring-sky-400 outline-none
                         transition-all duration-300 shadow-md resize-none"></textarea>

        <!-- Stats -->
        <div class="mt-4 text-gray-700 dark:text-gray-300 text-lg">
            ⏱️ الوقت: <span id="timer" class="font-bold text-sky-600 dark:text-sky-400">0</span> ث — 
            ⚡ السرعة: <span id="wpm" class="font-bold text-green-600 dark:text-green-400">0</span> كلمة/د — 
            🎯 الدقة: <span id="accuracy" class="font-bold text-indigo-600 dark:text-indigo-400">0</span>%
        </div>

        <!-- Restart -->
        <button id="restartBtn"
                class="hidden mt-6 bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600
                       text-white px-8 py-3 rounded-2xl font-semibold text-lg shadow-md hover:shadow-lg 
                       transition-all duration-300 transform hover:scale-105">
            🔁 إعادة اللعب
        </button>
    </div>

    <!-- Scoreboard -->
    <aside class="w-full lg:w-1/4 bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-6 
                   border border-gray-100 dark:border-gray-700 flex flex-col items-center gap-6 text-center relative z-[2]">
        <h2 class="text-2xl font-extrabold text-sky-700 dark:text-sky-400">
            📋 نتائجك الحالية
        </h2>
        <div class="mt-2 text-5xl font-extrabold text-green-600 dark:text-green-400 drop-shadow-sm">
            <span id="finalWPM">0</span>
            <p class="text-base text-gray-500 dark:text-gray-400 mt-1">كلمة/دقيقة</p>
        </div>
    </aside>
</div>

<audio id="successSound" preload="auto">
  <source src="{{ asset('sounds/success.mp3') }}" type="audio/mpeg">
</audio>

<script>
// =================================
// Arabic Typing Game (Enhanced)
// =================================
const texts = [
  "اللغة العربية بحر واسع من الجمال والبيان، ومن يتقنها يمتلك مفتاح التعبير عن الفكر والمشاعر بعمق وسلاسة.",
  "الطباعة السريعة ليست مجرد مهارة تقنية، بل وسيلة لزيادة الإنتاجية وتوفير الوقت في كل مجالات الحياة.",
  "عندما تتقن الكتابة دون النظر إلى لوحة المفاتيح، تصبح أفكارك أكثر انسيابية وسرعة في الوصول إلى النص.",
  "كل دقيقة تدريب على الكتابة تقربك من الإتقان، فلا تتوقف عن المحاولة والممارسة.",
  "تعلم الكتابة بالعربية يعزز من قدرتك على التفكير المنظم والتواصل الفعال."
];

let selectedText = "";
let startTime = null;
let timerInterval;
let finished = false;

// Elements
const paragraph = document.getElementById('paragraph');
const input = document.getElementById('userInput');
const timerEl = document.getElementById('timer');
const wpmEl = document.getElementById('wpm');
const accuracyEl = document.getElementById('accuracy');
const finalWPM = document.getElementById('finalWPM');
const restartBtn = document.getElementById('restartBtn');
const successSound = document.getElementById('successSound');
const typingArea = document.getElementById('typingArea');
const backBtn = document.getElementById('backBtn');

// ========================
// Back button fix
// ========================
backBtn.onclick = () => {
  if (window.parent && window.parent.location) {
    window.parent.location.href = "{{ url('/games') }}";
  } else {
    window.location.href = "{{ url('/games') }}";
  }
};

// ========================
// Load random text
// ========================
function loadText() {
  selectedText = texts[Math.floor(Math.random() * texts.length)];
  paragraph.innerHTML = selectedText.split('').map(ch => `<span>${ch}</span>`).join('');
}

// ========================
// Countdown before start
// ========================
function startCountdown() {
  const cd = document.createElement('div');
  cd.id = "countdown";
  cd.className = "absolute text-[8rem] font-extrabold text-white drop-shadow-[0_0_40px_rgba(0,0,0,0.8)] select-none z-50";
  cd.style.top = "50%";
  cd.style.left = "50%";
  cd.style.transform = "translate(-50%, -50%)";
  cd.textContent = "3";
  typingArea.appendChild(cd);

  let c = 3;
  const t = setInterval(() => {
    cd.textContent = c > 0 ? c : "ابدأ!";
    if (c === 0) cd.classList.add('text-green-400');
    if (c < 0) {
      clearInterval(t);
      cd.remove();
      startGame();
    }
    c--;
  }, 1000);
}

// ========================
// Start game logic
// ========================
function startGame() {
  input.disabled = false;
  input.focus();
  startTime = Date.now();
  timerInterval = setInterval(updateStats, 1000);
}

// ========================
// Update stats
// ========================
function updateStats() {
  if (finished) return;
  const elapsed = (Date.now() - startTime) / 1000;
  timerEl.textContent = Math.round(elapsed);
  const words = input.value.trim().split(/\s+/).filter(Boolean).length;
  const wpm = Math.round(words / (elapsed / 60)) || 0;
  wpmEl.textContent = wpm;
  finalWPM.textContent = wpm;
}

// ========================
// Typing logic
// ========================
input.addEventListener('input', handleTyping);
function handleTyping() {
  if (finished) return;

  const typed = input.value;
  const spans = paragraph.querySelectorAll('span');
  let correct = 0;

  for (let i = 0; i < spans.length; i++) {
    if (typed[i] == null) {
      spans[i].className = '';
    } else if (typed[i] === selectedText[i]) {
      spans[i].className = 'text-green-600 dark:text-green-400';
      correct++;
    } else {
      spans[i].className = 'text-red-500 dark:text-red-400';
    }
  }

  const accuracy = Math.round((correct / selectedText.length) * 100);
  accuracyEl.textContent = accuracy;

  if (typed.trim() === selectedText.trim()) finishGame();
}

// ========================
// Finish game
// ========================
function finishGame() {
  finished = true;
  clearInterval(timerInterval);
  input.disabled = true;
  successSound.play().catch(() => {});
  paragraph.classList.add('ring-4', 'ring-green-400', 'scale-[1.02]', 'transition-all');

  const elapsed = (Date.now() - startTime) / 1000;

  const cleanText = input.value.replace(/\s+/g, ''); 
  const charCount = cleanText.length;
  const words = charCount / 5; 
  const wpm = Math.round(words / (elapsed / 60)) || 0;

  const accuracy = parseInt(accuracyEl.textContent);

  Swal.fire({
    title: '🎉 ممتاز!',
    html: `
      أنهيت النص في <b>${elapsed.toFixed(1)}</b> ثانية.<br>
      السرعة: <b>${wpm}</b> كلمة/دقيقة<br>
      الدقة: <b>${accuracy}%</b>
    `,
    icon: 'success',
    confirmButtonText: 'تم',
    confirmButtonColor: '#22c55e'
  });

  saveResult('arabic-typing', wpm, accuracy);

  restartBtn.classList.remove('hidden');
}


// ========================
// Save result + rank alert
// ========================
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
        title: '✅ تم حفظ نتيجتك بنجاح!',
        html: `<b>${playerName}</b><br>سرعتك: ${wpm} كلمة/دقيقة<br>دقتك: ${accuracy}%${rankMsg}`,
        confirmButtonText: 'رائع!',
        confirmButtonColor: '#22c55e'
      });
    }
  })
  .catch(() => Swal.fire('⚠️ خطأ', 'تعذر حفظ النتيجة.', 'error'));
}

// ========================
// Restart logic
// ========================
restartBtn.onclick = restartGame;
function restartGame() {
  finished = false;
  clearInterval(timerInterval);
  input.value = "";
  input.disabled = true;
  timerEl.textContent = 0;
  wpmEl.textContent = 0;
  accuracyEl.textContent = 0;
  finalWPM.textContent = 0;
  paragraph.classList.remove('ring-4', 'ring-green-400');
  restartBtn.classList.add('hidden');
  loadText();
  startCountdown();
}

// ========================
// Auto start after load
// ========================
document.addEventListener('DOMContentLoaded', () => {
  loadText();
  setTimeout(startCountdown, 800);
});
setTimeout(() => {
  if (!startTime) { loadText(); startCountdown(); }
}, 1500);
</script>
