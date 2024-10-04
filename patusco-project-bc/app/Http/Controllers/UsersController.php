<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function registerClient(Request $request)
    {
        try {
            $user = (object) $request->input('newUser');
            if (empty($user->firstName) || empty($user->lastName) || empty($user->email) || empty($user->password) || empty($user->confirmPassword) || empty($user->birthday))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'Um campo obrigatório está em falta!']);
            if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) return response()->json(['errorType' => 'warning-toast', 'message' => "Insira um email válido!"]);
            if (User::where('email', $user->email)->first()) return response()->json(['errorType' => 'warning-toast', 'message' => "Email já se encontra a ser utilizado!"]);;
            if (trim($user->password) != trim($user->confirmPassword)) return response()->json(['errorType' => 'warning-toast', 'message' => "Palavras-passe não são iguais!"]);
            if (!User::isValidPassword($user->password)) return response()->json(['errorType' => 'warning-toast', 'message' => "Palavra-passe tem de conter uma letra maiúscula, uma letra especial, um número e tem de ter pelo menos 8 caracteres."]);
            if (Carbon::parse($user->birthday)->diffInYears(Carbon::now()) < 18)
                return response()->json([
                    'errorType' => 'warning-toast',
                    'message' => 'Você tem de ter pelo menos 18 anos para se registrar!'
                ]);
            if (!empty($user->cellphone)) {
                if (preg_match('/[a-zA-Z]/', $user->cellphone)) return response()->json(['errorType' => 'warning-toast', 'message' => "O número de telemóvel não pode conter letras!"]);
                if (strlen(trim($user->cellphone)) != 9) return response()->json(['errorType' => 'warning-toast', 'message' => "O número de telemóvel tem de ter 9 dígitos!"]);
            }
            DB::beginTransaction();
            $user = User::create([
                'first_name' => $user->firstName,
                'last_name' => $user->lastName,
                'email' => $user->email,
                'password' => Hash::make($user->password),
                'birthday' => Carbon::parse($user->birthday)->toDateString(),
                'cellphone' => empty($user->cellphone) ? null : $user->cellphone,
                'profile_id' => 3
            ]);
            DB::commit();
            return response()->json(['errorType' => 'success-toast', 'message' => 'Registro bem-sucedido.']);
        } catch (Exception $e) {
            Log::error('ERROR REGISTERING CLIENT: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
    public function getUserDetail(Request $request)
    {
        try {
            $user = $request->input('jwt_data');
            $userResponse = [
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'cellphone' => $user->cellphone,
                'birthday' => $user->birhtday
            ];
            return response()->json(['errorType' => 'success-toast', 'message' => '', 'data' => $userResponse]);
        } catch (Exception $e) {
            Log::error('ERROR GETTING USER DETAIL: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
    public function getUserDetailById($userId)
    {
        try {
            return response()->json(['errorType' => 'success-toast', 'message' => '', 'data' => User::find($userId)]);
        } catch (Exception $e) {
            Log::error('ERROR GETTING USER DETAIL: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
    public function getClients()
    {
        try {
            return response()->json(['errorType' => 'success-toast', 'message' => '', 'data' => User::where('profile_id', 3)->get()]);
        } catch (Exception $e) {
            Log::error('ERROR GETTING ALL CLIENTS: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
    public function getMedics()
    {
        try {
            return response()->json(['errorType' => 'success-toast', 'message' => '', 'data' => User::where('profile_id', 2)->get()]);
        } catch (Exception $e) {
            Log::error('ERROR GETTING ALL MEDICS: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
}
