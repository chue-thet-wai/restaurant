@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card" id="custom-card">     
        <div class="card-body">
            <div class="button-container">
                <a href="{{ route('roles.index') }}" id="list-btn">
                    Role List
                </a>
                <a href="{{ route('roles.create') }}" id="add-btn" class="add-active">
                    Add New Role
                </a>
            </div>   
            <form class="mt-3" action="{{ route('roles.store') }}" method="post">
                @csrf

                <div class="mb-5 row">
                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Name:</strong></label>
                    <div class="col-md-6">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <div class="row g-4 ms-1" style="display: flex;overflow-x: auto;">
                            <table cellpadding="0" cellspacing="0" class="datatable table table-striped table-bordered" style="text-align:center;">
                                <thead style="background-color: #212529 !important;color: #fff;vertical-align: middle !important;">
                                    <tr>
                                        <th rowspan='2' style="width:10%;">Menu</th>
                                        <th colspan='6'>Actions</th>
                                    </tr>
                                    <tr>
                                        <th style="width:5%;">List</th>
                                        <th style="width:5%;">Create</th>
                                        <th style="width:5%;">View</th>
                                        <th style="width:5%;">Edit</th>
                                        <th style="width:5%;">Delete</th>
                                        <!-- <th style="width:5%;">Download</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($menu) && $menu->count())
                                        @foreach($menu as $value)
                                            <tr>
                                                <td style="text-align: left; padding-left: 2%;"><label>{{ $value->sub_menu }}</label></td>
                                                <td>
                                                    @if (array_key_exists("list", $permission[$value->id]))
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission[$value->id]['list'] }}" class="name">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (array_key_exists("create", $permission[$value->id]))
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission[$value->id]['create'] }}" class="name create-checkbox">
                                                    @endif
                                                    @if (array_key_exists("store", $permission[$value->id]))
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission[$value->id]['store'] }}" class="name store-checkbox" style="display:none;">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (array_key_exists("view", $permission[$value->id]))
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission[$value->id]['view'] }}" class="name">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (array_key_exists("edit", $permission[$value->id]))
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission[$value->id]['edit'] }}" class="name edit-checkbox">
                                                    @endif
                                                    @if (array_key_exists("update", $permission[$value->id]))
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission[$value->id]['update'] }}" class="name update-checkbox" style="display:none;">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (array_key_exists("delete", $permission[$value->id]))
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission[$value->id]['delete'] }}" class="name">
                                                    @endif
                                                </td>
                                                <!--<td>
                                                    @if (array_key_exists("download", $permission[$value->id]))
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission[$value->id]['download'] }}" class="name">
                                                    @endif
                                                </td>-->
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8">There are no data.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>   
                
                <div class="mb-3 me-5 row justify-content-end">
                    <input type="submit" class="col-1 btn" id="btn-apply" value="Apply">
                    <a class="col-1 btn ms-2" id="btn-cancel" href="{{ route('roles.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-container>

<script>
    document.querySelectorAll('.create-checkbox').forEach(function(createCheckbox) {
        createCheckbox.addEventListener('change', function() {
            this.closest('tr').querySelector('.store-checkbox').checked = this.checked;
        });
    });

    document.querySelectorAll('.edit-checkbox').forEach(function(editCheckbox) {
        editCheckbox.addEventListener('change', function() {
            this.closest('tr').querySelector('.update-checkbox').checked = this.checked;
        });
    });
</script>

@endsection
