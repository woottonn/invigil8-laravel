<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Traits\HasRoles;

class Group extends Model
{
    protected $guarded = [];
    use HasRoles;

    public function users()
    {
        return $this->hasMany(User::class);
    }


}
