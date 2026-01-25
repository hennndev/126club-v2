<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'code',
        'name',
        'capacity',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tables()
    {
        return $this->hasMany(Tabel::class);
    }

    public function internalUsers()
    {
        return $this->hasMany(InternalUser::class);
    }
}
