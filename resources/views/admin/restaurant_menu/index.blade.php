@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card">
        <div class="card-header">Manage Restaurant Menu</div>
        <div class="card-body">
            @if ($check_permission['create'])
                <a href="{{ route('restaurant_menu.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Menu</a>
            @endif
            
            @include('components.common_table', [
                'headers' => ['Name','Status','Price','Description'],
                'fields' => ['name','status','price','description'],
                'items' => $restaurant_menus,
                'permissions' => $check_permission,
                'action'       => true,
                'destroyRoute' => 'restaurant_menu.destroy',
                'showRoute' => 'restaurant_menu.show',
                'editRoute' => 'restaurant_menu.edit'
            ])
        </div>
    </div>
</x-container>

@endsection
