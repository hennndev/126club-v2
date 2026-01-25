<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerUser extends Model
{
    protected $guarded;

    protected $casts = [
        'total_visits' => 'integer',
        'lifetime_spending' => 'decimal:2',
        'points' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id');
    }

    // Calculate points (1 point per 10,000 spent)
    public function getPointsAttribute()
    {
        return (int) floor($this->lifetime_spending / 10000);
    }
}
