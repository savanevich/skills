<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Convert data to JSON 
     * @param  int $status     Http status code
     * @param  array $data     data from db   
     * @param  $error          error message
     * @return json object
     */
    public function toJsonResponse($status, $data, $error)
    {
        $result = [];

        $result['status'] = $status;
        $result['data'] = $data;
        $result['error'] = $error;

        return response()->json($result, $status);
    }
}
