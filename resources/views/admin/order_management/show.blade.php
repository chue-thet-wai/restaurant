@extends('layouts.admin_app')

@section('content')
<x-container>
    <div class="card" id="custom-card">
        <div class="card-body">  
            <div class="button-container mb-4">
                <a href="{{ route('order_management.index') }}" id="list-btn" class="list-active">
                    Order List
                </a>
            </div>  

            <!-- Order Details and Receipt Section -->
            <div class="row m-2">
                <!-- Order Details Card -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Order Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <strong class="col-4">Order ID</strong>
                                <strong class="col-1">:</strong>
                                <span class="flex-grow-1">{{ $reservation->order_id }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <strong class="col-4">Name</strong>
                                <strong class="col-1">:</strong>
                                <span class="flex-grow-1">{{ $reservation->name }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <strong class="col-4">Email</strong>
                                <strong class="col-1">:</strong>
                                <span class="flex-grow-1">{{ $reservation->email }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <strong class="col-4">Phone</strong>
                                <strong class="col-1">:</strong>
                                <span class="flex-grow-1">{{ $reservation->phone }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <strong class="col-4">Date</strong>
                                <strong class="col-1">:</strong>
                                <span class="flex-grow-1">{{ $reservation->date }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <strong class="col-4">Time</strong>
                                <strong class="col-1">:</strong>
                                <span class="flex-grow-1">{{ $reservation->time }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Receipt Card -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">Receipt</h5>
                        </div>
                        <div class="card-body text-center">
                            @if(file_exists(public_path('assets/receipts/' . $reservation->receipt)) && $reservation->receipt)
                                <img src="{{ asset('assets/receipts/' . $reservation->receipt) }}" alt="Receipt Image" class="img-thumbnail mt-2" style="max-width: 200px;">
                            @else
                                <p class="text-muted">Receipt image not available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items Section -->
            <div class="card m-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Remark</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalAmount = 0; @endphp
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->name }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ number_format($order->price, 2) }}</td>
                                    <td>{{ $order->remark }}</td>
                                    <td>{{ number_format($order->price * $order->quantity, 2) }}</td>
                                    @php $totalAmount += $order->price * $order->quantity; @endphp
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="text-right"><strong>Total Amount</strong></td>
                                <td><strong>{{ number_format($totalAmount, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-container>
@endsection
