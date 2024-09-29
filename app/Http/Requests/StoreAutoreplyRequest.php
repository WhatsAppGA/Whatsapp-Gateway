<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAutoreplyRequest extends FormRequest
{
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
            'keyword' => ['required', 'unique:autoreplies,keyword,NULL,id,device_id,' . $this->device],
            'image' => 'required_if:type,image',
            'caption' => 'required_if:type,image',
            'button' => 'required_if:type,button|array|min:1|max:5',
            'template' => ['required_if:type,template|array|min:1|max:3',function($attribute, $value, $fail) {
              
              $i = 0;
                  foreach ($value as  $val) {
                        $i++;
                        // must separator by | and must have 3 value
                        $count = count(explode('|', $val));
                        $type = explode('|', $val)[0];
                        if ($count != 3) {
                              $fail('The ' . $attribute . ' ' . $i . ' is wrong format');
                              return;
                             
                        }
                        if ($type != 'call' && $type != 'url') {
                              $fail('Invalid type for ' . $attribute . ' ' . $i . ' must be call or url like this : call|+6282298859671|Call me or url|https://google.com|Visit Google');
                                return;
                             
                        }
                  }
            }],
            'list' => 'required_if:type,list',
            'title' => 'required_if:type,list',
            'buttontext' => 'required_if:type,list',
      ];
    }
}
