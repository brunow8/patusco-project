<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalType;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

class AnimalController extends Controller
{
    public function getAnimalTypes()
    {
        try {
            $animalTypes = AnimalType::all();
            return response()->json(['errorType' => 'success-toast', 'message' => '', 'data' => $animalTypes]);
        } catch (Exception $e) {
            Log::error('ERROR GETTING ANIMAL TYPES: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
    public function getAnimals()
    {
        try {
            return response()->json(['errorType' => 'success-toast', 'message' => '', 'data' => Animal::all()]);
        } catch (Exception $e) {
            Log::error('ERROR GETTING ALL ANIMALS: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
    public function getAnimalByClientId($clientId)
    {
        try {
            return response()->json(['errorType' => 'success-toast', 'message' => '', 'data' => Animal::where('user_id', $clientId)->get()]);
        } catch (Exception $e) {
            Log::error('ERROR GETTING ALL ANIMALS: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
    public function getAnimalDetailById($animalId)
    {
        try {
            $animal = Animal::select('animals.*', 'animal_types.name as animal_type')
                ->join('animal_types', 'animal_types.id', '=', 'animals.animal_type_id')
                ->find($animalId);
            return response()->json(['errorType' => 'success-toast', 'message' => '', 'data' => $animal]);
        } catch (Exception $e) {
            Log::error('ERROR GETTING USER DETAIL: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
}
