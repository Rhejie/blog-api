<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => "datetime:M-d-Y",
    ];
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
