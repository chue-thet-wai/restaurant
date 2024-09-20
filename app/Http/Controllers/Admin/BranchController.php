<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Interfaces\CommonRepositoryInterface;
use App\Models\Branch;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    private CommonRepositoryInterface $commonRepository;
    protected $nowDate;

    public function __construct(CommonRepositoryInterface $commonRepository) 
    {
        $this->commonRepository = $commonRepository;
        $this->nowDate  = date('Y-m-d H:i:s', time());
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
                'remark'         =>$request->remark,
                'created_by'     =>$login_id,
                'updated_by'     =>$login_id,
                'created_at'     =>$this->nowDate,
                'updated_at'     =>$this->nowDate
            );

            $result=Branch::insert($insertData);     

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
        $status           = $this->commonRepository->getStatus();

        return view('admin.branch.edit', [
            'branch'        => $branch,
            'status'        => $status
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
                'status'         =>$request->status,
                'remark'         =>$request->remark,
                'updated_by'     =>$login_id,
                'updated_at'     =>$this->nowDate
            );
            
            $result=Branch::where('id',$branchID)->update($updateData);
                      
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
