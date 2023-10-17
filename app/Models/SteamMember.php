<?php

namespace App\Models;

use App\Traits\GenerateUuid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SteamMember extends Model
{
    use HasFactory, GenerateUuid;
    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function steamProject()
    {
        return $this->belongsTo(SteamProject::class, 'steamProject_id');
    }
}
