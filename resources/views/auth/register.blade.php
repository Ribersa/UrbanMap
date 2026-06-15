<x-layouts.auth title="Register">
    <div class="w-full max-w-md">
        {{-- Logo / Brand --}}
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(6, 182, 212, 0.2)); border: 1px solid rgba(139, 92, 246, 0.3);">
                <svg class="w-8 h-8 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
            </div>
            <h1 class="text-2xl font-orbitron font-bold tracking-wider">
                <span class="bg-gradient-to-r from-violet-400 to-cyan-500 bg-clip-text text-transparent">BERGABUNG</span>
            </h1>
            <p class="mt-2 text-sm text-slate-500">Daftar dan jelajahi misteri</p>
        </div>

        {{-- Register Card --}}
        <div class="neon-card p-8">
            <h2 class="text-lg font-semibold text-slate-200 mb-6">Buat Akun Baru</h2>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg text-sm" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="register-name" class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Nama Lengkap</label>
                    <input
                        id="register-name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Nama lengkap kamu"
                        class="neon-input w-full px-4 py-3 rounded-lg text-sm"
                    />
                </div>

                {{-- Email --}}
                <div>
                    <label for="register-email" class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Email</label>
                    <input
                        id="register-email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="nama@email.com"
                        class="neon-input w-full px-4 py-3 rounded-lg text-sm"
                    />
                </div>

                {{-- Password --}}
                <div>
                    <label for="register-password" class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Password</label>
                    <input
                        id="register-password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        class="neon-input w-full px-4 py-3 rounded-lg text-sm"
                    />
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="register-password-confirm" class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wider">Konfirmasi Password</label>
                    <input
                        id="register-password-confirm"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Ketik ulang password"
                        class="neon-input w-full px-4 py-3 rounded-lg text-sm"
                    />
                </div>

                {{-- Submit --}}
                <button type="submit" id="register-submit" class="neon-btn w-full py-3 rounded-lg text-sm tracking-wide">
                    DAFTAR SEKARANG
                </button>
            </form>

            {{-- Login Link --}}
            <p class="mt-6 text-center text-sm text-slate-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-cyan-400 hover:text-cyan-300 transition-colors font-medium">Masuk di sini</a>
            </p>
        </div>

        {{-- Footer --}}
        <p class="mt-6 text-center text-xs text-slate-600">
            &copy; {{ date('Y') }} Urban Legend Map. Hati-hati di luar sana.
        </p>
    </div>
</x-layouts.auth>
