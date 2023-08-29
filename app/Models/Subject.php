<?php

namespace App\Models;

use App\Traits\GenerateUuid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory, GenerateUuid;
    
    protected $guarded = [ 'id' ];
    public function achievement()
    {
        return $this->hasMany(achievement::class);
    }
}
