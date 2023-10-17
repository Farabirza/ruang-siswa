<?php

namespace App\Models;

use App\Traits\GenerateUuid;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory, GenerateUuid;

    protected $guarded = [ 'id' ];
    public function videoable(): MorphTo
    {
        return $this->morphTo();
    }
}
