@extends('layouts.admin_app')

@section('content')
<x-container>
    <div class="card" id="custom-card">     
        <div class="card-body">
            <div class="button-container">
                <a href="{{ route('users.index') }}" id="list-btn">
                    User List
                </a>
                <a href="{{ route('users.create') }}" id="add-btn" class="add-active">
                    Add New User
                </a>
            </div>   
            <form class="mt-5" action="{{ route('users.store') }}" method="post">
                @csrf

                <div class="mb-3 row">
                    <label for="first_name" class="col-md-4 col-form-label text-md-end text-start">First Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}">
                        @if ($errors->has('first_name'))
                            <span class="text-danger">{{ $errors->first('first_name') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="last_name" class="col-md-4 col-form-label text-md-end text-start">Last Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}">
                        @if ($errors->has('last_name'))
                            <span class="text-danger">{{ $errors->first('last_name') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email Address</label>
                    <div class="col-md-6">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="password" class="col-md-4 col-form-label text-md-end text-start">Password</label>
                    <div class="col-md-6">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="password_confirmation" class="col-md-4 col-form-label text-md-end text-start">Confirm Password</label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="roles" class="col-md-4 col-form-label text-md-end text-start">Roles</label>
                    <div class="col-md-6">
                        @foreach ($roles as $role)
                            <div class="form-check">
                                <input class="form-check-input @error('roles') is-invalid @enderror" type="checkbox" id="role_{{ $role->id }}" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                        @endforeach
                        @if ($errors->has('roles'))
                            <span class="text-danger">{{ $errors->first('roles') }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3 me-5 row justify-content-end">
                    <input type="submit" class="col-1 btn" id="btn-apply" value="Apply">
                    <a class="col-1 btn ms-2" id="btn-cancel" href="{{ route('users.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-container>   
@endsection