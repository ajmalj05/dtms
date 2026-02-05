<?php
namespace App\Http\Controllers\users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MobileApp\BpStatus;
use App\Models\MobileApp\BloodGlucose;
use Carbon\Carbon;
use App\Models\PatientVisits;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\PatientBpStatus;
use App\Models\Test_results;
use Illuminate\Support\Facades\DB;

class DTMSManagementController extends Controller
{


    public function dtmsVerification(){
        {
            $data=array();
            $userDetails=null;
            $userData =array();

            $data['PageName']="DTMS Verification";
            return Parent::page_maker('webpanel.appManagement.dtmsVerification',$data);

        }


        }
        public function getbpStatusVerification(Request $request){
            $branch_id=Session::get('current_branch');
            $bpstuatu=BpStatus::select('app_patient_bpstatus.id',
            'app_patient_bpstatus.reading_date',

            'app_patient_bpstatus.reading_date',
            'app_patient_bpstatus.reading_time',
            'app_patient_bpstatus.bpd',
            'app_patient_bpstatus.bps',
            'app_patient_bpstatus.pulse',
            'app_patient_bpstatus.patientId',
            'app_patient_bpstatus.created_date',
            'app_patient_bpstatus.medicine',
            'patient_registration.gender',
            'patient_registration.uhidno',
            'patient_registration.uhidno',
            'patient_registration.mobile_number',
            // 'patient_registration.name',
            DB::raw("CONCAT(patient_registration.name,patient_registration.last_name) AS name")
            )
            ->join('patient_registration','patient_registration.id','app_patient_bpstatus.patientId')
            ->where('patient_registration.branch_id', $branch_id)
            ->where('app_patient_bpstatus.is_verified',0)->orderBy('app_patient_bpstatus.id','DESC')->get();

            $output = array(
                "recordsTotal" => count($bpstuatu),
                "recordsFiltered" => count($bpstuatu),
                "data" => $bpstuatu,
            );
            echo json_encode($output);
        }


    public function getsmbgStatusVerification(){
        $branch_id=Session::get('current_branch');;
        $smbgData=BloodGlucose::select('app_patient_blood_glucose.autoId'
        ,'app_patient_blood_glucose.TestId',
        'app_patient_blood_glucose.blood_glucose',
        'app_patient_blood_glucose.reading_date',
        'app_patient_blood_glucose.reading_time',
        'app_patient_blood_glucose.patientid',
        'app_patient_blood_glucose.created_at',

        'patient_registration.gender',
        'patient_registration.uhidno','patient_registration.uhidno',
        'test_master.TestName',
        'patient_registration.mobile_number','app_patient_blood_glucose.medicine','app_patient_blood_glucose.dosage',
        DB::raw("CONCAT(patient_registration.name,patient_registration.last_name) AS name"))
        ->join('test_master','test_master.TestId', 'app_patient_blood_glucose.TestId')
        ->join('patient_registration','patient_registration.id','app_patient_blood_glucose.patientid')
        ->where('patient_registration.branch_id', $branch_id)

        ->where('is_verified',0)-> orderBy('app_patient_blood_glucose.autoId','DESC')->get();
        $output = array(
        "recordsTotal" => count($smbgData),
        "recordsFiltered" => count($smbgData),
        "data" => $smbgData,
    );
    echo json_encode($output);
    }

 public function getVisitIdfoDtms($pid,$vist_date)
{
    $patientVisitsData=PatientVisits::select('id')->where('visit_date',$vist_date)
    ->where('patient_id', $pid)->first();
     if($patientVisitsData)
     {
        return $patientVisitsData->id;
     }
     else{

        $ins_data=array(
            'visit_type_id'=>12,
            'visit_date'=>$vist_date,
            'branch_id'=>Session::get('current_branch'),
            'display_status' => 1,
            'patient_id'=>$pid,
            'is_deleted' => 0,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        );
        $visitId=PatientVisits::insertGetId( $ins_data);

        return $visitId;

     }

}
    public function bpStatusVerification(Request $request){
        $visit_id=$this->getVisitIdfoDtms($request->patient_id,$request->reading_date);
        $visitCount=PatientBpStatus::select('id')->where('visit_id', $visit_id)->get();


        if(count($visitCount)<=4){

            $addedremarks=$request->medicine;
            $cond=array(
                array('id',$visit_id),
             );

            $getRemarks=getAfeild("dtms_remarks","patient_visits",$cond);
             if($getRemarks!=null){
                $newRemarks=$getRemarks.''.$addedremarks.'';
             }else{
                $newRemarks=$addedremarks.'';
             }
             $data=PatientVisits::where('id',$visit_id)->update(['dtms_remarks'=>  $newRemarks]);



            $bpStatusData=array(
                'visit_id' => $visit_id,
                'time' => $request->reading_time,
                'bps' => $request-> bps,
                'bpd' => $request->bpd,
                'pulse' => $request->pulse,
                'display_status' => 1,
                'order_no' =>count($visitCount)+1,
                'branch_id' => Session::get('current_branch'),
                'specialist_id' => Auth::id(),
                'created_by' => Auth::id(),
                'is_deleted' => 0,
            );
            $visitCountdata=PatientBpStatus::insert($bpStatusData);
            if($visitCountdata){
                $data=array(
                    'is_verified'=>1,
                );
                $statusCHange=BpStatus::where('id',$request->id)->update($data);

            }
            return response()->json([

                'status' => 1,
                'message' => 'Data Verified successfully.',

            ]);
        }
        return response()->json([

            'status' => 0,
            'message' => 'failed.',

        ]);

    }

    public function smbgStatusVerification(Request $request){
        $addedremarks=$request->medicine . ' ' .$request->dosage;

        $visit_id=$this->getVisitIdfoDtms($request->patient_id,$request->reading_date);
        $cond=array(
            array('id',$visit_id),
         );

        $getRemarks=getAfeild("dtms_remarks","patient_visits",$cond);
         if($getRemarks!=null){
            $newRemarks=$getRemarks.''.$addedremarks.'';
         }else{
            $newRemarks=$addedremarks.'';
         }
         $data=PatientVisits::where('id',$visit_id)->update(['dtms_remarks'=>  $newRemarks]);

        if($visit_id){
          $ins_data=array(
            'TestId'=>$request->TestId,
            'ResultValue'=>$request->blood_glucose,
            'PatientId'=>$request->patient_id,
            'is_smbg'=>1,
            'visit_id'=>$visit_id,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
            'created_by'=>Auth::id(),
            'data_entry_date'=>date('Y-m-d H:i:s')
            );
            $visitCountdata=Test_results::insert($ins_data);
            if($visitCountdata){
                $data=array(
                    'is_verified'=>1,
                );
                $statusCHange=BloodGlucose::where('autoId',$request->autoId)->update($data);

            }
            return response()->json([

                'status' => 1,
                'message' => 'Data Verified successfully.',

            ]);
        }
    else{
        return response()->json([

            'status' => 0,
            'message' => 'failed.',

        ]);

    }

    }

}
