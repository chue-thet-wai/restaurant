@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Edit Branch
            </div>
            <div class="float-end">
                <a href="{{ route('branch.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('branch.update', $branch->id) }}" method="post">
                @csrf
                @method("PUT")

                <div class="mb-3 row">
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

                <div class="mb-3 row">
                    <label for="remark" class="col-md-4 col-form-label text-md-end text-start"><strong>Remark:</strong></label>
                    <div class="col-md-6">
                        <textarea class="form-control @error('remark') is-invalid @enderror" name="remark" id="remark">{{ $branch->remark }}</textarea>
                        @if ($errors->has('remark'))
                            <span class="text-danger">{{ $errors->first('remark') }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Update Branch">
                </div>
                
            </form>
        </div>
    </div>
</x-container>
    
@endsection