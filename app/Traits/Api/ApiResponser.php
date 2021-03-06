<?php 

namespace App\Traits\Api;

use Carbon\Carbon;

trait ApiResponser 
{
    /**
     * Token Generator
     *
     * @param [type] $personalAccessToken
     * @param [type] $message
     * @param integer $code
     * @return \Illuminate\Http\JsonResponse
     */
	public function token($personalAccessToken, $message = null, $data = null, $code = 200)
	{
		$tokenData = [
			'access_token' => $personalAccessToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($personalAccessToken->token->expires_at)->toDateTimeString(),
            'data' => $data
		];

		return $this->success($tokenData, $message, $code);
	}


    /**
     * Success Response
     *
     * @param [type] $data
     * @param [type] $message
     * @param integer $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = null, $message = null, int $code = 200)
	{
        return self::jsonResponse($code, $data, $message, 'success');
	}


    /**
     * Error Response
     *
     * @param [type] $message
     * @param integer $code
     * @return \Illuminate\Http\JsonResponse
     */
	public function error($message = null, int $code = 422)
	{
        return self::jsonResponse($code, null, $message, 'error');
    }


    /**
     * Success Response
     *
     * @param [type] $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function noContent(string $message = 'No Content')
	{
        return self::jsonResponse(204, null, $message, 'no content');
	}

    private static function jsonResponse(int $code = 200, $data = null, $message = null, string $status = '')
    {
        $status_message = [
            200 => '200 OK',
            204 => 'No Content',
            400 => '400 Bad Request',
            404 => 'Not Found',
            403 => 'Forbidden Request',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error'
        ];

        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status,
            'status_message' => $status_message[$code]
        ], $code);
    }
}
