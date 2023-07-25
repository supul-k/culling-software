@extends('layout')

@section('title', 'Photo Gallery')

@extends('Navbar')

@section('content')

    <div class="bg-dark">

        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner w-100 h-auto">
                @foreach ($photos->chunk(ceil($photos->count() / 2)) as $chunk)
                    @foreach ($chunk as $index => $photo)
                        @php
                            $carouselItemClass = $index === 0 ? 'carousel-item active' : 'carousel-item';
                            $trimmedPath = Str::replaceFirst('public/photos/', '', $photo->path);
                        @endphp

                        <div class="{{ $carouselItemClass }} text-center">
                            <img style="height: 100vh;" src="{{ asset('storage/photos/' . $trimmedPath) }}" alt="Image {{ $index + 1 }}">
                        </div>
                    @endforeach
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div style="display: flex; align-items: center; justify-content: center; padding: 20px;">
            <form action="{{ route('photos.process') }}" id="processForm" method="POST">
                @csrf
                <label id="upload" for="upload" class="btn btn-light text-center" style="width: 50vw; ">
                    <p class="fs-3 fw-bold" style="display: flex; align-items: center; justify-content: center;">
                        Process Images
                    </p>
                    <input type="hidden" id="album_id" name="album_id" value="{{ $photos[0]->album_id }}">
                </label>
            </form>
        </div>
        
        



    </div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#upload').on('click', function() {
            console.log('uploading');
            $('#processForm').submit();
        });
    });
</script>
