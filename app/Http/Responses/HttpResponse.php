<?php 

namespace App\Http\Responses;

trait HttpResponse
{
    /**
     * Standar Json Response
     * 
     * @param array $data Data to return
     * @param int $code Http code
     * @param array $header Data associates at the response
     */
    public function jsonResponse($data, $code = 200, $headers =[])
    {
        return response()->json($data, $code, $headers);
    }

    /**
     * Json response success
     * 
     * @param mixed $data Data to return
     * @param string $message Response description
     * @param int $code Http code
     * 
     * @return json
     */
    public function success($data, $message = 'Request seccessful.', $code = 200)
    {
        return response()->json([
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
    public function error($errors, $message = 'An Error has ocurred...', $code)
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}