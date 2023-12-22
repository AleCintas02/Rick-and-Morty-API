<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = ['name','air_date', 'episode'];

    public function characters()
    {
        return $this->belongsToMany(Character::class, 'character_episode', 'episode_id', 'character_id');
    }
}
