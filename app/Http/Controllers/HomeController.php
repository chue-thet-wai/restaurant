<?php

namespace App\Http\Controllers;

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
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalUsers = User::count();
        $totalPremiumUsers = 5;
        $totalNormalUsers  = 10;
        $totalUsersThisMonth = 10;
        $totalPremiumUsersThisMonth = 5;
        $totalNormalUsersThisMonth = 10;
        $totalIncomeThisMonth    = 500000;
        $totalIncomePremium      = 300000;
        $totalIncomePoint       = 200000;

        
        /*$totalUsers = User::where('is_admin', '0')
                ->where('is_active', '1')
                ->count();

        
        $totalPremiumUsers = User::where('is_admin', '0')
                ->whereHas('userInfo', function ($query) {
                    $query->where('membership_expire', '>=', Carbon::now());
                })
                ->count();

        
        $totalNormalUsers = User::where('is_admin', '0')
                ->whereHas('userInfo', function ($query) {
                    $query->where('membership_expire', '<', Carbon::now())
                        ->orWhereNull('membership_expire');
                })
                ->count();

        $totalUsersThisMonth = User::where('is_admin', '0')
                                ->where('is_active', '1')
                                ->whereYear('created_at', Carbon::now()->year)
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->count();
        
        $totalPremiumUsersThisMonth = User::where('is_admin', '0')
                                ->whereHas('userInfo', function ($query) {
                                    $query->where('membership_expire', '>=', Carbon::now());
                                })
                                ->whereYear('created_at', Carbon::now()->year)
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->count();
        
        $totalNormalUsersThisMonth = User::where('is_admin', '0')
                                ->whereHas('userInfo', function ($query) {
                                    $query->where('membership_expire', '<', Carbon::now())
                                        ->orWhereNull('membership_expire');
                                })
                                ->whereYear('created_at', Carbon::now()->year)
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->count();
        
        $totalIncomeThisMonth = DB::table('mydt_transactions')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->sum('amount');
        
        $totalIncomePremium    = DB::table('mydt_transactions')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->where('plan_type',config('constant.plan_type.package'))
                                    ->sum('amount');
        
        $totalIncomePoint       = DB::table('mydt_transactions')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->where('plan_type',config('constant.plan_type.point'))
                                    ->sum('amount');*/
        
        return view('home', compact(
            'totalUsers', 
            'totalPremiumUsers', 
            'totalNormalUsers',
            'totalUsersThisMonth', 
            'totalPremiumUsersThisMonth', 
            'totalNormalUsersThisMonth',
            'totalIncomeThisMonth', 
            'totalIncomePremium', 
            'totalIncomePoint',
        ));

        //return view('home');
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

    public function getPremiumPurchaseHistory(Request $request)
    {
        $premiumPurchaseHistory = DB::table('mydt_transactions')
                                    ->join('users', 'users.id', '=', 'mydt_transactions.user_id') 
                                    ->where('mydt_transactions.plan_type', config('constant.plan_type.package'))
                                    ->select('mydt_transactions.*', 'users.name') 
                                    ->orderBy('mydt_transactions.created_at', 'desc') 
                                    ->limit(5)
                                    ->get();
        return response()->json($premiumPurchaseHistory);
    }

    public function getPointPurchaseHistory(Request $request)
    {
        $pointPurchaseHistory  = DB::table('mydt_transactions')
                                    ->join('users', 'users.id', '=', 'mydt_transactions.user_id') 
                                    ->where('mydt_transactions.plan_type', config('constant.plan_type.point'))
                                    ->select('mydt_transactions.*', 'users.name') 
                                    ->orderBy('mydt_transactions.created_at', 'desc') 
                                    ->limit(5)
                                    ->get();
        return response()->json($pointPurchaseHistory);
    }
}
