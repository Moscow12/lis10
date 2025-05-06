<?php

namespace App\Http\Controllers;

use App\Models\lab_results;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HL7Controller extends Controller
{
    public function receive(Request $request)
    {
        // $hl7Data = $request->getContent(); // HL7 often sent as raw text
        // Log::info('HL7 Received', ['data' => $hl7Data]);

        // Optional: parse or store the HL7 data here
        $json = $request->getContent();
        $data = json_decode($json, true);
    
        if (!isset($data['data'])) {
            return response()->json(['error' => 'Invalid data format'], 400);
        }
    
        $raw = trim($data['data'], "\x0b\x1c\r"); // remove HL7 framing
        $segments = explode("\r", $raw);
    
        $patientName = '';
        $gender = '';
        $results = [];
    
        foreach ($segments as $segment) {
            $fields = explode('|', $segment);
            if (isset($fields[0])) {
                switch ($fields[0]) {
                    case 'PID':
                        $patientName = $fields[5] ?? '';
                        $gender = $fields[8] ?? '';
                        break;
                    case 'OBX':
                        $results[] = [
                            'test_code'       => $fields[3] ?? '',
                            'test_name'       => '', // if available separately
                            'value'           => $fields[5] ?? '',
                            'unit'            => $fields[6] ?? '',
                            'reference_range' => $fields[7] ?? '',
                            'abnormal_flag'   => $fields[8] ?? '',
                        ];
                        break;
                }
            }
        }
    
        // Save to database
        foreach ($results as $result) {
            lab_results::create([
                'patient_name'     => $patientName,
                'gender'           => $gender,
                'test_code'        => $result['test_code'],
                'test_name'        => $result['test_name'],
                'value'            => $result['value'],
                'unit'             => $result['unit'],
                'reference_range'  => $result['reference_range'],
                'abnormal_flag'    => $result['abnormal_flag'],
            ]);
        }
    
        return response()->json(['status' => 'success', 'inserted' => count($results)]);
        // return response()->json(['message' => 'HL7 data received successfully'], 200);
    }
}
