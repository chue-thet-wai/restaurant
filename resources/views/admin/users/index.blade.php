@extends('layouts.admin_app')

@section('content')
<x-container>
    <div class="card" id="custom-card">
        <div class="card-body">  
            <div class="button-container">
                <a href="{{ route('users.index') }}" id="list-btn" class="list-active">
                    User List
                </a>
                @if ($check_permission['create'])
                    <a href="{{ route('users.create') }}" id="add-btn">
                        Add New User
                    </a>
                @endif
            </div>       

            @include('components.common_table', [
                'headers' => ['First Name','Last Name', 'Email'],
                'fields' => ['first_name','last_name', 'email'],
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
