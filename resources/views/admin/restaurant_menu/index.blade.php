@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card" id="custom-card">
        <div class="card-body">  
            <div class="button-container">
                <a href="{{ route('restaurant_menu.index') }}" id="list-btn" class="list-active">
                    Menu List
                </a>
                @if ($check_permission['create'])
                    <a href="{{ route('restaurant_menu.create') }}" id="add-btn">
                        Add New Menu
                    </a>
                @endif
            </div>    
            
            @include('components.common_table', [
                'headers' => ['Name','Status','Price','Rating','Description'],
                'fields' => ['name','status','price','rating','description'],
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
