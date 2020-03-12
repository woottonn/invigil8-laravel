<?php

namespace App;

class Permission extends \Spatie\Permission\Models\Permission
{
    protected $guarded = [];
    protected $guard_name = 'protector';

}
