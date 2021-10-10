<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function cells()
    {
        return $this->hasMany(Cell::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function freshCells()
    {
        foreach ($this->cells as $cell) {
            $cell->player()->dissociate();
        }
    }
}
