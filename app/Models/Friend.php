<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model {

    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'reject';

    protected $fillable = [
        'user_id', 'friend_id', 'status'
    ];

    protected $table = "friends";

    public function user()
    {
        return $this->belongsTo(User::class, 'friend_id', 'id');
    }

    public function by_user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
