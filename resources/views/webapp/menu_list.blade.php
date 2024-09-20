@extends('layouts.web_app')

@section('content')
    <div class="container">
        <header>
            <div class="system-bar">
                <div class="row">
                    <div class="col-2 left" id="menu-left">
                        <a href="{{ url()->previous() }}" class="header-icon">
                            <i class="bi bi-arrow-bar-left"></i>
                        </a>
                    </div>
                    <div class="col-8 center">Our Menu</div>
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

            <div class="menu-card text-center">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="menuTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-all" data-category="all" data-toggle="tab" href="#category-all" role="tab">
                            All
                        </a>
                    </li>
                    @foreach($categories as $category)
                    <li class="nav-item">
                        <a class="nav-link" id="tab-{{ $category->id }}" data-category="{{ $category->id }}" data-toggle="tab" href="#category-{{ $category->id }}" role="tab">
                            {{ $category->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>

                <!-- Tab panes -->
                <div class="tab-content mt-3">
                    <!-- Placeholder for dynamically loaded content -->
                    <div class="tab-pane fade show active" id="category-all" role="tabpanel">
                        <div class="row" id="menu-content">
                            <!-- Default content for 'All' category -->
                            @foreach($categories as $category)
                                @foreach($category->menuItems as $item)
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                                        <div class="menu-item text-center">
                                            <a href="/menu/{{$reservation->order_id}}/detail/{{$item->id}}"><img src="{{ asset('assets/menu_images/'.$item->menu_image) }}" alt="{{ $item->name }}" class="menu-img"></a>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

