<!-- MODAL DOWNLOAD PDF DATA KEPALA DINAS -->
<div id="downloadModal"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">

    <div class="bg-[#234B26] w-full max-w-md max-h-[90vh] flex flex-col p-6 rounded-3xl shadow-2xl border border-white/10 mx-4 transform scale-95 transition-transform duration-300" id="downloadModalContent">

        <div class="text-center mb-3 shrink-0">
            <h3 class="text-xl font-bold text-[#E6EB9C]">Masukkan Data</h3>
            <p class="text-xl italic font-bold text-[#E6EB9C]">Kepala Dinas</p>
        </div>

        <form action="{{ $exportRoute ?? route('admin.export.pdf') }}" method="POST" class="flex flex-col flex-1 min-h-0">
            @csrf
            @if(!empty($kabupatenId))
                <input type="hidden" name="kabupaten_id" value="{{ $kabupatenId }}">
            @elseif(isset($kabupaten) && !empty($kabupaten->id))
                <input type="hidden" name="kabupaten_id" value="{{ $kabupaten->id }}">
            @endif

            <!-- SCROLLABLE FORM BODY -->
            <div class="space-y-4 overflow-y-auto pr-1">
                <!-- Tanggal -->
                <div>
                    <label class="block text-white font-semibold mb-1 text-sm">
                        Tanggal <span class="text-red-400">*</span>
                    </label>
                    <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                        class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
                </div>

                <!-- Lokasi -->
                <div>
                    <label class="block text-white font-semibold mb-1 text-sm">
                        Lokasi <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="lokasi"
                        placeholder="Masukkan lokasi" required
                        class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
                </div>

                <!-- Nomenklatur Dinas KOMINFO -->
                <div>
                    <label class="block text-white font-semibold mb-1 text-sm">
                        Nomenklatur Dinas KOMINFO <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="nomenklatur"
                        placeholder="Masukkan nomenklatur" required
                        class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
                </div>

                <!-- Nama Kepala Dinas -->
                <div>
                    <label class="block text-white font-semibold mb-1 text-sm">
                        Nama Kepala Dinas <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="kepala_dinas"
                        placeholder="Masukkan nama kepala dinas" required
                        class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
                </div>

                <!-- Pangkat / Gol -->
                <div>
                    <label class="block text-white font-semibold mb-1 text-sm">
                        Pangkat / Gol <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="pangkat"
                        placeholder="Contoh: IV/a" required
                        class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
                </div>

                <!-- NIP -->
                <div>
    <label class="block text-white font-semibold mb-1 text-sm">
        NIP <span class="text-red-400">*</span>
    </label>

    <input
        type="text"
        name="nip"
        placeholder="Masukkan NIP"
        required
        inputmode="numeric"
        pattern="[0-9]+"
        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
        class="w-full bg-white text-[#234B26] px-3 py-2.5 rounded-xl text-sm outline-none border border-transparent focus:border-white/30">
</div>

                <!-- BUTTON ACTIONS -->
                <div class="flex justify-end gap-3 pt-3 shrink-0 border-t border-white/10 mt-2">
                    <button type="button" onclick="closeDownloadModal()" class="bg-white text-red-700 font-bold px-4 py-2 rounded-lg hover:bg-gray-200 text-sm transition">
                        Cancel
                    </button>
                    <button type="submit" class="bg-white text-[#234B26] font-bold px-4 py-2 rounded-lg hover:bg-gray-200 text-sm transition">
                        Tambahkan
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
window.openDownloadModal = function() {
    const modal = document.getElementById('downloadModal');
    const content = document.getElementById('downloadModalContent');

    if (modal && content) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        }, 10);
    }
};

window.closeDownloadModal = function() {
    const modal = document.getElementById('downloadModal');
    const content = document.getElementById('downloadModalContent');

    if (modal && content) {
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
};

document.addEventListener('DOMContentLoaded', function() {
    window.addEventListener('click', function(e){
        const modal = document.getElementById('downloadModal');
        if(modal && e.target === modal){
            window.closeDownloadModal();
        }
    });
});
</script>
