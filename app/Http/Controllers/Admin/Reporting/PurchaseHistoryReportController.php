<?php

namespace App\Http\Controllers\Admin\Reporting;

use App\Exports\ExportPurchaseHistory;
use App\Http\Controllers\Controller;
use App\Interfaces\CommonRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseHistoryReportController extends Controller
{

    private CommonRepositoryInterface $commonRepository;

    public function __construct(CommonRepositoryInterface $commonRepository) 
    {
        $this->commonRepository = $commonRepository;
    }

    public function purchaseHistoryReport(Request $request) {

        $check_permission = $this->commonRepository->checkPermission('PurchaseHistoryReportController');

        $res = DB::table('mydt_transactions')
                ->leftjoin('users','users.id','mydt_transactions.user_id')
                ->orderBy('mydt_transactions.created_at','desc');
        if ($request['action'] == 'search' || $request['action'] == 'export') {
            if (request()->has('purchasehistory_startdate') && request()->input('purchasehistory_startdate') != '') {
                $res->whereDate('mydt_transactions.created_at' ,'>=',request()->input('purchasehistory_startdate'));
            }
            if (request()->has('purchasehistory_enddate') && request()->input('purchasehistory_enddate') != '') {
                $res->whereDate('mydt_transactions.created_at' ,'<=', request()->input('purchasehistory_enddate'));
            }
        } else {
            request()->merge([
                'purchasehistory_startdate'    => null,
                'purchasehistory_enddate'      => null
            ]);
        } 
        $res->select(
            'mydt_transactions.*',
            'users.name as name',
            DB::raw("
                CASE
                    WHEN mydt_transactions.plan_type = 1 THEN 'Premium'
                    ELSE 'Point'
                END as plan_name
            ")
        );
        if ($request['action'] == 'export') {
            $listresult = $res->get();

            $resultData = [];
            foreach ($listresult as $res) {
                $resArr['transaction_id'] = $res->transaction_id;
                $resArr['name']           = $res->name;
                $resArr['plan']           = $res->plan_name;
                $resArr['amount']         = $res->amount;
                if ($res->created_at != '') {
                    $resArr['created_date'] = date('Y-m-d H:i:s',strtotime($res->created_at));
                } else {
                    $resArr['created_date'] = $res->created_at;
                }

                $resultData[] = $resArr;
            }
    
            return Excel::download(new ExportPurchaseHistory($resultData), 'purchasehistory_export.csv');
        }  
        $res = $res->paginate(10);

        return view('admin.reporting.purchasehistoryreport',[
            'list_result'      => $res,
            'check_permission' => $check_permission
        ]);
    }

}
