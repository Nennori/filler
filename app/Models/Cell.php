<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
