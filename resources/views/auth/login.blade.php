<x-layouts.auth title="Login">
    <div class="w-full max-w-md">
        {{-- Logo / Brand --}}
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(139, 92, 246, 0.2)); border: 1px solid rgba(6, 182, 212, 0.3);">
                <svg class="w-8 h-8 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-orbitron font-bold tracking-wider">
                <span class="bg-gradient-to-r from-cyan-400 to-violet-500 bg-clip-text text-transparent">URBAN</span>
                <span class="text-slate-300">MAP</span>
            </h1>
            <p class="mt-2 text-sm text-slate-500">Masuk ke dunia misteri</p>
        </div>

        {{-- Login Card --}}
        <div class="neon-card p-8">
            <h2 class="text-lg font-semibold text-slate-200 mb-6">Masuk ke Akun</h2>

            {{-- Session Status / Error --}}
            @if (session('status'))
                <div class="mb-4 p-3 rounded-lg text-sm" style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.2); color: #67e8f9;">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg text-sm" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="login-email" class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Email</label>
                    <input
                        id="login-email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="nama@email.com"
                        class="neon-input w-full px-4 py-3 rounded-lg text-sm"
                    />
                </div>

                {{-- Password --}}
                <div>
                    <label for="login-password" class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Password</label>
                    <input
                        id="login-password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="neon-input w-full px-4 py-3 rounded-lg text-sm"
                    />
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="login-remember" class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-cyan-500 focus:ring-cyan-500/30">
                        <span class="text-xs text-slate-400">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" id="login-submit" class="neon-btn w-full py-3 rounded-lg text-sm tracking-wide">
                    MASUK
                </button>
            </form>

            {{-- Register Link --}}
            <p class="mt-6 text-center text-sm text-slate-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-cyan-400 hover:text-cyan-300 transition-colors font-medium">Daftar sekarang</a>
            </p>
        </div>

        {{-- Footer --}}
        <p class="mt-6 text-center text-xs text-slate-600">
            &copy; {{ date('Y') }} Urban Legend Map. Hati-hati di luar sana.
        </p>
    </div>
</x-layouts.auth>
