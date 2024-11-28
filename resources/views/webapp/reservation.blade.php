@extends('layouts.web_app')

@section('content')
    <div class="container">
        <header>
            <div class="system-bar">
                <div class="row">
                    <div class="col-10"></div>
                    <div class="col-2 right">
                        <a href="" class="header-icon">
                            <img src="{{ asset('assets/images/translate.png') }}" alt="" >
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <div class="mt-3 page">
            <div class="reservation-card text-center">
                @include('layouts.error')  <!-- Display error messages here -->

                <h3>Welcome</h3>
                <p>Our New Experience</p>
                <br />
                <h4>RESERVATION</h4>

                <form class="px-3 mt-2" method="POST" action="{{ route('webapp.reservation.store') }}">
                    @csrf
                    <div class="mb-3">
                        <select class="form-control" id="branch" name="branch" required>
                            <option value="" disabled selected>Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <input type="email" class="form-control email-input" id="email" name="email" required placeholder="Email">
                    </div>

                    <div class="mb-3">
                        <input type="tel" class="form-control tel-input" id="phone" name="phone" required placeholder="Phone">
                    </div>

                    <div class="mb-3 position-relative">
                        <input type="date" class="form-control" id="date" name="date" required min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                        <img src="{{ asset('assets/images/calendar.png') }}" alt="Calendar Icon" class="custom-icon">
                    </div>

                    <div class="mb-3 position-relative">
                        <select class="form-control" id="time" name="time" required>
                            @foreach($timeslots as $timeslot)
                                <option value="{{ $timeslot->time }}">{{ \Carbon\Carbon::parse($timeslot->time)->format('g:i A') }}</option>
                            @endforeach
                        </select>
                        <img src="{{ asset('assets/images/clock.png') }}" alt="Clock Icon" class="custom-icon">
                    </div>

                    <!-- Seat count with increment/decrement -->
                    <div class="d-flex justify-content-end mb-3 align-items-center">
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control me-2" id="seat-count" name="seat_count" value="1" min="1" readonly>
                            <button type="button" class="btn btn-outline-light me-2" id="decrement">
                                <img src="{{ asset('assets/images/sub.png') }}" alt="">
                            </button>
                            <button type="button" class="btn btn-outline-light" id="increment">
                                <img src="{{ asset('assets/images/add.png') }}" alt="">
                            </button>
                        </div>
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="mt-4 p-2 btn btn-primary w-100">Check Available</button>
                </form>

                <!-- Logo -->
                <div class="mt-5 mb-3 logo">
                    <img src="{{ asset('assets/images/techy-solutions.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection
