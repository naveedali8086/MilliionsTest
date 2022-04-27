<?php

namespace App\Models;

use App\Traits\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory, HasUuidPrimaryKey;

    protected $fillable = [
        'image', 'description', 'author', 'title'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }

    public function users(): BelongsToMany
    {
       return $this->belongsToMany(User::class, 'post_user', 'post_id', 'user_id', 'id', 'id');
    }

    public function postLikedBy()
    {
        return $this->users()->where('post_user.is_liked', 1);
    }

}
