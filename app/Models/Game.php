<?php

namespace App\Models;

use App\Exceptions\ControllerException;
use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory, UsesUuid;

    public $incrementing = false;

    public $timestamps = false;

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function currentPlayer()
    {
        return $this->belongsTo(Player::class, 'current_player_id', 'id');
    }

    public function winner()
    {
        return $this->belongsTo(Player::class, 'winner_player_id', 'id');
    }

    public static function createGame($data)
    {
        $game = new Game();
        $field = Field::firstOrFail();
        $firstPlayer = Player::findOrFail(1);
        $secondPlayer = Player::findOrFail(2);
        $firstPlayer->freshCells();
        $secondPlayer->freshCells();
        $field->update(['width' => $data['width'], 'height' => $data['height']]);
        $field->refresh();
        $field->fillField();
        $game->field()->associate($field);
        $game->save();
        $game->addPlayers($firstPlayer, $secondPlayer);
        return $game;
    }

    public function makeStep(array $data)
    {
        $player = Player::findOrFail($data['playerId']);
        $color = Color::where('name', $data['color'])->firstOrFail();
        if ($this->winner_player_id !== null || $player->id == $this->currentPlayer_id) {
            throw new ControllerException('Игрок с указанным номером не может сейчас ходить', 403);
        }
        if ($color->id === $player->color_id || $color->id === $this->currentPlayer->color_id) {
            throw new ControllerException('Игрок с указанным номером не может выбрать указанный цвет', 409);
        }
        $this->fillCells($player, $color);
    }

    private function fillCells(Player $player, Color $color)
    {
        $cells = $player->cells;
        $width = $this->field->width;
        $initialCellCount = $cells->count();
        $opponent = Player::where('id', '!=', $player->id)->firstOrFail();
        foreach ($cells as $cell) {
            $newCells = Cell::whereIn('id', [$cell->id + $width, $cell->id + $width - 1, $cell->id - $width, $cell->id - $width + 1])->where('color_id', $color->id)->get();
            if (!$newCells->isEmpty()) {
                foreach ($newCells as $newCell) {
                    $newCell->player()->associate($player);
                    $newCell->save();
                    $cells->push(clone $newCell);
                }
            }
        }
        foreach ($cells as $cell) {
            $cell->color()->associate($color);
        }
        $newCellCount = $cells->count();
        $opponentCellCount = $opponent->cells->count();
        if ($initialCellCount >= $opponentCellCount && $newCellCount > $opponentCellCount) {
            $this->winner()->associate($player);
            $this->currentPlayer()->dissociate();
        } elseif ($initialCellCount <= $opponentCellCount && $newCellCount < $opponentCellCount) {
            $this->winner()->associate($opponent);
            $this->currentPlayer()->dissociate();
        } else {
            $this->currentPlayer()->associate($player);
            $player->color()->associate($color);
        }
        $this->save();
    }

    private function addPlayers(Player $firstPlayer, Player $secondPlayer)
    {
        $width = $this->field->width;
        $firstPlayerCells = Cell::where('id', $width * ($this->field->height - 1) + 1)->get();
        $firstPlayerCell = $firstPlayerCells->firstOrFail();
        $firstPlayerCell->player()->associate($firstPlayer);
        $firstPlayerCell->save();
        $color = $firstPlayerCell->color;
        foreach ($firstPlayerCells as $cell) {
            $newCells = Cell::query()->whereIn('id', [$cell->id + $width, $cell->id + $width - 1, $cell->id - $width, $cell->id - $width + 1])->where('color_id', $color->id)->get();
            if (!$newCells->isEmpty()) {
                foreach ($newCells as $newCell) {
                    $newCell->player()->associate($firstPlayer);
                    $newCell->save();
                    $firstPlayerCells->push(clone $newCell);
                }
            }
        }
        $firstPlayer->color()->associate($color);
        $firstPlayer->save();
        $secondPlayerCells = Cell::where('id', $width)->get();
        $secondPlayerCell = $secondPlayerCells->firstOrFail();
        $secondPlayerCell->player()->associate($secondPlayer);
        $secondPlayerCell->save();
        $color = $secondPlayerCell->color;
        foreach ($secondPlayerCells as $cell) {
            $newCells = Cell::whereIn('id', [$cell->id + $width, $cell->id + $width - 1, $cell->id - $width, $cell->id - $width + 1])->where('color_id', $color->id)->get();
            if ($newCells->isNotEmpty()) {
                foreach ($newCells as $newCell) {
                    $newCell->player()->associate($secondPlayer);
                    $newCell->save();
                    $secondPlayerCells->push(clone $newCell);
                }
            }
        }
        $secondPlayer->color()->associate($color);
        $secondPlayer->save();
    }

    public function getState()
    {
        $field = $this->field;
        $cells = [];
        $players = [];
        $field->cells()->whereNotNull('color_id')->cursor()->each(function ($cell) use (&$cells) {
            $cells[] = [
                'color' => $cell->color->name,
                'playerId' => $cell->player_id
            ];
        });
        Player::whereIn('id', [1, 2])->get()->each(function ($player) use (&$players) {
            $players[] = [
                'id' => $player->id,
                'color' => $player->color->name
            ];
        });
        return response()->json([
            'id' => $this->id,
            'field' => [
                'width' => $field->width,
                'height' => $field->height,
                'cells' => $cells,
            ],
            'players' => $players,
            'currentPlayerId' => $this->current_player_id,
            'winnerPlayerId' => $this->winner_player_id
        ]);
    }
}
