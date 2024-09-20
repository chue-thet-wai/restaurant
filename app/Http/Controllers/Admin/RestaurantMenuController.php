<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestaurantMenuRequest;
use App\Interfaces\CommonRepositoryInterface;
use App\Models\Category;
use App\Models\RestaurantMenu;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RestaurantMenuController extends Controller
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
        $check_permission = $this->commonRepository->checkPermission('RestaurantMenuController');
        $status           = $this->commonRepository->getStatus();

        $restuaurant_menu_list = RestaurantMenu::orderBy('id', 'DESC')->paginate(10);
        $restuaurant_menu_list->getCollection()->transform(function($menu) use ($status) {
            $menu->status = $status[$menu->status] ?? 'Inactive'; 
            return $menu;
        });
        
        return view('admin.restaurant_menu.index', [
            'restaurant_menus' => $restuaurant_menu_list,
            'check_permission' => $check_permission
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::all();
        return view('admin.restaurant_menu.create',[
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RestaurantMenuRequest $request): RedirectResponse
    {
        $login_id = Auth::user()->id;
        DB::beginTransaction();
        try{
            
            if($request->hasFile('menu_image')){
                $image=$request->file('menu_image');
                $extension = $image->extension();
                $image_name = time() . "." . $extension;
            }else{
                $image_name="";
            } 

            $insertData = array(
                'name'              =>$request->name,
                'price'             =>$request->price,
                'category_id'       =>$request->category,
                //'status'            =>$request->status,
                'menu_image'        =>$image_name,
                'description'       =>$request->description,
                'created_by'        =>$login_id,
                'updated_by'        =>$login_id,
                'created_at'        =>$this->nowDate,
                'updated_at'        =>$this->nowDate
            );

            $result=RestaurantMenu::insert($insertData);     

            if($result){
                if ($image_name != '') {
                    $image->move(public_path('assets/menu_images'),$image_name); 
                }   
                DB::commit(); 
                return redirect()->route('restaurant_menu.index')->with('success','New Restaurant Menu is added successfully.');
            }else{
                DB::rollback();
                return redirect()->back()->with('danger','New Restaurant Menu Added Fail !');
            }
        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Restaurant Menu Added Fail !');
        }  
        
    }

    /**
     * Display the specified resource.
     */
    public function show($restaurantMenuID): View
    {
        $restaurantMenu = RestaurantMenu::find($restaurantMenuID);
        $categories = Category::all();

        return view('admin.restaurant_menu.show', [
            'restaurant_menu'      => $restaurantMenu,
            'categories'           => $categories
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($restaurantMenuID): View
    {
        $restaurantMenu = RestaurantMenu::find($restaurantMenuID);
        $status           = $this->commonRepository->getStatus();
        $categories       = Category::all();

        return view('admin.restaurant_menu.edit', [
            'restaurant_menu'        => $restaurantMenu,
            'status'                 => $status,
            'categories'             => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RestaurantMenuRequest $request, $restaurantMenuID): RedirectResponse
    {
        $login_id = Auth::user()->id;
        DB::beginTransaction();
        try{
            if($request->hasFile('menu_image')){
                $previous_img=$request->previous_image;
                @unlink(public_path('/assets/menu_images/'. $previous_img));
    
                $image=$request->file('menu_image');
                $extension = $image->extension();
                $image_name = time() . "." . $extension;
            }else{
                $image_name="";
            } 

            $updateData = array(
                'name'              =>$request->name,
                'price'             =>$request->price,
                'category_id'       =>$request->category,
                'status'            =>$request->status,
                'description'       =>$request->description,
                'updated_by'        =>$login_id,
                'updated_at'        =>$this->nowDate
            );

            if ($image_name != "") {
                $updateData['menu_image'] = $image_name;
            }
            
            $result=RestaurantMenu::where('id',$restaurantMenuID)->update($updateData);
                      
            if($result){
                if ($image_name != "") {
                    $image->move(public_path('assets/menu_images'),$image_name);  
                }
                DB::commit();               
                return redirect()->route('restaurant_menu.index')->with('success','Restaurant Menu is updated successfully.');
            }else{
                DB::rollback();
                return redirect()->back()->with('danger','Restaurant Menu Updated Fail !');
            }

        }catch(\Exception $e){
            DB::rollback();
            Log::info($e->getMessage());
            return redirect()->back()->with('danger','Restaurant Menu Updated Fail !');
        }  
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($restaurantMenuID): RedirectResponse
    {

        $restaurantMenu = RestaurantMenu::find($restaurantMenuID);
        $restaurantMenu->delete();
        return redirect()->route('restaurant_menu.index')->with('success','Restaurant Menu is deleted successfully.');
    }

}
