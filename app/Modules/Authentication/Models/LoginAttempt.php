<?php

namespace App\Modules\Authentication\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $table = 'login_attempts';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $casts = [
        'successful' => 'boolean',
        'attempted_at' => 'datetime',
    ];

    /**
     * ব্যবহারকারী সম্পর্ক
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}