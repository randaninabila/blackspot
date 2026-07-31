<div id="detailSection"
     class="bg-[#F3F3E8] rounded-[2rem] p-6 md:p-8 border border-gray-200/40 shadow-xl hidden mt-8">

    <h4 class="text-[#234B26] font-bold text-2xl mb-6 border-b border-gray-300/60 pb-3">
        Detail Data Blankspot
    </h4>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm h-fit">
            <table class="w-full text-sm text-left">
                <tbody class="divide-y divide-gray-200">
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">ID</td><td id="detail-id" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Kabupaten</td><td id="detail-kabupaten" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Kecamatan</td><td id="detail-kecamatan" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Desa</td><td id="detail-desa" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Longitude</td><td id="detail-longitude" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Latitude</td><td id="detail-latitude" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Prioritas</td><td id="detail-prioritas" class="px-4 py-3 font-bold text-amber-800">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Status Jaringan</td><td id="detail-status-jaringan" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Kondisi Geografis</td><td id="detail-kondisi-geografis" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Jumlah Penduduk</td><td id="detail-jumlah-penduduk" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Jarak ke Ibu Kota (Km)</td><td id="detail-jarak-ibukota" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Tahun</td><td id="detail-tahun" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Operator</td><td id="detail-operator" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Tanggal</td><td id="detail-tanggal" class="px-4 py-3">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Status Validasi</td><td id="detail-status" class="px-4 py-3 font-bold">-</td></tr>
                    <tr id="row-catatan-revisi" class="hidden"><td class="bg-gray-50 px-4 py-3 font-bold text-[#234B26]">Catatan Revisi</td><td id="detail-catatan-revisi" class="px-4 py-3 text-red-600 font-semibold">-</td></tr>
                    <tr><td class="bg-gray-50 px-4 py-3 font-bold">Keterangan / Sinyal</td><td id="detail-keterangan" class="px-4 py-3">-</td></tr>
                </tbody>
            </table>
        </div>

        <div class="w-full h-[320px] rounded-2xl overflow-hidden border shadow-inner">
            <div id="validasiMap" class="w-full h-full"></div>
        </div>

    </div>

    <!-- GALERI FOTO DOKUMENTASI -->
    <div id="container-foto" class="mt-6 border-t border-gray-300/60 pt-4">
        <h5 class="text-[#234B26] font-bold text-base mb-3 flex items-center gap-2">
            📷 Galeri Foto Dokumentasi
        </h5>
        <div id="admin-detail-photos-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
        <p id="admin-detail-photos-empty" class="text-gray-500 text-sm italic hidden">Belum ada foto dokumentasi.</p>
    </div>
</div>
