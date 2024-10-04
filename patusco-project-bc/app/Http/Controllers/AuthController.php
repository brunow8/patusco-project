<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        try {
            $userCredentials = $request->input('user');
            $user = User::where('email', $userCredentials['email'])->first();
            if (empty($userCredentials['email']) || empty($userCredentials['password']))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'Por favor insira um válido email e palavra-passe!']);
            if (!filter_var($userCredentials['email'], FILTER_VALIDATE_EMAIL)) return response()->json(['errorType' => 'warning-toast', 'message' => "Insira um email válido!"]);
            if (!$token = JWTAuth::attempt($userCredentials))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'Email ou palavra-passe estão inválidas!']);
            return response()->json([
                'errorType' => 'success-toast',
                'message' => 'Login bem-sucedido.',
                'token' => $token,
                'profile' => $user->profile_id
            ]);
        } catch (JWTException $e) {
            Log::error('ERROR WHEN LOGIN: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Ums erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        } catch (Exception $e) {
            Log::error('ERROR WHEN LOGIN: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
}
