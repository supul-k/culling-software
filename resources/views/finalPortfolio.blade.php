@extends('layout')

@section('title', 'Photo Gallery')

@extends('Navbar')

@section('content')

    <div class="bg-dark">

        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner w-100 h-70">

                @php
                    $images = new FilesystemIterator($imageDirectory, FilesystemIterator::SKIP_DOTS);
                    $index = 0; // Initialize the index counter
                @endphp
            
                @foreach ($images as $image)
                    @php
                        $carouselItemClass = $index === 0 ? 'carousel-item active' : 'carousel-item';
                        $imageName = basename($image->getPathname());
                        $urlPath = str_replace('\\', '/', 'storage/ML_Project/good/' . $imageName);
                    @endphp
                    <div class="{{ $carouselItemClass }} text-center">
                        <img style="height: 100vh;" src="{{ asset($urlPath) }}" alt="Image {{ $index + 1 }}">
                    </div>
            
                    @php
                        $index++; // Increment the index counter for the next iteration
                    @endphp
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
            <form action="{{ route('photos.download') }}" id="downloadForm" method="POST">
                @csrf
                <label id="download" for="upload" class="btn btn-light text-center" style="width: 50vw; ">
                    <p class="fs-3 fw-bold" style="display: flex; align-items: center; justify-content: center;">
                        Download Images
                    </p>
                </label>
            </form>
        </div>





    </div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#download').on('click', function() {
            console.log('uploading');
            $('#downloadForm').submit();
        });
    });
</script>
