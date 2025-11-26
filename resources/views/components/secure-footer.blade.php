<footer class="gradient-bg text-white text-center py-6 mt-10 fade-in select-none relative">
    <p class="text-sm font-semibold">
         تطبيق
        <span class="text-yellow-300">الخوارزمي لتعلم الطباعة</span> — جميع الحقوق محفوظة.
   © {{ date('Y') }} </p>

    <p class="text-xs text-indigo-200 mt-1 relative group cursor-pointer inline-block">
        تصميم وتطوير
        <span class="font-medium text-white relative group">
            معهد الخوارزمي للتدريب
            <span class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-[10px] px-3 py-1 rounded-lg shadow-lg opacity-0 scale-90 group-hover:opacity-100 group-hover:scale-100 transition-all duration-500 ease-out whitespace-nowrap border border-yellow-400/30">
                💻 برمجة <span id="protected-dev" class="font-medium text-yellow-300">BASHIR OSMAN</span> — 📞 0544207345
            </span>
        </span>
    </p>

    <script>
        document.addEventListener('contextmenu', e => {
            if (e.target.closest('footer')) e.preventDefault();
        });

        const footer = document.querySelector('footer');
        if (footer) footer.addEventListener('selectstart', e => e.preventDefault());

        const dev = document.getElementById('protected-dev');
        const phone = '0544207245';
        const originalText = dev?.innerText.trim();

        const observer = new MutationObserver(() => {
            if (!dev || dev.innerText.trim() !== originalText || !footer.innerText.includes(phone)) {
                alert("⚠️ تم اكتشاف تعديل غير مصرح به في بيانات المطور!");
                location.reload();
            }
        });

        if (dev) observer.observe(dev, { childList: true, characterData: true, subtree: true });
    </script>
</footer>
