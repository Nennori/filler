<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['width', 'height'];

    public function cells()
    {
        return $this->hasMany(Cell::class);
    }

    public function fillField()
    {
        $cellCount = $this->width * $this->height;
        $colors = Color::all();
        Cell::where('id', '<=', $cellCount)->cursor()->each(function ($cell) use ($colors) {
            $cell->color()->associate($colors->random());
            $cell->save();
        });
    }

    public function refresh()
    {
        Cell::query()->whereNotNull('color_id')->update(['color_id' => null, 'player_id' => null]);
    }
}
