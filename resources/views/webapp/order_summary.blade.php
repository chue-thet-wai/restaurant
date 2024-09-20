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
                <div class="col-2 right"></div>
            </div>
        </div>
    </header>

    <div class="mt-5 page">
        @include('layouts.error')  <!-- Display error messages here -->

        <!-- Order Summary Section -->
        <div class="order-summary-card text-left mx-auto">
            <h5>Order summary</h5>
            
            <!-- Cart Items -->
            <table class="list-group mb-3">
                @foreach ($cart as $id => $item)
                    <tr class="list-group-item d-flex justify-content-between lh-sm">
                        <td>
                            <span class="text-muted">{{ $item['menu_name'] }}<br>
                                <small>{{ "" }}</small>
                            </span>
                        </td>
                        <td class="text-muted">{{ $item['quantity'] }}</td>
                        <td class="text-muted">MMK {{ number_format($item['price']) }}</td>
                    </tr>
                @endforeach

                <!-- Subtotal and Tax Section -->
                <tr class="list-group-item d-flex justify-content-between lh-sm" id="ordersummary-subtotal">
                    <td class="text-muted">Subtotal</td>
                    <td></td>
                    <td class="text-muted">MMK {{ number_format($total) }}</td>
                </tr>
                <tr class="list-group-item d-flex justify-content-between lh-sm" id="ordersummary-tax">
                    <td class="text-muted">Tax</td>
                    <td></td>
                    <td class="text-muted">MMK {{ number_format($tax) }}</td>
                </th>
            </table>

            <!-- Total Section -->
            <div class="btn btn-primary btn-block mt-4 d-flex justify-content-between">
                <strong>Total (incl. fees and tax)</strong>
                <strong>MMK {{ number_format($total + $tax) }}</strong>
            </div>

            <!-- Form for Receipt Upload and Confirmation -->
            <form action="{{ route('order.confirm', ['orderID' => $reservation->order_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Receipt Upload -->
                <div class="form-group mt-3">
                    <label for="receipt">Please attach your receipt</label>
                    <input type="file" class="form-control" id="receipt" name="receipt" required>
                </div>

                <!-- Confirm Button -->
                <button type="submit" class="btn btn-primary btn-block mt-4">Confirm</button>
            </form>
        </div>
    </div>
</div>
@endsection
