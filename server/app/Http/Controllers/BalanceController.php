<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Balance;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Validator;
class BalanceController extends Controller
{
    public function getAll()
    {
        $response = [];
        try {
            $data = Balance::all();
            if (!is_null($data)) {
                $response = $data;
            }
        } catch (\Exception $e) {
            Log::channel('database')->error('Error message: ' . $e->getMessage());
        }
        return response()->json($response, 200);
    }
}
