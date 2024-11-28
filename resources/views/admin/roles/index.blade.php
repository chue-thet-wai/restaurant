@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card" id="custom-card">
        <div class="card-body">  
            <div class="button-container">
                <a href="{{ route('roles.index') }}" id="list-btn" class="list-active">
                    Role List
                </a>
                @if ($check_permission['create'])
                    <a href="{{ route('roles.create') }}" id="add-btn">
                        Add New Role
                    </a>
                @endif
            </div>    
            
            @include('components.common_table', [
                'headers' => ['Name'],
                'fields' => ['name'],
                'items' => $roles,
                'permissions' => $check_permission,
                'action'       => true,
                'destroyRoute' => 'roles.destroy',
                'showRoute' => 'roles.show',
                'editRoute' => 'roles.edit'
            ])
        </div>
    </div>
</x-container>

@endsection
