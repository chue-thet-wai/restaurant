<?php

namespace App\Http\Controllers\Admin\Reporting;

use App\Exports\ExportReportUsers;
use App\Http\Controllers\Controller;
use App\Interfaces\CommonRepositoryInterface;
use App\Models\UserReport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportUserListController extends Controller
{

    private CommonRepositoryInterface $commonRepository;

    public function __construct(CommonRepositoryInterface $commonRepository) 
    {
        $this->commonRepository = $commonRepository;
    }

    public function reportUserListReport(Request $request) 
    {
        $check_permission = $this->commonRepository->checkPermission('ReportUserListController');

        $res = UserReport::leftJoin('users as u1', 'u1.id', '=', 'mydt_user_report.user_1')
            ->leftJoin('users as u2', 'u2.id', '=', 'mydt_user_report.user_2')
            ->orderBy('mydt_user_report.created_at', 'desc');

        if ($request['action'] == 'search' || $request['action'] == 'export') {
            if ($request->has('reportlist_username') && $request->input('reportlist_username') != '') {
                $res->where(function ($query) {
                    $query->where('u1.name', 'Like', '%' . request()->input('reportlist_username') . '%')
                        ->orWhere('u2.name', 'Like', '%' . request()->input('reportlist_username') . '%');
                });
            }
        } else {
            request()->merge(['reportlist_username' => null]);
        }

        $res->select(
            'mydt_user_report.*',
            'u1.name as user_1_name',
            'u2.name as user_2_name'
        );

        if ($request['action'] == 'export') {
            $listresult = $res->get();

            $resultData = [];
            foreach ($listresult as $res) {
                $resArr['user_1']         = $res->user_1_name;
                $resArr['user_2']         = $res->user_2_name;
                $resArr['report_time_1']  = $res->report_time_1 ? date('Y-m-d H:i:s', strtotime($res->report_time_1)) : null;
                $resArr['report_time_2']  = $res->report_time_2 ? date('Y-m-d H:i:s', strtotime($res->report_time_2)) : null;

                $resultData[] = $resArr;
            }

            return Excel::download(new ExportReportUsers($resultData), 'reportusers_export.csv');
        }

        $res = $res->paginate(10);

        return view('admin.reporting.reportuserlist', [
            'list_result'      => $res,
            'check_permission' => $check_permission
        ]);    
        
    }
    

}
