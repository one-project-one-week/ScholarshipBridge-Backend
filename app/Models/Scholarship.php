<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Scholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'organization',
        'eligibility',
        'category',
        'program',
        'field',
        'country',
        'deadline',
        'apply_link',
        'photo_link',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'scholarship_users');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
