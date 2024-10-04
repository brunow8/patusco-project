<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Appointment extends Model
{
    protected $table = "appointments";
    protected $fillable = [
        'user_id',
        'animal_id',
        'symptoms',
        'start_appointment_date',
        'end_appointment_date',
        'medic_id',
        'state',
    ];

    public static function getMedicApppointments($request)
    {
        $filters = $request->input('filters');
        $appointments = Appointment::select(
            'appointments.id as appointment_id',
            'animal_id',
            'animals.name as animal_name',
            'start_appointment_date',
            'end_appointment_date',
            "client.id as client_id",
            DB::raw("CONCAT(client.first_name, ' ', client.last_name) as client_name"),
            'client.email as client_email',
            'medic.id as medic_id',
            DB::raw("CONCAT(medic.first_name, ' ', medic.last_name) as medic_name"),
            'medic.email as medic_email',
            'symptoms'
        )
            ->where('medic_id', $request->input('jwt_data')->id)
            ->where('state', 2)
            ->join('users as client', 'client.id', 'appointments.user_id')
            ->join('users as medic', 'medic.id', 'appointments.medic_id')
            ->join('animals', 'animals.id', 'appointments.animal_id')
            ->join('animal_types', 'animal_types.id', 'animals.animal_type_id');
        if (!empty($filters['animal_type'])) $appointments = $appointments->where('animal_types.id', $filters['animal_type']);
        if (!empty($filters['fromDate'])) {
            $fromDate = Carbon::parse($filters['fromDate'])->startOfDay();
            $appointments = $appointments->where('start_appointment_date', '>=', $fromDate);
        }
        if (!empty($filters['toDate'])) {
            $toDate = Carbon::parse($filters['toDate'])->endOfDay();
            $appointments = $appointments->where('start_appointment_date', '<=', $toDate);
        }
        return $appointments->orderBy('start_appointment_date', 'asc')->get();
    }
    public static function getRecepcionistAppointments($request)
    {
        $filters = $request->input('filters');
        $appointments = Appointment::select(
            'appointments.id as appointment_id',
            'animal_id',
            'animals.name as animal_name',
            'start_appointment_date',
            'end_appointment_date',
            "client.id as client_id",
            DB::raw("CONCAT(client.first_name, ' ', client.last_name) as client_name"),
            'client.email as client_email',
            'medic.id as medic_id',
            DB::raw("CONCAT(medic.first_name, ' ', medic.last_name) as medic_name"),
            'medic.email as medic_email',
            'symptoms'
        )
            ->whereIn('state', [1, 2])
            ->join('users as client', 'client.id', 'appointments.user_id')
            ->leftJoin('users as medic', 'medic.id', 'appointments.medic_id')
            ->join('animals', 'animals.id', 'appointments.animal_id')
            ->join('animal_types', 'animal_types.id', 'animals.animal_type_id');
        if (!empty($filters['animal_type'])) $appointments = $appointments->where('animal_types.id', $filters['animal_type']);
        if (!empty($filters['fromDate'])) {
            $fromDate = Carbon::parse($filters['fromDate'])->startOfDay();
            $appointments = $appointments->where('start_appointment_date', '>=', $fromDate);
        }
        if (!empty($filters['toDate'])) {
            $toDate = Carbon::parse($filters['toDate'])->endOfDay();
            $appointments = $appointments->where('start_appointment_date', '<=', $toDate);
        }
        return $appointments->orderBy('start_appointment_date', 'asc')->get();
    }
}
