@extends('layouts.admin_app')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="{{ asset('js/table_management.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/table_management.css') }}">

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <div>
                <button class="btn btn-outline-secondary px-3 py-1 me-2" id="prevDate">&lt;</button>
                <span id="selectedDate" class="fw-bold px-3 py-1 border rounded bg-light">{{ $date->format('F j, Y') }}</span>
                <button class="btn btn-outline-secondary px-3 py-1 ms-2" id="nextDate">&gt;</button>
            </div>
            <div class="ms-2">
                <select class="form-control" id="branchFilter" name="branchFilter" required>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if ($check_permission['create'])
            <button class="btn add-table-btn d-flex align-items-center" id="addTable" data-bs-toggle="modal" data-bs-target="#addTableModal">
                Add Table
                <span class="add-icon ms-1">+</span>
            </button>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table text-center align-middle table-no-border">
            <tbody id="table-management-tbody">
                @include('admin.table_management.tbody', [
                    'tables' => $tables,
                    'timeslots' => $timeslots,
                    'date' => $date,
                    'tableAvailability' => $tableAvailability,
                    'branchStatus'      => $branchStatus
                ])
            </tbody>
        </table>
    </div>

    <!-- Add Table Modal -->
    <div class="modal fade" id="addTableModal" tabindex="-1" aria-labelledby="addTableModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body">
            <h5>Add New Table</h5>
            <form id="addTableForm" class="mt-3">
                <div class="mb-3">
                    <input type="text" class="form-control custom-input" id="table_name" name="table_name" placeholder="Table No. (or) Name" required>
                </div>
                <div class="mb-3">
                    <input type="number" class="form-control custom-input" id="capacity" name="capacity" placeholder="Capacity" required>
                </div>
                <div class="mb-3">
                    <select class="form-control" id="branch" name="branch" required>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn me-2" id="btn-apply">Apply</button>
                    <button type="button" id="btn-cancel" class="btn" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
