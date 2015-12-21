<?php

namespace AbbyLynn\Translatable\Http\Requests;

use App\Http\Requests\Request;

class LanguageRequest extends Request {

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
      'id'    => 'required|exists:languages,id|integer',
      'name' => 'required|min:3|max:255',
      'abbr' => 'required|min:2|max:2'
    ];
  }

}