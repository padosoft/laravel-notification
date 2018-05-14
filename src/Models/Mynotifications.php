<?php

namespace Padosoft\Laravel\Notification\Models;

use Illuminate\Database\Eloquent\Model;

class Mynotifications extends Model
{
    //
    protected $table = 'mynotifications';

    protected $guarded = ['id'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function scopeOfClass($query, $class)
    {
        if (is_object($class)){
            return $query->where('class', get_class($class));
        }
        return $query->where('class', $class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function notifications_users()
    {
        return $this->hasMany(Mynotifications_users::class, 'mynotifications_ID');
    }

    public function notifications_roles()
    {
        return $this->hasMany(Mynotifications_roles::class, 'mynotifications_ID');
    }

}
