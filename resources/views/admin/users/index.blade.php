@extends('layouts.admin_app')

@section('content')
<x-container>
    <div class="card">
        <div class="card-header">Manage Users</div>
        <div class="card-body">
            @if ($check_permission['create'])
                <a href="{{ route('users.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New User</a>
            @endif
            
            @include('components.common_table', [
                'headers' => ['Name', 'Email'],
                'fields' => ['name', 'email'],
                'items' => $users,
                'permissions' => $check_permission,
                'action'       => true,
                'destroyRoute' => 'users.destroy',
                'showRoute' => 'users.show',
                'editRoute' => 'users.edit'
            ])
        </div>
    </div>
</x-container>
@endsection
