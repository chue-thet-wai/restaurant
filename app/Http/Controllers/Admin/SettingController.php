<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\CommonRepositoryInterface;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SettingController extends Controller
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

    public function editProfile(): View
    {
        $login_id = Auth::user()->id;

        $user = User::find($login_id);
        return view('admin.setting.edit', [
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $userId = $user->id;

        $request->validate([
            'first_name'       => 'required|string|max:250',
            'last_name'        => 'nullable|string|max:250',
            
            'current_password' => ['nullable', 'string', 'min:6', function ($attribute, $value, $fail) use ($request, $user) {
                if(!Hash::check($value, $user->password)) {
                    $fail('Invalid current password');
                }
            }],
            
            'new_password'     => 'nullable|string|min:6',
        ]);

        $updateData = [
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
        ];       
 
        if(!empty($request->new_password)){
            $updateData['password'] = Hash::make($request->new_password);
        }

        $previous_profile=$request->previous_profile;
        @unlink(public_path('/assets/profiles/'. $previous_profile));

        if($request->hasFile('profile_image')){
            $image=$request->file('profile_image');
            $extension = $image->extension();
            $image_name = time() . "." . $extension;
        }else{
            $image_name="";
        } 
        
        $updateData['profile'] = $image_name;

        if ($image_name != "") {
            $image->move(public_path('assets/profiles'),$image_name);  
        }
        $updateData['is_admin'] = true;
        $updateData['updated_at'] = $this->nowDate;

        $user = User::find($userId);
        
        $user->update($updateData);

        $updateUser = User::find($userId);

        return redirect()->route('setting.edit', [
            'user' => $updateUser
        ])->with('success','Profile is updated successfully.');

    }

}