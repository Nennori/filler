<?php

namespace App\Exceptions;

use Exception;

class ControllerException extends Exception
{
    /**
     *
     * @OA\Schema(
     *     schema="ControllerException",
     *     title="ControllerException",
     *     description="ControllerException model",
     *     @OA\Xml(
     *         name="ControllerException"
     *     ),
     *     @OA\Property(
     *         property="message",
     *         title="message",
     *         description="Response message",
     *         type="string"
     *     )
     * )
     */
    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }

}
