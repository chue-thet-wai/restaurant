<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\CommonRepositoryInterface;
use App\Models\Role;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
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

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $check_permission = $this->commonRepository->checkPermission('RoleController');
        $userList = User::where('is_admin','1')->latest('id')->paginate(10);
        return view('admin.users.index', [
            'users' => $userList,
            'check_permission' => $check_permission
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => Role::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $login_id = Auth::user()->id;
        $user = User::create([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'is_admin'       => true,
            //'created_by'     =>$login_id,
            //'updated_by'     =>$login_id,
            'created_at'     =>$this->nowDate,
            'updated_at'     =>$this->nowDate
        ]);

        $user->assignRole($request->roles);

        return redirect()->route('users.index')->with('success','New user is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($userId): View
    {
        $user = User::find($userId);
        return view('admin.users.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($userId): View
    {
        $user = User::find($userId);
        $userRoles = $user->roles->pluck('id')->toArray();
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::all(),
            'userRoles' => $userRoles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $userId): RedirectResponse
    {
        $request->validate([
            'email' => [Rule::unique('users', 'email')->ignore($userId)],            
        ]);

        $login_id = Auth::user()->id;
        $updateData = [
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'email'          => $request->email,
        ];       
 
        if(!empty($request->password)){
            $updateData['password'] = Hash::make($request->password);
        }

        $updateData['is_admin'] = true;
        //$updateData['updated_by'] = $login_id;
        $updateData['updated_at'] = $this->nowDate;

        $user = User::find($userId);
        
        $user->update($updateData);

        $user->assignRole($request->roles);

        return redirect()->route('users.index')->with('success','User is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userId): RedirectResponse
    {
        $user = User::find($userId);
        //$user->syncRoles([]);
        $user->delete();
        return redirect()->route('users.index')->with('success','User is deleted successfully.');
    }
}