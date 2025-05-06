<?php

namespace App\Http\Controllers;

use App\Models\lab_results;
use App\Models\patients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HL7Controller extends Controller
{
    public function receive(Request $request)
    {
        $hl7 = $request->input('data');
        // Clean control characters
        $cleaned = preg_replace("/[\x0b\x1c]/", '', $hl7);
        $lines = preg_split("/\r\n|\r|\n/", trim($cleaned));

        $patientData = [];
        $results = [];
        foreach ($lines as $line) {
            $fields = explode('|', $line);
            $segment = $fields[0];
            
            if ($segment === 'PID') { 
                $patientData = [
                    'external_id' => !empty($fields[2]) ? $fields[2] : $fields[3],
                    'name' => $fields[5] ?? null,
                    'dob' => !empty($fields[6]) ? $fields[6] : $fields[7], // not provided
                    'sex' => $fields[8],
                ];
            }
            
            if ($segment === 'OBX') {
                $results[] = [
                    'test_code' => $fields[3] ?? null,
                    'value' => $fields[5] ?? null,
                    'units' => !empty($fields[3]) ? $fields[3] : $fields[5],
                    'reference_range' => $fields[7] ?? null,
                    'abnormal_flag' => $fields[8] ?? null,
                ];
            }
        }

        // Save to database (Patient and related LabResults)
        $patient = \App\Models\Patients::firstOrCreate(
            ['external_id' => $patientData['external_id']],
            $patientData
        );

        foreach ($results as $res) {
            $patient->results()->create($res);
        }

        return response()->json([
            'message' => 'Data extracted and saved',
            'patient' => $patientData,
        ]);


        // return response()->json(['message' => 'HL7 data received successfully'], 200);
    }
}
