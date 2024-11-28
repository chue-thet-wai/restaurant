@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card" id="custom-card">
        <div class="card-body">  
            <div class="button-container">
                <a href="{{ route('admin_reservation.index') }}" id="list-btn" class="list-active">
                    Reservation List
                </a>
                @if ($check_permission['edit'])
                    <a href="{{ route('admin_reservation.create') }}" id="add-btn">
                        Add New Reservation
                    </a>
                @endif
            </div>    
    
            <!-- Filter Form -->
            <form method="POST" action="{{ route('admin_reservation.index') }}" class="p-3">
            @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="status"><strong>Order ID</strong></label>
                        <input type="text" name="reservation_orderId" class="form-control" placeholder="Order ID" value="{{ request()->input('reservation_orderid') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="branch"><strong>Branch</strong></label>
                        <select class="form-control" id="branch" name="reservation_branch">
                            <option value=''>--Select--</option>
                            @foreach($branches as $branch)
                                @if ($branch->id == request()->input('reservation_branch'))
                                    <option value="{{ $branch->id }}" selected>{{ $branch->name }}</option>
                                @else
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status"><strong>Date</strong></label>
                        <input type="date" name="reservation_date" class="form-control" value="{{ request()->input('reservation_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="status"><strong>Time</strong></label>
                        <input type="time" name="reservation_time" class="form-control" value="{{ request()->input('reservation_time') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="status"><strong>Status</strong></label>
                        <select class="form-select" id="status" name="reservation_status">
                            <option value=''>--Select--</option>
                            @foreach($status_arr as $key => $value)
                            <option value="{{$key}}" 
                            @if (request()->input('reservation_status') == $key)
                                selected
                            @endif
                            >{{$value}}</option>
                            @endforeach
                        </select>
                    </div>                   
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <button type="submit" name="action" value="search" class="btn btn-primary">Filter</button>
                        <button type="submit" name="action" value="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
            </form>
            <!-- End Filter Form -->
            
            @include('components.common_table', [
                'headers' => ['Order ID','Branch','Email','Phone','Date','Time','Seat Count','Status'],
                'fields' => ['order_id','branch_name','email','phone','date','time','seat_count','status'],
                'items' => $reservation_list,
                'permissions' => $check_permission,
                'action'       => true,
                'destroyRoute' => 'admin_reservation.destroy',
                'showRoute' => 'admin_reservation.show',
                'editRoute' => 'admin_reservation.edit'
            ])
        </div>
    </div>
</x-container>

@endsection
