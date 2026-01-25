<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'birth_date',
        'address',
        'phone',
        'avatar',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function internalUser()
    {
        return $this->hasOne(InternalUser::class);
    }

    public function customerUser()
    {
        return $this->hasOne(CustomerUser::class);
    }
}
