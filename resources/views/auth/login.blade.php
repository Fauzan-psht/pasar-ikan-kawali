<x-guest-layout>
    <div class="mx-auto w-full max-w-md">
        <a href="{{ route('public.home') }}" class="mb-7 inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-teal-600">
            <i class="bi bi-arrow-left"></i>
            Kembali ke beranda
        </a>

        <div class="mb-8">
            <p class="mb-2 text-sm font-bold uppercase tracking-[0.18em] text-teal-600">Area Staff</p>
            <h2 class="login-brand text-3xl font-extrabold text-slate-900 sm:text-4xl">Selamat datang</h2>
            <p class="mt-3 text-sm leading-6 text-slate-500">Masuk menggunakan akun kasir atau administrator Anda.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <i class="bi bi-envelope"></i>
                </span>
                <input
                    id="email"
                    class="login-input block w-full rounded-xl border-slate-200 bg-white py-3.5 pl-11 pr-4 text-sm text-slate-900 transition outline-none placeholder:text-slate-400 focus:border-teal-500"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="nama@email.com"
                >
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="mb-2 flex items-center justify-between">
                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-teal-600 no-underline hover:text-teal-700" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>
            <div class="relative" x-data="{ showPassword: false }">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <i class="bi bi-lock"></i>
                </span>
                <input
                    id="password"
                    class="login-input block w-full rounded-xl border-slate-200 bg-white py-3.5 pl-11 pr-12 text-sm text-slate-900 transition outline-none placeholder:text-slate-400 focus:border-teal-500"
                    x-bind:type="showPassword ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password"
                >
                <button type="button" class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-slate-400 transition hover:text-slate-700" x-on:click="showPassword = !showPassword" aria-label="Tampilkan password">
                    <i class="bi" x-bind:class="showPassword ? 'bi-eye-slash' : 'bi-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex cursor-pointer items-center">
                <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                <span class="ml-2 text-sm text-slate-600">Ingat saya</span>
            </label>
        </div>

        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-slate-900/10 transition duration-200 hover:-translate-y-0.5 hover:bg-teal-700 focus:outline-none focus:ring-4 focus:ring-teal-500/20">
            <span>Masuk ke Dashboard</span>
            <i class="bi bi-arrow-right"></i>
        </button>
    </form>
    </div>
</x-guest-layout>
