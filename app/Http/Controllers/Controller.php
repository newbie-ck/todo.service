<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Todo API",
 *     version="1.0.0",
 *     description="API for managing todos",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function response($data, $code = 200)
    {
        return response()->json($data, $code);
    }

    public function responseSuccess($data, $code = 200)
    {
        return $this->response([
            'success' => true,
            "data" => $data
        ], $code);
    }
}
