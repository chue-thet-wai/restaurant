@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card" id="custom-card">
        <div class="card-body">  
            <div class="button-container">
                <a href="{{ route('order_management.index') }}" id="list-btn" class="list-active">
                    Order List
                </a>
                <!--@if ($check_permission['edit'])
                    <a href="{{ route('admin_reservation.create') }}" id="add-btn">
                        Add New Reservation
                    </a>
                @endif-->
            </div>    
    
            <!-- Filter Form -->
            <form method="POST" action="{{ route('order_management.index') }}" class="p-3">
            @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="branch"><strong>Branch</strong></label>
                        <select class="form-control" id="branch" name="ordermanagement_branch">
                            <option value=''>--Select--</option>
                            @foreach($branches as $branch)
                                @if ($branch->id == request()->input('ordermanagement_branch'))
                                    <option value="{{ $branch->id }}" selected>{{ $branch->name }}</option>
                                @else
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status"><strong>Date</strong></label>
                        <input type="date" name="ordermanagement_date" class="form-control" value="{{ request()->input('ordermanagement_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="status"><strong>Time</strong></label>
                        <input type="time" name="ordermanagement_time" class="form-control" value="{{ request()->input('ordermanagement_time') }}">
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
                'headers' => ['Order ID','Branch','Email','Phone','Date','Time','Order Count'],
                'fields' => ['order_id','branch_name','email','phone','date','time','order_count'],
                'items' => $ordermanagement_list,
                'permissions' => $check_permission,
                'action'       => true,
                'destroyRoute' => 'order_management.destroy',
                'showRoute' => 'order_management.show',
                'editRoute' => 'order_management.edit'
            ])
        </div>
    </div>
</x-container>

@endsection
