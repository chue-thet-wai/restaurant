@extends('layouts.web_app')

@section('content')
    <div class="container">
        <header>
            <div class="system-bar">
                <div class="row">
                    <div class="col-10"></div>
                    <div class="col-2 right">
                        <a href="" class="header-icon">
                            <i class="bi bi-translate"></i>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <div class="mt-5 page">
            @include('layouts.error')  <!-- Display error messages here -->

            <div class="reservation-card text-center">
                <h3 style="color: #fff;">Welcome</h3>
                <p style="color: #b9f7f7;">Our New Experience</p>
                <br />
                <h4 style="color: #fff;">RESERVATION</h4>

                <form class="px-3 mt-3" method="POST" action="{{ route('reservation.store') }}">
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
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Email">
                    </div>
                    <div class="mb-3">
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <input type="time" class="form-control" id="time" name="time" required>
                    </div>
                    <div class="d-flex justify-content-end mb-3 align-items-center">
                        <div class="d-flex align-items-center">
                            <input type="number" class="form-control me-2" id="seat-count" name="seat_count" value="1" min="1" readonly style="background: #d4f4f4; border: none;">
                            <button type="button" class="btn btn-outline-light me-2" id="decrement">-</button>
                            <button type="button" class="btn btn-outline-light" id="increment">+</button>
                        </div>
                    </div>
                    <button type="submit" class="mt-3 p-2 btn btn-primary w-100" style="background-color: #0e728a; border: none;">Check Available</button>
                </form>
            </div>
        </div>
    </div>
@endsection
