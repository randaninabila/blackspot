<meta name="csrf-token" content="{{ csrf_token() }}">

<nav class="bg-[#D7E3D4] px-12 py-3.5 flex justify-between items-center">

    <!-- Left -->
    <div class="flex items-center gap-4">
        <img src="{{ asset('images/logo.png') }}"
             alt="Logo"
             class="w-10">
        <div>
            <h2 class="font-semibold text-[#234B26] text-lg">
                Dinas Komunikasi dan Informatika
            </h2>
            <p class="text-[#234B26] text-sm">
                Provinsi Sumatera Utara
            </p>
        </div>
    </div>

    <!-- Right -->
    @auth
        <div class="flex items-center gap-4">
            <span class="text-[#234B26] font-medium text-sm hidden md:block">
                {{ auth()->user()->nama }}
                <span class="text-xs text-gray-500 ml-1">({{ auth()->user()->role === 'admin' ? 'Admin' : 'Operator' }})</span>
            </span>

            <!-- Tombol Logout dengan konfirmasi -->
            <button onclick="konfirmasiLogout()"
                class="bg-red-600 text-white px-5 py-2 rounded-xl font-medium hover:bg-red-700 transition text-sm">
                Logout
            </button>
        </div>

        <!-- Modal Konfirmasi Logout -->
        <div id="modalLogout"
            class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full mx-4">
                <h3 class="text-xl font-bold text-[#234B26] mb-2">Konfirmasi Logout</h3>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari sistem?</p>
                <div class="flex gap-3">
                    <button onclick="document.getElementById('modalLogout').classList.add('hidden')"
                        class="flex-1 border border-[#234B26] text-[#234B26] py-2.5 rounded-xl font-semibold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full bg-red-600 text-white py-2.5 rounded-xl font-semibold hover:bg-red-700 transition">
                            Ya, Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        

        <script>
        function konfirmasiLogout() {
            document.getElementById('modalLogout').classList.remove('hidden');
        }
        </script>
    @else
        <button onclick="window.location.href='{{ route('login') }}'"
            class="bg-[#234B26] text-white px-6 py-2 rounded-xl font-medium hover:opacity-90 transition">
            Login
        </button>
    @endauth

</nav>