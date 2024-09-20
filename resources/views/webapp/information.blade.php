@extends('layouts.web_app')

@section('content')
    <div class="container">
        <header>
            <div class="system-bar">
                <div class="row">
                    <div class="col-10 left">Your Information</div>
                    <div class="col-2 right">
                        <a href="" class="header-icon">
                            <i class="bi bi-info-square"></i>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <div class="mt-5 page">
            @include('layouts.error')  <!-- Display error messages here -->

            <div class="information-card text-center">
                <form class="px-3 mt-3" method="POST" action="{{ route('information.store') }}">
                    @csrf

                    <input type="hidden" name="order_id" value="{{$reservation->order_id}}" />
                    <div class="mb-3 row">
                        <label for="name" class="text-start">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{$reservation->name}}" required>
                    </div>

                    <div class="mb-3 row">
                        <label for="phone" class="text-start">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="{{$reservation->phone}}" required>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="text-start">E-mail Address</label>
                        <input type="email" class="form-control" id="email" value="{{$reservation->email}}" name="email" required>
                    </div>

                    <button type="submit" class="mt-3 px-3 btn btn-primary">Reserved</button>
                </form>
            </div>
        </div>
    </div>
@endsection
