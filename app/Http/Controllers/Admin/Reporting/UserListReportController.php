<?php

namespace App\Http\Controllers\Admin\Reporting;

use App\Exports\ExportUserList;
use App\Http\Controllers\Controller;
use App\Interfaces\CommonRepositoryInterface;
use App\Interfaces\CountryCityRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class UserListReportController extends Controller
{

    private CommonRepositoryInterface $commonRepository;
    private CountryCityRepositoryInterface $countrycityRepository;

    public function __construct(CommonRepositoryInterface $commonRepository,CountryCityRepositoryInterface $countrycityRepository) 
    {
        $this->commonRepository = $commonRepository;
        $this->countrycityRepository = $countrycityRepository;
    }

    public function userListReport(Request $request) {
        $check_permission = $this->commonRepository->checkPermission('UserListReportController');
        $user_status      = $this->commonRepository->getUserStatus();
        $gender           = $this->commonRepository->getGender();
        $country          = $this->countrycityRepository->getCountry();
    
        $res = User::with(['userInfo.profileImages'])
                    ->where('is_admin', '0') // not admin
                    ->orderBy('users.created_at', 'desc');
    
        if ($request['action'] == 'search' || $request['action'] == 'export') {
            if (request()->has('userlist_name') && request()->input('userlist_name') != '') {
                $res->where('name', 'Like', '%' . request()->input('userlist_name') . '%');
            }
            if (request()->has('userlist_status') && request()->input('userlist_status') != '' && request()->input('userlist_status') != '99') {
                $res->where('is_active', request()->input('userlist_status'));
            }
            if (request()->has('userlist_joineddate') && request()->input('userlist_joineddate') != '') {
                $res->whereDate('created_at', request()->input('userlist_joineddate'));
            }
        } else {
            request()->merge([
                'userlist_name'        => null,
                'userlist_status'      => "99",
                'userlist_joineddate'  => null
            ]);
        }
    
        if ($request['action'] == 'export') {
            $listresult = $res->get();
    
            $resultData = [];
            foreach ($listresult as $res) {
                $resArr = [
                    'name'           => $res->name,
                    'email'          => $res->email,
                    'phone'          => $res->phone_number,
                    'gender'         => $res->userInfo ? ($gender[$res->userInfo->gender] ?? '') : '',
                    'country'        => $res->userInfo ? ($country[$res->userInfo->country] ?? '') : '',
                    'city'           => $res->userInfo ? ($this->countrycityRepository->getCity($res->userInfo->country)[$res->userInfo->city] ?? '') : '',
                    'status'         => $user_status[$res->is_active] ?? $res->is_active,
                    'joined_date'    => $res->created_at ? date('Y-m-d H:i:s', strtotime($res->created_at)) : ''
                ];
    
                $resultData[] = $resArr;
            }
    
            return Excel::download(new ExportUserList($resultData), 'userlist_export.csv');
        }
    
        $listres = $res->paginate(10);
        $resultData = [];
        foreach ($listres as $res) {
            $resObj = new \stdClass();
            $resObj->name = $res->name;
            $resObj->email = $res->email;
            $resObj->phone = $res->phone_number;
            $resObj->gender = $res->userInfo ? ($gender[$res->userInfo->gender] ?? '') : '';
            $resObj->country = $res->userInfo ? ($country[$res->userInfo->country] ?? '') : '';
            $resObj->city = $res->userInfo ? ($this->countrycityRepository->getCity($res->userInfo->country)[$res->userInfo->city] ?? '') : '';
            $resObj->status = $user_status[$res->is_active] ?? $res->is_active;
            $resObj->joined_date = $res->created_at ? date('Y-m-d H:i:s', strtotime($res->created_at)) : '';
    
            $resultData[] = $resObj;
        }
        
        return view('admin.reporting.userlistreport', [
            'list_result'      => $listres,
            'check_permission' => $check_permission,
            'user_status'      => $user_status,
            'result'           => $resultData
        ]);
    }
    

}
