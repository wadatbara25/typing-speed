// ===================================================
// 🎯 metrics.js — منطق موحد لحساب السرعة والدقة وحفظ النتائج
// ===================================================
// ✅ مبدأ الحساب الجديد:
//   كل 5 أحرف = كلمة واحدة (وفق المعيار الدولي لحساب WPM)
// ===================================================

/**
 * ⚡ حساب السرعة بالكلمات في الدقيقة (WPM)
 * @param {string} inputText - النص الذي كتبه المستخدم
 * @param {number} elapsedTime - الزمن المستغرق بالثواني
 * @returns {number} عدد الكلمات في الدقيقة
 */
export function calculateWPM(inputText, elapsedTime) {
    // إزالة الفراغات والحروف غير المقروءة
    const cleanText = inputText.replace(/\s+/g, '');
    const charCount = cleanText.length;

    // كل 5 أحرف = كلمة واحدة
    const words = charCount / 5;

    // الزمن بالدقائق
    const minutes = elapsedTime / 60;

    return Math.round(words / minutes) || 0;
}

/**
 * 🎯 حساب الدقة بالنسبة المئوية
 * @param {string} inputText - النص الذي كتبه المستخدم
 * @param {string} targetText - النص الأصلي المطلوب كتابته
 * @returns {number} نسبة الدقة %
 */
export function calculateAccuracy(inputText, targetText) {
    if (!targetText.length) return 0;
    let correct = 0;
    const minLen = Math.min(inputText.length, targetText.length);

    for (let i = 0; i < minLen; i++) {
        if (inputText[i] === targetText[i]) correct++;
    }

    return Math.round((correct / targetText.length) * 100);
}

/**
 * 🧮 حساب النتيجة الكاملة (سرعة + دقة + الزمن)
 * @param {string} inputText - النص الذي كتبه المستخدم
 * @param {string} targetText - النص الأصلي المطلوب كتابته
 * @param {number} startTime - وقت بدء اللعبة (timestamp)
 * @returns {{ wpm: number, accuracy: number, elapsed: number }}
 */
export function getTypingMetrics(inputText, targetText, startTime) {
    const elapsed = (Date.now() - startTime) / 1000;
    const wpm = calculateWPM(inputText, elapsed);
    const accuracy = calculateAccuracy(inputText, targetText);
    return { wpm, accuracy, elapsed };
}

/**
 * 💾 حفظ نتيجة اللعبة في السيرفر (موحد لكل الألعاب)
 * @param {string} gameType - اسم اللعبة (مثلاً: arabic-typing)
 * @param {number} wpm - سرعة الكتابة
 * @param {number} accuracy - الدقة
 * @param {string|null} playerName - اسم اللاعب (اختياري)
 */
export async function saveGameResult(gameType, wpm, accuracy, playerName = null) {
    try {
        const name = playerName || localStorage.getItem('playerName') || 'لاعب';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        const response = await fetch("{{ route('games.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                player_name: name,
                wpm,
                accuracy,
                game_type: gameType
            })
        });

        const data = await response.json();

        if (data.success) {
            const rankMsg = data.rank ? `<br>🏆 ترتيبك الحالي: <b>${data.rank}</b>` : '';
            Swal.fire({
                icon: 'success',
                title: '✅ تم حفظ نتيجتك!',
                html: `<b>${name}</b><br>السرعة: ${wpm} كلمة/دقيقة<br>الدقة: ${accuracy}%${rankMsg}`,
                confirmButtonText: 'رائع!',
                confirmButtonColor: '#22c55e'
            });
        } else {
            Swal.fire('⚠️ خطأ', 'تعذر حفظ النتيجة في السيرفر.', 'error');
        }

    } catch (error) {
        console.error('❌ saveGameResult error:', error);
        Swal.fire('⚠️ خطأ', 'حدثت مشكلة أثناء الاتصال بالخادم.', 'error');
    }
}
