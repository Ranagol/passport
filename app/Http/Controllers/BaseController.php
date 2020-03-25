<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
        /*Explanation: the sendResponse will send a response to the frontend user. For example, a succesfull PUT request response will be something like this:
        {
        "success": true,
        "data": {
            "id": 1,
            "name": "Gyertyaedited",
            "detail": "szepedited",
            "created_at": "25/03/2020",
            "updated_at": "25/03/2020"
        },
        "message": "Product updated successfully."
}
        */
    }


    /**
     * return error response to the fronted, when the request from the frontend is not OK. For example: token is wrong, data can't be validated...
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
