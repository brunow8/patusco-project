<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function getAppointments(Request $request)
    {
        try {
            $user = $request->input('jwt_data');
            Appointment::where('start_appointment_date', '<', Carbon::now())
                ->whereNotNull('end_appointment_date')
                ->update(['state' => 0]);
            Appointment::whereNotNull('medic_id')
                ->update(['state' => 2]);
            if ($user->profile_id == 2) {
                $appointments = Appointment::getMedicApppointments($request);
                return response()->json(['errorType' => 'success-toast', 'message' => '', 'data' => $appointments]);
            } else if ($user->profile_id == 4) {
                $appointments = Appointment::getRecepcionistAppointments($request);
                return response()->json(['errorType' => 'success-toast', 'message' => '', 'data' => $appointments]);
            } else {
                return response()->json(['errorType' => 'success-toast', 'message' => '', 'data' => []]);
            }
        } catch (Exception $e) {
            Log::error('ERROR GETTING APPOINTMENTS: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
    public function getAppointmentsByMedic(Request $request, $medicId)
    {
        try {
            $jwt_data = $request->input('jwt_data');
            if ($jwt_data->id != $medicId && $jwt_data->profile == 2)
                return response()->json(['errorType' => 'error-toast', 'message' => 'O seu utilizador não tem permissão para esta informação.', 'data' => []]);
            $appointments = Appointment::select('appointments.*', 'appointments.start_appointment_date as start', 'appointments.end_appointment_date as end')->where('medic_id', $medicId)->get();
            return response()->json(['errorType' => 'success-toast', 'message' => '', 'data' => $appointments]);
        } catch (Exception $e) {
            Log::error('ERROR GETTING APPOINTMENTS: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
    public function createClientAppointment(Request $request)
    {
        try {
            $appointment = (object) $request->input('appointment');
            $user = $request->input('jwt_data');
            if (empty($appointment->petName) || empty($appointment->petBreed) || empty($appointment->petType) || empty($appointment->birthdayPet))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'Um campo obrigatório está em falta!']);
            if (Carbon::parse($appointment->birthdayPet)->gt(Carbon::now()))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'A data de nascimento do animal não pode ser maior que o dia de hoje!']);
            $animal = Animal::where('user_id', $user->id)->where('name', trim($appointment->petName))
                ->where('breed', trim($appointment->petBreed))
                ->where('animal_type_id', trim($appointment->petType))
                ->first();
            DB::beginTransaction();
            if (!$animal) {
                $animal = Animal::create([
                    'user_id' =>  $user->id,
                    'name' => $appointment->petName,
                    'breed' => $appointment->petBreed,
                    'animal_type_id' => $appointment->petType,
                    'birthday' => $appointment->birthdayPet
                ]);
            }
            if (Appointment::where('user_id', $user->id)->where('animal_id', $animal->id)->where('state', 1)->first())
                return response()->json(['errorType' => 'warning-toast', 'message' => 'Já existe uma marcação para este animal agendada.']);
            if (empty(trim($appointment->symptoms)) || empty($appointment->appointmentDate))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'Um campo obrigatório está em falta!']);
            if (Carbon::parse($appointment->appointmentDate)->lte(Carbon::now()))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'A data da consulta não pode ser inferior ou igual ao dia de hoje!']);
            Appointment::create([
                'user_id' => $user->id,
                'animal_id' => $animal->id,
                'symptoms' => trim($appointment->symptoms),
                'start_appointment_date' => Carbon::parse($appointment->appointmentDate),
                'state' => 1 // Significa por confirmar
            ]);
            DB::commit();
            return response()->json(['errorType' => 'success-toast', 'message' => 'Consulta agendada com sucesso!']);
        } catch (Exception $e) {
            Log::error('ERROR CREATING CLIENT APPOINTMENT: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }

    public function createRecepcionistAppointment(Request $request)
    {
        try {
            $appointment = (object) $request->input('appointment');
            if (empty($appointment->client_id) || empty($appointment->medic_id) || empty($appointment->animal_id) || empty($appointment->symptoms) || empty($appointment->start_appointment_date) || empty($appointment->end_appointment_date))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'Um campo obrigatório está em falta!']);
            if (Carbon::parse($appointment->start_appointment_date)->hour < 9) {
                return response()->json(['errorType' => 'warning-toast', 'message' => 'O horário de trabalho do médico é das 09h as 18h!']);
            }
            if (Carbon::parse($appointment->end_appointment_date)->hour > 18 || (Carbon::parse($appointment->end_appointment_date)->hour == 18 && Carbon::parse($appointment->end_appointment_date)->minute > 0)) {
                return response()->json(['errorType' => 'warning-toast', 'message' => 'O horário de trabalho do médico é das 09h as 18h!']);
            }
            if (Carbon::parse($appointment->start_appointment_date)->lt(Carbon::now()))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'A data de início da consulta não pode ser inferior á data atual!']);
            if (Carbon::parse($appointment->end_appointment_date)->lte(Carbon::parse($appointment->start_appointment_date)))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'A data de fim da consulta não pode ser inferior á data de início da mesma!']);
            if (Carbon::parse($appointment->start_appointment_date)->diffInHours(Carbon::parse($appointment->end_appointment_date)) < 1)
                return response()->json(['errorType' => 'warning-toast', 'message' => 'A consulta tem duração de pelo menos 1 hora.']);
            $existAppointment = Appointment::where('user_id', $appointment->client_id)->where('animal_id', $appointment->animal_id)->where('state', 1)->exists();
            if ($existAppointment)
                return response()->json(['errorType' => 'warning-toast', 'message' => 'Este utente já tem uma consulta agendada com este animal.']);
            $startDate = Carbon::parse($appointment->start_appointment_date);
            $endDate = Carbon::parse($appointment->end_appointment_date);
            $existAppointment = Appointment::where('medic_id', $appointment->medic_id)
                ->whereIn('state', [1, 2])
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_appointment_date', [$startDate, $endDate])
                        ->orWhereBetween('end_appointment_date', [$startDate, $endDate])
                        ->orWhere(function ($query) use ($startDate, $endDate) {
                            $query->where('start_appointment_date', '<=', $startDate)
                                ->where('end_appointment_date', '>=', $endDate);
                        });
                })
                ->exists();
            if ($existAppointment)
                return response()->json(['errorType' => 'warning-toast', 'message' => 'O médico encontra-se ocupado durante esse intervalo de tempo estipulado.']);
            DB::beginTransaction();
            Appointment::create([
                'user_id' => $appointment->client_id,
                'medic_id' => $appointment->medic_id,
                'animal_id' => $appointment->animal_id,
                'symptoms' => trim($appointment->symptoms),
                'start_appointment_date' => Carbon::parse($appointment->start_appointment_date),
                'end_appointment_date' => Carbon::parse($appointment->end_appointment_date),
                'state' => 2 // Significa confirmado
            ]);
            DB::commit();
            return response()->json(['errorType' => 'success-toast', 'message' => 'Consulta agendada com sucesso!']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('ERROR CREATING CLIENT APPOINTMENT: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
    public function editAppointmentMedic(Request $request)
    {
        try {
            $appointment = (object) $request->input('appointment');
            DB::beginTransaction();
            if (empty($appointment->start_appointment_date) || empty($appointment->end_appointment_date))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'Um campo obrigatório está em falta!']);
            if (Carbon::parse($appointment->start_appointment_date)->hour < 9) {
                return response()->json(['errorType' => 'warning-toast', 'message' => 'O horário de trabalho do médico é das 09h as 18h!']);
            }
            if (Carbon::parse($appointment->end_appointment_date)->hour > 18 || (Carbon::parse($appointment->end_appointment_date)->hour == 18 && Carbon::parse($appointment->end_appointment_date)->minute > 0)) {
                return response()->json(['errorType' => 'warning-toast', 'message' => 'O horário de trabalho do médico é das 09h as 18h!']);
            }
            if (Carbon::parse($appointment->start_appointment_date)->lt(Carbon::now()))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'A data de início da consulta não pode ser inferior á data atual!']);
            if (Carbon::parse($appointment->end_appointment_date)->lte(Carbon::parse($appointment->start_appointment_date)))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'A data de fim da consulta não pode ser inferior á data de início da mesma!']);
            if (Carbon::parse($appointment->start_appointment_date)->diffInHours(Carbon::parse($appointment->end_appointment_date)) < 1)
                return response()->json(['errorType' => 'warning-toast', 'message' => 'A consulta tem duração de pelo menos 1 hora.']);
            $startDate = Carbon::parse($appointment->start_appointment_date);
            $endDate = Carbon::parse($appointment->end_appointment_date);
            $existAppointment = Appointment::where('medic_id', $appointment->medic_id)
                ->where('state', 2)
                ->where('id', '<>', $appointment->appointment_id)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_appointment_date', [$startDate, $endDate])
                        ->orWhereBetween('end_appointment_date', [$startDate, $endDate])
                        ->orWhere(function ($query) use ($startDate, $endDate) {
                            $query->where('start_appointment_date', '<=', $startDate)
                                ->where('end_appointment_date', '>=', $endDate);
                        });
                })
                ->exists();
            if ($existAppointment)
                return response()->json(['errorType' => 'warning-toast', 'message' => 'O médico encontra-se ocupado durante esse intervalo de tempo estipulado.']);
            $updated = Appointment::where('id', $appointment->appointment_id)
                ->update(['start_appointment_date' => $startDate, 'end_appointment_date' => $endDate]);
            if (!$updated) {
                return response()->json([
                    'errorType' => 'error-toast',
                    'message' => 'Nenhuma consulta encontrada para atualizar!'
                ]);
            }
            DB::commit();
            return response()->json([
                'errorType' => 'success-toast',
                'message' => 'Consulta editada com sucesso!'
            ]);
        } catch (Exception $e) {
            Log::error('ERROR EDITING APPOINTMENT: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'errorType' => 'error-toast',
                'message' => 'Um erro inesperado aconteceu. Por favor contacte o suporte ou tente novamente!'
            ]);
        }
    }
    public function editAppointmentRecepcionist(Request $request)
    {
        try {
            $appointment = (object) $request->input('appointment');
            DB::beginTransaction();
            if (empty($appointment->start_appointment_date) || empty($appointment->end_appointment_date) || empty($appointment->medic_id))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'Um campo obrigatório está em falta!']);
            if (Carbon::parse($appointment->start_appointment_date)->hour < 9) {
                return response()->json(['errorType' => 'warning-toast', 'message' => 'O horário de trabalho do médico é das 09h as 18h!']);
            }
            if (Carbon::parse($appointment->end_appointment_date)->hour > 18 || (Carbon::parse($appointment->end_appointment_date)->hour == 18 && Carbon::parse($appointment->end_appointment_date)->minute > 0)) {
                return response()->json(['errorType' => 'warning-toast', 'message' => 'O horário de trabalho do médico é das 09h as 18h!']);
            }
            if (Carbon::parse($appointment->start_appointment_date)->lt(Carbon::now()))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'A data de início da consulta não pode ser inferior á data atual!']);
            if (Carbon::parse($appointment->end_appointment_date)->lte(Carbon::parse($appointment->start_appointment_date)))
                return response()->json(['errorType' => 'warning-toast', 'message' => 'A data de fim da consulta não pode ser inferior á data de início da mesma!']);
            if (Carbon::parse($appointment->start_appointment_date)->diffInHours(Carbon::parse($appointment->end_appointment_date)) < 1)
                return response()->json(['errorType' => 'warning-toast', 'message' => 'A consulta tem duração de pelo menos 1 hora.']);
            $startDate = Carbon::parse($appointment->start_appointment_date);
            $endDate = Carbon::parse($appointment->end_appointment_date);
            $existAppointment = Appointment::where('medic_id', $appointment->medic_id)
                ->where('state', 2)
                ->where('id', '<>', $appointment->appointment_id)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_appointment_date', [$startDate, $endDate])
                        ->orWhereBetween('end_appointment_date', [$startDate, $endDate])
                        ->orWhere(function ($query) use ($startDate, $endDate) {
                            $query->where('start_appointment_date', '<=', $startDate)
                                ->where('end_appointment_date', '>=', $endDate);
                        });
                })
                ->exists();
            if ($existAppointment)
                return response()->json(['errorType' => 'warning-toast', 'message' => 'O médico encontra-se ocupado durante esse intervalo de tempo estipulado.']);
            $updated = Appointment::where('id', $appointment->appointment_id)
                ->update(['start_appointment_date' => $startDate, 'end_appointment_date' => $endDate]);
            if (!$updated) {
                return response()->json([
                    'errorType' => 'error-toast',
                    'message' => 'Nenhuma consulta encontrada para atualizar!'
                ]);
            }
            DB::commit();
            return response()->json([
                'errorType' => 'success-toast',
                'message' => 'Consulta editada com sucesso!'
            ]);
        } catch (Exception $e) {
            Log::error('ERROR EDITING APPOINTMENT: ' . $e->getMessage());
            DB::rollBack(); // Roll back the transaction on error
            return response()->json([
                'errorType' => 'error-toast',
                'message' => 'Um erro inesperado aconteceu. Por favor contacte o suporte ou tente novamente!'
            ]);
        }
    }
    public function deleteAppointment($appointmentId)
    {
        try {
            DB::beginTransaction();
            Appointment::find($appointmentId)->delete();
            DB::commit();
        } catch (Exception $e) {
            Log::error('ERROR DELETING APPOINTMENT: ' . $e->getMessage());
            return response()->json(['errorType' => 'error-toast', 'message' => 'Um erro inesperado aconteceu. Por favor contacte o supporte ou tente novamente!']);
        }
    }
}
