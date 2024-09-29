<?php
/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/**************************/

namespace App\Http\Requests;

use App\Models\Device;
use App\Models\User;
use Error;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SendMessageRequest extends FormRequest
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
        $rules = [
            'sender' => [
                'required',
                function ($attribute, $value, $fail) {
                    $device = Device::where('body', $value)
                        ->where('status', 'connected')
                        ->first();
                    if (!$device) {
                        $fail(
                            'The ' . $attribute . ' is invalid or disconnected.'
                        );
                    }
                },
            ],
            'number' => [
                'required',
                function ($attribute, $value, $fail) {
                    $numbers = explode('|', $value);
                    if (count($numbers) > 10) {
                        $fail(
                            'The ' .
                                $attribute .
                                ' may not have more than 10 numbers.'
                        );
                    }
                    foreach ($numbers as $number) {
                        if (!strpos($number, '@g.us')) {

                            if (!preg_match('/^\d+$/', $number)) {
                                $fail(
                                    'The ' .
                                        $attribute .
                                        ' must have only numeric values separated by |.'
                                );
                            }
                        }
                    }
                },
            ],
            'type' => 'required',
            'message' => 'required_if:type,text,button,template',
			'latitude' => 'required_if:type,location',
			'longitude' => 'required_if:type,location',
            // for media
            'url' => 'required_if:type,media',
            'media_type' => 'required_if:type,media',

            // button required and array if type is button,and required and string if type is list
            'button' => 'required_if:type,button|array|min:1|max:5',
            // for template
            'template' => [
                'required_if:type,template',
                function ($attribute, $value, $fail) {

                    if (!is_array($value)) {
                        $fail('The ' . $attribute . ' must be array');
                        return;
                    }
                    if (count($value) > 3) {
                        $fail('The ' . $attribute . ' may not have more than 3 items');
                        return;
                    }
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
                }
            ],

            // for list
            'buttontext' => 'required_if:type,list',
            'name' => 'required_if:type,list',
            'title' => 'required_if:type,list',
            // 'list' must array and min 1
            'list' => 'required_if:type,list|array|min:1',
            'name' => 'required_if:type,poll',
            'option' => [
                'required_if:type,poll',
                'array',
                'min:2',
                'max:5',
                function ($attribute, $value, $fail) {
                    try {
                        //code...
                        if (count($value) !== count(array_unique($value))) {
                            $fail('The ' . $attribute . ' field must contain unique values.');
                        }
                    } catch (\Throwable $th) {
                        $fail('The ' . $attribute . ' must an array.');
                    }
                },
            ],
        ];

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        // jika permintaan adalah permintaan web, maka kembalikan respon dengan redirect ke halaman sebelumnya
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    public function messages()
    {
        return [
			'latitude.required_if' => 'Please add latitude',
			'longitude.required_if' => 'Please add longitude',
            'button1.required_if' => 'Please add BUTTON at least one',
            'button1.required' => 'Please add BUTTON at least one',
            'template1.required_if' => 'Please add template at least one',
            'template1.required' => 'Please add template at least one',
            'buttonlist.required_if' => 'The name of list button is required',
            'namelist.required_if' => 'The name of list is required',
            'titlelist.required_if' => 'The title of list is required',
        ];
    }
}
?>