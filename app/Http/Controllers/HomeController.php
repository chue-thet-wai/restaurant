<?php

namespace App\Http\Controllers;

use App\Interfaces\CommonRepositoryInterface;
use App\Models\BranchOpeningTime;
use App\Models\Reservation;
use App\Models\TimeSlots;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private CommonRepositoryInterface $commonRepository;
    protected $nowDate;
    
    public function __construct(CommonRepositoryInterface $commonRepository) 
    {
        $this->middleware('auth');
        $this->commonRepository = $commonRepository;
        $this->nowDate  = date('Y-m-d H:i:s', time());
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       
        $allBooking      = 0;
        $newBooking      = 0;
        $acceptedBooking = 0;
        $rejectedBooking = 0;
        $newOrder        = 0;

        $allBooking      =Reservation::count();
        $newBooking      =Reservation::where('status','1')->count();
        $acceptedBooking =Reservation::where('status','2')->count();
        $rejectedBooking =Reservation::where('status','3')->count();

        $newOrder = Reservation::leftJoin('myrt_branch', 'myrt_branch.id', '=', 'myrt_reservations.branch_id')
                            ->orderBy('myrt_reservations.id', 'DESC')
                            ->whereExists(function ($query) {
                                $query->select(DB::raw(1)) 
                                    ->from('myrt_order_menu')
                                    ->whereRaw('myrt_order_menu.order_id = myrt_reservations.order_id'); 
                            })
                            ->where('myrt_reservations.status','2')
                            ->count();
        
        return view('home', compact(
            'allBooking', 
            'newBooking', 
            'acceptedBooking',
            'rejectedBooking', 
            'newOrder',
        ));
    }

    public static function getDashboardPermission()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get the roles of the user
        $roles = $user->roles;
        
        // Initialize an empty collection for permissions
        $permissions = collect();
        
        // Iterate through each role and get the permissions
        foreach ($roles as $role) {
            $permissions = $permissions->merge($role->permissions()->where('action', 'list')->get());
        }
        
        // If there are no permissions, return an empty array
        if ($permissions->isEmpty()) {
            return [];
        }

        // Sort the permissions by menu_id
        $permissions = $permissions->sortBy('menu.id');
        
        // Initialize the return array
        $returnArray = [];
        
        // Process the permissions
        foreach ($permissions as $permission) {
            $menu = [];
            $menu['menu_id']           = $permission->menu->id;
            $menu['sub_menu']          = $permission->menu->sub_menu;
            $menu['controller_method'] = $permission->name;
            $menu['menu_route']        = $permission->menu->menu_route;
            $menu['type']              = $permission->menu->type;
            $returnArray[$permission->menu->main_menu][] = $menu;
        }
        
        return $returnArray;
    }

    public function getAvailableTimes(Request $request)
    {
        $branchId = $request->query('branch');
        $date     = $request->query('date');

        // Validate input
        if (!$branchId || !$date) {
            return response()->json(['error' => 'Invalid branch or date'], 400);
        }

        $reservationDay = Carbon::parse($date)->format('D'); 

        $branchOpeningTimes = BranchOpeningTime::where('branch_id', $branchId)
            ->where('day', $reservationDay)
            ->first();

        $start_time = $branchOpeningTimes ? $branchOpeningTimes->start_time : null;
        $end_time   = $branchOpeningTimes ? $branchOpeningTimes->end_time : null;
        $is_offday  = $branchOpeningTimes ? $branchOpeningTimes->is_offday : false;

        $availableTimes = [];
        if ($is_offday) {
            return response()->json(['times' => $availableTimes]);
        }else if (is_null($start_time) || is_null($end_time)) {
            return response()->json(['times' => $availableTimes]);
        }
        
        $availableTimes = TimeSlots::orderBy('time', 'ASC')
            ->whereBetween('time', [$start_time, $end_time])
            ->pluck('time');

        return response()->json(['times' => $availableTimes]);
    }

    public function getAvailableTablesList(Request $request)
    {
        $branch = $request->input('branch');
        $date = $request->input('date');
        $time = $request->input('time');
       
        $availableTables = [];
        $availableTables=$this->commonRepository->getAvailableTable($time,$date,$branch);
        return response()->json(['tables' => $availableTables]);
    }

}
