<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterEpisode extends Model
{
    protected $table = 'character_episode';

    protected $fillable = ['character_id', 'episode_id'];
}