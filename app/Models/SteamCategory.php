<?php

namespace App\Models;

use App\Traits\GenerateUuid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SteamCategory extends Model
{
    use HasFactory, GenerateUuid;
    protected $guarded = [ 'id' ];
    public function steamProject()
    {
        return $this->hasMany(SteamProject::class, 'steamCategory_id');
    }
}
