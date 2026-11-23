<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Pasar Ikan Kawali') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: #f1f5f9;
            }

            .login-brand {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            .login-panel {
                background:
                    radial-gradient(circle at 15% 20%, rgba(20, 184, 166, 0.16), transparent 28rem),
                    linear-gradient(145deg, #0a2540 0%, #14538f 58%, #2e86de 100%);
            }

            .login-panel::before,
            .login-panel::after {
                content: '';
                position: absolute;
                border-radius: 999px;
                border: 1px solid rgba(255, 255, 255, 0.10);
            }

            .login-panel::before {
                width: 20rem;
                height: 20rem;
                right: -9rem;
                bottom: -8rem;
            }

            .login-panel::after {
                width: 11rem;
                height: 11rem;
                left: -5rem;
                top: 5rem;
            }

            .login-card {
                box-shadow: 0 28px 70px rgba(15, 23, 42, 0.16);
            }

            .login-input:focus {
                border-color: #14b8a6 !important;
                box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.12) !important;
            }
        </style>
    </head>
    <body class="min-h-screen text-slate-900 antialiased">
        <main class="min-h-screen p-0 sm:p-6 lg:p-10 flex items-center justify-center">
            <div class="login-card w-full max-w-[1060px] min-h-screen sm:min-h-0 sm:rounded-[28px] overflow-hidden bg-white grid lg:grid-cols-[0.92fr_1.08fr]">
                <section class="login-panel relative hidden lg:flex flex-col justify-between p-12 text-white overflow-hidden">
                    <a href="{{ route('public.home') }}" class="relative z-10 flex items-center gap-3 no-underline">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-teal-400 to-cyan-500 text-xl shadow-lg">
                            <i class="bi bi-fish"></i>
                        </span>
                        <span class="login-brand text-xl font-bold text-white">
                            Pasar Ikan <span class="text-teal-300">Kawali</span>
                        </span>
                    </a>

                    <div class="relative z-10 my-auto py-12">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl border border-white/20 bg-white/10 text-2xl backdrop-blur">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h1 class="login-brand text-4xl font-extrabold leading-tight">
                            Kelola pasar ikan<br>
                            <span class="text-teal-300">lebih mudah.</span>
                        </h1>
                        <p class="mt-5 max-w-sm text-base leading-7 text-white/75">
                            Masuk untuk mengelola produk, pesanan, transaksi, dan laporan Pasar Ikan Kawali.
                        </p>
                    </div>

                    <p class="relative z-10 text-sm text-white/55">
                        &copy; {{ date('Y') }} Pasar Ikan Kawali
                    </p>
                </section>

                <section class="flex min-h-screen sm:min-h-[650px] flex-col justify-center px-6 py-10 sm:px-12 lg:min-h-[650px] lg:px-16">
                    <div class="mb-10 flex items-center justify-between lg:hidden">
                        <a href="{{ route('public.home') }}" class="login-brand flex items-center gap-2 no-underline">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-teal-500 to-cyan-500 text-white">
                                <i class="bi bi-fish"></i>
                            </span>
                            <span class="font-bold text-slate-900">Pasar Ikan <span class="text-teal-600">Kawali</span></span>
                        </a>
                    </div>

                    {{ $slot }}
                </section>
            </div>
        </main>
    </body>
</html>
