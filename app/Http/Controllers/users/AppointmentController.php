<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\Masters\SpecialistMaster;
use Illuminate\Http\Request;
use App\Models\PatientAppointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use App\Models\PatientRegistration;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HistoryController;



class AppointmentController extends Controller
{

    protected $HistoryController;

    public function __construct(HistoryController $HistoryController)
    {
        $this->HistoryController = $HistoryController;
    }

    public function index(Request $request)
    {
        $data = array();
        // $patient_data=[];
        if (isset($request->id)) {
            $cond = ['id' => $request->id];
            $patient_data = PatientAppointment::where('id', $request->id)->orderBy('id', 'DESC')->first();
            $data['patient_data'] = $patient_data;
        }

        $data['PageName'] = "Patient Appointment";
        return Parent::page_maker('webpanel.patientAppointment', $data);
    }

    public function appointmentList()
    {
        $data = array();

        $data['PageName'] = "Patient ";
        return Parent::page_maker('webpanel.patientAppointmentList', $data);
    }

    public function saveAppointment(Request $request)
    {

        $validated = $request->validate([
            'a_patientname'     => 'required',
            'a_appointment_date' => 'required',
            'a_specialist_id'   => 'required',
            'a_mobile_number'   => 'required',
        ]);
        if ($validated) {
            // $details = [
            //     'patientname' => $request->a_patientname,

            // ];
            $ins_data = array(
                'salutation_id' => $request->a_salutation_id,
                'patientname'  => $request->a_patientname,
                'last_name'    => $request->a_last_name,
                'appointment_type' => 2,
                'dob' => $request->a_dob ? date("Y-m-d", strtotime($request->a_dob)) : NULL,
                'age' => Carbon::parse($request->a_dob)->diff(Carbon::now())->y,
                'mobile_number' => $request->a_mobile_number,
                'email' => $request->a_email_address,
                'gender' => $request->a_gender,
                'department_id' => $request->a_department_id,
                'specialist_id' => $request->a_specialist_id,
                // 'appointment_date'=>$request->a_appointment_date,
                'appointment_date' => $request->a_appointment_date ? date("Y-m-d", strtotime($request->a_appointment_date)) : NULL,
                'appointment_time' => $request->a_appointment_time,
                'created_by' => Auth::id(),
                'branch_id' => Session::get('current_branch'),
                'patient_id' => $request->a_pid ?  $request->a_pid : 0,
            );



            if ($request->crude == 1) {
                DB::connection()->enableQueryLog(); // enable qry log
                $insert = PatientAppointment::create($ins_data);
                // TO GET LAST EXECUTED QRY
                $query = DB::getQueryLog();
                $lastQuery = end($query);
                $sql = $lastQuery['query'];
                $bindings = $lastQuery['bindings'];
                $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                //end of qury

                // LOG  // add parameters based on your need
                $log = array(
                    'primarykeyvalue_Id' => Session::get('dtms_pid'),
                    'user_id' => Auth::id(), // userId
                    'log_type' => 1, // save
                    'table_name' => 'PatientAppointment', // Save Patient Appointment
                    'qury_log' => $sql,
                    'description' => 'DTMS ,Save Patient Patient Appointment',
                    'created_date' => date('Y-m-d H:i:s'),
                    'patient_id' => Session::get('dtms_pid'),
                );

                $save_history = $this->HistoryController->saveHistory($log);
                ////////////////////////////////////////////////////////////////////////////////////
                // END OF LOG
                $patient_id = $insert->id;
                return ['status' => 1, 'message' => "Saved Successfully", 'id' => $patient_id];
            } else if ($request->crude == 2) {
                DB::connection()->enableQueryLog(); // enable qry log
                $update = PatientAppointment::whereId($request->a_appointmentid)->update($ins_data);
                // TO GET LAST EXECUTED QRY
                $query = DB::getQueryLog();
                $lastQuery = end($query);
                $sql = $lastQuery['query'];
                $bindings = $lastQuery['bindings'];
                $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                //end of qury

                // LOG  // add parameters based on your need
                $log = array(
                    'primarykeyvalue_Id' => Session::get('dtms_pid'),
                    'user_id' => Auth::id(), // userId
                    'log_type' => 2, // save
                    'table_name' => 'PatientAppointment', // Save Patient Appointment
                    'qury_log' => $sql,
                    'description' => 'DTMS ,Update Patient Appointment',
                    'created_date' => date('Y-m-d H:i:s'),
                    'patient_id' => Session::get('dtms_pid'),
                );

                $save_history = $this->HistoryController->saveHistory($log);
                ////////////////////////////////////////////////////////////////////////////////////
                // END OF LOG
                return ['status' => 2, 'message' => "Updated Successfully"];
            }
        } else {
            echo 2; // validation error
        }
    }
    public function searchPatientAppointment(Request $request)
    {


        $cond = [];
        if (!is_null($request->patient_name)) {
            array_push($cond, ['patientname', 'ILIKE', "%{$request->patient_name}%"]);
        }
        if (!is_null($request->uhid)) {
            array_push($cond, ['uhidno', 'ILIKE', "%{$request->uhid}%"]);
        }
        if (!is_null($request->mobile_number)) {
            array_push($cond, ['mobile_number', 'ILIKE', "%{$request->mobile_number}%"]);
        }
        // if( !is_null($request->patient_type) ){
        //     array_push($cond,['patient_type', $request->patient_type]);
        // }
        if (!is_null($request->last_name)) {
            array_push($cond, ['last_name', 'ILIKE', "%{$request->last_name}%"]);
        }
        if (!is_null($request->gender)) {
            array_push($cond, ['gender', $request->gender]);
        }
        if (!is_null($request->age)) {
            $cdate = date('Y');
            $birthYear = $cdate - $request->age;
            array_push($cond, [
                DB::raw('EXTRACT(YEAR  FROM dob )'),
                $birthYear
            ]);
        }
        if (!is_null($request->address)) {
            array_push($cond, ['address', 'ILIKE', "%{$request->address}%"]);
        }



        $start_date = Carbon::parse(request()->from_date)->toDateTimeString();
        $end_date = Carbon::parse(request()->to_date)->toDateTimeString();
        $end_date = str_replace("00:00:00", "24:00:00", $end_date);

        $wherebtm = [];

        if (!is_null($request->from_date)) {
            array_push($cond, ['appointment_date', '>=', "$start_date"]);
        }
        if (!is_null($request->to_date)) {
            array_push($cond, ['appointment_date', '<=', "$end_date"]);
        }


        array_push($cond, ['patient_appointment.branch_id', '=', Session::get('current_branch')]);

        // $cond=[['name', 'LIKE', '%{$request->patient_name}%'],['id', 'LIKE', "%{$request->uhid}%"],['mobile_number', 'LIKE', "%{$request->mobile_number}%"]];


        // DB::enableQueryLog();
        // $data = PatientRegistration::where($cond)->whereBetween('created_at', [$start_date, $end_date])->orderBy('id','DESC')->get();
        // return $result;
        //  dd(DB::getQueryLog());

        // $data = getSearchValue('patient_registration',$cond);

        $data = PatientAppointment::select(
            'patient_appointment.*',
            'departments.department_name',
            'specialist_master.specialist_name',
            'patient_registration.uhidno'
        )
            ->leftjoin('departments', 'departments.id', '=', 'patient_appointment.department_id')
            ->leftjoin('specialist_master', 'specialist_master.id', '=', 'patient_appointment.specialist_id')
            ->leftjoin('patient_registration', 'patient_registration.id', '=', 'patient_appointment.patient_id')
            ->where($cond)->orderBy('id', 'DESC')->get();


        $output = array(
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
        );
        echo json_encode($output);


        // return response()->json($data);
    }

