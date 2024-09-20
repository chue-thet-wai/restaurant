<?php

namespace App\Repositories;

use App\Interfaces\CommonRepositoryInterface;
use App\Models\Permission;
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
}
   