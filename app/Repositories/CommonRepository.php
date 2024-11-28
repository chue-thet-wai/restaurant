<?php

namespace App\Repositories;

use App\Interfaces\CommonRepositoryInterface;
use App\Models\BranchOpeningTime;
use App\Models\Permission;
use App\Models\Tables;
use App\Models\TimeSlots;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommonRepository implements CommonRepositoryInterface 
{
   

    public function checkPermission($controllerName)
    {
        $user = Auth::user(); 

        // Retrieve permissions related to the specified controller
        $permissions = Permission::where('name', 'LIKE', '%' . $controllerName . '%')->get();

        // Initialize flags for each action
        $isCreate = false;
        $isView = false;
        $isEdit = false;
        $isDelete = false;
        $isDownload = false;

        // Check if the user has each permission
        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission->name)) {
                switch ($permission->action) {
                    case "create":
                        $isCreate = true;
                        break;
                    case "view":
                        $isView = true;
                        break;
                    case "edit":
                        $isEdit = true;
                        break;
                    case "delete":
                        $isDelete = true;
                        break;
                    case "download":
                        $isDownload = true;
                        break;
                }
            }
        }

        // Return the results as an array or object
        return [
            'create' => $isCreate,
            'show'   => $isView,
            'edit'   => $isEdit,
            'delete' => $isDelete,
            'download' => $isDownload,
        ];
    }

    public function getStatus() {
        return array(
            '0' => 'Inactive',
            '1' => 'Active'
        );
    }

    public function getReservationStatus() {
        return array(
            '1' => 'Pending',
            '2' => 'Confirm',
            '3' => 'Reject'
        );
    }

    public function getAvailableTable($reservation_time,$reservation_date,$reservation_branchId){
        $availableTables = [];

        $reservationDay = Carbon::parse($reservation_date)->format('D'); 

        $branchOpeningTimes = BranchOpeningTime::where('branch_id', $reservation_branchId)
            ->where('day', $reservationDay)
            ->first();

        $start_time = $branchOpeningTimes ? $branchOpeningTimes->start_time : null;
        $end_time   = $branchOpeningTimes ? $branchOpeningTimes->end_time : null;
        $is_offday  = $branchOpeningTimes ? $branchOpeningTimes->is_offday : false;

        if ($is_offday) {
            return $availableTables;
        }else if (is_null($start_time) || is_null($end_time)) {
            return $availableTables;
        }
        
        $timeslot = TimeSlots::orderBy('time', 'ASC')
            ->whereBetween('time', [$start_time, $end_time])
            ->where('time', $reservation_time)
            ->first();
        log::info('timeslot');
        log::info($timeslot);

        if (!$timeslot) {
            return $availableTables;
        }

        $timeslotId = $timeslot->id;

        $availableTables = Tables::where('branch_id', $reservation_branchId) 
            ->whereDoesntHave('tableManagements', function ($query) use ($reservation_date, $timeslotId) {
                $query->where('reservation_date', $reservation_date)
                    ->where('timeslot_id', $timeslotId)
                    ->where('is_available', false); 
            })
        ->get();

        log::info('available tables');
        log::info($availableTables);

        return $availableTables;
    }
}
   