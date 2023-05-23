<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Balance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class PatientsController extends Controller
{
    /**
     * Display a listing of the patients.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = [];
        try {
            $data = Patient::all();
            if (!is_null($data)) {
                $response = $data;
            }
        } catch (\Exception $e) {
            Log::channel('database')->error('Error message: ' . $e->getMessage());
        }
        return response()->json($response, 200);
    }




    public function getPatientsDoctor(Request $request)
{
    $response = [];
    try {
        $doctorName = $request->input('doctor_name');

        $data = Patient::where('doctor_name', $doctorName)->get();

        if (!is_null($data)) {
            $response = $data;
        }
    } catch (\Exception $e) {
        Log::channel('database')->error('Error message: ' . $e->getMessage());
    }

    return response()->json($response, 200);
}



    /**
     * Create a newly created patient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'age' => 'required|string',
                'number' => 'required|string',
                'address' => 'required|string',
                'health_status' => 'required|string',
                'visits_one' => 'required|string',
                'visits_two' => 'nullable|string',
                'visits_three' => 'nullable|string',
                'visits_four' => 'nullable|string',
                'price' => 'nullable|numeric',
                'x_rays' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'doctor_name' => 'required|string',
                'note' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Check if the doctor exists
            $doctorName = $request->input('doctor_name');
            $doctor = Doctor::whereHas('user', function ($query) use ($doctorName) {
                $query->where('name', $doctorName);
            })->first();

            if (!$doctor) {
                return response()->json(['error' => 'Doctor not found'], 404);
            }

            // Create a new patient and associate it with the doctor
            $patient = new Patient();
            $patient->name = $request->input('name');
            $patient->age = $request->input('age');
            $patient->number = $request->input('number');
            $patient->address = $request->input('address');
            $patient->health_status = $request->input('health_status');
            $patient->visits_one = $request->input('visits_one');
            $patient->visits_two = $request->input('visits_two');
            $patient->visits_three = $request->input('visits_three');
            $patient->visits_four = $request->input('visits_four');
            $patient->price = $request->input('price');
            $patient->x_rays = $request->input('x_rays');
            $patient->note = $request->input('note');
            $patient->doctor_name = $doctorName;


            if ($x_rays = $request->file('x_rays')) {
                $destinationPath = 'images/';
                $profileImage = date('YmdHis') . "." . $x_rays->getClientOriginalExtension();
                $x_rays->move($destinationPath, $profileImage);
                $patient['x_rays'] = "$profileImage";
            }


            $patient->save();

            // Calculate 50% of the patient's price
            $price = $request->input('price');
            $doctorBalance = $doctor->balance;
            $balance = $price * 0.5;

            // Add 50% of the price to the doctor's balance
            $doctor->balance = $doctorBalance + $balance;
            $doctor->save();

            // Update the total_balance in the balances table
            $balanceRecord = Balance::first();
            if ($balanceRecord) {
                $balanceRecord->total_balance += $balance;
                $balanceRecord->save();
            } else {
                Balance::create(['total_balance' => $balance]);
            }

            return response()->json(['message' => 'Patient added successfully'], 201);
        } catch (\Exception $e) {
            // Handle the exception (e.g., log, display error message, etc.)
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getPatientsByDoctor(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'doctor_name' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $doctorName = $request->input('doctor_name');

            // Fetch all patients with the given doctor_name
            $patients = Patient::where('doctor_name', $doctorName)->get();

            return response()->json(['patients' => $patients], 200);
        } catch (\Exception $e) {
            // Log the error message or stack trace
            Log::error($e);

            // Return a detailed error response
            return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
        }
    }

    /**
     * Display the specified patient.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {
        $data = Patient::where('id', $id)->first();

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
     * Update the specified patient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $patient = Patient::find($id);

        if (!$patient) {
            $response = [
                'message' => 'resource_not_found'
            ];
            return response()->json($response, 404);
        }

        $patient->fill($request->all());

        if ($image = $request->file('x-rays')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $patient['x-rays'] = $profileImage;
        } else {
            unset($patient['x-rays']);
        }
        $isSaved = $patient->save();
        // Success
        if ($isSaved) {
            return response()->json($patient, 200);
        }

        // Internal error
        $response = [
            'message' => 'internal_server_error'
        ];
        return response()->json($response, 500);

    }


    /**
     * Remove the specified patient from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $patient = Patient::find($id);

        $isDeleted = $patient->delete();
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
