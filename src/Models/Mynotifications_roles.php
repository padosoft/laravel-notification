<?php

namespace Padosoft\Laravel\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Padosoft\Laravel\PermissionExtended\Models\Role;

class Mynotifications_roles extends Model
{
    //
    protected $table = 'mynotifications_roles';

    protected $guarded = ['id'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'methods' => 'collection',
    ];

    public function users()
    {
        return $this->roles->users();
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, 'roles_ID');
    }

    public function mynotifications()
    {
        return $this->belongsTo(Mynotifications::class, 'mynotifications_ID');
    }
}
