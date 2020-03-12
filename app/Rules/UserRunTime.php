<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UserRunTime implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($run_id)
    {
        $this->run_id = $run_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
    }

    public function rules()
    {
        $run_id = $this->run_id;
        return [
            'user_id' => Rule::unique('times', 'user_id')->where('run_id', $run_id)
        ];
    }

    /**
     * Get the validation error message.
     *
     * @return array
     */
    public function message()
    {
        return [
            'user_id.unique' => 'A user may only have 1 time entry per run'
        ];
    }
}
