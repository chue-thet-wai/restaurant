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
            <form action="{{ route('branch.update', $branch->id) }}" method="post">
                @csrf
                @method("PUT")

                <div class="mt-3 mb-3 row">
                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Name:</strong></label>
                    <div class="col-md-6">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $branch->name }}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="status" class="col-md-4 col-form-label text-md-end text-start"><strong>Status:</strong></label>
                    <div class="col-md-6">
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                            @foreach($status as $key => $value)
                                <option value="{{ $key }}" {{ $branch->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('status'))
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mt-3 mb-3 row">
                    <label for="phone" class="col-md-4 col-form-label text-md-end text-start"><strong>Phone:</strong></label>
                    <div class="col-md-6">
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ $branch->phone }}">
                        @if ($errors->has('phone'))
                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="address" class="col-md-4 col-form-label text-md-end text-start"><strong>Address:</strong></label>
                    <div class="col-md-6">
                        <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address">{{ $branch->address }}</textarea>
                        @if ($errors->has('address'))
                            <span class="text-danger">{{ $errors->first('address') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="remark" class="col-md-4 col-form-label text-md-end text-start"><strong>Remark:</strong></label>
                    <div class="col-md-6">
                        <textarea class="form-control @error('remark') is-invalid @enderror" name="remark" id="remark">{{ $branch->remark }}</textarea>
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
                            <input type="time" class="form-control d-inline-block w-auto" name="opening_time[{{ $day }}]" 
                                value="{{ old('opening_time.' . $day, $openingTimes[$day]['start_time'] ?? '') }}">
                            <input type="time" class="form-control d-inline-block w-auto" name="closing_time[{{ $day }}]" 
                                value="{{ old('closing_time.' . $day, $openingTimes[$day]['end_time'] ?? '') }}">
                            @if ($errors->has("opening_time.{$day}") || $errors->has("closing_time.{$day}"))
                                <br /><span class="text-danger">{{ $errors->first("opening_time.{$day}") ?? $errors->first("closing_time.{$day}") }}</span>
                            @endif
                        </div>
                        <div class="col-md-2 form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="offday-{{ $day }}" name="offday[{{ $day }}]" 
                                {{ old('offday.' . $day, $openingTimes[$day]['is_offday'] ?? false) ? 'checked' : '' }}>
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