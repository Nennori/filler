<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGameRequest;
use App\Http\Requests\MakeStepRequest;
use App\Models\Game;

class GameController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/game",
     *     operationId="createGame",
     *     summary="Create new game",
     *     @OA\Parameter(
     *         name="width",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="height",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Поле создано",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="id",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid data",
     *     )
     * )
     */
    public function create(CreateGameRequest $request)
    {
        $game = Game::createGame($request->only('width', 'height'));
        return response()->json(['uuid' => $game->id], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/game/{gameId}",
     *     operationId="makeStep",
     *     summary="Make step in game",
     *     @OA\Parameter(
     *         name="gameId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="playerId",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             enum={1, 2}
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="color",
     *         in="query",
     *         required=true,
     *         description="Selected color",
     *         @OA\Schema(
     *             type="string",
     *             enum={"blue", "green", "cyan", "red", "magenta", "yellow", "white"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ход успешно сделан",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Неправильные параметры запроса",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Игрок с указанным номером не может сейчас ходить",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Игра с указанным ID не существует",
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Игрок с указанным номером не может выбрать указанный цвет",
     *     )
     * )
     */
    public function makeStep(MakeStepRequest $request, Game $game)
    {
        $game->makeStep($request->only('playerId', 'color'));
        return response()->json(['message' => 'Ход успешно сделан'], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/game/{gameId}",
     *     operationId="getState",
     *     summary="Get state of the game",
     *     @OA\Parameter(
     *         name="gameId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Game state",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="id",
     *                 type="string",
     *                 description="game id"
     *             ),
     *             @OA\Property(
     *                 property="field",
     *                 type="object",
     *                 @OA\Property(
     *                     property="width",
     *                     type="integer",
     *                     description="field width"
     *                 ),
     *                 @OA\Property(
     *                     property="height",
     *                     type="integer",
     *                     description="field height"
     *                 ),
     *                 @OA\Property(
     *                     property="cells",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             property="color",
     *                             type="string",
     *                             description="cell color"
     *                         ),
     *                         @OA\Property(
     *                             property="playerId",
     *                             type="integer",
     *                             description="player id"
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="players",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(
     *                             property="id",
     *                             type="integer",
     *                             description="player id"
     *                         ),
     *                         @OA\Property(
     *                             property="color",
     *                             type="string",
     *                             description="player color"
     *                         )
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="currentPlayerId",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="winnerPlayerId",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Неправильные параметры запроса",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Игра с указанным ID не существует",
     *     )
     * )
     */
    public function getState(Game $game)
    {
        return $game->getState();
    }
}
