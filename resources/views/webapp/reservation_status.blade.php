@extends('layouts.web_app')

@section('content')
<div class="container">
    <div class="mt-3 page">
        @include('layouts.error')

        <div class="reservation-status-card text-center" id="reservation-status-card">
            @if($reservation->status == '2') 
                <div class="card card-success">
                    <a href="/reservation"><button type="button" class="close">&times;</button></a>
                    <h3 class="card-title">Congratulation!</h3>
                    <p>Your booking is <strong>available</strong></p>
                    <p>Please fill up your information</p>
                    <a href="/information/{{$reservation->order_id}}"><button type="button" class="btn btn-primary" id="card-submit">OK</button></a>
                </div>
            @else
                <div class="card card-failure">
                    <a href="/reservation"><button type="button" class="close">&times;</button></a>
                    <h3 class="card-title">Sorry!</h3>
                    <p>Your booking is <span class="text-danger">unavailable</span></p>
                    <p>Please make for next reservation</p>
                    <a href="/reservation"><button type="button" class="btn btn-primary" id="card-submit">OK</button></a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
