<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Interfaces\CommonRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;
use App\Models\Branch;
use App\Models\Reservation;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    private CommonRepositoryInterface $commonRepository;
    private EmailRepositoryInterface $emailRepository;
    protected $nowDate;

    public function __construct(CommonRepositoryInterface $commonRepository,EmailRepositoryInterface $emailRepository) 
    {
        $this->commonRepository = $commonRepository;
        $this->emailRepository  = $emailRepository;
        $this->nowDate  = date('Y-m-d H:i:s', time());
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $check_permission = $this->commonRepository->checkPermission('ReservationController');
        $status           = $this->commonRepository->getReservationStatus();

        $reservation_list = Reservation::leftjoin('myrt_branch','myrt_branch.id','myrt_reservations.branch_id')
                            ->orderBy('myrt_reservations.id', 'DESC')
                            ->select('myrt_reservations.*','myrt_branch.name as branch_name')
                            ->paginate(10);
        $reservation_list->getCollection()->transform(function($order) use ($status) {
            $order->status = $status[$order->status] ?? 'Pending'; 
            return $order;
        });
        
        return view('admin.reservation.index', [
            'reservation_list'  => $reservation_list,
            'check_permission'  => $check_permission
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($reservationID): View
    {
        $reservation = Reservation::leftjoin('myrt_branch','myrt_branch.id','myrt_reservations.branch_id')
                        ->where('myrt_reservations.id',$reservationID)
                        ->select('myrt_reservations.*','myrt_branch.name as branch_name')
                        ->first();
        $status           = $this->commonRepository->getReservationStatus();

        return view('admin.reservation.edit', [
            'reservation'   => $reservation,
            'status'        => $status
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $reservationID): RedirectResponse
    {
        $login_id = Auth::user()->id;
        DB::beginTransaction();
        try{
            $reservation = Reservation::where('id',$reservationID)->first();
            $updateData = array(
                'status'         =>$request->status,
                'updated_by'     =>$login_id,
                'updated_at'     =>$this->nowDate
            );

            if ($request->status == "2") { //send mail
                $subject = "Reservation";
                $name    = explode('@', $reservation->email)[0];
                $messageContent = "Your reservation is comfirmed. Please fill your infromation in below link.";
                $messageContent .= $rootUrl = request()->root()."/information/".$reservation->order_id;
                $mailRes = $this->emailRepository->sendEmail($reservation->email,$subject,$name,$messageContent);
            }
            
            $result=Reservation::where('id',$reservationID)->update($updateData);
                      
            if($result){
                DB::commit();               
                return redirect()->route('reservation.index')->with('success','Reservation Status changed successfully.');
            }else{
                DB::rollback();
                return redirect()->back()->with('danger','Reservation Status Changed Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Reservation Status Changed Fail !');
        }  
        
    }

}
