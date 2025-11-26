@extends('layouts.app')
@section('title', $lesson->title . ' — تطبيق الخوارزمي')

@section('content')
<div class="max-w-6xl mx-auto pt-2 px-6 space-y-8" dir="rtl">
<section class="w-full bg-white border border-gray-200 shadow-2xl 
                py-6 px-6 md:px-10 rounded-none md:rounded-3xl 
                transition-all duration-300 hover:shadow-[0_10px_40px_rgba(99,102,241,0.15)] mt-6 mb-10"
         dir="rtl">

    <div class="flex flex-col lg:flex-row items-center lg:items-start 
                justify-between max-w-6xl mx-auto w-full gap-4 lg:gap-8">

        <!-- Lesson info -->
        <div class="flex-grow text-right font-[Cairo] overflow-hidden">
            <h1 class="font-[Tajawal] font-extrabold 
                       bg-gradient-to-r from-indigo-700 via-blue-600 to-indigo-400 
                       bg-clip-text text-transparent drop-shadow-sm leading-snug tracking-wide mb-1
                       whitespace-nowrap overflow-hidden text-ellipsis
                       text-5xl sm:text-6xl md:text-7xl lg:text-8xl">
                {{ $lesson->title }}
            </h1>

            <p class="text-[clamp(1rem,1.4vw,1.2rem)] text-gray-700 leading-relaxed 
                      max-w-full truncate -mt-0.5">
                {{ $lesson->description ?? 'ابدأ هذا الدرس وطور مهارتك في الكتابة السريعة والدقيقة.' }}
            </p>
        </div>

        <!-- Lesson stats -->
        @php
            $userAttempts = Auth::user()->attempts()->where('lesson_id', $lesson->id)->count();
        @endphp

        <div class="flex justify-end items-center font-[Cairo]
                    flex-wrap lg:flex-nowrap gap-1.5 lg:gap-2 flex-none
                    text-sm lg:text-[0.9rem]">

            @foreach ([ 
                ['🎯', 'المستوى', $lesson->level ?? '—'],
                ['⏱️', 'المدة', ($lesson->duration_limit ?? 'غير محددة') . ' ثانية'],
                ['📚', 'المحاولات', $userAttempts],
            ] as [$icon, $title, $value])
                <div class="flex items-center gap-1 bg-gradient-to-br from-indigo-50 to-white 
                            border border-indigo-100 rounded-lg shadow-sm px-3 py-1.5 
                            hover:shadow-md hover:-translate-y-[1px] transition-all duration-300
                            flex-shrink-0 text-center">
                    <span class="text-sm leading-none">{{ $icon }}</span>
                    <h4 class="text-xs md:text-sm text-gray-600 font-medium">{{ $title }}:</h4>
                    <p class="text-indigo-700 font-semibold text-sm md:text-base whitespace-nowrap">
                        {{ $value }}
                    </p>
                </div>
            @endforeach
        </div>

    </div>
</section>

<!-- Training area -->
<div class="flex flex-col lg:flex-row gap-8 items-start mt-10 bg-white rounded-3xl shadow-2xl p-8 border border-gray-200">

    <!-- Stats -->
    <aside class="w-full lg:w-1/4 flex flex-col items-stretch justify-start gap-6 font-[Cairo]">
        <div class="flex flex-col items-center justify-center gap-2 bg-gradient-to-b from-indigo-50 to-white border border-indigo-100 rounded-2xl p-5 shadow-md">
            <span class="text-3xl">⌨️</span>
            <h4 class="text-gray-600 text-sm font-semibold">السرعة</h4>
            <p id="speed" class="text-3xl font-extrabold text-indigo-700">0</p>
            <span class="text-sm text-gray-500">كلمة في الدقيقة</span>
        </div>

        <div class="flex flex-col items-center justify-center gap-2 bg-gradient-to-b from-indigo-50 to-white border border-indigo-100 rounded-2xl p-5 shadow-md">
            <span class="text-3xl">🎯</span>
            <h4 class="text-gray-600 text-sm font-semibold">الدقة</h4>
            <p id="accuracy" class="text-3xl font-extrabold text-indigo-700">100</p>
            <span class="text-sm text-gray-500">%</span>
        </div>

        <div class="flex flex-col items-center justify-center gap-2 bg-gradient-to-b from-indigo-50 to-white border border-indigo-100 rounded-2xl p-5 shadow-md">
            <span class="text-3xl">⏱️</span>
            <h4 class="text-gray-600 text-sm font-semibold">الزمن</h4>
            <p id="time" class="text-3xl font-extrabold text-indigo-700">0.0</p>
            <span class="text-sm text-gray-500">دقيقة</span>
        </div>
    </aside>

    <!-- Typing area -->
    <section class="w-full lg:w-3/4 flex flex-col">

        <!-- Timer -->
        @if($lesson->duration_limit)
        <div class="flex justify-between items-center mb-3 font-[Cairo]">
            <span class="text-sm text-gray-600 font-semibold">⏳ الوقت المتبقي:</span>
            <span id="countdown"
                  class="text-lg font-bold text-indigo-700 transition-all duration-300">
                  {{ $lesson->duration_limit }}
            </span>
        </div>
        @endif

        <!-- Progress -->
        <div class="w-full bg-gray-200 rounded-full h-3 mb-6 overflow-hidden shadow-inner">
            <div id="progress-bar" 
                 class="bg-indigo-500 h-3 rounded-full transition-all duration-500 ease-in-out" 
                 style="width: 0%;"></div>
        </div>

        <!-- Target text -->
        <div id="target-text"
             class="font-[Tajawal] font-extrabold
                    text-[2rem] md:text-[2.3rem]
                    leading-[3rem]
                    tracking-wide text-gray-900 
                    mb-8 select-none whitespace-pre-wrap text-justify
                    transition-all duration-300 ease-in-out">
        </div>

        <!-- Input -->
        <textarea id="input-text"
            class="w-full p-5 border-2 border-gray-300 
                   rounded-2xl focus:ring-4 focus:ring-indigo-300 focus:border-indigo-400
                   outline-none font-[Cairo] text-[1.6rem] md:text-[1.8rem] leading-relaxed 
                   text-gray-900 bg-white placeholder-gray-400 transition-all duration-200
                   shadow-inner resize-none"
            rows="6"
            placeholder="ابدأ بالكتابة هنا..." autofocus></textarea>
    </section>
