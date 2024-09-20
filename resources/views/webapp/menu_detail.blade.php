@extends('layouts.web_app')

@section('content')
    <div class="container">
        <header>
            <div class="system-bar">
                <div class="row">
                    <div class="col-2 left" id="menu-left">
                        <a href="/menu/{{$reservation->order_id}}" class="header-icon">
                            <i class="bi bi-arrow-bar-left"></i> 
                        </a>
                    </div>
                    <div class="col-8"></div>
                    <div class="col-2 right">
                        <a href="/order-summary/{{$reservation->order_id}}" class="header-icon">
                            <i class="bi bi-cart3"></i> 
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <div class="mt-5 page">
            @include('layouts.error')  <!-- Display error messages here -->

            <!-- Menu Detail Card -->
            <div class="menu-detail-card text-center mx-auto">
                <!-- Menu Item Image -->
                <img src="{{ asset('assets/menu_images/'.$menu_detail->menu_image) }}" alt="" class="img-fluid rounded" style="object-fit: cover; height: 200px; width: 100%;">

                <!-- Menu Item Details -->
                <div class="pt-3">
                    <!-- Name and Rating in one row -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">{{$menu_detail->name}}</h5>
                        <span class="text-warning">★★★★☆</span> <!-- Star ratings -->
                    </div>

                    <!-- Price and Quantity Increment in one row -->
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <span class="price" data-value="{{$menu_detail->price}}" style="font-size: 16px;">MMK {{$menu_detail->price}}</span>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-light btn-sm me-2" id="decrement-quantity">
                                <i class="bi bi-dash"></i> 
                            </button>
                            <span id="quantity" data-value="1">1</span> 
                            <button class="btn btn-light btn-sm ms-2" id="increment-quantity">
                                <i class="bi bi-plus"></i> 
                            </button>
                        </div>
                    </div>

                    <!-- Menu Item Description -->
                    <p class="text-start mt-3" style="font-size: 14px;">
                        Description: {{$menu_detail->description}}
                    </p>

                    <!-- Price and Add to Cart Button -->
                    <div class="d-flex justify-content-between align-items-center mt-5" id="price-add-card">
                        <span style="font-size: 1.2rem;" id="total-amount">MMK {{$menu_detail->price}}</span>
                        <form action="{{ route('add-to-cart', $reservation->order_id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="menu_id" id="menu_id" value="{{$menu_detail->id}}">
                            <input type="hidden" name="quantity" id="cart-quantity" value="1">
                            <button class="btn btn-info btn-sm">
                                <i class="bi bi-cart"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
