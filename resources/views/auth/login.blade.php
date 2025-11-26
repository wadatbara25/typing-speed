<x-guest-layout>
    {{-- 🌍 واجهة تسجيل الدخول — تطبيق الخوارزمي --}}
    <div dir="rtl" 
         class="text-right max-w-md mx-auto mt-10 bg-white/80 dark:bg-gray-800/80 
                backdrop-blur-md rounded-2xl shadow-lg p-6 sm:p-8 border border-gray-200 dark:border-gray-700">

       {{-- 🎓 شعار التطبيق --}}
<div class="mb-8 flex flex-col items-center text-center select-none animate-fade-in">
    <div class="relative flex items-center justify-center w-28 h-28 rounded-2xl
                bg-gradient-to-br from-indigo-600 via-blue-500 to-sky-400 
                dark:from-indigo-400 dark:via-blue-300 dark:to-sky-200 
                shadow-[0_0_25px_rgba(59,130,246,0.4)] ring-4 ring-white/50 dark:ring-gray-700 transition-all duration-500">
        <img src="{{ asset('images/logo.png') }}"
             alt="شعار التطبيق"
             class="w-20 h-20 object-contain drop-shadow-lg">
    </div>

    <h1 class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-300 mt-4">
        تطبيق الخوارزمي لتعلم الطباعة
    </h1>
</div>


        <!-- ✅ Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- 📧 البريد الإلكتروني -->
            <div>
                <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                <x-text-input id="email"
                    class="block mt-1 w-full text-right"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required autofocus
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-right" />
            </div>

            <!-- 🔒 كلمة المرور -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('كلمة المرور')" />
                <x-text-input id="password"
                    class="block mt-1 w-full text-right"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-right" />
            </div>

            <!-- 💾 تذكرني -->
            <div class="flex items-center justify-between mt-4">
                <label for="remember_me" class="inline-flex items-center text-sm">
                    <input id="remember_me"
                        type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        name="remember">
                    <span class="mr-2 text-gray-600 dark:text-gray-300">تذكرني</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:text-indigo-500 font-medium"
                       href="{{ route('password.request') }}">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>

            <!-- 🚀 زر الدخول -->
            <div class="mt-6">
                <x-primary-button
                    class="w-full justify-center bg-gradient-to-r from-indigo-600 via-blue-600 to-indigo-700 
                           hover:from-indigo-700 hover:to-blue-700 focus:ring-indigo-400 text-white py-3 text-lg font-bold rounded-xl">
                    تسجيل الدخول
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
