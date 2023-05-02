<?php 

namespace App\Http\Responses;

trait JsonResponse
{
    /**
     * Json response success
     * 
     * @param mixed $data Data to return
     * @param string $message Response description
     * @param int $code Http code
     * 
     * @return json
     */
    public function success($data, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'Request seccessful.',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Json response error
     * 
     * @param mixed $data Data to return
     * @param string $message Response description
     * @param int $code Http code
     * 
     * @return json
     */
    public function error($data, $message = null, $code)
    {
        return response()->json([
            'status' => 'An Error has ocurred...',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}