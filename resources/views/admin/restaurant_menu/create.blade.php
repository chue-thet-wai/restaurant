@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card" id="custom-card">     
        <div class="card-body">
            <div class="button-container">
                <a href="{{ route('restaurant_menu.index') }}" id="list-btn">
                    Menu List
                </a>
                <a href="{{ route('restaurant_menu.create') }}" id="add-btn" class="add-active">
                    Add New Menu
                </a>
            </div>   
            <form class="mt-3" action="{{ route('restaurant_menu.store') }}" method="post" enctype="multipart/form-data">
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
                    <label for="category" class="col-md-4 col-form-label text-md-end text-start"><strong>Category:</strong></label>
                    <div class="col-md-6">
                        <select class="form-control @error('category') is-invalid @enderror" id="category" name="category">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('category'))
                            <span class="text-danger">{{ $errors->first('category') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="price" class="col-md-4 col-form-label text-md-end text-start"><strong>Price:</strong></label>
                    <div class="col-md-6">
                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}">
                        @if ($errors->has('price'))
                            <span class="text-danger">{{ $errors->first('price') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="image" class="col-md-4 col-form-label text-md-end text-start"><strong>Upload Image:</strong></label>
                    <div class="col-md-6 d-flex">
                        <div class="image-preview-container">
                            <div class="preview">
                                <img id="preview-selected-image" />
                            </div>
                            <label for="file-upload">Upload Image</label>
                            <input type="file" id="file-upload" name="menu_image" accept="image/*" onchange="previewImage(event);" />
                        </div>
                    </div>
                </div>  
                
                <div class="mb-3 row">
                    <label for="description" class="col-md-4 col-form-label text-md-end text-start"><strong>Description:</strong></label>
                    <div class="col-md-6">
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="remark">{{ old('description') }}</textarea>
                        @if ($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="rating" class="col-md-4 col-form-label text-md-end text-start"><strong>Rating:</strong></label>
                    <div class="col-md-6">
                        <select class="form-control @error('rating') is-invalid @enderror" id="rating" name="rating">
                            @for($i = 0; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        @if ($errors->has('rating'))
                            <span class="text-danger">{{ $errors->first('rating') }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3 me-5 row justify-content-end">
                    <input type="submit" class="col-1 btn" id="btn-apply" value="Apply">
                    <a class="col-1 btn ms-2" id="btn-cancel" href="{{ route('restaurant_menu.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-container>

@endsection