    public function linkpatientAppointment(Request $request)
    {


        $patient_data = PatientAppointment::where('id', $request->id)->orderBy('id', 'DESC')->first();
        $ins_data = array(
            'branch_id'    => Session::get('current_branch'),
            'name'         => $patient_data->patientname,
            'dob'          => $patient_data->dob,
            'gender'       => $patient_data->gender,
            'salutation_id' => $patient_data->salutation_id,
            'last_name'    => $patient_data->last_name,
            'email'        => $patient_data->email,
            'mobile_number' => $patient_data->mobile_number,
            'department_id' => $patient_data->department_id,
            'age'          => $patient_data->age,
        );
        $auto_increment_value = PatientRegistration::max('id');
        $ins_data['uhidno'] = Session::get('current_branch_code') . '-' . ($auto_increment_value + 1);
        $insert = PatientRegistration::create($ins_data);
        $patient_id = $insert->id;


        if ($patient_id) {

            $ups_data = array('patient_id' => $patient_id);
            $update = PatientAppointment::whereId($request->id)->update($ups_data);

            return ['status' => 1, 'message' => "Linked Successfully", 'id' => $patient_id];
        } else {
            return ['status' => 3, 'message' => "Something went wrong"];
        }
    }

    /**
     *
     * get consultant list in dropdown
     */
    public function getConsultantList(Request $request)
    {
        $status = 'false';
        if (!is_null($request->department_id)) {
            $specialists = SpecialistMaster::where('department_id', $request->department_id)
                ->select('specialist_name', 'id')
                ->where('is_deleted', 0)
                ->where('display_status', 1)
                ->get();
            $status = 'true';
        }

        return Response::json(['status' => $status, 'data' => $specialists]);
    }
}
