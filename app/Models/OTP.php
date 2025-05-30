<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    protected $table = 'otps';
    protected $fillable = ['email', 'user_id', 'code', 'expired_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}