<?php

namespace Bitfumes\Multiauth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    public $rules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $admin_id = request('admin.id');
        $rules    = [
            'name'     => 'required|max:255',
            'email'    => "required|email|max:255|unique:admins,email,{$admin_id}",
            'password' => 'required|min:8|confirmed',
            'role_id'  => 'required',
        ];
        $rules = $this->mergeClientRules($rules);
        $rules = $this->checkForUpdate($rules);

        return $rules;
    }

    protected function mergeClientRules($rules)
    {
        $clientRules = config('multiauth.admin.validations');

        return array_merge($rules, $clientRules);
    }

    protected function checkForUpdate($rules)
    {
        if (request()->method() == 'PATCH') {
            unset($rules['password']);
        }

        return $rules;
    }
}
