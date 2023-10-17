<?php

namespace App\Models;

use App\Traits\GenerateUuid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SteamLogBook extends Model
{
    use HasFactory, GenerateUuid;
    protected $guarded = [ 'id' ];
    public function steamProject()
    {
        return $this->belongsTo(SteamProject::class, 'steamProject_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function image()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function video()
    {
        return $this->morphMany(Video::class, 'videoable');
    }
    public function comment()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
