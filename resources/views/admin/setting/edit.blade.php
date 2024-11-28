@extends('layouts.admin_app')

@section('content')

<div class="setting_container m-5">
    <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h2 class="mb-4">Profile Settings</h2>

        <div class="profile-row mb-3">
            <div class="profile-picture">
                <input type="hidden" value="{{ $user->profile }}" id="previousProfileImage" name="previous_profile" />
                @if ($user->profile)
                    <img id="settingProfileImage" src="{{ asset('assets/profiles/'.$user->profile) }}" alt="Profile Picture" class="img-thumbnail">
                @else
                    <img id="settingProfileImage" src="{{ asset('assets/images/profile.png') }}" alt="Profile Picture" class="img-thumbnail">
                @endif
            </div>
            <div class="profile-title col-md-4">
                <h5 class="mt-2">Profile Picture</h5>
                <p class="text-muted">PNG, JPEG under 15MB</p>
            </div>
            <div class="profile-actions col-md-4">
                <label class="btn btn-primary mb-2">
                    Upload New Picture
                    <input type="file" id="settingProfileImageUpload" name="profile_image" accept="image/*" style="display: none;">
                </label>
                <button class="btn btn-danger" id="deleteSettingProfileImage">Delete</button>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required value="{{ $user->first_name }}">
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required value="{{ $user->last_name }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current password">
            </div>
            <div class="col-md-6">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New password">
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="language" class="form-label">Language</label>
                <select class="form-select" id="language" name="language">
                    <option selected>English</option>
                </select>
            </div>
        </div>

        <div class="me-2 row justify-content-end">
            <input type="submit" class="mt-3 col-1 btn" id="btn-apply" value="Apply">
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#settingProfileImageUpload').on('change', function(event) {
        const reader = new FileReader();
        reader.onload = function() {
            $('#settingProfileImage').attr('src', reader.result);
        };
        reader.readAsDataURL(event.target.files[0]);
    });

    $('#deleteSettingProfileImage').on('click', function() {
        event.preventDefault();
        const defaultImage = "{{ asset('assets/images/profile.png') }}"; 
        $('#settingProfileImage').attr('src', defaultImage);
        $('#settingProfileImageUpload').val(''); 
    });
});
</script>

@endsection
