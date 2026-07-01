@extends('app')

@section('content')

<section class="max-w-7xl mx-auto py-16 px-8">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-[#234B26] uppercase">Peta Blank Spot Sumatera Utara</h1>
        <p class="text-gray-600 mt-4">Visualisasi wilayah blank spot berdasarkan kabupaten/kota</p>
    </div>

    <div class="bg-[#F3F3E8] rounded-3xl shadow-2xl p-8">
        <div id="map-public" style="height: 600px; width: 100%; border-radius: 16px;"></div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map-public').setView([3.5952, 98.6722], 8);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Ambil data dari API
    fetch('/admin/api/all-spots')
        .then(function(response) { return response.json(); })
        .then(function(data) {
            var spots = [];
            if (data && data.data && Array.isArray(data.data)) {
                spots = data.data;
            } else if (Array.isArray(data)) {
                spots = data;
            }

            var bounds = [];
            spots.forEach(function(item) {
                var lat = item.latitude || item.lat;
                var lng = item.longitude || item.lng;
                
                if (lat && lng) {
                    var color = '#ef4444';
                    var status = (item.status_sinyal || '').toLowerCase();
                    if (status.includes('lemah')) color = '#f59e0b';
                    else if (status.includes('stabil')) color = '#3b82f6';
                    else if (status.includes('ada')) color = '#22c55e';

                    L.circleMarker([parseFloat(lat), parseFloat(lng)], {
                        radius: 8,
                        fillColor: color,
                        color: '#fff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.8
                    })
                    .bindPopup(
                        '<b>' + (item.nama_lokasi || item.kabupaten || '-') + '</b><br>' +
                        'Kecamatan: ' + (item.kecamatan || '-') + '<br>' +
                        'Desa: ' + (item.desa || '-') + '<br>' +
                        'Status: ' + (item.status_sinyal || '-')
                    )
                    .addTo(map);

                    bounds.push([parseFloat(lat), parseFloat(lng)]);
                }
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        })
        .catch(function(error) {
            console.error('Error loading map data:', error);
        });
});
</script>
@endpush