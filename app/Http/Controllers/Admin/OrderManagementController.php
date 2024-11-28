<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\CommonRepositoryInterface;
use App\Interfaces\EmailRepositoryInterface;
use App\Models\Branch;
use App\Models\OrderMenu;
use App\Models\Reservation;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderManagementController extends Controller
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
    public function index(Request $request): View
    {

        $check_permission = $this->commonRepository->checkPermission('OrderManagementController');
        $branches         = Branch::get();

        $query = Reservation::leftJoin('myrt_branch', 'myrt_branch.id', '=', 'myrt_reservations.branch_id')
                            ->orderBy('myrt_reservations.id', 'DESC')
                            ->whereExists(function ($query) {
                                $query->select(DB::raw(1)) 
                                    ->from('myrt_order_menu')
                                    ->whereRaw('myrt_order_menu.order_id = myrt_reservations.order_id'); 
                            })
                            ->where('myrt_reservations.status','2'); //confirm
        if ($request['action'] == 'search') {
           
            if (request()->has('ordermanagement_branch') && request()->input('ordermanagement_branch') != '') {
                $query->where('myrt_reservations.branch_id', request()->input('ordermanagement_branch'));
            }
            if (request()->has('ordermanagement_date') && request()->input('ordermanagement_date') != '') {
                $query->where('myrt_reservations.date', request()->input('ordermanagement_date'));
            }
            if (request()->has('ordermanagement_time') && request()->input('ordermanagement_time') != '') {
                $query->where('myrt_reservations.time', request()->input('ordermanagement_time'));
            }
        } else {
            request()->merge([
                'ordermanagement_branch'  => null,
                'ordermanagement_date'    => null,
                'ordermanagement_time'    => null,
            ]);
        }

        $query->select('myrt_reservations.*', 'myrt_branch.name as branch_name');
        $query->addSelect(DB::raw('(SELECT COUNT(*) FROM myrt_order_menu WHERE myrt_order_menu.order_id = myrt_reservations.order_id) as order_count'));
        $ordermanagement_list = $query->paginate(10);

        return view('admin.order_management.index', [
            'ordermanagement_list'   => $ordermanagement_list,
            'check_permission'       => $check_permission,
            'branches'               => $branches
        ]);
    }

    public function show($reservationId): View
    {
        $reservation = Reservation::where('id',$reservationId)->first();
        $orders = [];
        if ($reservation) {
            $order_id = $reservation->order_id;
            $orders      = OrderMenu::join('myrt_restaurant_menu','myrt_restaurant_menu.id','myrt_order_menu.menu_id')
                            ->where('order_id',$order_id)
                            ->get();
        }       
        return view('admin.order_management.show', [
            'reservation' => $reservation,
            'orders'      => $orders
        ]);
    }

}
