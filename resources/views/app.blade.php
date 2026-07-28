<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blankspot Sumut</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    {{-- LEAFET CSS & JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-[#FFFFFF] min-h-screen flex flex-col">

    {{-- Navbar --}}
    @include('layouts.navbar')

    {{-- Content --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    {{-- Global Lightbox Modal --}}
    <div id="globalLightboxModal" class="fixed inset-0 bg-black/80 z-[9999] hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300 opacity-0" onclick="closeLightbox()">
        <div class="relative max-w-4xl max-h-[90vh] flex flex-col items-center" onclick="event.stopPropagation()">
            <button onclick="closeLightbox()" class="absolute -top-10 right-0 text-white hover:text-gray-300 font-bold text-2xl bg-black/50 w-8 h-8 rounded-full flex items-center justify-center cursor-pointer">&times;</button>
            <img id="globalLightboxImage" src="" alt="Perbesar Foto" class="max-w-full max-h-[85vh] rounded-2xl object-contain shadow-2xl border border-white/20">
        </div>
    </div>

    <script>
    function openLightbox(url) {
        const modal = document.getElementById('globalLightboxModal');
        const img = document.getElementById('globalLightboxImage');
        if (!modal || !img) return;
        img.src = url;
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
        }, 10);
    }

    function closeLightbox() {
        const modal = document.getElementById('globalLightboxModal');
        if (!modal) return;
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    </script>

</body>
</html>