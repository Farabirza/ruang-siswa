<?php

namespace App\Models;

use App\Traits\GenerateUuid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SteamProject extends Model
{
    use HasFactory, GenerateUuid;
    protected $guarded = [ 'id' ];

    public function steamCategory()
    {
        return $this->belongsTo(SteamCategory::class, 'steamCategory_id');
    }
    public function steamMember()
    {
        return $this->hasMany(SteamMember::class, 'steamProject_id');
    }
    public function steamLogBook()
    {
        return $this->hasMany(SteamLogBook::class, 'steamProject_id');
    }
    public function comment()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
