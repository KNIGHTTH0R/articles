<?php

namespace Ergare17\Articles\Http\Requests;

use Acacha\Events\Http\Requests\Traits\ChecksPermissions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserStoreArticle
 *
 * @package App\Http\Requests
 */
class UserStoreArticle extends FormRequest
{
    use ChecksPermissions;

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
        return [
            'name' => 'required',
            'user_id' => 'required'
        ];
    }
}
