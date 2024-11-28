<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Interfaces\CommonRepositoryInterface;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
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
        $check_permission = $this->commonRepository->checkPermission('RoleController');
        return view('admin.roles.index', [
            'roles' => Role::orderBy('id','DESC')->paginate(10),
            'check_permission' => $check_permission
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $menu       =  Menu::get();

        $permission=[];
        $permissions_list = Permission::get();
        foreach($permissions_list as $per) {
            $permission[$per->menu_id][$per->action] = $per->id;
        }

        return view('admin.roles.create', [
            'menu'        => $menu,
            'permission' => $permission
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $login_id = Auth::user()->id;
        
        // Create the role
        $role = Role::create([
            'name' => $request->name,
            'created_by'     =>$login_id,
            'updated_by'     =>$login_id,
            'created_at'     =>$this->nowDate,
            'updated_at'     =>$this->nowDate
        ]);

        // Retrieve permissions by IDs
        $permissions = Permission::whereIn('id', $request->permissions)->pluck('id')->toArray();
        
        // Sync permissions with the role
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success','New role is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($roleId): View
    {
       
        $rolePermissions = Permission::join("role_has_permissions","permission_id","=","permissions.id")
            ->where("role_id",$roleId)
            ->select('name')
            ->get();
        $role = Role::find($roleId);
        return view('admin.roles.show', [
            'role' => $role,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($roleId): View
    {
        $menu       =  Menu::get();

        $permission=[];
        $permissions_list = Permission::get();
        
        foreach($permissions_list as $per) {
            $permission[$per->menu_id][$per->action] = $per->id;
        }

        $role = Role::find($roleId);

        $rolePermissions = DB::table("role_has_permissions")->where("role_id",$roleId)
            ->pluck('permission_id')
            ->all();

        return view('admin.roles.edit', [
            'role' => $role,
            'menu'       => $menu,
            'permission' => $permission,
            'rolepermissions' => $rolePermissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, $roleId): RedirectResponse
    {
        $login_id = Auth::user()->id;
        $updateData = array(
            'name'       =>$request->name,
            'updated_by' =>$login_id,
            'updated_at' =>$this->nowDate
        );

        $role = Role::find($roleId);
        $role->update($updateData);

        $permissions = Permission::whereIn('id', $request->permissions)->pluck('id')->toArray();

        $role->syncPermissions($permissions);    
        
        return redirect()->route('roles.index')->with('success','Role is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($roleId): RedirectResponse
    {

        $role = Role::find($roleId);
        $role->delete();
        return redirect()->route('roles.index')->with('success','Role is deleted successfully.');
    }
}