</div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Logic -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const targetText = @json($lesson->content);
    const targetElem = document.getElementById('target-text');
    const inputElem  = document.getElementById('input-text');
    const speedElem  = document.getElementById('speed');
    const accElem    = document.getElementById('accuracy');
    const timeElem   = document.getElementById('time');
    const progressBar = document.getElementById('progress-bar');

   
    const charsHTML = [...targetText].map(ch => {
        if (ch === ' ') {
            return `<span class="char bg-gray-100 inline-block w-4 h-8 mx-[3px] rounded-md align-middle">&nbsp;</span>`;
        }
        return `<span class="char px-[3px] rounded transition-all duration-150 text-gray-800 font-[Tajawal]">${ch}</span>`;
    });
    targetElem.innerHTML = charsHTML.join('');

    let startTime = null;
    let finished = false;

    
    inputElem.addEventListener('input', () => {
        if (finished) return;

        const typed = inputElem.value;
        const allChars = targetElem.querySelectorAll('.char');

        // يبدأ التوقيت عند أول ضغطة
        if (!startTime && typed.length > 0) {
            startTime = new Date();
        }

        const index = typed.length - 1;
        const char = allChars[index];

       
        if (char) {
            if (typed[index] === targetText[index]) {
                char.classList.remove('bg-red-100', 'text-red-800');
                char.classList.add('bg-green-100', 'text-green-800', 'scale-105');
            } else {
                char.classList.remove('bg-green-100', 'text-green-800');
                char.classList.add('bg-red-100', 'text-red-800', 'scale-105');
            }
            setTimeout(() => char.classList.remove('scale-105'), 120);
        }

    
        const correctChars = typed.split('').filter((ch, i) => ch && ch === targetText[i]).length;
        const accuracy = (correctChars / typed.length) * 100 || 100;

        
        const elapsedSeconds = startTime ? (new Date() - startTime) / 1000 : 0;
        const elapsedMinutes = elapsedSeconds / 60;

     
        const wpm = elapsedMinutes > 0 ? Math.round((correctChars / 5) / elapsedMinutes) : 0;

        
        const progress = Math.min((typed.length / targetText.length) * 100, 100);

      
        const mins = Math.floor(elapsedSeconds / 60);
        const secs = Math.floor(elapsedSeconds % 60);
        const formattedTime = `${mins}:${secs.toString().padStart(2, '0')}`;

    
        speedElem.textContent = wpm;
        accElem.textContent = accuracy.toFixed(1);
        timeElem.textContent = formattedTime;
        progressBar.style.width = `${progress}%`;

        
        if (progress === 100 && !finished) {
            endLesson({ speed: wpm, accuracy, elapsedSeconds });
        }
    });

    
    function endLesson(stats = {}) {
        finished = true;
        inputElem.disabled = true;
        progressBar.classList.replace('bg-indigo-500', 'bg-green-500');

        const mins = Math.floor(stats.elapsedSeconds / 60);
        const secs = Math.floor(stats.elapsedSeconds % 60);
        const formattedFinalTime = `${mins}:${secs.toString().padStart(2, '0')} دقيقة`;

      
        fetch("{{ route('typing.attempts.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify({
                lesson_id: {{ $lesson->id }},
                wpm: stats.speed,
                accuracy: stats.accuracy.toFixed(1),
                duration_seconds: Math.round(stats.elapsedSeconds),
            }),
        });

        
        Swal.fire({
            title: '🎉 أحسنت!',
            html: `
                <div class="text-right leading-relaxed text-gray-700 font-[Cairo]">
                    <p class="mb-2 text-lg font-bold text-indigo-700">لقد أنهيت الدرس بنجاح!</p>
                    <p>⌨️ <strong>السرعة:</strong> ${stats.speed} كلمة/دقيقة</p>
                    <p>🎯 <strong>الدقة:</strong> ${stats.accuracy.toFixed(1)}%</p>
                    <p>⏱️ <strong>الزمن:</strong> ${formattedFinalTime}</p>
                </div>
            `,
            icon: 'success',
            confirmButtonText: '🔁 إعادة المحاولة',
            cancelButtonText: '🏠 العودة للدروس',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#6b7280',
        }).then((r) => {
            if (r.isConfirmed) window.location.reload();
            else window.location.href = "{{ route('lessons.index') }}";
        });
    }
});
</script>

@endsection
