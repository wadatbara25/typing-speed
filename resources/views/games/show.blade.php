@extends('layouts.app')
@section('title', '🎮 تشغيل اللعبة')

@section('content')
<div dir="rtl" 
     class="relative min-h-screen bg-gradient-to-br from-sky-100 via-white to-sky-200 
            dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col">

    <!--  Header -->
    <header class="flex justify-between items-center px-6 py-4 
                   bg-white/70 dark:bg-gray-800/60 backdrop-blur-sm shadow-md">
        <div class="flex items-center gap-2 select-none">
            <i data-lucide="gamepad-2" class="w-6 h-6 text-indigo-600 dark:text-indigo-400"></i>
            <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200">🎯 اللعبة التعليمية</h1>
        </div>

        <a href="{{ route('games.index') }}" id="backLink"
           class="flex items-center gap-1 text-indigo-700 dark:text-indigo-300 font-semibold hover:underline transition">
            <i data-lucide="arrow-right" class="w-5 h-5"></i> العودة إلى قائمة الألعاب
        </a>
    </header>

    <!--  Game Container -->
    <main id="gameContainer" class="flex-1 w-full h-full flex items-center justify-center p-6">
        <div id="loadingGame" class="flex flex-col items-center gap-3 text-gray-600 dark:text-gray-300">
            <span class="text-2xl font-semibold animate-pulse">⏳ جارٍ تحميل اللعبة...</span>
            <div id="progressBar" class="w-64 h-2 bg-gray-300 dark:bg-gray-700 rounded-full overflow-hidden">
                <div id="progressFill" class="h-full bg-indigo-500 w-0 transition-all duration-500"></div>
            </div>
        </div>
    </main>

 {{-- 🔙 زر الرجوع (لأجهزة الكمبيوتر والتابلت) --}}
<a href="{{ route('games.index') }}"
   id="backButton"
   class="absolute top-4 left-4 hidden lg:flex items-center gap-2
          bg-amber-600 hover:bg-amber-700 text-white font-semibold
          px-4 py-2 rounded-xl shadow-md text-sm transition-all duration-300
          hover:scale-105 hover:shadow-lg focus:ring-4 focus:ring-amber-400/50 z-50">
    <i data-lucide="arrow-right" class="w-4 h-4"></i>
    <span>رجوع للألعاب</span>
</a>

{{-- 🔹 زر عائم (للموبايل فقط) --}}
<a href="{{ route('games.index') }}"
   id="mobileBack"
   class="fixed bottom-6 right-6 flex lg:hidden items-center justify-center gap-2
          bg-amber-600 hover:bg-amber-700 text-white font-semibold 
          px-5 py-3 rounded-full shadow-lg transition-transform duration-300
          hover:scale-110 focus:ring-4 focus:ring-amber-400/50 z-50">
    <i data-lucide="arrow-right" class="w-5 h-5"></i>
    <span class="text-sm font-semibold">رجوع</span>
</a>

    <!-- 🔹 Sounds -->
    <audio id="startSound" preload="auto">
        <source src="{{ asset('sounds/start.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="clickSound" preload="auto">
        <source src="{{ asset('sounds/click.mp3') }}" type="audio/mpeg">
    </audio>
</div>

<!--  SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!--  Game Loader Script -->
<script>
document.addEventListener("DOMContentLoaded", async () => {
    if (typeof lucide !== 'undefined') lucide.createIcons();

    const container = document.getElementById("gameContainer");
    const loader = document.getElementById("loadingGame");
    const fill = document.getElementById("progressFill");
    const type = "{{ $type }}";

    try {
        // Simulate loading bar progress
        for (let i = 0; i <= 100; i += 10) {
            fill.style.width = i + "%";
            await new Promise(r => setTimeout(r, 80));
        }

        // Fetch partial HTML of the selected game
        const res = await fetch(`/games/partials/${type}`);
        if (!res.ok) throw new Error("Failed to load game content");
        const html = await res.text();

        // Fade in loaded content
        loader.remove();
        container.style.opacity = "0";
        container.innerHTML = `<div id="gameBox" class="animate-fade-in-zoom">${html}</div>`;
        container.style.transition = "opacity 0.6s ease";
        container.offsetHeight;
        container.style.opacity = "1";

        // Play start sound
        document.getElementById('startSound').play().catch(() => {});

        // Execute inline scripts if exist
        const scripts = container.querySelectorAll("script");
        scripts.forEach(oldScript => {
            const s = document.createElement("script");
            if (oldScript.src) s.src = oldScript.src;
            else s.textContent = oldScript.textContent;
            document.body.appendChild(s);
        });

    } catch (err) {
        loader.remove();
        Swal.fire({
            icon: 'error',
            title: '⚠️ فشل تحميل اللعبة',
            text: 'تعذر تحميل اللعبة. تحقق من الاتصال أو المسار.',
            confirmButtonText: 'حسنًا',
            confirmButtonColor: '#EF4444',
        });
        console.error(err);
    }
});

/* 🔸 Click sound on back link */
["backLink", "mobileBack"].forEach(id => {
    const el = document.getElementById(id);
    el.addEventListener("click", () => {
        document.getElementById('clickSound').play().catch(() => {});
    });
});
</script>

<!--  Animations -->
<style>
@keyframes fadeInZoom {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
.animate-fade-in-zoom {
  animation: fadeInZoom 0.8s ease-out forwards;
}
</style>
@endsection
