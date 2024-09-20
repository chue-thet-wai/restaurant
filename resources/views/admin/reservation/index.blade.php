@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card">
        <div class="card-header">Manage Reservation</div>
        <div class="card-body">
            @if ($check_permission['create'])
                <a href="{{ route('reservation.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Reservation</a>
            @endif
            
            @include('components.common_table', [
                'headers' => ['Order ID','Branch','Email','Date','Time','Seat Count','Status'],
                'fields' => ['order_id','branch_name','email','date','time','seat_count','status'],
                'items' => $reservation_list,
                'permissions' => $check_permission,
                'action'       => true,
                'destroyRoute' => 'reservation.destroy',
                'showRoute' => 'reservation.show',
                'editRoute' => 'reservation.edit'
            ])
        </div>
    </div>
</x-container>

@endsection
