@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card">
        <div class="card-header">Manage Branch</div>
        <div class="card-body">
            @if ($check_permission['create'])
                <a href="{{ route('branch.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Branch</a>
            @endif
            
            @include('components.common_table', [
                'headers' => ['Name','Status','Remark'],
                'fields' => ['name','status','remark'],
                'items' => $branch,
                'permissions' => $check_permission,
                'action'       => true,
                'destroyRoute' => 'branch.destroy',
                'showRoute' => 'branch.show',
                'editRoute' => 'branch.edit'
            ])
        </div>
    </div>
</x-container>

@endsection
