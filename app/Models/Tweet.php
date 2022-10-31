<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = ['body'];

    protected $appends = ['url'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return route('tweet.show', ['username' => $this->owner->username, 'tweet' => $this->id]);
            }
        );
    }
}
