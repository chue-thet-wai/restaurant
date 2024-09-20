<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\CommonRepositoryInterface;
use App\Models\Branch;
use App\Models\Category;
use App\Models\OrderMenu;
use App\Models\Reservation;
use App\Models\RestaurantMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebappController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     */
    private CommonRepositoryInterface $commonRepository;
    protected $nowDate;

    public function __construct(CommonRepositoryInterface $commonRepository) 
    {
        $this->commonRepository = $commonRepository;
        $this->nowDate  = date('Y-m-d H:i:s', time());
    }

    public function reservation()
    {
        $branchList = Branch::get();
        return view('webapp.reservation', [
            'branches' => $branchList
        ]);
    }

    public function storeReservation(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'branch'     => 'required',
            'email'      => 'required|email',
            'date'       => 'required|date',
            'time'       => 'required',
            'seat_count' => 'required|integer|min:1',
        ]);

        // Generate a unique order ID
        $orderId = 'ORD-' . strtoupper(uniqid());

        DB::beginTransaction();
        try{

            $insertData = array(
                'order_id'   =>$orderId,
                'branch_id'  =>$request->branch,
                'email'      =>$request->email,
                'date'       =>$request->date,
                'time'       =>$request->time,
                'seat_count' =>$request->seat_count,
                'created_at' =>$this->nowDate,
                'updated_at' =>$this->nowDate
            );

            $result=Reservation::insert($insertData);     

            if($result){
                DB::commit(); 
                return redirect()->back()->with('success', 'Reservation successful! Your order ID is ' . $orderId);
            }else{
                DB::rollback();
                return redirect()->back()->with('danger','Something is wrong.Please contact with developer');
            }
        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Something is wrong.Please contact with developer');
        }  
    }

    public function information($orderID)
    {
        $reservation = Reservation::where('order_id',$orderID)->first();
        return view('webapp.information',[
            'reservation' => $reservation
        ]);
    }

    public function storeInformation(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'order_id'   => 'required',
            'name'       => 'required',
            'email'      => 'required|email',
            'phone'      => 'required',
        ]);

        DB::beginTransaction();
        try{

            $updateData = array(
                'name'       =>$request->name,
                'phone'      =>$request->branch,
                'email'      =>$request->email,
                'updated_at' =>$this->nowDate
            );

            $result=Reservation::where('order_id',$request->order_id)->update($updateData);    

            if($result){
                DB::commit(); 
                return redirect()->to('/reservation-confirm/'.$request->order_id);
            }else{
                DB::rollback();
                return redirect()->back()->with('danger','Something is wrong.Please contact with developer');
            }
        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Something is wrong.Please contact with developer');
        }  
    }

    public function reservationConfirm($orderID)
    {
        $reservation = Reservation::where('order_id',$orderID)->first();
        return view('webapp.reservation_confirm',[
            'reservation' => $reservation
        ]);
    }

    public function menuList($orderID)
    {
        $categories = Category::with('menuItems')->get();
        $reservation = Reservation::where('order_id',$orderID)->first();
        return view('webapp.menu_list', [
            'categories' => $categories,
            'reservation' => $reservation
        ]);
    }

    public function getMenuItems($id)
    {
        if ($id == 'all') {
            $menuItems = RestaurantMenu::all();
        } else {
            $category = Category::with('menuItems')->find($id);
            $menuItems = $category ? $category->menuItems : [];
        }

        return response()->json(['menuItems' => $menuItems]);
    }

    public function getMenuDetail($orderID,$id)
    {
        $menuDeatil = RestaurantMenu::find($id);
        $reservation = Reservation::where('order_id',$orderID)->first();

        return view('webapp.menu_detail', [
            'menu_detail' => $menuDeatil,
            'reservation' => $reservation
        ]);
    }

    public function addToCart(Request $request,$orderID)
    {
        $menu_id    = $request->menu_id;
        $quantity   = $request->quantity;

        $cart = session()->get('cart', []);

        $cartKey = $orderID . '_' . $menu_id;

        $menuDetail = RestaurantMenu::find($menu_id);

        if (isset($cart[$menu_id])) {
            $cart[$cartKey]['quantity'] += $quantity;
            $cart[$cartKey]['price']    += $menuDetail['price'] * $quantity;
        } else {
            $cart[$cartKey] = [
                "menu_id"  => $menu_id,
                "menu_name"=> $menuDetail['name'],
                "quantity" => $quantity,
                "price"    => $menuDetail['price'] * $quantity
            ];
        }

        // Update the cart session
        session()->put('cart', $cart);
        return redirect()->to('/menu/'.$orderID);
    }

    public function getOrderSummary($orderID)
    {
        $cart = session()->get('cart', []);

        $total = array_sum(array_column($cart, 'price'));

        $reservation = Reservation::where('order_id',$orderID)->first();

        return view('webapp.order_summary',[
            'cart'  => $cart,
            'total' => $total,
            'tax'   => 0,
            'reservation' => $reservation
        ]);
    }

    public function confirmOrder(Request $request,$orderID)
    {
        $cart = session()->get('cart', []);

        $image_name = $orderID . ".png";
        if($request->hasFile('receipt')){
            
            @unlink(public_path('/assets/receipts/'. $image_name));
            $image=$request->file('receipt');
        }else{
            $image_name="";
        } 

        foreach ($cart as $id => $item) {
            OrderMenu::create([
                'order_id' => $orderID,
                'menu_id'  => $item['menu_id'],
                'quantity' => $item['quantity'],
                'price'    => $item['price'],
            ]);
        }
        if ($image_name != "") {
            $image->move(public_path('assets/receipts'),$image_name);  
        }

        session()->forget('cart');

        return redirect()->to('/reservation-confirm/'.$orderID);
    }

}