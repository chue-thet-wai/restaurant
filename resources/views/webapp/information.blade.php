@extends('layouts.web_app')

@section('content')
    <div class="container">
        <header>
            <div class="system-bar">
                <div class="row">
                    <div class="col-10 left">Your Information</div>
                    <div class="col-2 right">
                        <a href="/information/{{$reservation->order_id}}" class="header-icon">
                            <img src="{{ asset('assets/images/information.png') }}" alt="" >
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <div class="mt-3 page">

            <div class="information-card text-center">

                @include('layouts.error')

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

                    <button type="submit" class="mt-5 px-3 btn btn-primary">Reserved</button>
                </form>
            </div>
        </div>
    </div>
@endsection
