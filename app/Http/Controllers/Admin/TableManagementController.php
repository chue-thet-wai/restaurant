<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\CommonRepositoryInterface;
use App\Models\Branch;
use App\Models\BranchOpeningTime;
use App\Models\TableManagement;
use App\Models\Tables;
use App\Models\TimeSlots;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TableManagementController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     */
    private CommonRepositoryInterface $commonRepository;
    protected $nowDate;

    public function __construct(CommonRepositoryInterface $commonRepository) 
    {
        $this->commonRepository = $commonRepository;
        $this->nowDate  = date('Y-m-d H:i:s', time());
    }

    public function index()
    {
        $date = Carbon::today();
        return $this->fetchTableDataForDate($date);
    }

    public function fetchTableList(Request $request)
    {
        $date = Carbon::parse($request->date);
        $branch = $request->branch_id;
        return $this->fetchTableDataForDate($date,$branch);
    }

    public function addTable(Request $request)
    {
        $request->validate([
            'table_name' => 'required|string',
            'capacity'   => 'required|integer',
            'branch_id'  => 'required|integer'
        ]);

        $login_id = Auth::user()->id;

        Tables::create([
            'table_name'       => $request->table_name,
            'capacity'         => $request->capacity,
            'branch_id'        => $request->branch_id,
            'created_by'       =>$login_id,
            'updated_by'       =>$login_id,
            'created_at'       =>$this->nowDate,
            'updated_at'       =>$this->nowDate
        ]);

        return response()->json(['success' => true, 'message' => 'Table added successfully']);
    }

    public function toggleAvailability(Request $request)
    {
        $login_id = Auth::user()->id;

        $tableId = $request->table_id;
        $timeslotId = $request->timeslot_id;
        $date = Carbon::parse($request->date);
        $isAvailable = $request->is_available;

        $availability = TableManagement::firstOrNew([
            'table_id'         => $tableId,
            'timeslot_id'      => $timeslotId,
            'reservation_date' => $date,
        ]);

        if (!$availability->exists) {
            $availability->created_by = $login_id;
            $availability->created_at = $this->nowDate;
        }

        $availability->is_available = $isAvailable;
        $availability->updated_by = $login_id;
        $availability->updated_at = $this->nowDate;

        $availability->save();

        return response()->json(['success' => true]);
    }

    public function fetchTableDataForDate($date,$branchId=null)
    {
        $branchStatus = '';
        $check_permission = $this->commonRepository->checkPermission('RoleController');
        $branches = Branch::get();

        if ($branchId === null && $branches->isNotEmpty()) {
            $branch = $branches->first()->id;
        } else {
            $branch = $branchId;
        }
    
        $currentDay = Carbon::parse($date)->format('D'); // Get the current day like Sun,Mon

        $branchOpeningTimes = BranchOpeningTime::where('branch_id', $branch)
            ->where('day', $currentDay)
            ->first();
    
        $start_time = $branchOpeningTimes ? $branchOpeningTimes->start_time : null;
        $end_time   = $branchOpeningTimes ? $branchOpeningTimes->end_time : null;
        $is_offday  =  $branchOpeningTimes ? $branchOpeningTimes->is_offday : false;

        if ($is_offday) {
            $branchStatus = 'This day is off.';
        }else if (is_null($start_time) || is_null($end_time)) {
           $branchStatus = 'Branch does not have opening hours set for this day.';
        }

        $tables = Tables::where('branch_id', $branch)->get();
       
        $timeslots = Timeslots::orderBy('time', 'ASC')
            ->whereBetween('time', [$start_time, $end_time])
            ->get();
        
        $tableAvailability = TableManagement::where('reservation_date', $date)
            ->whereIn('table_id', $tables->pluck('id')) 
            ->get()
            ->keyBy(function ($item) {
                return $item->table_id . '_' . $item->timeslot_id;
            });

        if (request()->ajax()) {
            return view('admin.table_management.tbody', 
                compact('tables', 'timeslots', 'date', 'tableAvailability','check_permission','branches','branchStatus'));
        }

        return view('admin.table_management.index', 
            compact('tables', 'timeslots', 'date', 'tableAvailability', 'check_permission','branches','branchStatus'));
    }

}
