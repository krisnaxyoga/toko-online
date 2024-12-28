@extends('layouts.front')
@section('contents')
    <!-- Title page -->
    <section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('/cozas/images/bg-01.jpg');">
        <h2 class="ltext-105 cl0 txt-center">
            Gallery
        </h2>
    </section>

    <section>
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 p-b-20">
                    <div class="row">
                        @foreach ($products_images->chunk(4) as $chunk)
                            <div class="col-sm-6 p-b-20">
                                <div class="row">
                                    @foreach ($chunk as $product)
                                        <div class="col-6 p-lr-5">
                                            <div class="of-hidden pos-relative">
                                                <img class="max-w-full m-b-7" src="{{ url($product->image_url) }}"
                                                    alt="IMG-PRODUCT">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
