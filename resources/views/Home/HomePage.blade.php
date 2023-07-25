@extends('layout')

@section('title', 'Photo Gallery')

@extends('Navbar')

@section('content')

    {{-- <hr class="bg-gradient mt-0 mb-0"
        style="background-image: linear-gradient(to right, rgb(255, 0, 0), rgb(0, 255, 0)); height: 20px;"> --}}

    <section class="vh-100 bg-dark">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 text-black"
                    style="display: grid;
                justify-items: center;
                align-items: center;">

                    <div style="width: 50%;">

                        <p class="fs-1 fw-normal text-light">Cull photos fast with</p>
                        <p class="fs-1 fw-bold text-light">Narrative Select</p>
                        <p class="card-text text-light">Game-changing image culling – powered by smart tech and designed from the
                            ground
                            up for professional photographers.</p>
                        <form action="{{ route('photos.upload') }}" id="uploadForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="upload" class="btn btn-dark bg-light" style="width: 100%; height: 10%;">
                                <p class="fs-3 fw-bold text-dark"
                                    style="display: flex; align-items: center; justify-content: center;">
                                    Discover select
                                </p>
                                <input type="file" id="upload" name="files[]" multiple hidden>
                            </label>
                        </form>


                    </div>

                </div>
                <div class="col-sm-6 px-0 d-none d-sm-block">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/img3.webp"
                        alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
                </div>
            </div>
        </div>
    </section>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#upload').on('change', function() {
            console.log('uploading');
            $('#uploadForm').submit();
        });
    });
</script>