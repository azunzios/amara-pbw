@extends('layouts.app')

@section('title', 'Galeri Kegiatan BPS Jawa Timur')

@section('content')
<h2>Galeri Kegiatan BPS Provinsi Jawa Timur</h2>

<div class="galeri-container">

    <!-- Foto utama -->
    <div class="galeri-preview">
        <img id="fotoPreview"
             src="https://sensus.bps.go.id/view_image/images/254/Geladi%20Bersih%20(1)%20(Large).jpg"
             alt="Kegiatan BPS Jawa Timur">
    </div>

    <!-- Thumbnail -->
    <div class="galeri-thumbnails">

        <img src="https://sensus.bps.go.id/view_image/images/288/Sosialisasi%20(11)%20(Large).jpg"
             alt="Kegiatan 1"
             onclick="gantiPreview(this)">

        <img src="https://sensus.bps.go.id/view_image/images/281/Sosialisasi%20(4)%20(Large).jpg"
             alt="Kegiatan 2"
             onclick="gantiPreview(this)">

        <img src="https://sensus.bps.go.id/view_image/images/204/Audiensi%20SP%20ke%20TVRI%20(2)%20(Large).jpg"
             alt="Kegiatan 3"
             onclick="gantiPreview(this)">

        <img src="https://sensus.bps.go.id/view_image/images/4042/Workshop%20Evaluasi%20Data%20Hasil%20ST2023%20Provinsi%20Sumatera%20Utara.jpg"
             alt="Kegiatan 4"
             onclick="gantiPreview(this)">

        <img src="https://sensus.bps.go.id/view_image/images/953/IMG_9189.jpg"
             alt="Kegiatan 5"
             onclick="gantiPreview(this)">

        <img src="https://sensus.bps.go.id/view_image/images/291/Sosialisasi%20(14)%20(Large).jpg"
             alt="Kegiatan 6"
             onclick="gantiPreview(this)">

    </div>
</div>
@endsection

@push('scripts')
<script>
    function gantiPreview(thumbnail) {
        document.getElementById('fotoPreview').src = thumbnail.src;
        document.getElementById('fotoPreview').alt = thumbnail.alt;
    }
</script>
@endpush
