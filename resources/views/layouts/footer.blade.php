<footer class="bg-[#D7E3D4] mt-20 pl-40 pr-80 py-10">

    <div class="flex flex-col md:flex-row justify-between gap-10 text-[#234B26]">

        <!-- KIRI: LOGO + NAMA + ALAMAT -->
        <div class="space-y-6 max-w-lg">

            <!-- Logo + Nama -->
            <div class="flex items-center gap-5">
                <img src="{{ asset('images/logo.png') }}"
                     class="w-16"
                     alt="logo">

                <div>
                    <h2 class="font-semibold text-2xl">
                        Dinas Komunikasi dan Informatika
                    </h2>
                    <p class="text-lg">
                        Provinsi Sumatera Utara
                    </p>
                </div>
            </div>

            <!-- Alamat -->
            <div class="flex gap-2 ml-21">
                <p class="text-sm leading-relaxed">
                    Jl. HM. Said No.27, Gaharu,
                    Kec. Medan Timur, Kota Medan,
                    Sumatera Utara 20233
                </p>
            </div>

        </div>

        <!-- KANAN: KONTAK -->
        <div class="space-y-4 mt-6">

            <!-- Telepon -->
            <div class="flex gap-3">
                <svg class="w-6 h-6 mt-1 flex-shrink-0"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-width="2"
                          d="M3 5a2 2 0 012-2h3l2 5-2 2a16 16 0 006 6l2-2 5 2v3a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <p class="text-sm">
                    (061) 4527254 - 4527038 - 4515038
                </p>
            </div>

            <!-- Fax -->
            <div class="flex gap-3">
                <svg class="w-6 h-6 mt-1 flex-shrink-0"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-width="2"
                          d="M9 17v-2h6v2m-7 4h8a2 2 0 002-2V7l-4-4H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm">
                    (061) 4510185
                </p>
            </div>

            <!-- Email -->
            <div class="flex gap-3">
                <svg class="w-6 h-6 mt-1 flex-shrink-0"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-width="2"
                          d="M3 8l9 6 9-6m0 8H3V8h18v8z"/>
                </svg>
                <p class="text-sm">
                    diskominfo@sumutprov.go.id
                </p>
            </div>

        </div>

    </div>

</footer>