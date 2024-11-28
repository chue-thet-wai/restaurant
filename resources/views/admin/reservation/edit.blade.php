@extends('layouts.admin_app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/reservation.js') }}"></script>

@section('content')
<x-container>
    <div class="card" id="custom-card">     
        <div class="card-body">
            <div class="button-container">
                <a href="{{ route('admin_reservation.index') }}" id="list-btn">
                    Reservation List
                </a>
                <a href="{{ route('admin_reservation.create') }}" id="add-btn" class="add-active">
                    Add New Reservation
                </a>
            </div>  
            <form class="mt-3" action="{{ route('admin_reservation.update', $reservation->id) }}" method="post" id="reservatoin_edit">
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
                    <label for="phone" class="col-md-4 col-form-label text-md-end text-start"><strong>Phone:</strong></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $reservation->phone }}" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="status" class="col-md-4 col-form-label text-md-end text-start"><strong>Status:</strong></label>
                    <div class="col-md-6">
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" onchange="reservationRejectNote()">
                            @foreach($status as $key => $value)
                                <option value="{{ $key }}" {{ $reservation->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('status'))
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row" id="reject_note_row" style="display: none;">
                    <label for="reject_note" class="col-md-4 col-form-label text-md-end text-start"><strong>Note:</strong></label>
                    <div class="col-md-6">
                        <textarea class="form-control @error('reject_note') is-invalid @enderror" name="reject_note" id="reject_note">{{ old('reject_note', $reservation->reject_note) }}</textarea>
                        @if ($errors->has('reject_note'))
                            <span class="text-danger">{{ $errors->first('reject_note') }}</span>
                        @endif
                    </div>
                </div>

                @if ($reservation->status == 2)
                    <div class="mb-3 row">
                        <label for="table" class="col-md-4 col-form-label text-md-end text-start"><strong>Choose Table:</strong></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="table" name="table" value="{{ $reservation->table_name }}" readonly>
                        </div>
                    </div>
                @else
                    <div class="mb-3 row">
                        <label for="table" class="col-md-4 col-form-label text-md-end text-start"><strong>Choose Table:</strong></label>
                        <div class="col-md-6">
                            <select class="form-control @error('table') is-invalid @enderror" id="table" name="table">
                                <option value="">Select Table</option>
                                @foreach($available_tables as $value)
                                    <option value="{{ $value->id }}" {{ $reservation->table_id == $value->id ? 'selected' : '' }}>{{ $value->table_name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('table'))
                                <span class="text-danger">{{ $errors->first('table') }}</span>
                            @endif
                        </div>
                    </div>
                @endif
                
                <div class="mb-3 me-5 row justify-content-end">
                    <input type="submit" class="col-1 btn" id="btn-apply" value="Apply">
                    <a class="col-1 btn ms-2" id="btn-cancel" href="{{ route('admin_reservation.index') }}">Cancel</a>
                </div>
                
            </form>
        </div>
    </div>
</x-container>
    
@endsection
