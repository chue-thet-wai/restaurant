@extends('layouts.admin_app')

@section('content')
<x-container>
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Edit User
            </div>
            <div class="float-end">
                <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="post">
                @csrf
                @method("PUT")

                <div class="mb-3 row">
                    <label for="name" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user->name }}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email Address</label>
                    <div class="col-md-6">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $user->email }}">
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
                                <input type="checkbox" class="form-check-input" id="role-{{ $role->id }}" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, $userRoles ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>
                            </div>
                        @endforeach
                        @if ($errors->has('roles'))
                            <span class="text-danger">{{ $errors->first('roles') }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Update User">
                </div>
                
            </form>
        </div>
    </div>
</x-container>   
@endsection