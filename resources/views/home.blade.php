@extends('layouts.admin_app')

@section('content')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="{{ asset('js/dashboard.js') }}" defer></script>

<div class="dashboard-container container">
    <div class="row justify-content-center">
        <div class="col-md-12">          
            <div class="card-body m-2">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-2">
                        <div class="dashboard-card card">
                            <div class="card-content">
                                <span class="card-title">All Booking</span>
                                <div class="card-number">
                                    <span>{{$allBooking}}</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('admin_reservation.index') }}" class="more-info">More Info &nbsp; 
                                    <i class="bi bi-arrow-right-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-2">
                        <div class="dashboard-card card">
                            <div class="card-content">
                                <span class="card-title">New Booking</span>
                                <div class="card-number">
                                    <span>{{$newBooking}}</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('admin_reservation.index', ['reservation_status' => '1']) }}" class="more-info">More Info &nbsp; 
                                    <i class="bi bi-arrow-right-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-2">
                        <div class="dashboard-card card">
                            <div class="card-content">
                                <span class="card-title">Accepted Booking</span>
                                <div class="card-number">
                                    <span>{{$acceptedBooking}}</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('admin_reservation.index', ['reservation_status' => '2']) }}" class="more-info">More Info &nbsp; 
                                    <i class="bi bi-arrow-right-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-2">
                        <div class="dashboard-card card">
                            <div class="card-content">
                                <span class="card-title">Rejected Booking</span>
                                <div class="card-number">
                                    <span>{{$rejectedBooking}}</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('admin_reservation.index', ['reservation_status' => '3']) }}" class="more-info">More Info &nbsp; 
                                    <i class="bi bi-arrow-right-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-2">
                        <div class="dashboard-card card">
                            <div class="card-content">
                                <span class="card-title">New Order</span>
                                <div class="card-number">
                                    <span>{{$newOrder}}</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('order_management.index') }}" class="more-info">More Info &nbsp; 
                                    <i class="bi bi-arrow-right-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>                                                                       
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
