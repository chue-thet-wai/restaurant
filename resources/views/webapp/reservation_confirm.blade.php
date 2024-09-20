@extends('layouts.web_app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<div class="container">
    <header>
        <div class="system-bar">
            <div class="row">
                <div class="col-10 left">Thank You!</div>
                <div class="col-2 right">
                    <a href="" class="header-icon">
                        <i class="bi bi-info-square"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>
    <div class="mt-5 page">
        @include('layouts.error') <!-- Display error messages here -->

        <div class="reservation-confirm-card text-center" id="reservation-confirm-card">
            <form class="px-3 mt-3" method="GET">
                @csrf

                <div class="mb-3 row">
                    <label for="booking-id" class="col-sm-6 text-start col-form-label">Your Booking ID</label>
                    <div class="col-sm-6 text-end">
                        <input type="text" class="form-control" id="booking-id" name="booking-id" value="{{$reservation->order_id}}" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="start-time" class="col-sm-6 text-start col-form-label">Booking Date</label>
                    <div class="col-sm-6 text-end">
                        <input type="text" class="form-control" id="start-time" name="start-time" value="{{$reservation->date}}" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="expire-time" class="col-sm-6 text-start col-form-label">Booking Time</label>
                    <div class="col-sm-6 text-end">
                        <input type="text" class="form-control" id="expire-time" name="expire-time"  value="{{$reservation->time}}" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-12 text-start">Show your booking ID to our front desk.</label>
                </div>

                <button type="button" class="mt-3 px-3 btn btn-primary" id="save-to-gallery">Save to Gallery</button>
                <br />
                <a href="/menu/{{$reservation->order_id}}"><button type="button" class="mt-3 px-3 btn btn-primary" id="save-to-gallery">Choose Menu</button></a>
            </form>
        </div>
    </div>
</div>
@endsection
