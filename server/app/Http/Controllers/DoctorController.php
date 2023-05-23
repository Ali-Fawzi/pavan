<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
class DoctorController extends Controller
{
    /**
     * Display a listing of the Doctors.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $response = [];
        try {
            $data = Doctor::all();
            if (!is_null($data)) {
                $response = $data;
            }
        } catch (\Exception $e) {
            Log::channel('database')->error('Error message: ' . $e->getMessage());
        }
        return response()->json($response, 200);
    }

    /**
     * Store a newly created Doctor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'uuid' => 'required|exists:users,uuid',
            'age' => 'required|string',
            'phone_number' => 'required|string',
            'online_days' => 'required|string',
            'online_hours' => 'required|string',
            'balance' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('uuid', $request->input('uuid'))
            ->where('role_id', 2)
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid user UUID or role'], 422);
        }

        $doctor = new Doctor();
        $doctor->uuid = $request->input('uuid');
        $doctor->age = $request->input('age');
        $doctor->phone_number = $request->input('phone_number');
        $doctor->online_days = $request->input('online_days');
        $doctor->online_hours = $request->input('online_hours');
        $doctor->balance = $request->input('balance');
        // Set other fields as needed

        $doctor->save();

        return response()->json(['message' => 'Doctor details added successfully'], 201);

    } catch (\Exception $e) {
        // Handle the exception (e.g., log, display error message, etc.)
        return response()->json(['error' => 'An error occurred while adding doctor details'], 500);
    }
}



    /**
     * Display the specified Doctor.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {
        $data = Doctor::where('id', $id)->first();

        // Data not found
        if (is_null($data)) {
            $response = [
                'message' => 'resource_not_found'
            ];
            return response()->json($response, 404);
        }

        // Success
        return response()->json($data, 200);
    }
    /**
     * Update the specified Doctor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getPatientsByUuid($uuid)
    {
        $patients = Patient::where('uuid', $uuid)->get();
        return $patients;
    }

    public function update(Request $request, $id)
{
    $doctor = Doctor::find($id);

    if (!$doctor) {
        $response = [
            'message' => 'resource_not_found'
        ];
        return response()->json($response, 404);
    }

    $doctor->fill($request->all());


    $isSaved = $doctor->save();
        // Success
        if ($isSaved) {
            return response()->json($doctor, 200);
        }

        // Internal error
        $response = [
            'message' => 'internal_server_error'
        ];
        return response()->json($response, 500);
}


    /**
     * Remove the specified Doctor from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $doctor = Doctor::find($id);

        $isDeleted = $doctor->delete();
        // Success
        if ($isDeleted) {
            return response()->json(null, 204);
        }

        // Internal error
        $response = [
            'message' => 'internal_server_error'
        ];
        return response()->json($response, 500);
    }

}
