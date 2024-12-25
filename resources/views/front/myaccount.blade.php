@extends('layouts.front')
@section('contents')
    <!-- Title page -->
    <section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('/cozas/images/bg-01.jpg');">
        <h2 class="ltext-105 cl0 txt-center">
            Profile
        </h2>
    </section>
    <section class="bg0 p-t-104 p-b-116">
        <div class="container">
            <div class="flex-w flex-tr justify-content-center">

                <div class="size-210 bor10 flex-w flex-col-m p-lr-93 p-tb-30 p-lr-15-lg w-full-md">
                    <div class="flex-w w-full p-b-42">

                    </div>
                    <div class="flex-w w-full p-b-42">
                        <span class="fs-18 cl5 txt-center size-211">
                            <span class="lnr lnr-user"></span>
                        </span>

                        <div class="size-212 p-t-2">
                            <span class="mtext-110 cl2">
                                Name
                            </span>

                            <h3 class="stext-115 cl1 size-213 p-t-18">
                                {{ auth()->user()->name }}
                            </h3>
                        </div>
                    </div>

                    <div class="flex-w w-full p-b-42">
                        <span class="fs-18 cl5 txt-center size-211">
                            <span class="lnr lnr-phone-handset"></span>
                        </span>

                        <div class="size-212 p-t-2">
                            <span class="mtext-110 cl2">
                                Phone
                            </span>

                            <p class="stext-115 cl1 size-213 p-t-18">
                                {{ auth()->user()->phone }}
                            </p>
                        </div>
                    </div>

                    <div class="flex-w w-full">
                        <span class="fs-18 cl5 txt-center size-211">
                            <span class="lnr lnr-envelope"></span>
                        </span>

                        <div class="size-212 p-t-2">
                            <span class="mtext-110 cl2">
                                Email
                            </span>

                            <p class="stext-115 cl1 size-213 p-t-18">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                    </div>
                    <div class="flex-w w-full p-b-42">
                        <span class="fs-18 cl5 txt-center size-211">
                            <span class="lnr lnr-map-marker"></span>
                        </span>

                        <div class="size-212 p-t-2">
                            <span class="mtext-110 cl2">
                                Address
                            </span>

                            <p class="stext-115 cl6 size-213 p-t-18">

                                {{ auth()->user()->user_address->where('is_primary', 1)->first()->address }}
                            </p>
                            <p>{{ auth()->user()->user_address->where('is_primary', 1)->first()->province->name }}</p>
                            <p>{{ auth()->user()->user_address->where('is_primary', 1)->first()->city->name }}</p>
                        </div>
                    </div>
                    <div class="flex-w w-full">
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-danger">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
