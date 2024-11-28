@extends('layouts.admin_app')

@section('content')

<x-container>
    <div class="card" id="custom-card">
        <div class="card-body">  
            <div class="button-container">
                <a href="{{ route('branch.index') }}" id="list-btn" class="list-active">
                    Branch List
                </a>
                @if ($check_permission['create'])
                    <a href="{{ route('branch.create') }}" id="add-btn">
                        Add New Branch
                    </a>
                @endif
            </div>    
            
            @include('components.common_table', [
                'headers' => ['Name','Phone','Address','Status'],
                'fields' => ['name','phone','address','status'],
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
