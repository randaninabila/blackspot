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
    <button onclick="window.location.href='{{ url('/login') }}'"
    class="bg-[#234B26] text-white px-6 py-2 rounded-xl font-medium hover:opacity-90 transition">
    Login
</button>

</nav>