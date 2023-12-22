<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = ['name', 'status', 'species', 'gender'];

    public function episodes()
    {
        return $this->belongsToMany(Episode::class, 'character_episode', 'character_id', 'episode_id');
    }
}
