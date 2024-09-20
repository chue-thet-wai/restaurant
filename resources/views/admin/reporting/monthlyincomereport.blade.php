@extends('layouts.admin_app')

@section('content')
<x-container>
    <div class="card">
        <div class="card-header">Monthly Income List</div>	
        <div class="card-body">
            <form class="row g-4" method="POST" action="{{ url('admin/reporting/monthly_income') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-3">
                        <label for="Income Type"><b>Income Type</b></label>
						<select class="form-select" id="monthlyincome_type" name="monthlyincome_type">
                            @foreach ($income_types as $key => $value)
                                <option value="{{ $key }}" {{ $key == request()->input('monthlyincome_type') ? 'selected' : '' }} >
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
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
                'headers' => ['Transaction ID', 'User Name','Plan','Amount','Date'],
                'fields' => ['transaction_id', 'name','plan_name','amount','created_at'],
                'items' => $list_result,
                'permissions' => $check_permission,
                'action'      => false
            ])
        </div>
    </div>
</x-container>
@endsection
