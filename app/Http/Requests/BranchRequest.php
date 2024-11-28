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
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        $rules = [
            'name'   => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('myrt_branch', 'name')->ignore($branchID)
            ],
            'phone'   => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'remark'  => ['nullable', 'string'],
        ];

        foreach ($days as $day) {
            $rules["opening_time.$day"] = ['nullable'];
            $rules["closing_time.$day"] = ['nullable'];

            $rules["opening_time.$day"][] = function ($attribute, $value, $fail) use ($day) {
                $closingTime = $this->input("closing_time.$day");

                if ($value && !$closingTime) {
                    $fail("To time is needed for $day .");
                }

                if (!$value && $closingTime) {
                    $fail("From time for $day .");
                }

                if ($value && $closingTime) {
                    $openingTime = strtotime($value);
                    $closingTime = strtotime($closingTime);

                    if ($openingTime >= $closingTime) {
                        $fail("From Time must be earlier than To Time for $day.");
                    }
                }
            };
        }

        return $rules;
    }
}
