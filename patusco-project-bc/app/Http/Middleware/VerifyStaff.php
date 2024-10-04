<?php

namespace App\Http\Middleware;

use Closure;
use Exception;

class VerifyStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $jwt_data = $request->input('jwt_data');
            if ($jwt_data->profile_id == 2 || $jwt_data->profile_id == 4) {
                return $next($request);
            } else {
                return response()->json(['error' => "O seu utilizador não tem autorização para aceder a esta informação"], 401);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!'], 500);
        }
    }
}
