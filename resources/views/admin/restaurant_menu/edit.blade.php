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
            <form class="mt-3" action="{{ route('restaurant_menu.update', $restaurant_menu->id) }}" method="post" enctype="multipart/form-data">
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
                    <label for="rating" class="col-md-4 col-form-label text-md-end text-start"><strong>Rating:</strong></label>
                    <div class="col-md-6">
                        <select class="form-control @error('rating') is-invalid @enderror" id="rating" name="rating">
                            @for($i = 0; $i <= 5; $i++)
                                <option value="{{ $i }}" @if ($i == $restaurant_menu->rating) selected @endif>{{ $i }}</option>
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