<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History_log;

class HistoryController extends Controller
{

    // public function saveHistory(array $data)
    // {
    //     $users = History_log::insertGetId([
    //         'primarykeyvalue_Id' => $data['primarykeyvalue_Id'],
    //         'user_id' => $data['user_id'],
    //         'log_type' => $data['log_type'],
    //         'table_name' => $data['table_name'],
    //         'qury_log' => $data['qury_log'],
    //         'description' => $data['description'],
    //         'created_date' => $data['created_date'],
    //         'patient_id' => $data['patient_id']??0,
    //     ]);

    //     return $users;
    // }

    public function saveHistory(array $data) 
{
    $patientId = isset($data['patient_id']) ? $data['patient_id'] : 0;

    $users = History_log::insertGetId([
        'primarykeyvalue_Id' => $data['primarykeyvalue_Id'],
        'user_id' => $data['user_id'],
        'log_type' => $data['log_type'],
        'table_name' => $data['table_name'],
        'qury_log' => $data['qury_log'],
        'description' => $data['description'],
        'created_date' => $data['created_date'],
        'patient_id' => $patientId,
    ]);

    return $users;
}


}
?>
