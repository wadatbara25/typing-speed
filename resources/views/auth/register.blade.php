<x-guest-layout>

    {{-- 🎓 شعار التطبيق --}}
    <div class="mb-8 flex flex-col items-center text-center select-none animate-fade-in">
        <div class="relative flex items-center justify-center w-32 h-28 rounded-2xl
                    bg-gradient-to-br from-indigo-600 via-blue-500 to-sky-400 
                    dark:from-indigo-400 dark:via-blue-300 dark:to-sky-200 
                    shadow-[0_0_25px_rgba(59,130,246,0.4)] ring-4 ring-white/50 dark:ring-gray-700 transition-all duration-500">
            <img src="{{ asset('images/logo.png') }}"
                 alt="شعار التطبيق"
                 class="w-24 h-20 object-contain drop-shadow-lg">
        </div>

        <h1 class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-300 mt-4">
            تطبيق الخوارزمي لتعلّم الطباعة
        </h1>
    </div>

    {{-- 🌍 واجهة التسجيل --}}
    <div dir="rtl" class="text-right max-w-md mx-auto bg-white/80 dark:bg-gray-800/80 
                        backdrop-blur-md rounded-2xl shadow-lg p-6 sm:p-8 border border-gray-200 dark:border-gray-700">

        <!-- 🏷️ العنوان -->
        <div class="text-center mb-6">
            <h2 class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-300">
                📝 إنشاء حساب جديد
            </h2>
           
        </div>

        {{-- 🧾 نموذج التسجيل --}}
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- 👤 الاسم الكامل -->
            <div>
                <x-input-label for="name" :value="__('الاسم الكامل')" />
                <x-text-input id="name"
                    class="block mt-1 w-full text-right"
                    type="text" name="name"
                    :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-right" />
            </div>

            <!-- 📧 البريد الإلكتروني -->
            <div>
                <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                <x-text-input id="email"
                    class="block mt-1 w-full text-right"
                    type="email" name="email"
                    :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-right" />
            </div>

            <!-- 🔒 كلمة المرور -->
            <div>
                <x-input-label for="password" :value="__('كلمة المرور')" />
                <x-text-input id="password"
                    class="block mt-1 w-full text-right"
                    type="password" name="password"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-right" />
            </div>

            <!-- 🔁 تأكيد كلمة المرور -->
            <div>
                <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
                <x-text-input id="password_confirmation"
                    class="block mt-1 w-full text-right"
                    type="password" name="password_confirmation"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-right" />
            </div>

            <!-- 🚀 الأزرار -->
            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('login') }}"
                   class="text-sm text-gray-600 dark:text-gray-300 hover:text-indigo-600 underline transition">
                    لديك حساب بالفعل؟ تسجيل الدخول
                </a>

                <x-primary-button
                    class="bg-gradient-to-r from-indigo-600 via-blue-600 to-indigo-700 
                           hover:from-indigo-700 hover:to-blue-700 focus:ring-indigo-400 text-white 
                           py-2 px-5 rounded-xl font-semibold text-base transition-all">
                    إنشاء حساب
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
