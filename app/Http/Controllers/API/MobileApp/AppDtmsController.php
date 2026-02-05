<?php

namespace App\Http\Controllers\API\MobileApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PatientVisits;
use App\Models\PatientPrescriptions;
use App\Models\Masters\VitalsMaster;
use App\Models\TestMasterExt;
use App\Models\MobileApp\BloodGlucose;
use App\Models\MobileApp\BpStatus;
use App\Models\PatientBilling;
use App\Models\TestResults;
use Carbon\Carbon;

class AppDtmsController extends Controller
{

    public function getAllPatientVisit(Request $request)
    {

        try {
            $patient_id = $request->patient_id;
            if (isset($patient_id)) {
                $patientVisitList = PatientVisits::select(
                    'patient_visits.visit_type_id',
                    'patient_visits.id',
                    'patient_visits.specialist_id',
                    'patient_visits.visit_code',
                    'patient_visits.visit_date',
                    'visit_type_master.visit_type_name'
                )
                    ->join('visit_type_master', 'visit_type_master.id', '=', 'patient_visits.visit_type_id')
                    ->where('patient_id', $patient_id)
                    ->orderByDesc('patient_visits.visit_date')
                    ->get();

                $response['status'] = 100;
                $response['message'] = 'success';
                $response['result'] =   $patientVisitList;
                return response()->json($response, 200);
            } else {
                $response['status'] = 101;
                $response['message'] = 'fail';
                $response['result'] = [];
                return response()->json($response, 200);
            }
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response, 500);
        }
    }

    public function getPrescriptions(Request $request)
    {
        try {
            $patient_id = $request->patient_id;
            $visit_id = $request->visit_id;

            $prescriptionData = PatientPrescriptions::select('medicine_master.medicine_name', 'tablet_type_master.tablet_type_name', 'patient_prescriptions.*')
                ->join('medicine_master', 'medicine_master.id', '=', 'patient_prescriptions.medicine_id')
                ->join('tablet_type_master', 'tablet_type_master.id', '=', 'medicine_master.tablet_type_id')
                ->where('visit_id', $visit_id)->where('patient_prescriptions.is_deleted', 0)->get();

            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result'] =   $prescriptionData;
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response, 500);
        }
    }

    public function getVitalSigns(Request $request)
    {

        try {
            $vitalsLists = VitalsMaster::select('vitals_master.*')->orderBy('order', 'ASC')->get();
            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result'] =   $vitalsLists;
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response, 500);
        }
    }

    public function getSMBGTest(Request $request)
    {
        try {
            $cond = array();


            array_push($cond, ['test_master.is_deleted', 0], ['test_master.is_service_item', 0], ['test_master.is_smbg', 1]);
            $filldata = TestMasterExt::select('test_master.id', 'test_master.TestName', 'test_master.test_code', 'test_master.TestId', 'service_group_master.group_name')
                ->leftjoin('service_group_master', 'service_group_master.id', '=', 'test_master.group_id')
                ->where($cond)
                ->orderBy('test_master.id', 'DESC')
                ->get();

            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result'] =   $filldata;
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response, 500);
        }
    }

    public function saveBloodGlucose(Request $request)
    {

        try {
            if (isset($request->patientid)) {
                $data = array(
                    'id' => $request->id,
                    'TestId' => $request->TestId,
                    'blood_glucose' => $request->blood_glucose,
                    'reading_date' => $request->date,
                    'reading_time' => $request->time,
                    'created_by' => $request->user_id,
                    'patientid' => $request->patientid,
                    'created_at' => date('Y-m-d H:i:s'),
                    'medicine' => $request->medication,
                    'dosage' => $request->dose,

                );

                $ins = BloodGlucose::insert($data);
                if ($ins) {
                    $response['status'] = 100;
                    $response['message'] = 'success';
                    $response['result'] =   [];
                    return response()->json($response, 200);
                } else {
                    $response['status'] = 101;
                    $response['message'] = 'Unable to save data';
                    $response['result'] =   [];
                    return response()->json($response, 500);
                }
            } else {
                $response['status'] = 101;
                $response['message'] = 'No Patientid';
                $response['result'] =   [];
                return response()->json($response, 500);
            }
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response, 500);
        }
    }

    public function getBloodGlucose(Request $request)
    {

        try {
            $patientId = $request->patientid;
            if ($request->patientid > 0) {
                $data = BloodGlucose::select(
                    'app_patient_blood_glucose.autoId',
                    'app_patient_blood_glucose.id',
                    'app_patient_blood_glucose.TestId',
                    'app_patient_blood_glucose.patientid',
                    'app_patient_blood_glucose.blood_glucose',
                    'app_patient_blood_glucose.reading_date as date',
                    'app_patient_blood_glucose.reading_time as time',
                    'test_master.TestName'
                )->join('test_master', 'test_master.TestId', 'app_patient_blood_glucose.TestId')
                    ->where('app_patient_blood_glucose.patientid', $patientId)->orderBy('autoId', 'DESC')->get();

                $response['status'] = 100;
                $response['message'] = 'success';
                $response['result'] =   $data;
                return response()->json($response, 200);
            } else {
                $response['status'] = 101;
                $response['message'] = 'fails';
                $response['result'] =   [];
                return response()->json($response, 200);
            }
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response, 500);
        }
    }

    public function saveBpStatus(Request $request)
    {
        try {
            $data = array(
                'reading_date' => $request->reading_date,
                'reading_time' => $request->reading_time,
                'bpd' => $request->bpd,
                'bps' => $request->bps,
                'pulse' => $request->pulse,
                'created_by' => $request->user_id,
                'patientId' => $request->patientId,
                'medicine' => $request->medication,
                'created_date' => date('Y-m-d H:i:s')

            );

            $ins = BpStatus::insert($data);
            if ($ins) {
                $response['status'] = 100;
                $response['message'] = 'success';
                $response['result'] =   [];
                return response()->json($response, 200);
            } else {
                $response['status'] = 101;
                $response['message'] = 'Unable to save data';
                $response['result'] =   [];
                return response()->json($response, 500);
            }
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response, 500);
        }
    }

    public function getBpStatus(Request $request)
    {

        try {
            $patientId = $request->patientid;
            if ($request->patientid > 0) {
                $type = $request->type;
                if ($type == 1) {

                    $currentDate = Carbon::now();
                    $formattedDate = $currentDate->format('Y-m-d');

                    $bpdata = BpStatus::where('patientId', $patientId)->where('reading_date', $formattedDate)
                        ->orderBy('id', 'DESC')->get();
                        if($bpdata){
                            foreach($bpdata as $data ){
                                $datas=array(
                                    'id'=>$data->id,
                                    'reading_date'=>strval($data->reading_date),
                                    'reading_time'=>strval($data->reading_time),
                                    'bpd'=>strval($data->bpd),
                                    'bps'=>strval($data->bps),
                                    'pulse'=>strval($data->pulse),
                                    'created_by'=>strval($data->created_by),
                                    'patientId'=>$data->patientId,
                                    'created_date'=>strval($data->created_date),
                                    'updated_at'=>strval($data->updated_at),
                                    'medicine'=>strval($data->medicine),
                                    'is_verified'=>intval($data->is_verified)
        
                                );
        
                            }
        
                        }
    
    
                } else if ($type == 0) {
                   

                    $bpdata = BpStatus::where('patientId', $patientId)->orderBy('id', 'DESC')->get();
                    if($bpdata){
                        foreach($bpdata as $data ){
                            $datas=array(
                                'id'=>strval($data->id),
                                'reading_date'=>strval($data->reading_date),
                                'reading_time'=>strval($data->reading_time),
                                'bpd'=>strval($data->bpd),
                                'bps'=>strval($data->bps),
                                'pulse'=>strval($data->pulse),
                                'created_by'=>strval($data->created_by),
                                'patientId'=>strval($data->patientId),
                                'created_date'=>strval($data->created_date),
                                'updated_at'=>strval($data->updated_at),
                                'medicine'=>strval($data->medicine),
                                'is_verified'=>$data->is_verified
    
                            );
    
                        }
    
                    }

                } else {

                    $bpdata = [];
                }
                $response['status'] = 100;
                $response['message'] = 'success';
                $response['result'] =   $bpdata;
                return response()->json($response, 200);
            } else {
                $response['status'] = 101;
                $response['message'] = 'fail';
                $response['result'] =   [];
                return response()->json($response, 200);
            }
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response, 500);
        }
    }

    public function getTestBills(Request $request)
    {
        try {
            $patientId = $request->patientid;


            $cond = array();
            array_push($cond, ['patient_billing.PatientId', $patientId]);
            array_push($cond, ['patient_billing.billing_type', 3]);

            $patientBilling = PatientBilling::select(
                'patient_billing.*',
                'patient_billing_accounts.TotalAmount',
                'patient_billing_accounts.serviceCharge',
                'patient_billing_accounts.Discamount',
                'patient_billing_accounts.Grossamount',
                'patient_billing_accounts.NetAmount',
                'patient_billing_accounts.patient_billing_mode_id',
                'patient_billing_accounts.TotalAmount',
                'patient_billing_accounts.total_paid',
                'patient_billing_accounts.balance_amount',
                'patient_billing_accounts.discount_in_percentage',
                'patient_billing_accounts.total_paid',
                'patient_visits.id as patient_visit_id',
                'patient_visits.visit_date',
                'patient_registration.name',
                'patient_registration.uhidno'
            )
                ->join('patient_billing_accounts', 'patient_billing_accounts.PatientBillingId', '=', 'patient_billing.id')
                ->join('payment_mode_master', 'payment_mode_master.id', '=', 'patient_billing_accounts.patient_billing_mode_id')
                ->join('patient_visits', 'patient_visits.id', '=', 'patient_billing.visit_id')
                ->join('patient_registration', 'patient_registration.id', '=', 'patient_billing.PatientId')
                ->where($cond);

            $patientBilling = $patientBilling->orderByDesc('patient_billing.id')
                ->get();


            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result'] =   $patientBilling;
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = $e;
            return response()->json($response, 500);
        }
    }

    public function getTestByVisit(Request $request)
    {
        try {

            $visit_id = $request->visit_id;
            $testData = DB::table('test_results as tr')
                // ->join('test_master as tm', 'tm.TestId', '=', 'tr.TestId')
                ->join('test_master as tm', 'tm.TestId', '=', 'tr.TestId')
                ->select('tr.ResultValue', 'tr.TestId', 'tm.TestName', 'tm.dtms_code', 'tr.is_outside_lab', 'tr.is_smbg')
                ->where('tr.visit_id', $visit_id)
                ->orderBy('tm.dtms_order_no', 'ASC')
                ->get();

            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result'] =   $testData;
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = $e;
            return response()->json($response, 500);
        }
    }

    public function getTestsByPatient(Request $request)
    {
        try {
            $patientId = $request->patient_id;

            if ($patientId > 0) {


                $testResults = TestResults::where('PatientId', $patientId)
                    ->select('test_results.TestId', 'test_master.TestName', 'test_master.dtms_code', 'test_master.dtms_order_no', 'test_master.chart_order_no')
                    ->leftjoin('test_master', 'test_master.TestId', '=', 'test_results.TestId')
                    ->distinct('test_results.TestId')
                    ->where('test_master.display_status', 1)
                    ->where('test_master.chart_order_no', '>', 0)
                    ->get();
            } else {
                $testResults = [];
            }

            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result'] =   $testResults;
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = $e;
            return response()->json($response, 500);
        }
    }

    public function getGraphByTest(Request $request)
    {
        try {
            $testId = $request->TestId;
            $patientId = $request->patient_id;

            // $testResult= TestResults::where('TestId',$testId)
            // ->where('PatientId',$patientId)
            // ->select('test_results.id','test_results.ResultValue','test_results.created_at', 'is_outside_lab','is_smbg')
            // ->distinct()
            // ->orderBy('test_results.created_at','ASC')
            // ->get();



            $testResult = TestResults::where('TestId', $testId)
                ->where('PatientId', $patientId)
                ->select('test_results.id', 'test_results.ResultValue', 'test_results.created_at', 'is_outside_lab', 'is_smbg')
                ->distinct()
                ->orderBy('test_results.created_at', 'ASC')
                ->get();

            $history = array();
            foreach ($testResult as $item) {
                $testDate = date("d-m-Y", strtotime($item->created_at));
                $testResultDate = $testDate;
                if (is_numeric($item->ResultValue)) {
                    $testResultValue = $item->ResultValue;
                    $status = 1;
                } else {
                    $testResultValue = 0;
                    $status = 0;
                }

                $history[] = [
                    'id' => $item['id'],
                    'ResultValue' => $testResultValue,
                    'is_outside_lab' => $item['is_outside_lab'],
                    'is_smbg' => $item['is_smbg'],
                    'created_at' => $testResultDate
                ];
            }

            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result'] =   $history;
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = $e;
            return response()->json($response, 500);
        }
    }
}
