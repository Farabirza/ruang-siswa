<?php

namespace App\Models;

use App\Traits\GenerateUuid;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory, GenerateUuid;
    
    protected $guarded = [ 'id' ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function image()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
