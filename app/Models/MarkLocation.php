<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarkLocation extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
