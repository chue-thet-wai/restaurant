@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card">
        <div class="card-header">Manage Roles</div>
        <div class="card-body">
            @if ($check_permission['create'])
                <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Role</a>
            @endif
            
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
