@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Edit Menu
            </div>
            <div class="float-end">
                <a href="{{ route('restaurant_menu.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('restaurant_menu.update', $restaurant_menu->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method("PUT")

                <div class="mb-3 row">
                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Name</strong></label>
                    <div class="col-md-6">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $restaurant_menu->name }}">
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
                                <option value="{{ $category->id }}" {{ $restaurant_menu->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('category'))
                            <span class="text-danger">{{ $errors->first('category') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="status" class="col-md-4 col-form-label text-md-end text-start"><strong>Status:</strong></label>
                    <div class="col-md-6">
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                            @foreach($status as $key => $value)
                                <option value="{{ $key }}" {{ $restaurant_menu->status == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('status'))
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="price" class="col-md-4 col-form-label text-md-end text-start"><strong>Price:</strong></label>
                    <div class="col-md-6">
                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ $restaurant_menu->price }}">
                        @if ($errors->has('price'))
                            <span class="text-danger">{{ $errors->first('price') }}</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="image" class="col-md-4 col-form-label text-md-end text-start"><strong>Upload Image:</strong></label>
                    <div class="col-md-6 d-flex">
                        <input type="hidden" id="previous_image" name="previous_image" value="{{$restaurant_menu->menu_image}}">
                        <div class="image-preview-container">
                            <div class="preview">
                                <img id="preview-selected-image" src="{{asset('assets/menu_images/'.$restaurant_menu->menu_image)}}" style='display: block;' />
                            </div>
                            <label for="file-upload">Upload Image</label>
                            <input type="file" id="file-upload" name="menu_image" accept="image/*" onchange="previewImage(event);" />
                        </div>
                    </div>
                </div>  
                
                <div class="mb-3 row">
                    <label for="description" class="col-md-4 col-form-label text-md-end text-start"><strong>Description:</strong></label>
                    <div class="col-md-6">
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="remark">{{ $restaurant_menu->description }}</textarea>
                        @if ($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Update Menu">
                </div>
                
            </form>
        </div>
    </div>
</x-container>
    
@endsection