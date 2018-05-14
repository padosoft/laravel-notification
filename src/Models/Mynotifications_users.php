<?php

namespace Padosoft\Laravel\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Padosoft\Laravel\Notification\Models\Mynotifications;

class Mynotifications_users extends Model
{
    //
    protected $table = 'mynotifications_users';

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
        return $this->belongsTo(config('auth.providers.users.model'), 'users_ID');
    }

    public function notifications()
    {
        return $this->belongsTo(Mynotifications::class, 'mynotifications_ID');
    }
}
