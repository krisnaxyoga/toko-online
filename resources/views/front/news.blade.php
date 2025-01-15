@extends('layouts.front')
@section('contents')
    <!-- Title page -->
    <section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('/cozas/images/bg-01.jpg');">
        <h2 class="ltext-105 cl0 txt-center">
            News
        </h2>
    </section>

    <section>
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 p-b-20">
                    <div class="row">
                        <div class="col-12 text-right">
                            <button class="btn btn-secondary btn-toggle-view" data-view="list">
                                <i class="fas fa-list"></i>
                            </button>
                            <button class="btn btn-secondary btn-toggle-view" data-view="grid">
                                <i class="fas fa-th"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Gallery Container with Dynamic Layout -->
                    <div class="row gallery-container grid">
                        @foreach ($data as $item)
                            <div class="col-6 col-sm-4 col-md-4 col-lg-4 p-2 product-item">
                                <div class="of-hidden pos-relative">
                                    <img class="max-w-full m-b-7" src="{{ url($item->image1) }}" alt="IMG-PRODUCT">
                                </div>
                                <a href="{{ route('front.pagedetail', $item->id) }}"
                                    class="fw-bold text-center fs-17 text-dark">{{ $item->title }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select elements
            const container = document.querySelector('.gallery-container');
            const viewButtons = document.querySelectorAll('.btn-toggle-view');

            // Handle view toggle
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const view = this.dataset.view;

                    // Remove active class from all buttons
                    viewButtons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');

                    // Update container class and item layouts
                    container.classList.remove('grid', 'list');
                    container.classList.add(view);

                    const items = container.querySelectorAll('.product-item');

                    items.forEach(item => {
                        if (view === 'list') {
                            item.className = 'col-12 product-item mb-3';
                            // Restructure item for list view
                            item.innerHTML = `
                        <div class="card">
                            <div class="row no-gutters">
                                <div class="col-md-3">
                                    <div class="of-hidden pos-relative">
                                        ${item.querySelector('img').outerHTML}
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body">
                                        <h5 class="card-title">${item.querySelector('p').textContent}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                        } else {
                            item.className =
                                'col-6 col-sm-4 col-md-3 col-lg-2 p-2 product-item';
                            // Restore grid view structure
                            item.innerHTML = `
                        <div class="of-hidden pos-relative">
                            ${item.querySelector('img').outerHTML}
                        </div>
                        <p class="fw-bold text-center">${item.querySelector('.card-title').textContent}</p>
                    `;
                        }
                    });
                });
            });
        });
    </script>
    <style>
        .gallery-container.grid {
            display: flex;
            flex-wrap: wrap;
        }

        .gallery-container.list .product-item {
            transition: all 0.3s ease;
        }

        .gallery-container.list .card {
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.25rem;
        }

        .gallery-container.list img {
            height: 100%;
            object-fit: cover;
        }

        .btn-toggle-view.active {
            background-color: #6c757d;
            color: white;
        }

        .of-hidden {
            overflow: hidden;
        }

        .pos-relative {
            position: relative;
        }

        .max-w-full {
            max-width: 100%;
        }

        .m-b-7 {
            margin-bottom: 0.7rem;
        }
    </style>
@endsection
