<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Interfaces\CommonRepositoryInterface;
use App\Models\Branch;
use App\Models\BranchOpeningTime;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    private CommonRepositoryInterface $commonRepository;
    protected $nowDate;
    public $days;

    public function __construct(CommonRepositoryInterface $commonRepository) 
    {
        $this->commonRepository = $commonRepository;
        $this->nowDate  = date('Y-m-d H:i:s', time());
        $this->days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $check_permission = $this->commonRepository->checkPermission('BranchController');
        $status           = $this->commonRepository->getStatus();

        $branch_list = Branch::orderBy('id', 'DESC')->paginate(10);
        $branch_list->getCollection()->transform(function($branch) use ($status) {
            $branch->status = $status[$branch->status] ?? 'Inactive'; 
            return $branch;
        });
        
        return view('admin.branch.index', [
            'branch'            => $branch_list,
            'check_permission'  => $check_permission
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.branch.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchRequest $request): RedirectResponse
    {
        $login_id = Auth::user()->id;
        DB::beginTransaction();
        try{

            $insertData = array(
                'name'           =>$request->name,
                'phone'          =>$request->phone,
                'address'        =>$request->address,
                'remark'         =>$request->remark,
                'created_by'     =>$login_id,
                'updated_by'     =>$login_id,
                'created_at'     =>$this->nowDate,
                'updated_at'     =>$this->nowDate
            );

            $resultBranch=Branch::create($insertData); 
            $branch_id = $resultBranch->id;
            
            $openingHours = [];
            
            foreach ($this->days as $day) {
                $openingHours[] = [
                    'day'            => $day,
                    'start_time'     => $request->opening_time[$day],
                    'end_time'       => $request->closing_time[$day],
                    'is_offday'      => $request->has("offday.{$day}") ? 1 : 0,
                    'branch_id'      => $branch_id,
                    'created_by'     => $login_id,
                    'updated_by'     => $login_id,
                    'created_at'     => $this->nowDate,
                    'updated_at'     => $this->nowDate
                ];
            }

            $result=BranchOpeningTime::insert($openingHours); 

            if($result){
                DB::commit(); 
                return redirect()->route('branch.index')->with('success','New Branch is added successfully.');
            }else{
                DB::rollback();
                return redirect()->back()->with('danger','New Branch Added Fail !');
            }
        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Branch Added Fail !');
        }  
        
    }

    /**
     * Display the specified resource.
     */
    public function show($branchID): View
    {
        $branch = Branch::find($branchID);

        return view('admin.branch.show', [
            'branch'      => $branch
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($branchID): View
    {
        $branch = Branch::find($branchID);
        
        $durationTime = $branch->duration_time;
        $durationHours = $durationMinutes = null;
        if ($durationTime) {
            list($durationHours, $durationMinutes) = explode(':', $durationTime);
        }

        $status = $this->commonRepository->getStatus();

        $openingHours = BranchOpeningTime::where('branch_id', $branchID)->get()->keyBy('day');

        $openingTimes = [];
        foreach ($openingHours as $openingHour) {
            $openingTimes[$openingHour->day] = [
                'start_time' => $openingHour->start_time,
                'end_time' => $openingHour->end_time,
                'is_offday' => $openingHour->is_offday,
            ];
        }

        return view('admin.branch.edit', [
            'branch'        => $branch,
            'status'        => $status,
            'openingTimes'  => $openingTimes,
            'durationHours' => $durationHours,
            'durationMinutes' => $durationMinutes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BranchRequest $request, $branchID): RedirectResponse
    {
        $login_id = Auth::user()->id;
        DB::beginTransaction();
        try{
            $updateData = array(
                'name'           =>$request->name,
                'phone'          =>$request->phone,
                'address'        =>$request->address,
                'status'         =>$request->status,
                'remark'         =>$request->remark,
                'updated_by'     =>$login_id,
                'updated_at'     =>$this->nowDate
            );
            
            $result=Branch::where('id',$branchID)->update($updateData);

            foreach ($this->days as $day) {
                BranchOpeningTime::updateOrCreate(
                    ['branch_id' => $branchID, 'day' => $day],
                    [
                        'start_time'     => $request->opening_time[$day],
                        'end_time'       => $request->closing_time[$day],
                        'is_offday'      => $request->has("offday.{$day}") ? 1 : 0,
                        'updated_by'     => $login_id,
                        'updated_at'     => $this->nowDate
                    ]
                );
            }
                      
            if($result){
                DB::commit();               
                return redirect()->route('branch.index')->with('success','Branch is updated successfully.');
            }else{
                DB::rollback();
                return redirect()->back()->with('danger','Branch Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Branch Updated Fail !');
        }  
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($branchID): RedirectResponse
    {

        $branch = Branch::find($branchID);
        $branch->delete();
        return redirect()->route('branch.index')->with('success','Branch is deleted successfully.');
    }

}
