<?php

namespace App\Http\Controllers\Admin\Reporting;

use App\Exports\ExportMonthlyIncome;
use App\Http\Controllers\Controller;
use App\Interfaces\CommonRepositoryInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyIncomeReportController extends Controller
{

    private CommonRepositoryInterface $commonRepository;

    public function __construct(CommonRepositoryInterface $commonRepository) 
    {
        $this->commonRepository = $commonRepository;
    }

    public function monthlyIncomeReport(Request $request) {

        $check_permission = $this->commonRepository->checkPermission('MonthlyIncomeReportController');
        $income_types     = $this->commonRepository->getMonthlyIncomeType();

        $res = DB::table('mydt_transactions')
            ->leftjoin('users','users.id','mydt_transactions.user_id')
            ->whereYear('mydt_transactions.created_at', Carbon::now()->year)
            ->whereMonth('mydt_transactions.created_at', Carbon::now()->month)
            ->orderBy('mydt_transactions.created_at','desc');
            
        if ($request['action'] == 'search' || $request['action'] == 'export') {
            if (request()->has('monthlyincome_type') && request()->input('monthlyincome_type') != '' 
            && request()->input('monthlyincome_type') != '0') {
                $res->where('mydt_transactions.plan_type', request()->input('monthlyincome_type'));
            }
        } else {
            request()->merge([
                'monthlyincome_type'  => '0',
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
    
            return Excel::download(new ExportMonthlyIncome($resultData), 'monthlyincome_export.csv');
        } 

        $res = $res->paginate(10);
        return view('admin.reporting.monthlyincomereport',[
            'list_result'      => $res,
            'check_permission' => $check_permission,
            'income_types'     => $income_types
        ]);
    }

}
