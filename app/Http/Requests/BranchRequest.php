<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules()
    {
        $branchID = $this->route('branch'); // Get the ID from the route

        // Initialize the rules
        $rules = [
            'name'   => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('myrt_branch', 'name')->ignore($branchID) // Check for uniqueness, ignoring the current branch
            ],
            'remark' => ['nullable', 'string'],
        ];

        return $rules;
    }
}
