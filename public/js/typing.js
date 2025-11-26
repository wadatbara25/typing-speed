document.addEventListener('DOMContentLoaded', () => {
    const promptDiv = document.getElementById('prompt');
    const spans = promptDiv ? promptDiv.querySelectorAll('span') : [];
    const area = document.getElementById('typingArea');
    const startBtn = document.getElementById('startBtn');
    const timerEl = document.getElementById('timer');
    const wpmEl = document.getElementById('wpm');
    const accEl = document.getElementById('accuracy');
    const resultDiv = document.getElementById('result');
    const finalWpmEl = document.getElementById('finalWpm');
    const finalAccuracyEl = document.getElementById('finalAccuracy');
    const errorCountEl = document.getElementById('errorCount');
    const progressBar = document.getElementById('progress-bar');
    const fingerImage = document.getElementById('finger-image');

    let startTime = null, intervalId = null, finished = false;

    ['paste','copy','cut'].forEach(ev=>{
        area.addEventListener(ev, e => e.preventDefault());
    });

    function formatTime(s){
        const mm = String(Math.floor(s/60)).padStart(2,'0');
        const ss = String(s%60).padStart(2,'0');
        return mm+':'+ss;
    }

    function computeStats(){
        const typed = area.value;
        let correctChars = 0;
        let errorsArr = [];
        spans.forEach((span,i)=>{
            const typedChar = typed[i] || '';
            if(typedChar === span.innerText){
                span.classList.add('correct');
                span.classList.remove('incorrect');
                correctChars++;
            } else {
                span.classList.add('incorrect');
                span.classList.remove('correct');
                if(typedChar) errorsArr.push({pos:i, expected:span.innerText, got:typedChar});
            }
        });

        const totalTyped = typed.length;
        const errors = errorsArr.length;
        const elapsedSecs = Math.max(1, Math.floor((Date.now() - startTime)/1000));
        const minutes = elapsedSecs / 60;
        const raw_wpm = (correctChars / 5) / minutes;
        const accuracy = totalTyped ? (correctChars/totalTyped)*100 : 100;
        const final_wpm = raw_wpm * (accuracy/100);

        return {totalTyped, correctChars, errors, elapsedSecs, raw_wpm, accuracy, final_wpm, errorsArr};
    }

    function tick(){
        const stats = computeStats();
        timerEl.innerText = formatTime(stats.elapsedSecs);
        wpmEl.innerText = Math.round(stats.final_wpm);
        accEl.innerText = Math.round(stats.accuracy) + '%';
        if(progressBar) progressBar.style.width = Math.min((stats.correctChars/spans.length)*100,100) + '%';
        if(fingerImage) fingerImage.style.transform = `translateX(${Math.min((stats.correctChars/spans.length)*200,200)}px)`;
    }

    function endTest(){
        if(finished) return;
        finished = true;
        clearInterval(intervalId);
        const stats = computeStats();

        finalWpmEl.innerText = Math.round(stats.final_wpm);
        finalAccuracyEl.innerText = Math.round(stats.accuracy);
        errorCountEl.innerText = stats.errors;

        resultDiv.classList.remove('hidden');

        fetch('/typing/attempts', {
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body:JSON.stringify({
                lesson_id: window.lessonId || null,
                wpm: Math.round(stats.final_wpm),
                raw_wpm: Math.round(stats.raw_wpm),
                accuracy: Math.round(stats.accuracy),
                errors: stats.errorsArr,
                duration_seconds: stats.elapsedSecs,
                typed_text: area.value
            })
        }).then(r=>r.json()).then(console.log).catch(console.error);
    }

    startBtn?.addEventListener('click',()=>{
        if(startTime) return;
        startTime = Date.now();
        area.focus();
        intervalId = setInterval(tick,500);
        startBtn.disabled = true;
    });

    area.addEventListener('input',()=>{
        if(!startTime) return;
        if(area.value === Array.from(spans).map(s=>s.innerText).join('')){
            endTest();
        }
    });
});
