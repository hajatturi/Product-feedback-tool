<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Feedback;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'feedback_id',
        'content',
    ];

    // Define the relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with Feedback model
    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

    public function mentionedUsers()
    {
        return $this->belongsToMany(User::class, 'mentioned_users', 'comment_id', 'user_id');
    }
}
