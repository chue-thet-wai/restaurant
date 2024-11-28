@extends('layouts.admin_app')

@section('content')

<x-container>
<div class="card" id="custom-card">     
        <div class="card-body">
            <div class="button-container">
                <a href="{{ route('branch.index') }}" id="list-btn">
                    Branch List
                </a>
                <a href="{{ route('branch.create') }}" id="add-btn" class="add-active">
                    Add New Branch
                </a>
            </div>   
            <form class="mt-3" action="{{ route('branch.store') }}" method="post">
                @csrf

                <div class="mb-3 row">
                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Name:</strong></label>
                    <div class="col-md-6">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="phone" class="col-md-4 col-form-label text-md-end text-start"><strong>phone:</strong></label>
                    <div class="col-md-6">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                        @if ($errors->has('phone'))
                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="address" class="col-md-4 col-form-label text-md-end text-start"><strong>Address:</strong></label>
                    <div class="col-md-6">
                        <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="remark">{{ old('address') }}</textarea>
                        @if ($errors->has('address'))
                            <span class="text-danger">{{ $errors->first('address') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="remark" class="col-md-4 col-form-label text-md-end text-start"><strong>Remark:</strong></label>
                    <div class="col-md-6">
                        <textarea class="form-control @error('remark') is-invalid @enderror" name="remark" id="remark">{{ old('remark') }}</textarea>
                        @if ($errors->has('remark'))
                            <span class="text-danger">{{ $errors->first('remark') }}</span>
                        @endif
                    </div>
                </div>
                
                @php
                    $days =  ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                @endphp

                @foreach($days as $day)
                    <div class="mb-3 row">
                        <label for="day" class="col-md-4 col-form-label text-md-end text-start text-capitalize"><strong>{{ ucfirst($day) }}:</strong></label>
                        <div class="col-md-4">
                            <input type="time" class="form-control d-inline-block w-auto" name="opening_time[{{ $day }}]">
                            <input type="time" class="form-control d-inline-block w-auto" name="closing_time[{{ $day }}]">
                            @if ($errors->has("opening_time.{$day}") || $errors->has("closing_time.{$day}"))
                                <br /><span class="text-danger">{{ $errors->first("opening_time.{$day}") ?? $errors->first("closing_time.{$day}") }}</span>
                            @endif
                        </div>
                        <div class="col-md-2 form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="offday-{{ $day }}" name="offday[{{ $day }}]" {{ old("offday.{$day}") ? 'checked' : '' }}>
                            <label class="form-check-label" for="offday-{{ $day }}">Offday</label>
                        </div>
                    </div>
                @endforeach
                
                <div class="mb-3 me-5 row justify-content-end">
                    <input type="submit" class="col-1 btn" id="btn-apply" value="Apply">
                    <a class="col-1 btn ms-2" id="btn-cancel" href="{{ route('branch.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-container>

@endsection
