<?php

namespace Alaame\Setting\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Setting extends Model
{
    public $table = 'ame_settings';


    public $guarded = [];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'key'=> 'required|unique:settings,key',
        'display_name' => 'required',
        'value' => 'required',
        'details' => 'nullable',
        'type' => 'required',
        'order' => 'numeric'
    ];


}
