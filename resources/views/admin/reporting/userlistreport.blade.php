@extends('layouts.admin_app')

@section('content')
<x-container>
    <div class="card">
        <div class="card-header">User List</div>	
        <div class="card-body">
            <form class="row g-4" method="POST" action="{{ url('admin/reporting/user_list') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-3">
                        <label for="userlist_name"><b>Name</b></label>
                        <input type="text" name="userlist_name" class="form-control" value="{{ request()->input('userlist_name') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="userlist_status"><b>Status</b></label>
						<select class="form-select" id="userlist_status" name="userlist_status">
							@if (request()->input('userlist_status') == "" || request()->input('userlist_status')=="99")
								<option value="99" selected>Select</option>
							@else
								<option value="99">Select</option>
							@endif
                            @foreach ($user_status as $key => $value)
                                <option value="{{ $key }}" {{ $key == request()->input('userlist_status') ? 'selected' : '' }} >
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="userlist_joineddate"><b>Joined Date</b></label>
                        <input type="date" name="userlist_joineddate" class="form-control" value="{{ request()->input('userlist_joineddate') }}">
                    </div>
                </div>
                
                <div class="row p-3">
                    <div class="col-md-1 p-2">
                        <div class="d-grid">
                            <button type="submit" name="action" value="search" class="btn btn-sm btn-primary">Search</button>
                        </div>
                    </div>
                    <div class="col-md-1 p-2">
                        <div class="d-grid">
                            <button type="submit" name="action" value="reset" class="btn btn-sm btn-secondary">Reset</button>
                        </div>
                    </div>	
                    <div class="col-md-9"></div>
                    <div class="col-md-1 p-2">
                        <div class="d-grid">
                            <button type="submit" name="action" value="export" class="btn btn-sm btn-success">Export</button>
                        </div>
                    </div>					
                </div>
            </form>
            <br />
            @include('components.common_table', [
                'headers' => ['Name','Email','Phone','Gender','Country','City','Status','Joined Date'],
                'fields' => ['name', 'email', 'phone', 'gender', 'country', 'city', 'status', 'joined_date'],
                'items'       => $result,
                'permissions' => $check_permission,
                'action'      => false,
                'paginate'    => $list_result
            ])
        </div>
    </div>
</x-container>
@endsection
