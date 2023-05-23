<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Expense;
use App\Models\Balance;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
class ExpenseController extends Controller
{

        /**
     * Display a listing of the expenses.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAll()
    {
        $response = [];
        try {
            $data = Expense::all();
            if (!is_null($data)) {
                $response = $data;
            }
        } catch (\Exception $e) {
            Log::channel('database')->error('Error message: ' . $e->getMessage());
        }
        return response()->json($response, 200);
    }


    /**
     * Create a newly created Expense in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function create(Request $request)
     {
         try {
             $validator = Validator::make($request->all(), [
                 'medicinal_materials' => 'required|string',
                 'count' => 'required|integer',
                 'buy_price' => 'required|integer',
                 'note' => 'nullable|string',
             ]);

             if ($validator->fails()) {
                 return response()->json(['errors' => $validator->errors()], 422);
             }

             // Retrieve input data
             $medicinalMaterials = $request->input('medicinal_materials');
             $count = $request->input('count');
             $buyPrice = $request->input('buy_price');

             // Calculate total cost
             $totalCost = $count * $buyPrice;

             // Retrieve the current balance record
             $balance = Balance::first();

             // Subtract total cost from total balance
             $balance->total_balance -= $totalCost;

             // Save the changes to the balance record
             $balance->save();

             // Create a new expense record
             $expense = new Expense();
             $expense->medicinal_materials = $medicinalMaterials;
             $expense->count = $count;
             $expense->buy_price = $buyPrice;
             $expense->note = $request->input('note');
             $expense->save();

             return response()->json(['message' => 'Expense added successfully'], 201);
         } catch (\Exception $e) {
             // Handle the exception (e.g., log, display error message, etc.)
             return response()->json(['error' => $e->getMessage()], 500);
         }
     }


        // $validator = Validator::make($request->all(), [
        //     'medicinal-materials' => 'required|string',
        //     'count' => 'required|integer',
        //     'buy-price' => 'required|integer',
        //     'total-price' => 'required|integer',
        //     'note' => 'nullable|string',
        // ]);

        // // Validation error
        // if ($validator->fails()) {
        //     $response = [
        //         'message' => 'validation_error', $validator->errors(),
        //         'reason' => $validator->errors(),
        //     ];
        //     return response()->json($response, 400);
        // }

        // // Internal error
        // $data = Expense::create($request->all());
        // if (is_null($data)) {
        //     $response = [
        //         'message' => 'internal_server_error'
        //     ];
        //     return response()->json($response, 500);
        // }

        // // Success
        // return response()->json($data, 201);




    /**
     * Update the specified Expense in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $data = Expense::find($id);
        // Data not found
        if (is_null($data)) {
            $response = [
                'message' => 'resource_not_found'
            ];
            return response()->json($response, 404);
        }

        $data->fill($request->all());
        $isSaved = $data->save();
        // Success
        if ($isSaved) {
            return response()->json($data, 200);
        }

        // Internal error
        $response = [
            'message' => 'internal_server_error'
        ];
        return response()->json($response, 500);
    }












    /**
     * Remove the specified Expense from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id)
    {
        $data = Expense::find($id);

        // Data not found
        if (!$data) {
            $response = [
                'message' => 'resource_not_found'
            ];
            return response()->json($response, 404);
        }

        $isDeleted = $data->delete();
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
