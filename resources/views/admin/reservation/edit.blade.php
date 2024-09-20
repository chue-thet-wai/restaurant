@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Edit Reservation
            </div>
            <div class="float-end">
                <a href="{{ route('reservation.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('reservation.update', $reservation->id) }}" method="post">
                @csrf
                @method("PUT")

                <div class="mb-3 row">
                    <label for="order_id" class="col-md-4 col-form-label text-md-end text-start"><strong>Order ID:</strong></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="order_id" name="order_id" value="{{ $reservation->order_id }}" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="branch" class="col-md-4 col-form-label text-md-end text-start"><strong>Branch:</strong></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="branch" name="branch" value="{{ $reservation->branch_name }}" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="date" class="col-md-4 col-form-label text-md-end text-start"><strong>Date:</strong></label>
                    <div class="col-md-6">
                        <input type="date" class="form-control" id="date" name="date" value="{{ $reservation->date }}" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="time" class="col-md-4 col-form-label text-md-end text-start"><strong>Time:</strong></label>
                    <div class="col-md-6">
                        <input type="time" class="form-control" id="time" name="time" value="{{ $reservation->time }}" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="email" class="col-md-4 col-form-label text-md-end text-start"><strong>Email:</strong></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="email" name="email" value="{{ $reservation->email }}" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="status" class="col-md-4 col-form-label text-md-end text-start"><strong>Status:</strong></label>
                    <div class="col-md-6">
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                            @foreach($status as $key => $value)
                                <option value="{{ $key }}" {{ $reservation->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('status'))
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Update Branch">
                </div>
                
            </form>
        </div>
    </div>
</x-container>
    
@endsection