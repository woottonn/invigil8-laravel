<?php

namespace App;

class Role extends \Spatie\Permission\Models\Role
{
    protected $guarded = [];
    protected $guard_name = 'protector';

}
