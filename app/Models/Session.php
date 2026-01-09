<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Session extends Model
{
    protected $table = 'sessions';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'last_activity' => 'integer',
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

