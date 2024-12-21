<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Interfaces\CommonRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;
use App\Models\Branch;
use App\Models\BranchOpeningTime;
use App\Models\Reservation;
use App\Models\TableManagement;
use App\Models\Tables;
use App\Models\TimeSlots;
use Carbon\Carbon;
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
    public function index(Request $request): View
    {

        $check_permission = $this->commonRepository->checkPermission('ReservationController');
        $status           = $this->commonRepository->getReservationStatus();
        $branches         = Branch::get();

        $query = Reservation::leftJoin('myrt_branch', 'myrt_branch.id', '=', 'myrt_reservations.branch_id')
                            ->select('myrt_reservations.*', 'myrt_branch.name as branch_name')
                            ->orderBy('myrt_reservations.id', 'DESC');
        if ($request['action'] == 'search') {
           
            if (request()->has('reservation_branch') && request()->input('reservation_branch') != '') {
                $query->where('myrt_reservations.branch_id',request()->input('reservation_branch'));
            }
            if (request()->has('reservation_date') && request()->input('reservation_date') != '') {
                $query->where('myrt_reservations.date', request()->input('reservation_date'));
            }
            if (request()->has('reservation_time') && request()->input('reservation_time') != '') {
                $query->where('myrt_reservations.time', request()->input('reservation_time'));
            }
            if (request()->has('reservation_status') && request()->input('reservation_status') != '') {
                $query->where('myrt_reservations.status', request()->input('reservation_status'));
            } 
        } else if (request()->has('reservation_status') && request()->input('reservation_status') != '' && $request['action'] != 'reset') {
            $query->where('myrt_reservations.status', request()->input('reservation_status'));
        } else {
            request()->merge([
                'reservation_branch' => null,
                'reservation_date'    => null,
                'reservation_time'    => null,
                'reservation_status'  => null,
            ]);
        }

        $reservation_list = $query->paginate(10);

        $reservation_list->getCollection()->transform(function ($order) use ($status) {
            $order->status = $status[$order->status] ?? 'Pending';
            return $order;
        });

        return view('admin.reservation.index', [
            'reservation_list'  => $reservation_list,
            'check_permission'  => $check_permission,
            'status_arr'        => $status,
            'branches'          => $branches
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $status   = $this->commonRepository->getReservationStatus();
        $branchList = Branch::get();
        $timeslots  = TimeSlots::get();
    
        return view('admin.reservation.create',[
            'branches' => $branchList,
            'status' => $status,
            'timeslots' => $timeslots
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $login_id = Auth::user()->id;
        Log::info('Reservation creation started');

        $request->validate([
            'branch'     => 'required',
            'email'      => 'required|email',
            'phone'      => 'required',
            'date'       => 'required|date',
            'time'       => 'required',
            'seat_count' => 'required|integer|min:1',
        ]);

        $orderId = 'ORD-' . strtoupper(uniqid());

        DB::beginTransaction();
        try {
            $insertData = [
                'order_id'   => $orderId,
                'branch_id'  => $request->branch,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'date'       => $request->date,
                'time'       => $request->time,
                'seat_count' => $request->seat_count,
                'status'     => '2', // Confirm
                'created_by' => $login_id,
                'updated_by' => $login_id,
                'created_at' => $this->nowDate,
                'updated_at' => $this->nowDate,
            ];

            Log::info('Reservation data:', $insertData);

            $reservation = Reservation::create($insertData);

            if (!$reservation) {
                throw new \Exception('Failed to create reservation.');
            }

            $this->handleTableReservation($reservation,  $request->table, $login_id);

            $this->sendReservationEmail($reservation);

            DB::commit();

            return redirect()->route('admin_reservation.index')->with('success', 'New Reservation added successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating reservation:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($reservationID): View
    {
        $reservation = Reservation::leftjoin('myrt_branch','myrt_branch.id','myrt_reservations.branch_id')
                        ->leftjoin('myrt_table_management','myrt_table_management.id','myrt_reservations.table_management_id')
                        ->leftJoin('myrt_tables', 'myrt_tables.id', 'myrt_table_management.table_id')
                        ->where('myrt_reservations.id',$reservationID)
                        ->select(
                            'myrt_reservations.*',
                            'myrt_branch.name as branch_name',
                            'myrt_table_management.table_id as table_id',
                            'myrt_tables.table_name as table_name' 
                        )
                        ->first();
        $availableTables = [];
        if ($reservation) {
            $availableTables=$this->commonRepository->getAvailableTable($reservation->time,$reservation->date,$reservation->branch_id);
        }
        $status           = $this->commonRepository->getReservationStatus();

        return view('admin.reservation.edit', [
            'reservation'      => $reservation,
            'status'           => $status,
            'available_tables' => $availableTables
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $reservationID): RedirectResponse
    {
        $login_id = Auth::user()->id;

        $request->validate(
            [
                'table' => 'required_if:status,2',
            ],
            [
                'table.required_if' => 'The requested time from the user falls outside the available timeslot for your branch. Please update your timeslot or inform the customer to reschedule accordingly.',
            ]
        );       

        DB::beginTransaction();
        try {
            $reservation = Reservation::findOrFail($reservationID);

            if ($request->status == '2') { // Confirm
                $this->handleTableReservation($reservation,  $request->table, $login_id);

            } elseif ($request->status == '3') { // Reject
                if ($reservation->table_management_id) {
                    TableManagement::where('id', $reservation->table_management_id)
                        ->update(['is_available' => true]);
        
                    $reservation->update(['table_management_id' => null]);
                }
            }

            $reservation->update([
                'status'         => $request->status,
                'reject_note'    => $request->reject_note,
                'updated_by'     => $login_id,
                'updated_at'     => $this->nowDate,
            ]);

            if ($request->status != "1") {
                $this->sendReservationEmail($reservation);
            }

            DB::commit();

            return redirect()->route('admin_reservation.index')->with('success', 'Reservation Status changed successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating reservation:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    private function handleTableReservation($reservation, $tableId, $loginId)
    {
        //  Validate reservation time
        $day = Carbon::parse($reservation->date)->format('D');
        $branchOpeningTimes = BranchOpeningTime::where('branch_id', $reservation->branch_id)
            ->where('day', $day)
            ->first();

        if (!$branchOpeningTimes) {
            throw new \Exception('Branch does not have opening hours set for today.');
        }

        if ($branchOpeningTimes->is_offday) {
            throw new \Exception('Reservation date is an off day.');
        }

        $timeslot = Timeslots::whereBetween('time', [$branchOpeningTimes->start_time, $branchOpeningTimes->end_time])
            ->where('time', $reservation->time)
            ->first();

        if (!$timeslot) {
            throw new \Exception('Invalid time for table management.');
        }

        $timeslotId = $timeslot->id;

        // Find an available table
        $table = Tables::where('branch_id', $reservation->branch_id)
            ->whereDoesntHave('tableManagements', function ($query) use ($reservation, $timeslotId) {
                $query->where('reservation_date', $reservation->date)
                    ->where('timeslot_id', $timeslotId)
                    ->where('is_available', false);
            })
            ->where('id', $tableId)
            ->first();

        if (!$table) {
            throw new \Exception('No available table found for the given reservation.');
        }

        // Manage the table
        $tableManagement = TableManagement::firstOrNew([
            'table_id'         => $table->id,
            'timeslot_id'      => $timeslotId,
            'reservation_date' => $reservation->date,
        ]);

        $tableManagement->fill([
            'is_available' => false,
            'updated_by'   => $loginId,
            'updated_at'   => $this->nowDate,
        ]);

        if (!$tableManagement->exists) {
            $tableManagement->created_by = $loginId;
            $tableManagement->created_at = $this->nowDate;
        }

        $tableManagement->save();

        // Update reservation with table management ID
        $reservation->update(['table_management_id' => $tableManagement->id]);

        return $tableManagement;
    }


    private function sendReservationEmail($reservation)
    {
        $subject = "Reservation";
        $name = explode('@', $reservation->email)[0];
        $messageContent = "Please check your reservation status in the link below:\n";
        $messageContent .= request()->root() . "/reservation-status/" . $reservation->order_id;

        $this->emailRepository->sendEmail($reservation->email, $subject, $name, $messageContent);
    }

}
