<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Blankspot Sumut</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-[#E6EB9C]/90 via-[#E6EB9C]/7 to-white min-h-screen flex items-center justify-center m-0 p-0 antialiased">

    <div class="w-full max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-center gap-12 lg:gap-24 p-6 sm:p-12">
        
        <div class="flex flex-col items-center justify-center w-full max-w-sm lg:max-w-md">
            <img src="{{ asset('images/logo.png') }}" 
                 alt="Logo Sumatera Utara" 
                 class="w-72 sm:w-80 md:w-full h-auto object-contain drop-shadow-xl">
        </div>

        <div class="w-full max-w-md bg-white border border-white/60 rounded-[2.5rem] shadow-[0_25px_50px_-12px_rgba(0,0,0,0.1)] p-8 sm:p-10">
            
            <h2 class="text-4xl font-bold text-[#234B26] text-center mb-8 tracking-wide">
                Masuk
            </h2>

           <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
            @if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm">
        {{ $errors->first() }}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm">
        {{ session('error') }}
    </div>
@endif
                @csrf

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-[#234B26] pl-1">
                        Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           placeholder="Masukkan Email" 
                           required
                           class="w-full bg-white/80 text-[#234B26] placeholder-gray-400 border border-gray-300 rounded-2xl px-5 py-3.5 outline-none focus:border-[#234B26] focus:ring-2 focus:ring-[#234B26]/10 text-sm transition-all shadow-sm">
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-semibold text-[#234B26] pl-1">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Masukkan Pass" 
                               required
                               class="w-full bg-white/80 text-[#234B26] placeholder-gray-400 border border-gray-300 rounded-2xl px-5 py-3.5 pr-12 outline-none focus:border-[#234B26] focus:ring-2 focus:ring-[#234B26]/10 text-sm transition-all shadow-sm">
                        
                        <button type="button" 
                                onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#234B26] transition-colors">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-[#234B26] text-white font-bold py-3.5 rounded-2xl hover:bg-[#1a381c] transition-all transform active:scale-[0.99] shadow-md tracking-wider text-base">
                        Masuk
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-500 font-medium">
    Masuk dengan 
    <a href="https://sso.sumutprov.go.id/login" 
       class="text-gray-800 font-bold cursor-pointer hover:underline">
       SSO SUMUT
    </a>
</p>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M17.89 13.577m0 0a3 3 0 01-4.07-4.07m-5.186 5.186l5.186-5.186" />
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}
</script>
</body>
</html>