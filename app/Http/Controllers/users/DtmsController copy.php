<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\ApiLogs;
use App\Models\Masters\FormEngineAttributes;
use App\Models\Masters\FormEngineQuestions;
use App\Models\Masters\MedicineMaster;
use App\Models\Masters\StockManagement;
use App\Models\Masters\VaccinationMaster;
use App\Models\Masters\VitalsMaster;
use App\Models\OldTeleMedicineAnswerSheet;
use App\Models\PatientAbroadDetail;
use App\Models\PatientAlerts;
use App\Models\PatientAnswerSheet;
use App\Models\PatientAppointment;
use App\Models\PatientBilling;
use App\Models\PatientBillingAccounts;
use App\Models\PatientBpStatus;
use App\Models\PatientCategory;
use App\Models\PatientDocument;
use App\Models\PatientMedicalHistory;
use App\Models\PatientDietPlan;
use App\Models\PatientPep;
use App\Models\PatientComplication;
use App\Models\PatientDiagnosis;
use App\Models\PatientReminders;
use App\Models\PatientserviceItems;
use App\Models\PatientSubCategory;
use App\Models\PatientTarget;
use App\Models\PatientTargetDetail;
use App\Models\PatientVaccination;
use App\Models\PatientVitals;
use App\Models\Tele_usual_medicines;
use App\Models\TestMaster;
use App\Models\TestResults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\PatientRegistration;
use App\Models\Masters\DietQuestionMaster;
use App\Models\Masters\DietQuestionSub;
use App\Models\PatientGallery;
use App\Models\PatientVisits;
use App\Models\Masters\VisitTypeMaster;
use App\Models\Masters\DiagnosisMaster;
use App\Models\Masters\ComplicationMaster;
use App\Models\Masters\SubComplicationMaster;
use App\Models\Masters\SubDiagnosisMaster;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Models\PatientPrescriptions;
use App\Models\Dtms\FormEngineAnswers;
use App\Models\Dtms\PatientHereditaryDetails;
use App\Models\Dtms\PatientMiscellaneousDetails;
use PDF;


class DtmsController extends Controller
{
    public function indexnew_1($pid,Request $request)
    {
        $data=array();
        $data['PageName']="DTMS Home ";
        $isenabled=false;
        Session::put('dtms_visitid','');

        if(isset($pid) ) {
            Session::put('dtms_pid',$pid);
            $patientTestResultsFinal = [];
            $patientTargetDetails = PatientTargetDetail::select('weight_target','weight_present','action_plan','fibro_scan')->where('patient_id',$pid)->first();
            $patientTestResultsFinal = $this->getTestResultValue(); // need to make it in ajax

            $diagnosisData = DiagnosisMaster::where('is_deleted',0)->where('display_status', 1)->orderByDesc('id')->get();
            $complicationData = ComplicationMaster::where('is_deleted',0)->where('display_status', 1)->orderByDesc('id')->get();
            $vaccinationData = VaccinationMaster::where('is_deleted',0)->where('display_status', 1)->orderByDesc('id')->get();

            //select patient data
            $patient_data = PatientRegistration::select('patient_registration.*','patient_type_master.patient_type_name',
            'category_master.category_name')
                ->leftjoin('patient_type_master','patient_type_master.id','=','patient_registration.patient_type')
                ->leftjoin('patient_category','patient_category.patient_id','=','patient_registration.id')
                ->leftjoin('category_master','category_master.id','=','patient_category.id')
                ->where('patient_registration.id',$pid)
                ->orderByDesc('patient_registration.id')
                ->first();




                //maket it simple

                $patientTargetListData = TestMaster::select('test_master.*',  'test_master.id as test_master_id')
                ->where('show_test_in_targets', 1)
                ->orderByDesc('test_master.id')
                ->get();

            $patientTargetList = [];
            foreach ($patientTargetListData as $item) {
                $patientTargetLatestValue = PatientTarget::where('patient_targets.patient_id', $pid)
                    ->where('patient_targets.test_id', $item->id)
                    ->orderByDesc('id')
                    ->first();
                if (! is_null($patientTargetLatestValue)) {
                    $patientTargetList[] = [
                        'TestName' => $item->TestName,
                        'test_master_id' => $item->id,
                        'target_value' => $patientTargetLatestValue->target_value,
                        'present_value' => $patientTargetLatestValue->present_value,
                    ];
                } else {
                    $patientTargetList[] = [
                        'TestName' => $item->TestName,
                        'test_master_id' => $item->id,
                        'target_value' => $item->target_default_value,
                        'present_value' => '',
                    ];
                }
            }


            $gallery=PatientGallery::where('patient_id',$pid)->orderBy('id','DESC')->first();


                //Medical History Data
        $medical_history=PatientMedicalHistory::select('patient_medicalhistory.*', 'users.name')
        ->leftjoin('users','users.id','=','patient_medicalhistory.updated_by')
        ->where('patient_medicalhistory.patient_id',Session::get('dtms_pid'))
        ->orderBy('patient_medicalhistory.id','DESC')
        ->first();


        $diet_history=PatientDietPlan::select('*')->where('patient_id',Session::get('dtms_pid'))->orderBy('answer_sheet_id','DESC')->get()->toArray();
        $pep_history=PatientPep::select('*')->where('patient_id',Session::get('dtms_pid'))->orderBy('answer_sheet_id','DESC')->get()->toArray();


        $pep_questions_data=DietQuestionMaster::with('sub_question')->orderBy('order_no')->where('display_status',1)->where('type',2)->where('is_deleted',0)->get();


        $question_data=DietQuestionMaster::with('sub_question')->where('type',1)->orderBy('order_no')->where('display_status',1)->where('is_deleted',0)->get();

            //make it simple
        $vitalsLists= VitalsMaster::select('vitals_master.*')->orderBy('order','ASC')->get();

        $vitalData = [];
        foreach ($vitalsLists as $value) {
            $vitalData[$value->id] = [
                'id' => $value->id,
                'vital_name' => $value->vital_name,
                'class_name' => $value->class_name,
            ];
        }




        $data['patient_data']=$patient_data;
        $data['medical_history']=$medical_history;
        $data['question_data']=$question_data;
        $data['diet_history']=$diet_history;
        //PEP Modal
        $data['isenabled']=true;
        $data['pep_questions_data']=$pep_questions_data;
        $data['pep_history']=$pep_history;
        $data['diagnosis_data']=$diagnosisData;
        $data['complication_data']=$complicationData;
        $data['vaccination_data']=$vaccinationData;
        $data['gallery']=$gallery;
        $data['isenabled']=$isenabled;
        $data['vital_data'] = $vitalData;
        $data['patient_test_results'] = $patientTestResultsFinal;
        $data['patient_test_targets'] = $patientTargetList;
        $data['patient_target_details'] = $patientTargetDetails;
        $data['uhidNo']=$patient_data->uhidno;

            return Parent::page_maker('webpanel.dtms.home',$data);
        }
        else{
            Session::put('dtms_pid','');
            return redirect('dashboard');
        }


    }

    public function index($pid,Request $request)
    {


        $data=array();

        if(isset($pid) ) {
            Session::put('dtms_pid',$pid);


            //WHAT IS THE NEED OF THIS QUERY


            // $patientVisitList = PatientVisits::select('patient_visits.visit_type_id', 'patient_visits.id','patient_visits.visit_code', 'patient_visits.visit_date', 'visit_type_master.visit_type_name')
            // ->join('visit_type_master','visit_type_master.id','=','patient_visits.visit_type_id')
            // ->where('patient_id',Session::get('dtms_pid'))
            // ->orderByDesc('patient_visits.id')
            // ->get();

//            $patientTestResultList = TestMaster::select('test_master.*')
//            ->where('show_test_in_dtms',1)
//            ->orderByDesc('test_master.id')
//            ->get();



            $patientTestResultsFinal = [];

            //WHAT IS THE NEED OF THIS
            // $PatientLabNo="";
            // $condition=array('PatientId'=>Session::get('dtms_pid'));
            // $getLabNoData= getASingleValueByorderLimit('patient_billing',$condition,'id');
            // if($getLabNoData){
            //     $PatientLabNo=$getLabNoData->PatientLabNo;
            // }




//            foreach($patientTestResultList as $testMasterItem) {
//                $testResult = null;
//                if ($PatientLabNo) {
//                    $condition2 = [
//                        'Labno'=>$PatientLabNo,
//                        'TestId' =>$testMasterItem->TestId
//                    ];
//                    $testResult = getASingleValue('test_results', $condition2);
//                }
//               $patientTestResultsFinal[$testMasterItem->id] = [
//                    'test_name' => $testMasterItem->TestName,
//                    'result' => ! is_null($testResult) ? $testResult->ResultValue : '',
//                    'testId' =>$testMasterItem->TestId,
//                   'is_outside_lab' => ! is_null($testResult) ? $testResult->is_outside_lab: 0,
//                ];

//            }
            $patientTargetDetails = PatientTargetDetail::where('patient_id',$pid)->first();


            //slow qury
          $patientTestResultsFinal = $this->getTestResultValue();

            $vitalsLists= VitalsMaster::select('vitals_master.*')->orderBy('order','ASC')->get();

            $vitalData = [];
            foreach ($vitalsLists as $value) {
                $vitalData[$value->id] = [
                    'id' => $value->id,
                    'vital_name' => $value->vital_name,
                    'class_name' => $value->class_name,
                ];
            }

        }

        $isenabled=false;


        Session::put('dtms_visitid','');

        // if(Session::get('dtms_visitid') !=""){
        //     $isenabled=true;
        // }

        //  change here.. based on visit sttaus from db based on pid last data


        $data['PageName']="DTMS Home ";

        $diagnosisData = DiagnosisMaster::where('is_deleted',0)->where('display_status', 1)->orderByDesc('id')->get();
        $complicationData = ComplicationMaster::where('is_deleted',0)->where('display_status', 1)->orderByDesc('id')->get();
        $vaccinationData = VaccinationMaster::where('is_deleted',0)->where('display_status', 1)->orderByDesc('id')->get();


        $patient_data =$gallery=$uhidNos="";
        if(isset($pid)){
            $cond=['id' => $pid];


            $patient_data = PatientRegistration::select('patient_registration.*','patient_type_master.patient_type_name',
            'category_master.category_name')
                ->leftjoin('patient_type_master','patient_type_master.id','=','patient_registration.patient_type')
                ->leftjoin('patient_category','patient_category.patient_id','=','patient_registration.id')
                ->leftjoin('category_master','category_master.id','=','patient_category.id')
                ->where('patient_registration.id',$pid)
                ->orderByDesc('patient_registration.id')
                ->first();
            $vitalHeight = PatientVitals::select('patient_vitals.*')
                ->where('patient_id',$pid)
                ->where('patient_vitals.vitals_id','=', 1)
                ->orderByDesc('patient_vitals.id')
                ->limit(1)
                ->first();
            $vitalWeight = PatientVitals::select('patient_vitals.*')
                ->where('patient_id',$pid)
                ->where('patient_vitals.vitals_id','=', 2)
                ->orderByDesc('patient_vitals.id')
                ->limit(1)
                ->first();

                $vitalage = PatientRegistration::select(DB::raw('DATE_PART(\'year\', age(dob)) AS age')) // Select the age directly
                ->where('patient_id', $pid)
                ->limit(1) // Limit to 1 result
                ->first();

                $vitalgender = PatientRegistration::select('patient_registration.gender') 
                ->where('patient_id', $pid)
                ->limit(1) // Limit to 1 result
                ->first();


            $patientTargetListData = TestMaster::select('test_master.*',  'test_master.id as test_master_id')
                ->where('show_test_in_targets', 1)
                ->orderByDesc('test_master.id')
                ->get();

            $patientTargetList = [];
            foreach ($patientTargetListData as $item) {
                $patientTargetLatestValue = PatientTarget::where('patient_targets.patient_id', $pid)
                    ->where('patient_targets.test_id', $item->id)
                    ->orderByDesc('id')
                    ->first();
                if (! is_null($patientTargetLatestValue)) {
                    $patientTargetList[] = [
                        'TestName' => $item->TestName,
                        'test_master_id' => $item->id,
                        'target_value' => $patientTargetLatestValue->target_value,
                        'present_value' => $patientTargetLatestValue->present_value,
                    ];
                } else {
                    $patientTargetList[] = [
                        'TestName' => $item->TestName,
                        'test_master_id' => $item->id,
                        'target_value' => $item->target_default_value,
                        'present_value' => '',
                    ];
                }
            }

            $gallery=PatientGallery::where('patient_id',$pid)->orderBy('id','DESC')->first();
            $uhidNos=Session::get('current_branch_code').' -'.$pid;

        }

        //Medical History Data
        $medical_history=PatientMedicalHistory::select('patient_medicalhistory.*', 'users.name')
            ->leftjoin('users','users.id','=','patient_medicalhistory.updated_by')
            ->where('patient_medicalhistory.patient_id',Session::get('dtms_pid'))
            ->orderBy('patient_medicalhistory.id','DESC')
            ->first();

        $diet_history=PatientDietPlan::select('*')->where('patient_id',Session::get('dtms_pid'))->orderBy('answer_sheet_id','DESC')->get()->toArray();
        $pep_history=PatientPep::select('*')->where('patient_id',Session::get('dtms_pid'))->orderBy('answer_sheet_id','DESC')->get()->toArray();


        $pep_questions_data=DietQuestionMaster::with('sub_question')->orderBy('order_no')->where('display_status',1)->where('type',2)->where('is_deleted',0)->get();


        $question_data=DietQuestionMaster::with('sub_question')->where('type',1)->orderBy('order_no')->where('display_status',1)->where('is_deleted',0)->get();

        //Photos

        $data['vital_height']=$vitalHeight;
        $data['vital_weight']=$vitalWeight;
        $data['patient_data']=$patient_data;
        $data['uhidNo']=$uhidNos;
        // $data['patient_data']=$patient_data;
        // $data['uhidNo']=$uhidNos;
        $data['medical_history']=$medical_history;
        $data['question_data']=$question_data;
        $data['diet_history']=$diet_history;
        //PEP Modal
        $data['isenabled']=true;
        $data['pep_questions_data']=$pep_questions_data;
        $data['pep_history']=$pep_history;

       // $data['patient_visit_list']=$patientVisitList;  //what is the need if this


        $data['patient_data']=$patient_data;
        $data['diagnosis_data']=$diagnosisData;
        $data['complication_data']=$complicationData;
        $data['vaccination_data']=$vaccinationData;
        $data['gallery']=$gallery;
        $data['uhidNo']=$uhidNos;
        $data['isenabled']=$isenabled;
        $data['vital_data'] = $vitalData;
        $data['patient_test_results'] = $patientTestResultsFinal;
        $data['patient_test_targets'] = $patientTargetList;
        $data['patient_target_details'] = $patientTargetDetails;


        return Parent::page_maker('webpanel.dtms.home',$data);
    }

    /**
     *
     * get sub complication list in dropdown
     */
    public function getSubComplicationList(Request $request)
    {
        $status = 'false';
        if(! is_null($request->complication_id)) {
            $subcomplications = SubComplicationMaster::where('complication_id', $request->complication_id)
                ->select('subcomplication_name', 'id')
                ->where('is_deleted',0)
                ->where('display_status', 1)
                ->get();
            $status = 'true';
        }

        return Response::json(['status' => $status, 'data' => $subcomplications]);
    }
    public function getSubDiagnosisList(Request $request)
    {
        $status = 'false';
        if(! is_null($request->diagnosis_id)) {
            $subdignosis = SubDiagnosisMaster::where('diagnosis_id', $request->diagnosis_id)
                ->select('subdiagnosis_name', 'id')
                ->where('is_deleted',0)
                ->where('display_status', 1)
                ->get();
            $status = 'true';
        }

        return Response::json(['status' => $status, 'data' => $subdiagnosis]);
    }

    /**
     * store diagnosis data in db
     * @param Request $request
     * @return array
     */
    public function savePhotos(Request $request)
    {
        $imageName ="";
        $files_array=array();

        if($request->TotalFiles > 0)
        {

           for ($x = 0; $x < $request->TotalFiles; $x++)
           {

               if ($request->hasFile('files'.$x))
                {
                    $file   = $request->file('files'.$x);
                    $name = $file->getClientOriginalName();


                    $file->move(public_path('images/patient_documents'), $name);  // changing images to patient_documents

                    array_push($files_array,$name);
                }
           }
          foreach($files_array as $fileName){
              if (PatientDocument::where('image', "patient_documents/".$fileName)->exists()){
                  return [ 'status'=>3, 'message'=>"File name is already taken"];

              } else {
                  $imgData=array(
                      'image'=>"patient_documents/".$fileName,
                      'remarks' => $request->remarks,
                      'category_id' => $request->document_category,
                      'display_status' => 1,
                      'created_by'=>Auth::id(),
                      'branch_id'=>Session::get('current_branch'),
                      'is_deleted' => 0,
                      'patient_id'=>Session::get('dtms_pid'),
                      'created_at'=>date('Y-m-d h:i:s'),
                      'updated_at'=>date('Y-m-d h:i:s')
                  );
                  PatientDocument::insert($imgData);
              }
          };

           return [ 'status'=>1, 'message'=>"Saved Successfully"];
        }


        else if($request->has('patient_snapshot') && $request->patient_snapshot !=""){
            $img = $request->patient_snapshot;
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $imageName = time() . '.png';
            $file =  $imageName;
            Storage::disk('public_two')->put($file, $image_base64);
            $imgData=array(
                'image'=>$imageName,
                'remarks' => $request->remarks,
                'category_id' => $request->document_category,
                'display_status' => 1,
                'type' => 2,
                'created_by'=>Auth::id(),
                'branch_id'=>Session::get('current_branch'),
                'is_deleted' => 0,
                'patient_id'=>Session::get('dtms_pid'),
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s')
            );
            PatientDocument::insert($imgData);
            return [ 'status'=>1, 'message'=>"Saved Successfully"];
        }
        else{
            return [ 'status'=>3, 'message'=>"Failed to save" ];
        }
    }

    /**
     * store diagnosis data in db
     * @param Request $request
     * @return array
     */
    public function savePatientGallery(Request $request){

        $imageName ="";
        $files_array=array();

        if($request->TotalFiles > 0)
        {

           for ($x = 0; $x < $request->TotalFiles; $x++)
           {

               if ($request->hasFile('files'.$x))
                {
                    $file   = $request->file('files'.$x);
                    $name = $file->getClientOriginalName();


                    $file->move(public_path('images'), $name);

                    array_push($files_array,$name);
                }
           }
          foreach($files_array as $fileName) {
              if (PatientGallery::where('image', $fileName)->exists()) {
                  return ['status' => 3, 'message' => "File name is already taken"];

              } else {
                  $imgData = array(
                      'image' => $fileName,
                      //                'remarks' => $request->remarks,
                      //                'is_main' =>1,
                      'is_deleted' => 0,
                      'patient_id' => Session::get('dtms_pid'),
                      'created_at' => date('Y-m-d h:i:s'),
                      'updated_at' => date('Y-m-d h:i:s')
                  );
                  PatientGallery::insert($imgData);
              };
          }

           return [ 'status'=>1, 'message'=>"Saved Successfully"];
        }


        else if($request->has('patient_snapshot') && $request->patient_snapshot !=""){
            $img = $request->patient_snapshot;
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $imageName = time() . '.png';
            $file =  $imageName;
            Storage::disk('public_two')->put($file, $image_base64);
            $imgData=array(
                'image'=>$imageName,
                //                'remarks' => $request->remarks,
                //                'is_main' =>1,
                'upload_type' => 2,
                'is_deleted' => 0,
                'patient_id'=>Session::get('dtms_pid'),
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s')
            );
            PatientGallery::insert($imgData);
            return [ 'status'=>1, 'message'=>"Saved Successfully"];
        }
        else{
            return [ 'status'=>3, 'message'=>"Failed to save" ];
        }
    }

    /**
     * patient gallery list
     * @param Request $request
     */
    public function getPatientGallery(Request $request){

        $cond=array();
        array_push($cond,['patient_gallery.patient_id',Session::get('dtms_pid')]);
        array_push($cond,['patient_gallery.is_deleted',0]);
        $filldata = PatientGallery::select('id','image','patient_id','created_at','upload_type')
            ->where($cond)
            ->orderByDesc('patient_gallery.id')
            ->get();


        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);

    }

    public function deletePatientGallery(Request $request)
    {
        $id=$request->id;
        if($id)

        {
            $ins=array(
                'is_deleted' =>1,
            );
            $insert_id=PatientGallery::whereId($id)->update($ins);
            if($insert_id) {
                return [ 'status'=>1, 'message'=>"Data deleted successfully" ];
            }
            else{
                return [ 'status'=>3, 'message'=>"Failed to delete data" ];
            }
        }
    }

    public function saveDiagnosisList(Request $request){

        $validated = $request->validate([
            'diagnosis_name' => 'required',
            'diagnosis_year' => 'required',
            'diagnosis_date' => 'required',
        ]);
        if($validated)
        {
            $ins_data=array(
                'diagnosis_id'=>$request->diagnosis_name,
                'icd_diagnosis' => null,
                'icd_code' => null,
                'diagnosis_year' =>$request->diagnosis_year,
                'display_status' => 1,
                'specialist_id'=> Auth::id(),
                'examined_date'=>$request->diagnosis_date ? date("Y-m-d", strtotime($request->diagnosis_date)) :NULL,
                'created_by'=>Auth::id(),
                'updated_by'=>null,
                'branch_id'=>Session::get('current_branch'),
                'patient_id'=>Session::get('dtms_pid'),


                'is_deleted' => 0,
               // 'created_at' => Carbon::now(),
                //'updated_at' => null,
            );

            if($request->crude==1){
//                $cond=['diagnosis_id' => $request->diagnosis_name,'is_deleted'=>0];
//                $getId=getAfeild("diagnosis_id","patient_diagnosis",$cond);
//                if($getId)
//                {
//                    return [ 'status'=>4, 'message'=>"Diagnosis name already exist" ];
//                }
//                else {
                    // insert
                    $ins_data['created_at'] = Carbon::now();
                    $ins_data['created_by'] = Auth::id();
//                    $cond = array(
//                        array('diagnosis_id', $request->diagnosis_name),
//                        array('is_deleted', 0),
//                    );
//
//                    $getId = getAfeild("id", "patient_diagnosis", $cond);
//
//                    if ($getId) {
//                        return ['status' => 4, 'message' => "Patient diagnosis already exist"];
//                    } else {
                        $insert_id = PatientDiagnosis::create($ins_data);
                        if ($insert_id) {
                            return ['status' => 1, 'message' => "Saved Successfully"];
                            // echo 1; //save success
                        } else {
                            return ['status' => 3, 'message' => "Failed to save"];

                        }
//                    }
//                }

            }
            else if($request->crude==2){


                // update


                $ins_data['updated_at']=Carbon::now();
                $ins_data['updated_by']=Auth::id();

                // $update_data=array(
                //     'diagnosis_id'=>$request->diagnosis_id,
                //     'icd_diagnosis' => null,
                //     'icd_code' => null,
                //     'diagnosis_year' => Carbon::parse($request->diagnosis_year)->diff(Carbon::now())->y,
                //     'display_status' => 1,
                //     'specialist_id'=> Auth::id(),
                //     'examined_date'=>$request->diagnosis_date ? date("Y-m-d", strtotime($request->diagnosis_date)) :NULL,
                //     'created_by'=>null,
                //     'updated_by'=>Auth::id(),
                //     'branch_id'=>Session::get('current_branch'),
                //     'is_deleted' => 0,
                //     'updated_at' => Carbon::now(),
                //     'created_at' => null,
                // );
                $insert_id = PatientDiagnosis::whereId($request->diagnosis_id)->update($ins_data);
                if($insert_id) {
                    return [ 'status'=>1, 'message'=>"Data updated Successfully" ];
                }
                else{
                    return [ 'status'=>3, 'message'=>"Failed to update data" ];
                }
            }

        }else{
            echo 2; // validation error
        }

    }

    /**
     * store complication data in db
     * @param Request $request
     * @return array
     */
    public function saveComplicationList(Request $request){
        $validated = $request->validate([
            'complication_id' => 'required',
          //  'subcomplication_id' => 'required',
            'complication_year' => 'required',
            'complication_date' => 'required',
        ]);
        if($validated)
        {
            $ins_data=array(
                'complication_id'=>$request->complication_id,
                'sub_complication_id'=>$request->subcomplication_id,
                'icd_diagnosis' => null,
                'icd_code' => null,
                // 'complication_year' => Carbon::parse($request->complication_year)->diff(Carbon::now())->y,
                'complication_year' => $request->complication_year,

                'display_status' => 1,
                'specialist_id'=> Auth::id(),
                'examined_date'=>$request->complication_date ? date("Y-m-d", strtotime($request->complication_date)) :NULL,
//                'created_by'=>Auth::id(),
                'updated_by'=>null,
                'branch_id'=>Session::get('current_branch'),
                'is_deleted' => 0,
                'patient_id'=>Session::get('dtms_pid'),

                // 'created_at' => Carbon::now(),
                // 'updated_at' => null,
            );

            if($request->crude==1){
//                $cond=['complication_id' => $request->complication_id,'is_deleted'=>0];
//                $getId=getAfeild("complication_id","patient_complication",$cond);
//                if($getId)
//                {
//                    return [ 'status'=>4, 'message'=>"Complication name already exist" ];
//                }
//                else {
                    // insert
                    $ins_data['created_at'] = Carbon::now();
                    $ins_data['created_by'] = Auth::id();
//                    $cond = array(
//                        array('complication_id', $request->complication_id),
//                        array('is_deleted', 0),
//                    );
//
//                    $getId = getAfeild("id", "patient_complication", $cond);
//
//                    if ($getId) {
//                        return ['status' => 4, 'message' => "Patient complication already exist"];
//                    } else {
                        $insert_id = PatientComplication::create($ins_data);
                        if ($insert_id) {
                            return ['status' => 1, 'message' => "Saved Successfully"];
                            // echo 1; //save success
                        } else {
                            return ['status' => 3, 'message' => "Failed to save"];

                        }
//                    }
//                }

            }
            else if($request->crude==2){


                // update

                $ins_data['updated_at']=Carbon::now();
                $ins_data['updated_by']=Auth::id();

                $insert_id = PatientComplication::whereId($request->complication_id)->update($ins_data);
                if($insert_id) {
                    return [ 'status'=>1, 'message'=>"Data updated Successfully" ];
                }
                else{
                    return [ 'status'=>3, 'message'=>"Failed to update data" ];
                }
            }

        }else{
            echo 2; // validation error
        }

    }

    /**
     *
     * complication list
     * @param Request $request
     */
    public function getComplicationData()
    {
        $cond=array();
        array_push($cond,['patient_complication.patient_id',Session::get('dtms_pid')]);
        array_push($cond,['patient_complication.is_deleted',0]);
        $patientComplication= PatientComplication::select('patient_complication.*', 'complication_master.complication_name')
            ->join('complication_master','complication_master.id','=','patient_complication.complication_id')
          //  ->join('subcomplication_master','subcomplication_master.id','=','patient_complication.sub_complication_id')
            ->where($cond)
            ->orderByDesc('patient_complication.id')
            ->get();

        $complicationElements = [];
        foreach($patientComplication as $item) {
            $complicationYear = null;
            if((int)$item->complication_year != 0) {
                $complicationYear = Carbon::now()->year - (int)$item->complication_year;
                $complicationYear = ($complicationYear < 0) ? 0 : $complicationYear;
            }

            $sub_complication_id=$item->sub_complication_id;
            if($sub_complication_id>0)
            {

                 $cond2=['id' =>$sub_complication_id,];
                 $sub_complication=getAfeild("subcomplication_name","subcomplication_master",$cond2);
            }
            else{
                $sub_complication="";
            }
            $complicationDate =Carbon::parse($item->examined_date)->format('d-m-Y');
            $complicationElements[] =[
                'id' => $item->id,
                'complication_name' => $item->complication_name,
                'subcomplication_name' => $sub_complication,
                'complication_year' => $complicationYear .' yrs',
                'examined_date' => $complicationDate,
            ];

        }
        $output = array(
            "recordsTotal" => count($complicationElements),
            "recordsFiltered" => count($complicationElements),
            "data" => $complicationElements
        );
        echo json_encode($output);
    }

    /**
     * diagnosis list
     * @param Request $request
     */
    public function getPhotos(Request $request){

        $cond=array();
        array_push($cond,['patient_documents.patient_id',Session::get('dtms_pid')]);
        array_push($cond,['patient_documents.is_deleted',0]);
        $filldata = PatientDocument::select('patient_documents.*', 'document_category.category_name')
        ->leftjoin('document_category','document_category.id','=','patient_documents.category_id')
        ->where($cond)
        ->orderByDesc('patient_documents.id')
        ->get();


        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
          );
          echo json_encode($output);

    }

    /**
     * get diagnosis data
     */
    public function getDiagnosisData()
    {
        $cond=array();
        array_push($cond,['patient_diagnosis.patient_id',Session::get('dtms_pid')]);
        array_push($cond,['patient_diagnosis.is_deleted',0]);
        $patientDiagnosis = PatientDiagnosis::select('patient_diagnosis.*', 'diagnosis_master.diagnosis_name')
            ->join('diagnosis_master','diagnosis_master.id','=','patient_diagnosis.diagnosis_id')
            ->where($cond)
            ->orderByDesc('patient_diagnosis.id')
            ->get();

        $diagnosisElements = array();
        foreach($patientDiagnosis as $item) {
            $diagnosisYear = null;
            if((int)$item->diagnosis_year != 0) {
                $diagnosisYear = Carbon::now()->year - (int)$item->diagnosis_year;
                $diagnosisYear = ($diagnosisYear < 0) ? 0 : $diagnosisYear;
            }
            $diagnosisDate =Carbon::parse($item->examined_date)->format('d-m-Y');
            $diagnosisElements[] = [
                'id' => $item->id,
                'diagnosis_name' => $item->diagnosis_name,
                'diagnosis_year' => $diagnosisYear . ' yrs',
                'examined_date' => $diagnosisDate,
            ];
        }

        $output = array(
            "recordsTotal" => count($diagnosisElements),
            "recordsFiltered" => count($diagnosisElements),
            "data" => $diagnosisElements
        );
        echo json_encode($output);
    }

    public function saveVisit(Request $request){

        $returnedRequest = $request; // do whatever with your request here

        Session::put('dtms_visitid',1);

        return redirect()->route('dtmshome',$request->pid);

    }

    public function medicalhistory(){

        $data=array();
        $data['PageName']="Medical History ";
        $data['sessions']=array('pid'=>Session::get('dtms_pid'));
        $patient_data=PatientRegistration::select('name','id','gender','dob','age','mobile_number')->where('id',Session::get('dtms_pid'))->orderBy('id','DESC')->first();
        $uhidNos=Session::get('current_branch_code').' -'.$patient_data->id;
        $medical_history=PatientMedicalHistory::select('*')->where('patient_id',Session::get('dtms_pid'))->orderBy('id','DESC')->first();
        $diet_history=PatientDietPlan::select('*')->where('patient_id',Session::get('dtms_pid'))->get()->toArray();
        $question_data=DietQuestionMaster::select('question','order_no','id')->where('is_deleted',0)->get();
        $data['patient_data']=$patient_data;
        $data['uhidNo']=$uhidNos;
        $data['medical_history']=$medical_history;
        $data['question_data']=$question_data;
        $data['diet_history']=$diet_history;
        return Parent::page_maker('webpanel.dtms.medicalhistory',$data);
    }

    public function medications(){
        $data=array();
        $data['PageName']="Medications ";
        $data['sessions']=array(
                            'pid'=>Session::get('dtms_pid'),
                            'dtms_visitid'=>Session::get('dtms_visitid')
                        );
        $patient_data=PatientRegistration::select('name','id','gender','dob','age','mobile_number')->where('id',Session::get('dtms_pid'))->orderBy('id','DESC')->first();
        $uhidNos=Session::get('current_branch_code').' -'.$patient_data->id;
        $data['patient_data']=$patient_data;
        $data['uhidNo']=$uhidNos;
        $data['isenabled']=true;

        return Parent::page_maker('webpanel.dtms.medications',$data);
    }

    public function pep(){
        $data=array();
        $data['PageName']="Patient education module (PEP)";
        $data['sessions']=array('pid'=>Session::get('dtms_pid'));
        $patient_data=PatientRegistration::select('name','id','gender','dob','age','mobile_number')->where('id',Session::get('dtms_pid'))->orderBy('id','DESC')->first();
        $uhidNos=Session::get('current_branch_code').' -'.$patient_data->id;
        $data['patient_data']=$patient_data;
        $data['uhidNo']=$uhidNos;
        $data['isenabled']=true;
        return Parent::page_maker('webpanel.dtms.pep',$data);
    }
    public function prescription(){
        $data=array();
        $data['PageName']="Prescription";
        $data['sessions']=array('pid'=>Session::get('dtms_pid'));
        $patient_data=PatientRegistration::select('name','id','gender','dob','age','mobile_number')->where('id',Session::get('dtms_pid'))->orderBy('id','DESC')->first();
        $uhidNos=Session::get('current_branch_code').' -'.$patient_data->id;
        $data['patient_data']=$patient_data;
        $data['uhidNo']=$uhidNos;
        $data['isenabled']=true;

        return Parent::page_maker('webpanel.dtms.prescription',$data);
    }
    public function vaccination(){
        $data=array();
        $data['PageName']="Vaccination";
        $data['sessions']=array('pid'=>Session::get('dtms_pid'));
        $patient_data=PatientRegistration::select('name','id','gender','dob','age','mobile_number')->where('id',Session::get('dtms_pid'))->orderBy('id','DESC')->first();
        $uhidNos=Session::get('current_branch_code').' -'.$patient_data->id;
        $data['patient_data']=$patient_data;
        $data['uhidNo']=$uhidNos;
        $data['isenabled']=true;

        return Parent::page_maker('webpanel.dtms.vaccination',$data);
    }
    public function miscellaneous(){
        $data=array();
        $data['PageName']="Miscellaneous";
        $data['sessions']=array('pid'=>Session::get('dtms_pid'));
        $patient_data=PatientRegistration::select('name','id','gender','dob','age','mobile_number')->where('id',Session::get('dtms_pid'))->orderBy('id','DESC')->first();
        $uhidNos=Session::get('current_branch_code').' -'.$patient_data->id;
        $data['patient_data']=$patient_data;
        $data['uhidNo']=$uhidNos;
        $data['isenabled']=true;

        return Parent::page_maker('webpanel.dtms.miscellaneous',$data);
    }

    public function dtms_dashboard(){
        $data=array();
        $data['PageName']="DTMS Dashboard";
        $data['sessions']=array('pid'=>Session::get('dtms_pid'));
        // $patient_data=PatientRegistration::select('name','id','gender','dob','age','mobile_number')->where('id',Session::get('dtms_pid'))->orderBy('id','DESC')->first();
        // $uhidNos=Session::get('current_branch_code').' -'.$patient_data->id;
        // $data['patient_data']=$patient_data;
        // $data['uhidNo']=$uhidNos;
        // $data['isenabled']=true;

        return Parent::page_maker('webpanel.dtms.dashboard',$data);
    }
    public function visitlists(){
        $data=array();
        $data['PageName']="Visit Lists";
        $data['sessions']=array('pid'=>Session::get('dtms_pid'));
        // $patient_data=PatientRegistration::select('name','id','gender','dob','age','mobile_number')->where('id',Session::get('dtms_pid'))->orderBy('id','DESC')->first();
        // $uhidNos=Session::get('current_branch_code').' -'.$patient_data->id;
        // $data['patient_data']=$patient_data;
        // $data['uhidNo']=$uhidNos;
        // $data['isenabled']=true;

        return Parent::page_maker('webpanel.dtms.visitlist',$data);
    }

    public function getVisitLists(Request $request){

        // $group_data=UserGroup::where('group_id',$request->id)->orderBy('group_id','DESC')->join('user_group_menus','user_group_menus.user_group_id','=','user_group.group_id')->first();
        $branch_id=Session::get('current_branch');

        $start_date = Carbon::parse(request()->from_date)->toDateTimeString();
        $end_date = Carbon::parse(request()->to_date)->toDateTimeString();
        $end_date= str_replace("00:00:00","24:00:00",$end_date);



        $cond=[];
        if( !is_null($request->patient_name) ){
            array_push($cond,['patient_registration.name', 'ILIKE', "%{$request->patient_name}%"]);
        }
        if( !is_null($request->uhid) ){
            array_push($cond,['uhidno', 'ILIKE', "%{$request->uhid}%"]);
        }




        array_push($cond,['patient_visits.is_deleted','=',0]);

        array_push($cond,['patient_visits.branch_id','=',$branch_id]);
        $filldata = PatientVisits::select('patient_visits.*','patient_registration.name as patientname','patient_registration.uhidno','visit_type_master.visit_type_name')
            ->join('patient_registration','patient_registration.id','=','patient_visits.patient_id')
            ->join('visit_type_master','visit_type_master.id','=','patient_visits.visit_type_id')
            ->whereBetween('patient_visits.visit_date', [$start_date, $end_date])
            ->where($cond)->orderByDesc('id')
            ->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
      echo json_encode($output);

    }

    /**
     * store dtms dashboard data in db
     * @param Request $request
     * @return array
     */
    public function savePep(Request $request)
    {
            $insert_id="";
            $ins=array();
            $cond=array();
            array_push($cond,['is_deleted',0]);
            array_push($cond,['type',2]);
//            $questions=DietQuestionMaster::where($cond)->select('id','question')->get()->toArray();
//            foreach($questions as $question_data){
//                $answer='pep_answer_'.$question_data['id'];
//                $notes='notes_'.$question_data['id'];
//
//                if($request->$answer!=null){
//                    array_push($ins,
//                    ['patient_id' =>Session::get('dtms_pid'),
//                    'question_id' =>$question_data['id'],
//                    'answer' =>$request->$answer,
//                    'notes' =>$request->$notes,
//                    'branch_id' => Session::get('current_branch'),
//                    'created_by'=>Auth::id(),
//                    'display_status' => 1,
//                    'is_deleted' => 0,
//                    'created_at' => Carbon::now(),
//                    'updated_at' => Carbon::now(),]
//                );
//                }
//
//            }
//                PatientPep::where('patient_id',Session::get('dtms_pid'))->delete();
//                $insert_id = PatientPep::insert($ins);
//                    if($insert_id) {
//                        return [ 'status'=>1, 'message'=>"Saved Successfully" ];
//                        // echo 1; //save success
//                    }
//                    else{
//                        return [ 'status'=>3, 'message'=>"Failed to save" ];
//
//                    }
        $questions=DietQuestionMaster::where($cond)->select('id','question')->get();
        if (! $questions->isEmpty()) {

//                PatientPep::where('patient_id', Session::get('dtms_pid'))->delete();
            $pepDate = PatientAnswerSheet::whereDate('created_at', Carbon::today())
                ->where('question_type_id',2)
                ->where('patient_id',Session::get('dtms_pid'))
                ->first();
            if($pepDate)
            {
                return [ 'status'=>4, 'message'=>"Pep already exist" ];
            }
            else {
                $pepAnswerSheetData = PatientAnswerSheet::create([
                    'question_type_id' =>2,
                    'patient_id' => Session::get('dtms_pid'),
                ]);
                $questionData = [];
                foreach ($questions as $question_data) {
                    if ($request->has('pep_answer_' . $question_data->id)) {
                        if ('' != $request->get('pep_answer_' . $question_data->id)) {
                            $questionData[] = [
                                'question_id' =>$question_data->id,
                                'answer' =>$request->get('pep_answer_' . $question_data->id),
                                'notes' =>$request->get('notes_' . $question_data->id),
                                'answer_sheet_id' =>$pepAnswerSheetData->id,
                                'display_status' => 1,
                                'branch_id' => Session::get('current_branch'),
                                'patient_id' => Session::get('dtms_pid'),
                                'created_by' => Auth::id(),
                                'is_deleted' => 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }
                    }
                }
                $insert_id = PatientPep::insert($questionData);
                if($insert_id) {
                    return [ 'status'=>1, 'message'=>"Saved Successfully" ];
                }
                else{
                    return [ 'status'=>3, 'message'=>"Failed to save" ];

                }
            }

        }
    }
    public function savePatientDietPlan(Request $request)
    {
            $insert_id="";
            $ins=array();
            $cond=array();
            array_push($cond,['is_deleted',0]);
            array_push($cond,['type',1]);
//            $questions=DietQuestionMaster::where($cond)->select('id','question')->get()->toArray();
//
//            foreach($questions as $question_data){
//                $answer='dietplan_answer_'.$question_data['id'];
//                $notes='notes_'.$question_data['id'];
//
//                if($request->$answer!=null){
//                    array_push($ins,
//                    ['patient_id' =>Session::get('dtms_pid'),
//                    'dietplan_id' =>$question_data['id'],
//                    'dietplan_answer' =>$request->$answer,
//                    'notes' =>$request->$notes,
//                    'branch_id' => Session::get('current_branch'),
//                    'created_by'=>Auth::id(),
//                    'display_status' => 1,
//                    'is_deleted' => 0,
//                    'created_at' => Carbon::now(),
//                    'updated_at' => Carbon::now(),]
//                );
//                }
//
//            }
//
//
//
//                PatientDietPlan::where('patient_id',Session::get('dtms_pid'))->delete();
//                $insert_id = PatientDietPlan::insert($ins);
//                    if($insert_id) {
//                        return [ 'status'=>1, 'message'=>"Saved Successfully" ];
//                        // echo 1; //save success
//                    }
//                    else{
//                        return [ 'status'=>3, 'message'=>"Failed to save" ];
//
//                    }

        $questions=DietQuestionMaster::where($cond)->select('id','question')->get();

        if (! $questions->isEmpty()) {

            $dietPlanDate = PatientAnswerSheet::whereDate('created_at', Carbon::today())
                ->where('question_type_id',1)
                ->where('patient_id',Session::get('dtms_pid'))
                ->first();
            if($dietPlanDate)
            {
                return [ 'status'=>4, 'message'=>"Diet plan already exist" ];
            }
            else {
                $dietPlanAnswerSheetData = PatientAnswerSheet::create([
                    'question_type_id' =>1,
                    'patient_id' => Session::get('dtms_pid'),
                ]);
                $questionData = [];
                foreach ($questions as $question_data) {
                    if ($request->has('dietplan_answer_' . $question_data->id)) {

                        if ('' != $request->get('dietplan_answer_' . $question_data->id)) {
                            $questionData[] = [
                                'dietplan_id' =>$question_data->id,
                                'dietplan_answer' =>$request->get('dietplan_answer_' . $question_data->id),
                                'notes' =>$request->get('notes_' . $question_data->id),
                                'answer_sheet_id' =>$dietPlanAnswerSheetData->id,
                                'display_status' => 1,
                                'branch_id' => Session::get('current_branch'),
                                'patient_id' => Session::get('dtms_pid'),
                                'created_by' => Auth::id(),
                                'is_deleted' => 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }
                    }
                }
                $insert_id = PatientDietPlan::insert($questionData);
                if($insert_id) {
                    return [ 'status'=>1, 'message'=>"Saved Successfully" ];
                    // echo 1; //save success
                }
                else{
                    return [ 'status'=>3, 'message'=>"Failed to save" ];

                }
            }
        }




    }

    public function deletePhoto(Request $request)
    {
        $id=$request->id;
        if($id)

        {
            $ins=array(
                'is_deleted' =>1,
                );
            $insert_id=PatientDocument::whereId($id)->update($ins);
            if($insert_id) {
                return [ 'status'=>1, 'message'=>"Data deleted successfully" ];
               }
           else{
            return [ 'status'=>3, 'message'=>"Failed to delete data" ];
           }
        }
    }
    public function saveMedicalHistory(Request $request)
    {
            $insert_id="";
            $ins=array(
                'patient_id' =>Session::get('dtms_pid'),

                // 'present_illness' => $request->present_illness,
                'chief_complaints' => $request->chief_complaints,
                'past_illness' => $request->past_illness,
                'present_medication' => $request->present_medication,
                'past_medication' => $request->past_medication,

                'family_history' => $request->family_history,
                'cardiovascular_system' => $request->cardiovascular_system,
                'respiratory_system' => $request->respiratory_system,
                'head_ears_nose' => $request->head_ears_nose,
                'gastrointestinal' => $request->gastrointestinal,
                'musculoskeletal_system' => $request->musculoskeletal_system,
                'central_nervous_system' => $request->central_nervous_system,
                'general_apperance' => $request->general_apperance,
                'thyroid_gland' => $request->thyroid_gland,
                'node_palpation' => $request->node_palpation,
                'branch_id' => Session::get('current_branch'),
                'created_by'=>Auth::id(),
                'display_status' => 1,
                'is_deleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            if($request->crude==1)
            {


                    $insert_id = PatientMedicalHistory::insert($ins);
                    if($insert_id) {
                        return [ 'status'=>1, 'message'=>"Saved Successfully" ];
                        // echo 1; //save success
                    }
                    else{
                        return [ 'status'=>3, 'message'=>"Failed to save" ];

                    }

            }
            else if($request->crude==2){
                    $ins['updated_by'] =Auth::id();
                    $insert_id= PatientMedicalHistory::where('patient_id',Session::get('dtms_pid'))->update($ins);
                    if($insert_id) {
                        return [ 'status'=>1, 'message'=>"Data updated Successfully" ];
                    }
                    else{
                        return [ 'status'=>3, 'message'=>"Failed to update data" ];
                    }



            }



    }
    public function saveDtmsData(Request $request)
    {

       // dd(session()->all());
      //  exit;
        // Bp Status
        if ($request->session()->exists('dtms_visitid')) {
            $bpStatusData= [];
            for ($i = 1; $i <= 4; $i++) {
                if ('' != $request->get('bps_status_time_' . $i)) {
                    $bpStatusData[]=[
                        'visit_id' => Session::get('dtms_visitid'),
                        'time' => $request->get('bps_status_time_' . $i),
                        'bps' => $request->get('bps_status_bps_' . $i),
                        'bpd' => $request->get('bps_status_bpd_' . $i),
                        'pulse' => $request->get('bps_status_pulse_' . $i),
                        'display_status' => 1,
                        'order_no' => $i,
                        'branch_id' => Session::get('current_branch'),
                        'specialist_id' => Auth::id(),
                        'created_by' => Auth::id(),
                        'is_deleted' => 0,
                    ];
                }
            }
            if (! empty($bpStatusData)) {
                PatientBpStatus::where('visit_id', Session::get('dtms_visitid'))->delete();
                PatientBpStatus::insert($bpStatusData);
            }
        }

        // vital master
        $vitalsLists = VitalsMaster::select('vitals_master.*')->orderBy('id','DESC')->get();
        if (! $vitalsLists->isEmpty()) {
            $vitalData = [];
            if ($request->session()->exists('dtms_visitid')) {
                foreach ($vitalsLists as $item) {
                    if ($request->has('vital_' . $item->id)) {
                        if ('' != $request->get('vital_' . $item->id)) {
                            $vitalData[] = [
                                'visit_id' => Session::get('dtms_visitid'),
                                'vitals_id' => $item->id,
                                'vitals_value' => $request->get('vital_' . $item->id),
                                'display_status' => 1,
                                'branch_id' => Session::get('current_branch'),
                                'patient_id' => Session::get('dtms_pid'),
                                'created_by' => Auth::id(),
                                'is_deleted' => 0,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }
                    }
                }
                if (! empty($vitalData)) {
                    PatientVitals::where('visit_id', Session::get('dtms_visitid'))->delete();
                    PatientVitals::insert($vitalData);
                }
            }
        }

        // Patient target
        $patientLists = TestMaster::select('test_master.TestId')->where('show_test_in_targets',1)->orderBy('id','DESC')->get();
        if (! $patientLists->isEmpty()) {
            $targetData = [];
            if ($request->session()->exists('dtms_visitid')) {
                foreach ($patientLists as $item) {
                    if ($request->has('patient_target_' . $item->TestId)) {
                        if ('' != $request->get('patient_target_' . $item->TestId)) {
                            $targetData[] = [
                                'visit_id' => Session::get('dtms_visitid'),
                                'test_id' => $item->TestId,
                                'target_value' => $request->get('patient_target_' . $item->TestId),
                                'present_value' => $request->get('patient_present_' . $item->TestId),
                                'patient_id' => Session::get('dtms_pid'),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }
                    }
                }
                if (! empty($targetData)) {
                    PatientTarget::where('visit_id', Session::get('dtms_visitid'))->delete();
                    PatientTarget::insert($targetData);
                }
            }
        }

        // Patient target detail
        if ($request->session()->exists('dtms_visitid')) {
            PatientTargetDetail::updateOrCreate(
                ['patient_id' => Session::get('dtms_pid')],
                [
                    'weight_target' => $request->get('weight_target'),
                    'weight_present' => $request->get('weight_present'),
                    'action_plan' => $request->get('action_plan'),
                    'fibro_scan'=>$request->get('fibro_scan'),
                    'visit_id' => Session::get('dtms_visitid'),
                ]
            );
        }

         //Remark
        if ($request->get('visit_type_id')>0){
            $visitDate = Carbon::parse($request->get('visit_date'))->format('Y-m-d');
            PatientVisits::where('id', Session::get('dtms_visitid'))->update([
                // 'visit_date' =>$visitDate ,
                'dtms_remarks' => $request->get('remark'),
                'fibro_scan'=> $request->get('fibro_scan'),
            ]);
        }

        // updating prescriptions
        if ($request->session()->exists('dtms_visitid')) {

            $prrescriptionData= [];
            if($request->medicine_idlist !=""){

                PatientPrescriptions::where('visit_id', Session::get('dtms_visitid'))->delete();

                foreach ($request->medicine_idlist as $key => $pres) {

                   if($pres){
                        $prrescriptionData[] = [
                        'visit_id' => Session::get('dtms_visitid'),
                        'tablet_type_id' => $request->tablet_type_idlist[$key],
                        'dose'  => $request->doselist[$key],
                        'medicine_id' =>$pres,
                        'remarks'  => $request->remarkslist[$key],
                        'display_status' => 1,
                        'branch_id' => Session::get('current_branch'),
                        'patient_id' => Session::get('dtms_pid'),
                        'created_by' => Auth::id(),
                        'is_deleted' => 0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        ];



                   }


                }

                PatientPrescriptions::insert($prrescriptionData);
            }
        }


        // $request->session()->forget('dtms_visitid');
        return [ 'status'=> 1, 'message' => 'Saved Successfully' ];

    }

    // public function targetAfterSave(REQUEST $request){
    //     $visitId=$request->id;

    //     $data=$this->getvisitbasedTarget($visitId);
    //     echo json_encode($data);


    // }

    public function getTestResultValue($visitId = null)
    {

        // if($visitId=="")
        // {
        //     $cond=array(
        //         array('patient_id',Session::get('dtms_pid')),
        //     );
        //     $visitId= getAfeildByOrder("id","patient_visits",$cond,'id','DESC');
        // }


        // if($visitId>0)
        // {
        //     $qry='select tm."TestId",tm."TestName",tr."ResultValue",tr."is_outside_lab",tr."visit_id"
        //     from test_master as tm
        //     left join
        //     (select "ResultValue","is_outside_lab","visit_id","TestId" from test_results where "visit_id"='.$visitId.'
        //     ) as tr
        //     on tr."TestId"=tm."TestId" where  tm."show_test_in_dtms"=1';
        // }
        // else{
        //     $qry='select tm."TestId",tm."TestName",tr."ResultValue",tr."is_outside_lab",tr."visit_id"
        //     from test_master as tm
        //     left join
        //     (select "ResultValue","is_outside_lab","visit_id","TestId" from test_results where "visit_id"=
        //         (select id from patient_visits where patient_id='.Session::get('dtms_pid').' order by id desc limit 1)
        //     ) as tr
        //     on tr."TestId"=tm."TestId" where  tm."show_test_in_dtms"=1';
        // }


        // $patientTargetList=DB::select($qry);
        // return $patientTargetList;


        //wrong method

        $patientTestResultListData = TestMaster::select('test_master.TestId','test_master.TestName', 'test_master.id as test_master_id')
            ->where('show_test_in_dtms', 1)
            ->orderByDesc('test_master.id')
            ->first();

        $patientTargetList = [];
        foreach ($patientTestResultListData as $item) {
            if (is_null($visitId)) {
                // process while first page load
                $testResultLatestValue = TestResults::where('TestId', $item->TestId)
                    ->where('PatientId', Session::get('dtms_pid'))
                    ->orderByDesc('id')
                    ->first();
                $patientTargetList[] = [
                    'test_name' => $item->TestName,
                    'result' => $testResultLatestValue->ResultValue ?? '',
                    'is_outside_lab' => $testResultLatestValue->is_outside_lab ?? '',
                ];
            } else {
               // On click of test result
                $testResultValue = TestResults::where('visit_id', $visitId)
                    ->where('TestId', $item->TestId)
                    ->where('PatientId', Session::get('dtms_pid'))
                    ->orderByDesc('id')
                    ->first();
                if (! is_null($testResultValue)) {
                    $patientTargetList[] = [
                        'test_name' => $item->TestName,
                        'result' => $testResultValue->ResultValue,
                        'is_outside_lab' => $testResultValue->is_outside_lab,
                    ];
                } else {
                    $patientTargetList[] = [
                        'test_name' => $item->TestName,
                        'result' => '',
                        'is_outside_lab' => '',
                    ];
                }
            }

        }
        return $patientTargetList;
    }

    public function getvisitbasedTarget($visitId)
    {
        $patientTargetListData = TestMaster::select('test_master.TestId','test_master.TestName', 'test_master.id as test_master_id')
            ->where('show_test_in_targets', 1)
            ->orderByDesc('test_master.id')
            ->get();

        $patientTargetList = [];
        foreach ($patientTargetListData as $item) {
            $patientTargetLatestValue = PatientTarget::where('patient_targets.visit_id', $visitId)
                ->where('patient_targets.test_id', $item->TestId)
                ->orderByDesc('id')
                ->first();
            if (! is_null($patientTargetLatestValue)) {
                $patientTargetList[] = [
                    'TestName' => $item->TestName,
                    'test_master_id' => $item->TestId,
                    'target_value' => $patientTargetLatestValue->target_value,
                    'present_value' => ! is_null($patientTargetLatestValue->present_value) ? $patientTargetLatestValue->present_value : '',
                ];
            } else {
                $patientTargetList[] = [
                    'TestName' => $item->TestName,
                    'test_master_id' => $item->TestId,
                    'target_value' => ! is_null($item->target_default_value) ? $item->target_default_value : '',
                    'present_value' => '',
                ];
            }
        }
        return $patientTargetList;

//        $targetArray = [];
//        $targetMainArray = [];
//        $patientCurrentTestTargetData = PatientTarget::select('patient_targets.*', 'test_master.TestName',  'test_master.id as test_master_id')
//            ->leftjoin('test_master','test_master.id','=','patient_targets.test_id')
//            ->where('patient_targets.visit_id', $visitId)
//            ->orderByDesc('patient_targets.id')
//            ->first();
////        dd($patientCurrentTestTargetData);
//
//        // to get previous test data
//        $patientVisitList = PatientVisits::select('patient_visits.visit_type_id', 'patient_visits.id','patient_visits.visit_code', 'patient_visits.visit_date', 'visit_type_master.visit_type_name')
//            ->join('visit_type_master','visit_type_master.id','=','patient_visits.visit_type_id')
//            ->where('patient_id',Session::get('dtms_pid'))
//            ->orderByDesc('patient_visits.id')
//            ->get();
//        foreach($patientVisitList as $patientListValue) {
//            if ($visitId > $patientListValue->id) {
//                $patientPreviousTestTargetList = PatientTarget::select('patient_targets.target_value','patient_targets.present_value', 'test_master.TestName',  'test_master.id as test_master_id')
//                    ->leftjoin('test_master','test_master.id','=','patient_targets.test_id')
//                    ->where('patient_targets.visit_id',$patientListValue->id)
//                    ->orderByDesc('patient_targets.visit_id')
//                    ->first();
////                dd($patientPreviousTestTargetList);
//                if (! is_null($patientPreviousTestTargetList)) {
//                    $targetArray = array('target_name' =>  isset($patientPreviousTestTargetList->TestName) ? $patientPreviousTestTargetList->TestName : '' ,
//                        'target_default_value'  =>isset($patientPreviousTestTargetList->target_value) ? $patientPreviousTestTargetList->target_value : '' ,
//                        'target_present_value'=>isset($patientCurrentTestTargetData->present_value) ? $patientCurrentTestTargetData->present_value : '' ,
//                        'test_id'=> isset($patientCurrentTestTargetData->test_master_id) ? $patientCurrentTestTargetData->test_master_id:'',
//                    );
//
//                    break;
//                }
//
//            }
//        }
//
//        // if no previous data fount, set default value
//        if (empty($targetArray)) {
////            dd('dfvdv');
//            $patientTestTargetList = TestMaster::select('test_master.*',  'test_master.id as test_master_id')
//                ->where('show_test_in_targets', 1)
//                ->orderByDesc('test_master.id')
//                ->first();
//            $targetArray=array('target_name' => isset($patientTestTargetList->TestName) ? $patientTestTargetList->TestName : '' ,
//                'target_default_value'  =>isset($patientTestTargetList->target_default_value) ? $patientTestTargetList->target_default_value : '' ,
//                'target_present_value'=> '',
//                'test_id'=>$patientTestTargetList->test_master_id,
//            );
//
//        }
//        array_push($targetMainArray,$targetArray);
//        return $targetMainArray;
    }

    public function visitHistory(Request $request)
    {
        $data=array();
        Session::put('dtms_visitid',$request->id);
        $bpStatusData = PatientBpStatus::select('id','time','bps','bpd','pulse','order_no')->where('visit_id',$request->id)->orderBy('order_no','ASC')->get();
        $vitalData = PatientVitals::select('id','vitals_value','vitals_id')->where('visit_id',$request->id)->orderBy('id','DESC')->get();
        $targetMainArray=$this->getvisitbasedTarget($request->id);

        $patientTargetDetails = PatientTargetDetail::where('visit_id',$request->id)->first();
        $patientTargetData =[];
        $patientTargetData =
            [
            'weight_target' => isset($patientTargetDetails->weight_target) ? $patientTargetDetails->weight_target : '' ,
            'weight_present'  =>isset($patientTargetDetails->weight_present) ? $patientTargetDetails->weight_present : '',
            'action_plan'=> isset($patientTargetDetails->action_plan ) ? $patientTargetDetails->action_plan : '',
            'fibro_scan'=> isset($patientTargetDetails->fibro_scan ) ? $patientTargetDetails->fibro_scan : '',
            ];

        $remarkData = PatientVisits::select('patient_visits.*', 'visit_type_master.visit_type_name')
            ->join('visit_type_master','visit_type_master.id','=','patient_visits.visit_type_id')
            ->where('patient_visits.id',$request->id)
            ->first();
        $uhidNos=Session::get('current_branch_code').' -'.Session::get('dtms_pid');
        $prescriptionData=PatientPrescriptions::
                        select('medicine_master.medicine_name','tablet_type_master.tablet_type_name','patient_prescriptions.*')
                        ->join('medicine_master','medicine_master.id','=','patient_prescriptions.medicine_id')
                        ->join('tablet_type_master','tablet_type_master.id','=','medicine_master.tablet_type_id')
                        ->where('visit_id',$request->id)->get();

        $data['bp_data']=$bpStatusData;
        $data['vital_data']=$vitalData;
        $data['remark_data']=$remarkData;
        $data['prescriptionData']=$prescriptionData;
        $data['patient_test_targets'] = $targetMainArray;
        $data['patient_target_details'] = $patientTargetData;
        $data['dtms_visitid'] =Session::get('dtms_visitid');
        $data['uhidNo']=$uhidNos;


        echo json_encode($data);
    }

    public function testResultData(Request $request)
    {

		Session::put('dtms_visitid',$request->id);
		$testResultValue = $this->getTestResultValue($request->id);
		echo json_encode(['test_result_data' => $testResultValue]);

    }

    public function visitHistoryById(Request $request){
        $branch_id=Session::get('current_branch');
        // $cond=['visit_code' =>$request->id,'branch_id' => $branch_id];
        // $visitid=getAfeild("id","patient_visits",$cond);
        $visitid = $request->id;
        $prescriptionData=PatientPrescriptions::
        select('medicine_master.medicine_name','tablet_type_master.tablet_type_name','patient_prescriptions.*')
        ->join('medicine_master','medicine_master.id','=','patient_prescriptions.medicine_id')
        ->join('tablet_type_master','tablet_type_master.id','=','medicine_master.tablet_type_id')
        ->where('visit_id',$visitid)->get();

        $data['prescriptionData']=$prescriptionData;
        echo json_encode($data);

    }

    /**
     * dtms profile info
     * @param Request $request
     */
    public function getDtmsProfileData(Request $request){

        $data=array();
        $patientDiagnosis = PatientDiagnosis::select('diagnosis_master.diagnosis_name','patient_diagnosis.diagnosis_year')
            ->join('diagnosis_master','diagnosis_master.id','=','patient_diagnosis.diagnosis_id')
            ->where('patient_id',Session::get('dtms_pid'))
            ->where('patient_diagnosis.is_deleted',0)
            ->orderByDesc('patient_diagnosis.id')
            ->get();

        $patientComplication = PatientComplication::select( 'complication_master.complication_name', 'subcomplication_master.subcomplication_name', 'patient_complication.complication_year')
            ->join('complication_master','complication_master.id','=','patient_complication.complication_id')
            ->join('subcomplication_master','subcomplication_master.id','=','patient_complication.sub_complication_id')
            ->where('patient_id',Session::get('dtms_pid'))
            ->where('patient_complication.is_deleted',0)
            ->orderByDesc('patient_complication.id')
            ->get();

            $diagnosisElements = array();
            foreach($patientDiagnosis as $item) {
                $diagnosisYear = null;
                if((int)$item->diagnosis_year != 0) {
                    $diagnosisYear = Carbon::now()->year - (int)$item->diagnosis_year;
                    $diagnosisYear = ($diagnosisYear < 0) ? 0 : $diagnosisYear;
                }
//                $diagnosisElements[] = $item->diagnosis_name.'('.$diagnosisYear.' yrs'.')' ;
                $diagnosisElements[] = $item->diagnosis_name ;
            }


            $complicationElements = array();
            foreach($patientComplication as $item) {
                $complicationYear = null;
                if((int)$item->complication_year != 0) {
                    $complicationYear = Carbon::now()->year - (int)$item->complication_year;
                    $complicationYear = ($complicationYear < 0) ? 0 : $complicationYear;
                }
//                $complicationElements[] =$item->complication_name.'('.$complicationYear.' yrs'.')';
                $complicationElements[] =$item->complication_name;
            }

            $subComplicationElements = array();
            foreach($patientComplication as $name) {
                $subComplicationElements[] =$name->subcomplication_name;
            }
//            $data['diagnosis_info']=implode('<br>', $diagnosisElements);
            $data['diagnosis_info']=implode(' &nbsp; | &nbsp;', $diagnosisElements);
            $data['complication_info']=implode(' &nbsp; | &nbsp;', $complicationElements);
//            $data['complication_info']=implode('<br>', $complicationElements);
            $data['sub_complication_info']=implode(',', $subComplicationElements);

            echo json_encode($data);

    }

    /**
     * store vaccination data in db
     * @param Request $request
     * @return array
     */
    public function saveVaccinationData(Request $request){
        $validated = $request->validate([
            'vaccination_id' => 'required',
//            'vaccination_remark' => 'required',
            'vaccination_date' => 'required',
        ]);
        if($validated)
        {

            $ins_data=array(
                'vaccination_id'=>$request->vaccination_id,
                'remarks'=>$request->vaccination_remark,
                'vaccination_date'=>$request->vaccination_date ? date("Y-m-d", strtotime($request->vaccination_date)) :NULL,
                'display_status' => 1,
                'created_by'=>Auth::id(),
                'branch_id'=>Session::get('current_branch'),
                'is_deleted' => 0,
                'patient_id'=>Session::get('dtms_pid'),
            );

            if($request->crude==1){
                // insert
                $ins_data['created_at']=Carbon::now();
                $ins_data['created_by']=Auth::id();
                $insert_id = PatientVaccination::create($ins_data);
                if ($insert_id) {
                    return ['status' => 1, 'message' => "Saved Successfully"];
                    // echo 1; //save success
                } else {
                    return ['status' => 3, 'message' => "Failed to save"];

                }

            }
            else if($request->crude==2){

                // update

                $ins_data['updated_at']=Carbon::now();
//                $ins_data['updated_by']=Auth::id();

                $insert_id = PatientVaccination::whereId($request->vaccination_id)->update($ins_data);
                if($insert_id) {
                    return [ 'status'=>1, 'message'=>"Data updated Successfully" ];
                    // echo 1; //save success
                }
                else{
                    return [ 'status'=>3, 'message'=>"Failed to update data" ];

                }
            }

        }else{
            echo 2; // validation error
        }

    }

    /**
     *
     * vaccination list
     * @param Request $request
     */
    public function getVaccinationData()
    {
        $cond=array();
        array_push($cond,['patient_vaccination.patient_id',Session::get('dtms_pid')]);
        array_push($cond,['patient_vaccination.is_deleted',0]);
        $filldata= PatientVaccination::select('patient_vaccination.*', 'vaccination_master.vaccination_name')
            ->join('vaccination_master','vaccination_master.id','=','patient_vaccination.vaccination_id')
            ->where($cond)
            ->orderByDesc('patient_vaccination.id')
            ->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);

    }

    /**
     * store alert data in db
     * @param Request $request
     * @return array
     */
    public function saveAlertData(Request $request)
    {
        $validated = $request->validate([
            'alerts' => 'required_without:psychiatric_medications',
            'psychiatric_medications' => 'required_without:alerts',
        ]);
        if ($validated) {
            $insert_id = "";
            $alertLists = PatientAlerts::select('patient_alert.*')->where('patient_id', Session::get('dtms_pid'))->orderBy('id', 'DESC')->get();
            $alertData = [];
            $alertData = [
                'patient_id' => Session::get('dtms_pid'),
                'alerts' => $request->get('alerts'),
                'branch_id' => Session::get('current_branch'),
                'display_status' => 1,
                'is_deleted' => 0,
                'psychiatric_medications' => $request->get('psychiatric_medications')
            ];
            if (!$alertLists->isEmpty()) {
                $alertData['updated_at'] = Carbon::now();
                //    dd(Session::get('dtms_pid'));
                DB::connection()->enableQueryLog(); // enable qry log
                $insert_id = PatientAlerts::where('patient_id', Session::get('dtms_pid'))->update($alertData);

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
                    'log_type' => 2, // Update
                    'table_name' => 'PatientAlerts', // Update AlertData
                    'qury_log' => $sql,
                    'description' => 'DTMS , Update AlertData ',
                    'created_date' => date('Y-m-d H:i:s'),
                    'patient_id' => Session::get('dtms_pid'),
                );

                $save_history = $this->HistoryController->saveHistory($log);
                ////////////////////////////////////////////////////////////////////////////////////
                // END OF LOG
                if ($insert_id) {
                    return ['status' => 1, 'message' => "Data updated Successfully"];
                } else {
                    return ['status' => 3, 'message' => "Failed to update data"];
                }
            } else {
                $alertData['created_at'] = Carbon::now();
                $alertData['created_by'] = Auth::id();

                DB::connection()->enableQueryLog(); // enable qry log

                $insert_id = PatientAlerts::insertGetId($alertData);

                // TO GET LAST EXECUTED QRY
                $query = DB::getQueryLog();
                $lastQuery = end($query);
                $sql = $lastQuery['query'];
                $bindings = $lastQuery['bindings'];
                $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                //end of qury

                // LOG  // add parameters based on your need
                $log = array(
                    'primarykeyvalue_Id' => $insert_id,
                    'user_id' => Auth::id(), // userId
                    'log_type' => 1, // Save
                    'table_name' => 'PatientAlerts', // save Alert Data
                    'qury_log' => $sql,
                    'description' => 'DTMS , Save AlertData',
                    'created_date' => date('Y-m-d H:i:s'),
                    'patient_id' => $insert_id,
                );

                $save_history = $this->HistoryController->saveHistory($log);
                ////////////////////////////////////////////////////////////////////////////////////
                // END OF LOG
                if ($insert_id) {
                    return ['status' => 1, 'message' => "Saved Successfully"];
                    // echo 1; //save success
                } else {
                    return ['status' => 3, 'message' => "Failed to save"];
                }
            }
        } else {
            echo 2; // validation error
        }
    }

    /**
     *
     * Alert data
     * @param Request $request
     */
    public function getAlertData(Request $request)
    {
        $data = array();
        $patientAlertData = PatientAlerts::select('id', 'alerts', 'psychiatric_medications')
            ->where('patient_id', Session::get('dtms_pid'))
            ->orderByDesc('id')
            ->first();

        echo json_encode($patientAlertData);
    }

    public function calculategfr(Request $request){
        $scr=$request->scr;
        $gender=$request->gender;
        $age=$request->age;

        echo $this->gfrCalculation($scr,$gender,$age);



    }

    public function gfrCalculation($scr,$gender,$age){
        if(!$gender){
            echo 'Failed to calculate';
            return ;
        }
        if($gender=='f'){
            if( $scr <= '0.7'){
                $val =-0.329;
            }else{
                $val =-1.209;
            }

        }else if($gender == 'm'){
            if( $scr <= '0.9'){
                $val =-0.411;
            }else{
                $val =-1.209;
            }
        }else{
            $val =0;
        }

        $gfr= (144*(pow(($scr /0.7),$val)))*(pow(0.993,$age));

        return $gfr;
    }


    /**
     * update patient data in db
     * @param Request $request
     * @return array
     */
    public function updatePatientData(Request $request)
    {
            $ins_data=array(
                'admission_date'=> date("Y-m-d", strtotime($request->admission_date)),
                'gender'=>$request->gender,
                'salutation_id'=>$request->salutation_id,
                'name'=>$request->name,
                'last_name'=>$request->last_name,
                'specialist_id'=>$request->specialist_id,
                'mobile_number'=>$request->mobile_number,
                'patient_reference_name'=>$request->patient_reference_name,
                'dob'=> date("Y-m-d", strtotime($request->dob)),
                'country_id'=>$request->country_id,
                'state_id'=>$request->state_id,
                'place_id'=>$request->place_id,
                'address'=>$request->address,
                'pincode'=>$request->pincode,
                'religion_id'=>$request->religion_id,
                'marital_status'=>$request->marital_status,
                'occupation'=>$request->occupation,
                'education'=>$request->education,
                'email_extension'=>$request->email_extension,
                'email'=>$request->email,
                'blood_group_id'=>$request->blood_group_id,
                'empanelment_no'=>$request->empanelment_no,
                'claim_id'=>$request->claim_id,
                'whatsapp_number'=>$request->whatsapp_number,
                'branch_id'=>Session::get('current_branch'),
            );
            if($request->crude==2){
                $ins_data['updated_at'] = Carbon::now();
                $ins_data['updated_by']=Auth::id();
                $insert_id = PatientRegistration::where('id',Session::get('dtms_pid'))->update($ins_data);
                if($insert_id) {
                    return [ 'status'=>1, 'message'=>"Data updated Successfully" ];
                }
                else{
                    return [ 'status'=>3, 'message'=>"Failed to update data" ];
                }

            }
    }

    public function saveMiscellaneousData(REQUEST $request){

        $input = $request->all();

//        FormEngineAnswers::where('patient_id',Session::get('dtms_pid'))->delete();
        $miscellaneousDate = PatientAnswerSheet::whereDate('created_at', Carbon::today())
            ->where('question_type_id',3)
            ->where('patient_id',Session::get('dtms_pid'))
            ->first();
        if($miscellaneousDate)
        {
            return [ 'status'=>4, 'message'=>"Miscellaneous  already exist" ];
        }
        else {
            $miscellaneousAnswerSheetData = PatientAnswerSheet::create([
                'question_type_id' =>3,
                'patient_id' => Session::get('dtms_pid'),
            ]);
            foreach ($input as $key => $value) {


                if(is_array($value)){
                    continue;
                }
                if (!$value) {
                    continue;
                }
                $isattribute = 0;
                $name = $key;
                $name_full = array();



                if ($key == 'visit_type_id' || $key == 'crude'  || $key=='bmi_height' || $key=='bmi_weight'|| $key=='bmi'|| $key=='scr' || $key=='gfr' || $key=='age') {
                    continue;
                }


                $name_full = array_pad(explode('_', $name), 2, null);

                if ($name_full && $name_full[1]) {
                    $isattribute = 1;
                    $questionId = $name_full[0];
                } else {
                    $questionId = $name;
                }


                $newarray = array(
                    'question_id' => $questionId,
                    'is_attribute' => $isattribute,
                    'answer' => $value,
                    'display_status' => 1,
                    'answer_sheet_id' => $miscellaneousAnswerSheetData->id,
                    'created_by'=>Auth::id(),
                    'branch_id'=>Session::get('current_branch'),
                    'is_deleted' => 0,
                    'patient_id'=>Session::get('dtms_pid'),
                    'created_at'=>date('Y-m-d h:i:s')

                );

                $insert_id = FormEngineAnswers::insert($newarray);



            }

            if($input['relation']){

             //            PatientHereditaryDetails::where('patient_id',Session::get('dtms_pid'))->delete();
                $herdetails=array();
                foreach ($input['relation'] as $key=> $value) {



                    if(isset($input['age'][$key])){



                        $herdetails=array(

                            'relation' =>$value,
                            'age' =>$input['age'][$key],
                            'expired_age' =>$input['death_age'][$key],
                            'diabetes' =>$input['diabetes'][$key],
                            'cad' =>$input['cad'][$key],
                            'ckd' =>$input['ckd'][$key],
                            'cvd' =>$input['cvd'][$key],
                            'amputation' =>$input['amputation'][$key],
                            'cancer' =>$input['cancer'][$key],
                            'thyroid' =>$input['thyroid'][$key],
                            'htn' =>$input['htn'][$key],
                            'dyslipidemia' =>$input['dyslipidemia'][$key],
                            'display_status' => 1,
                            'answer_sheet_id' => $miscellaneousAnswerSheetData->id,
                            'created_by'=>Auth::id(),
                            'branch_id'=>Session::get('current_branch'),
                            'is_deleted' => 0,
                            'patient_id'=>Session::get('dtms_pid'),
                            'created_at'=>date('Y-m-d h:i:s')


                        );

                        PatientHereditaryDetails::insert($herdetails);

                    }



                }



            }

            if($input['bmi'] || $input['scr']){

                PatientMiscellaneousDetails::updateOrCreate(
                    [
                        'patient_id'   => Session::get('dtms_pid'),
                    ],
                    [
                        'height' =>$input['bmi_height'],
                        'weight' =>$input['bmi_weight'],
                        'bmi' =>$input['bmi'],
                        'scr'  =>$input['scr'],
                        'gfr' =>$input['gfr'],
                        'display_status' => 1,
                        'created_by'=>Auth::id(),
                        'branch_id'=>Session::get('current_branch'),
                        'is_deleted' => 0,
                        'answer_sheet_id' => $miscellaneousAnswerSheetData->id,
                        'created_at'=>date('Y-m-d h:i:s')
                    ],
                );


            }



            return ['status' => 1, 'message' => "Saved Successfully"];


        }

    }

    public function getHeriditarydetails() {
        $PatientMiscellaneousDetails = PatientHereditaryDetails::where('patient_id',Session::get('dtms_pid'))
            ->orderByDesc('answer_sheet_id')
            ->limit(1)
            ->get();

        echo json_encode($PatientMiscellaneousDetails);



    }

    /**
     * store abroad data in db
     * @param Request $request
     * @return array
     */
    public function saveAbroadData(Request $request){
        $validated = $request->validate([
            'patient_name' => 'required|max:50',
            'phone_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'required|email',
            'address' => 'required',
        ]);
        if($validated)
        {
            $insert_id="";
            $abroadData = [];
            $abroadData = [
                'patient_name' => $request->get('patient_name'),
                'phone_no' => $request->get('phone_no'),
                'email_id' => $request->get('email'),
                'address' => $request->get('address'),
                'patient_id' => Session::get('dtms_pid'),
                'branch_id' => Session::get('current_branch'),
                'display_status' => 1,
                'is_deleted' => 0,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            if ($request->crude==1) {
                $insert_id = PatientAbroadDetail::insert($abroadData);
                if ($insert_id) {
                    return ['status' => 1, 'message' => "Saved Successfully"];
                    // echo 1; //save success
                } else {
                    return ['status' => 3, 'message' => "Failed to save"];

                }
            }

        }else{
            echo 2; // validation error
        }

    }

    /**
     *
     * Abroad data
     * @param Request $request
     */
    public function getAbroadData()
    {
        $cond=array();
        array_push($cond,['patient_id',Session::get('dtms_pid')]);
        array_push($cond,['is_deleted',0]);
        $filldata= PatientAbroadDetail::select('patient_abroad_details.*')
            ->where($cond)
            ->orderByDesc('id')
            ->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);

    }

    /**
     *
     * save patient reminders
     */
    public function savePatientReminders(Request $request)
    {
        $validated = $request->validate([
            'patient_reminder_date' => 'required',
            'patient_reminder_details' => 'required',
            'patient_reminder_remark' => 'required',
        ]);

        if ($validated) {
            $ins = [
                'date'=> date("Y-m-d", strtotime($request->patient_reminder_date)),
                'details' => $request->patient_reminder_details,
                'remarks' => $request->patient_reminder_remark,
                'branch_id' =>Session::get('current_branch'),
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'is_deleted' => 0,
                'patient_id'=>Session::get('dtms_pid'),
            ];
            if ($request->crude==1) {
                $ins['created_at']=Carbon::now();
                $ins['created_by']=Auth::id();
                $cond=array(
                    array('details',$request->patient_reminder_details),
                    array('remarks',$request->patient_reminder_remark),
                    array('date',date("Y-m-d", strtotime($request->patient_reminder_date))),
                    array('is_deleted',0),
                );

                $getId=getAfeild("id","patient_reminders",$cond);

                if($getId)
                {
                    return [ 'status'=>4, 'message'=>"Patient reminder already exist" ];
                }
                else {
                    $insert_id = PatientReminders::insert($ins);
                    if($insert_id) {
                        return [ 'status'=>1, 'message'=>"Saved Successfully" ];
                        // echo 1; //save success
                    }
                    else {
                        return [ 'status'=>3, 'message'=>"Failed to save" ];

                    }
                }

            } else if($request->crude==2){
                $ins['updated_at']=Carbon::now();
                PatientReminders::whereId($request->hid_patient_reminder_id)->update($ins);
                return [ 'status'=>1, 'message'=>"Data updated Successfully" ];
            }
        } else {
            echo 2;
        }
    }

    /**
     *
     * get  patient reminders
     */
    public function getPatientReminders()
    {
        $cond=array();
        array_push($cond,['patient_reminders.patient_id',Session::get('dtms_pid')]);
        array_push($cond,['patient_reminders.is_deleted',0]);
        $filldata= PatientRegistration::select('patient_registration.uhidno','patient_reminders.*','users.name')
            ->leftjoin('patient_reminders','patient_reminders.patient_id','=','patient_registration.id')
            ->leftjoin('users','users.id','=','patient_reminders.created_by')
            ->where($cond)
            ->orderBy('patient_reminders.id','DESC')
            ->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    /**
     *
     * delete  patient reminders
     */
    public function deletePatientReminders(Request $request)
    {
        $id=$request->id;
        if($id)
        {
            $ins=array(
                'is_deleted' =>1,
            );
            $insert_id= PatientReminders::whereId($id)->update($ins);
            if($insert_id) {
                return [ 'status'=>1, 'message'=>"Data deleted successfully" ];
            }
            else{
                return [ 'status'=>3, 'message'=>"Failed to delete data" ];
            }
        }
    }

    public function getPatientGFRdetails(){
        $PatientMiscellaneousDetails = PatientMiscellaneousDetails::
        where('patient_id',Session::get('dtms_pid'))
        ->first();

        echo json_encode($PatientMiscellaneousDetails);

    }

    /**
     * create new visit data
     * @param Request $request
     * @return array
     */
    public function saveNewVisitData(Request $request)
    {
        if ($request->visit_type_id == 1){
            $validated = $request->validate([
                'specialist' => 'required',
            ]);
        } else {
            $validated = $request->validate([
                'visit_type_id' => 'required',
                'new_visit_date' => 'required',
//                'specialist' => 'required',
            ]);
        }

        if($validated)
        {

            $ins_data=array(
                'visit_type_id'=>$request->visit_type_id,
                'specialist_id'=> $request->specialist,
                'visit_date'=>$request->new_visit_date ? date("Y-m-d", strtotime($request->new_visit_date)) :NULL,
                'branch_id'=>Session::get('current_branch'),
                'display_status' => 1,
                'patient_id'=>Session::get('dtms_pid'),
                'is_deleted' => 0,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            );
            $cond=[];
            array_push($cond,['visit_date', '=', $request->new_visit_date]);

            array_push($cond,['patient_id', Session::get('dtms_pid')]);

            $value=getAfeild("id","patient_visits", $cond);
            if($value){
                return [ 'status'=>4, 'message'=>"Already visit for this date is created" ];
            }
            else{
                if($request->crude==1){
                    // insert
                    $insert_id = PatientVisits::insert($ins_data);
                    if($insert_id) {
                        return [ 'status'=>1, 'message'=>"Saved Successfully" ];
                        // echo 1; //save success
                    }
                    else{
                        return [ 'status'=>3, 'message'=>"Failed to save" ];

                    }

                }
            }

        }else{
            echo 2; // validation error
        }

    }

    /**
     * get new visit data
     * @return false|string
     */
    public function getNewVisitData()
    {
        $data=[];
        $visitData = [];
        $visitTypes= VisitTypeMaster::select('visit_type_master.*')->where('is_deleted',0)->where('display_status', 1)->orderBy('id','DESC')->get();
        foreach ($visitTypes as $value) {
            $visitData[$value->id] = [
                'visit_name' => $value->visit_type_name,
                'visit_count' => PatientVisits::select(DB::raw('count(*) as total'))
                    ->where('patient_id', Session::get('dtms_pid'))
                    ->where('visit_type_id', $value->id)->count(),
            ];
        }

        $patientVisitList = PatientVisits::select('patient_visits.visit_type_id', 'patient_visits.id','patient_visits.visit_code', 'patient_visits.visit_date', 'visit_type_master.visit_type_name')
            ->join('visit_type_master','visit_type_master.id','=','patient_visits.visit_type_id')
            ->where('patient_id',Session::get('dtms_pid'))
            ->orderByDesc('patient_visits.visit_date')
            ->get();
        $patientVisitElements = "";
        foreach($patientVisitList as $item) {
            $visitDate =Carbon::parse($item->visit_date)->format('d-m-Y');
            $patientVisitElements.="<tr class='visit-data-tr' onClick='getVistData($item->id)'><td>$item->visit_type_name</td>
                        <td>$item->id</td>
                        <td>$visitDate</td></tr>
                        ";
        }
        $data['visit_list']= $patientVisitElements;
        $data['visit_data']= $visitData;

        return json_encode($data);
    }

    /**
     *
     * delete  abroad data
     */
    public function deleteAbroadData(Request $request)
    {
        $id=$request->id;
        if($id)
        {
            $ins=array(
                'is_deleted' =>1,
            );
            $insert_id= PatientAbroadDetail::whereId($id)->update($ins);
            if($insert_id) {
                return [ 'status'=>1, 'message'=>"Data deleted successfully" ];
            }
            else{
                return [ 'status'=>3, 'message'=>"Failed to delete data" ];
            }
        }
    }

    /**
     *
     * delete  diagnosis data
     */
    public function deleteDiagnosisData(Request $request)
    {
        $id=$request->id;
        if($id)
        {
            $ins=array(
                'is_deleted' =>1,
            );
            $insert_id= PatientDiagnosis::whereId($id)->update($ins);
            if($insert_id) {
                return [ 'status'=>1, 'message'=>"Data deleted successfully" ];
            }
            else{
                return [ 'status'=>3, 'message'=>"Failed to delete data" ];
            }
        }
    }

    /**
     *
     * delete  complication data
     */
    public function deleteComplicationData(Request $request)
    {
        $id=$request->id;
        if($id)
        {
            $ins=array(
                'is_deleted' =>1,
            );
            $insert_id= PatientComplication::whereId($id)->update($ins);
            if($insert_id) {
                return [ 'status'=>1, 'message'=>"Data deleted successfully" ];
            }
            else{
                return [ 'status'=>3, 'message'=>"Failed to delete data" ];
            }
        }
    }

    /**
     *
     * delete  vaccination data
     */
    public function deleteVaccinationData(Request $request)
    {
        $id=$request->id;
        if($id)
        {
            $ins=array(
                'is_deleted' =>1,
            );
            $insert_id= PatientVaccination::whereId($id)->update($ins);
            if($insert_id) {
                return [ 'status'=>1, 'message'=>"Data deleted successfully" ];
            }
            else{
                return [ 'status'=>3, 'message'=>"Failed to delete data" ];
            }
        }
    }

    /**
     * update visit data in db
     * @param Request $request
     * @return array
     */
    public function updateVisitListData(Request $request)
    {
        $validated = $request->validate([
            'visit_type_id' => 'required',
            'visit_date' => 'required',
            'specialist' => 'required',
        ]);
        if($validated)
        {
            $previousVisitTypeId = PatientVisits::where('id', $request->hid_visit_id)->select('visit_type_id')->first();
            $visitData = array(
                'patient_id' =>Session::get('dtms_pid'),
                'id'=> $request->hid_visit_id,
                'visit_type_id'=> $request->visit_type_id,
                'visit_date'=> date("Y-m-d", strtotime($request->visit_date)),
                'specialist_id'=>$request->specialist,
                'is_edited'=>1,
                'old_visit_type_id'=>$previousVisitTypeId->visit_type_id,
            );
            if($request->crude==2){
                $visitData['updated_at'] = Carbon::now();
                $visitData['updated_by']=Auth::id();
                $insert_id = PatientVisits::where('id',$request->hid_visit_id)->update($visitData);
                if($insert_id) {
                    return [ 'status'=>1, 'message'=>"Data updated Successfully" ];
                }
                else{
                    return [ 'status'=>3, 'message'=>"Failed to update data" ];
                }

            }
        }else{
            echo 2; // validation error
        }
    }

    /**
     * prescription document
     * @param Request $request
     * @return mixed
     */
    public function prescriptionDocument(Request $request)
    {
        $data=[];
        $patientData = PatientRegistration::select('patient_registration.name','patient_registration.age','patient_registration.dob',
            'patient_registration.uhidno','patient_registration.gender','patient_registration.address','patient_registration.mobile_number',
            'specialist_master.specialist_name')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_registration.specialist_id')
            ->where('patient_registration.id', Session::get('dtms_pid'))
            ->first();
        $prescriptionData = PatientPrescriptions::select('medicine_master.medicine_name', 'tablet_type_master.tablet_type_name',
            'patient_prescriptions.*')
            ->leftjoin('medicine_master', 'medicine_master.id', '=', 'patient_prescriptions.medicine_id')
            ->leftjoin('tablet_type_master', 'tablet_type_master.id', '=', 'medicine_master.tablet_type_id')
            ->where('visit_id', Session::get('dtms_visitid'))
            ->where('patient_id', Session::get('dtms_pid'))
            ->get();
        $data['patient_data']= $patientData;
        $data['prescriptionData'] = $prescriptionData;
        $pdf = \PDF::loadView('webpanel.prescription-print',[],['data' => $data],[
            'format'=>[210, 297]
        ]);

        return $pdf->stream('filename.pdf');
    }

    /**
     *
     * get  test results
     */
    public function getAllTestResults($pid)
    {
        $patientId =base64_decode($pid);
        $patientData = PatientRegistration::where('id', $patientId)->first();
        $testResults = TestResults::where('PatientId', $patientId)
            ->select('test_results.TestId', 'test_master.TestName')
            ->leftjoin('test_master', 'test_master.TestId', '=', 'test_results.TestId')
            ->distinct('test_results.TestId')
            ->get();
            $data =[];
            $data['PageName']="Chart ";
            $data['patient_details']=$patientData;
            $data['test_result_data']=$testResults;
            return Parent::page_maker('webpanel.dtms.view-test-result',$data);
    }

    /**
     * get test filter data
     * @return false|string
     */
    public function getTestFilterData(Request $request)
    {
        $data = array();
        $testResult= TestResults::where('TestId',$request->testId)
            ->where('PatientId',$request->patientId)
            ->select('test_results.id','test_results.ResultValue','test_results.date(created_at)', 'is_outside_lab')
            ->distinct()
            ->orderBy('id','DESC')
            ->get();

        $testResultDateElements = [];
        $testResultValueElements = [];
        $isOutSideLabElements = [];
        $status= [];
        foreach($testResult as $item) {
            if ($item->is_outside_lab ==1) {
                $isOutSideLab = "rgb(105, 103, 235)";
            } else {
                $isOutSideLab = "rgb(255, 182, 193)";
            }
         //   $testDate = date("d-m-Y h:i:s", strtotime($item->created_at));
            $testDate = date("d-m-Y", strtotime($item->created_at));
            $testResultDate = $testDate ;
            if (is_numeric($item->ResultValue)) {
                $testResultValue = $item->ResultValue;
                $status=1;
            } else {
                $testResultValue =0;
                $status=0;
            }

            array_push($testResultDateElements,$testResultDate);
            array_push($testResultValueElements,$testResultValue);
            array_push($isOutSideLabElements,$isOutSideLab);
        }
        $data['test_result_date']=$testResultDateElements;
        $data['test_result_value']=$testResultValueElements;
        $data['status_result']=$status;
        $data['is_outside_lab']=$isOutSideLabElements;
        return json_encode( $data);

    }

    /**
     * search test name
     * @param Request $request
     */
    public function searchTestNames(Request $request)
    {
        $data=array();
        if($request->searchTerm){

            $cond=[];
            array_push($cond,['show_test_in_targets', '=', 1]);

            array_push($cond,['TestName', 'ilike', '%' . $request->searchTerm . '%']);


            $filldata= TestMaster::where($cond)->limit(25)->get();


            if($filldata){
                foreach ($filldata as $key => $value) {

                    $data[]=array(
                        'id'=>$value['id'],
                        'text'=>$value['TestName'],
                    );
                }
            }

        }
        echo json_encode($data);

    }

    /**
     * store target data in db
     * @param Request $request
     * @return array
     */
    public function saveTargetData(Request $request)
    {
        $validated = $request->validate([
            'search_test_name' => 'required',
            'target_value' => 'required',
            'present_value' => 'required',
        ]);
        if($validated)
        {
            $insert_id="";
            $targetData = [];
            $targetData = [
                'patient_id' => Session::get('dtms_pid'),
                'test_id' => $request->get('search_test_name'),
                'visit_id' => Session::get('dtms_visitid'),
                'target_value' => $request->get('target_value'),
                'present_value' => $request->get('present_value'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

                $insert_id = PatientTarget::insert($targetData);
            if ($insert_id) {
                $targetMainArray=$this->getvisitbasedTarget(Session::get('dtms_visitid'));
                $patientTargetDetails = PatientTargetDetail::where('visit_id', Session::get('dtms_visitid'))->first();
                $patientTargetData = [
                    'weight_target' => isset($patientTargetDetails->weight_target) ? $patientTargetDetails->weight_target : '' ,
                    'weight_present'  =>isset($patientTargetDetails->weight_present) ? $patientTargetDetails->weight_present : '',
                    'action_plan'=> isset($patientTargetDetails->action_plan ) ? $patientTargetDetails->action_plan : '',
                    //'fibro_scan'=> isset($patientTargetDetails->fibro_scan ) ? $patientTargetDetails->fibro_scan : '',
                ];
                return ['status' => 1, 'patient_test_targets' => $targetMainArray,
                    'patient_target_details' => $patientTargetData,
                    'message' => "Saved Successfully"];
                // echo 1; //save success
            } else {
                return ['status' => 3, 'message' => "Failed to save"];

            }

        }else{
            echo 2; // validation error
        }

    }

    /**
     *
     * diet history answer sheet list
     * @param Request $request
     */
    public function getDietHistoryAnswerSheetData()
    {
        $cond=array();
        array_push($cond,['patient_answer_sheet.patient_id',Session::get('dtms_pid')]);
        array_push($cond,['patient_answer_sheet.question_type_id',1]);
        $filldata= PatientAnswerSheet::select('patient_answer_sheet.*', 'question_types.type_name')
            ->join('question_types','question_types.id','=','patient_answer_sheet.question_type_id')
            ->where($cond)
            ->orderByDesc('patient_answer_sheet.id')
            ->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);

    }

    /**
     * diet history print data
     * @param Request $request
     * @return mixed
     */
    public function dietHistoryPrintData(Request $request)
    {
        $data=[];
        $patientData = PatientRegistration::select('patient_registration.name','patient_registration.age','patient_registration.dob',
            'patient_registration.uhidno','patient_registration.gender','patient_registration.address','patient_registration.mobile_number',
             'specialist_master.specialist_name', 'patient_dietplan.created_at')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_registration.specialist_id')
            ->leftjoin('patient_answer_sheet','patient_answer_sheet.patient_id','=','patient_registration.id')
            ->leftjoin('patient_dietplan','patient_dietplan.patient_id','=','patient_registration.id')
            ->where('patient_registration.id', $request->patient_id)
            ->where('patient_answer_sheet.id', $request->patient_diet_answer_sheet_id)
            ->first();
        $patientDate= Carbon::parse($patientData->created_at)->format('d-m-Y');
        $questionData= DietQuestionMaster::with('sub_question')->orderBy('order_no')->where('display_status',1)->where('type',1)->where('is_deleted',0)->get();
        $dietHistory= PatientDietPlan::select('patient_dietplan.*')
            ->where('patient_dietplan.patient_id',$request->patient_id)
            ->where('patient_dietplan.answer_sheet_id', $request->patient_diet_answer_sheet_id)
            ->get()->toArray();

        $data['patient_data']= $patientData;
        $data['patient_date']= $patientDate;
        $data['question_data']= $questionData;
        $data['diet_history']= $dietHistory;

        $pdf = \PDF::loadView('webpanel.diet-history-print',compact('data'),[
            'format'=>[190, 236]
        ]);

        return  $pdf->stream('filename.pdf');

    }

    /**
     *
     * pep answer sheet list
     * @param Request $request
     */
    public function getPepAnswerSheetData()
    {
        $cond=array();
        array_push($cond,['patient_answer_sheet.patient_id',Session::get('dtms_pid')]);
        array_push($cond,['patient_answer_sheet.question_type_id',2]);
        $filldata= PatientAnswerSheet::select('patient_answer_sheet.*', 'question_types.type_name')
            ->join('question_types','question_types.id','=','patient_answer_sheet.question_type_id')
            ->where($cond)
            ->orderByDesc('patient_answer_sheet.id')
            ->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);

    }

    /**
     * pep history print data
     * @param Request $request
     * @return mixed
     */
    public function pepPrintData(Request $request)
    {
        $data=[];
        $patientData = PatientRegistration::select('patient_registration.name','patient_registration.age','patient_registration.dob',
            'patient_registration.uhidno','patient_registration.gender','patient_registration.address','patient_registration.mobile_number',
            'specialist_master.specialist_name', 'patient_pep.created_at')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_registration.specialist_id')
            ->leftjoin('patient_answer_sheet','patient_answer_sheet.patient_id','=','patient_registration.id')
            ->leftjoin('patient_pep','patient_pep.patient_id','=','patient_registration.id')
            ->where('patient_registration.id', $request->patient_id)
            ->where('patient_answer_sheet.id', $request->patient_pep_answer_sheet_id)
            ->first();
        $patientDate = Carbon::parse($patientData->created_at)->format('d-m-Y');
        $pepQuestionsData= DietQuestionMaster::with('sub_question')->orderBy('order_no')->where('display_status',1)->where('type',2)->where('is_deleted',0)->get();
        $pepHistory= PatientPep::select('patient_pep.*')
            ->where('patient_pep.patient_id', $request->patient_id)
            ->where('patient_pep.answer_sheet_id', $request->patient_pep_answer_sheet_id)
            ->get()->toArray();


        $data['patient_data']= $patientData;
        $data['patient_date']= $patientDate;
        $data['pep_questions_data']= $pepQuestionsData;
        $data['pep_history']= $pepHistory;

        $pdf = \PDF::loadView('webpanel.pep-history-print',compact('data'),[
            'format'=>[190, 236]
        ]);

        return  $pdf->stream('filename.pdf');

    }

    /**
     *
     * get miscellaneous data
     * @param Request $request
     */
    public function getMiscellaneousData()
    {
        $cond=array();
        array_push($cond,['patient_answer_sheet.patient_id',Session::get('dtms_pid')]);
        array_push($cond,['patient_answer_sheet.question_type_id',3]);
        $filldata= PatientAnswerSheet::select('patient_answer_sheet.*', 'question_types.type_name')
            ->join('question_types','question_types.id','=','patient_answer_sheet.question_type_id')
            ->where($cond)
            ->orderByDesc('patient_answer_sheet.id')
            ->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);

    }

    /**
     * miscellaneous print data
     * @param Request $request
     * @return mixed
     */
    public function miscellaneousPrintData(Request $request)
    {
        $data=[];
        $patientData = PatientRegistration::select('patient_registration.name','patient_registration.age','patient_registration.dob',
            'patient_registration.uhidno','patient_registration.gender','patient_registration.address','patient_registration.mobile_number',
            'formengine_answers.created_at','specialist_master.specialist_name')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_registration.specialist_id')
            ->leftjoin('patient_answer_sheet','patient_answer_sheet.patient_id','=','patient_registration.id')
            ->leftjoin('formengine_answers','formengine_answers.patient_id','=','patient_registration.id')
            ->where('patient_registration.id', $request->patient_id)
            ->where('patient_answer_sheet.id', $request->patient_miscellaneous_answer_sheet_id)
            ->first();
        $patientDate =Carbon::parse($patientData->created_at)->format('d-m-Y');
        $PatientMiscellaneousDetails = PatientHereditaryDetails::where('patient_id',$request->patient_id)
            ->where('answer_sheet_id', $request->patient_miscellaneous_answer_sheet_id)
            ->get();

        $patientMiscellaneousData = PatientMiscellaneousDetails::where('patient_id', $request->patient_id)
            ->where('answer_sheet_id', $request->patient_miscellaneous_answer_sheet_id)
            ->first();
        $questionData= FormEngineQuestions::select('formengine_questions.*','formengine_types.type','formengine_types.label as typelabel')
            ->join('formengine_types','formengine_types.id','=','formengine_questions.type_id')
            ->where([['is_deleted',0]])
            ->get();

        // foreach ($questionData as $key => $value) {
        //     $data['question'][$key] = [
        //         'question_name' => $value->question,
        //         'questionid' => $value->id,
        //         'type' => $value->type,
        //         'answer' => '',
        //     ];
        //     $questionid = $value->id;
        //     $patientAnswerSheet = PatientAnswerSheet::where('patient_id', Session::get('dtms_pid'))->orderByDesc('id')->first();
        //     $answerData = FormEngineAnswers::where('question_id', $questionid)
        //         ->where('patient_id', Session::get('dtms_pid'))
        //         ->where('answer_sheet_id', $patientAnswerSheet->id)
        //         ->orderByDesc('id')
        //         ->first();
        //     if ($answerData) {
        //         if ($value->type_id == '8') {
        //             $data['question'][$key]['answer'] = $answerData->answer;
        //         } else {
        //             $checkOptionsExists = FormEngineAttributes::select('formengine_attributes.*', 'formengine_types.type')
        //                 ->JOIN('formengine_types', 'formengine_types.id', '=', 'formengine_attributes.type_id')
        //                 ->where([['formengine_attributes.type_id', $value->type_id], ['question_id', $questionid], ['attr_name', 'option']])->get();
        //             $answerHtml = '';

        //             if (sizeof($checkOptionsExists)) {
        //                 foreach ($checkOptionsExists as $key => $value1) {
        //                     $attrid = $value1->id;
        //                     if ($value->type_id == '5') {
        //                         if ($attrid == $answerData->answer) {
        //                             $answerHtml .= $value1->attr_value;
        //                         }
        //                     } else if ($value->type_id == '4' || $value->type_id == '3') {
        //                         // for checkbox or radio we need to set value attr as formengine_attribute id
        //                         if ($value->type_id == '4') { // checkbox
        //                             $values_checkbox = explode('|', $answerData->answer);
        //                             if (in_array($attrid, $values_checkbox)) {
        //                                 $answerHtml .= $value1->attr_value . ', ';
        //                             }
        //                         } else {
        //                             // radio
        //                             if ($attrid == $answerData->answer) {
        //                                 $answerHtml .=  $value1->attr_value . ', ';
        //                             }
        //                         }
        //                     } else {
        //                         $answerHtml .= $value1->attr_value;
        //                     }
        //                 }
        //                 $data['question'][$key]['answer'] = $answerHtml;
        //             } else {
        //                 $data['question'][$key]['answer'] = $answerData->answer;
        //             }
        //         }

        //     }

        // }
        $resArray=FormEngineQuestions::select('formengine_questions.*','formengine_types.type')
        ->join('formengine_types','formengine_types.id','=','formengine_questions.type_id')->where([['is_deleted',0]])->get();


        $data_list='';
        $data['option_list'] ='';
        $data['answers'] ='';
        $data=array();
        $i=1;
        foreach ($resArray as $key => $value) {
            $answer= '';
            $data['question'][]=$value->question;//print_r($data['question']);exit;

            $data['questionid'][]=$value->id;
            $data['type'][]=$value->type;
            $questionid=$value->id;

            $checkOptionsExists =FormEngineAttributes::select('formengine_attributes.*','formengine_types.type')
                                ->JOIN('formengine_types','formengine_types.id','=','formengine_attributes.type_id')
                                ->where([['formengine_attributes.type_id',$value->type_id],['question_id',$questionid],['attr_name','option']])->get();

            $questionid=$value->id;
            $data_list='';
            $patientAnswerSheet = PatientAnswerSheet::where('patient_id',Session::get('dtms_pid'))->orderByDesc('id')->first();
            if (! is_null($patientAnswerSheet)){
                $answersArray = FormEngineAnswers::where('question_id',$questionid)
                    ->where('patient_id',Session::get('dtms_pid'))
                    ->where('answer_sheet_id',$patientAnswerSheet->id)
                    ->orderByDesc('id')
                    ->first();
            }
            else{
                $answersArray=null;
            }

            if($answersArray){
                $answer= $answersArray['answer'];
            }

            $data['answers'][]=$answer;
            if($value->type_id == '8') {  // boolean
                $checked= "";
                $checked2= "";
                if($answer == 'Yes' ){
                    $checked= "checked";
                }else if($answer == 'No'){
                    $checked2= "checked";
                }
                $data_list="<div class='fieldgroup'><input type='radio' name='$questionid' class='$questionid inputtext'   value='Yes' $checked data-names='Yes'> Yes <input type='radio' class='$questionid inputtext' name='$questionid' value='No' $checked2 data-names='No'> No   </div>";

                $data['option_list'][] = $data_list;

                continue;
            }
            $i++;

                    if(sizeof($checkOptionsExists)){
                        if($value->type_id == '5'){
                            $data_list="<select class='form-control inputtext' id='$questionid' name='".$questionid."_1'>";
                        }
                        foreach ($checkOptionsExists as $key => $value1) {
                            $typeName=$value1->type;
                            $attrid=$value1->id;
                            if($value->type_id == '5'){
                                $selected ="";
                                if($attrid == $answer){
                                    $selected ="selected";
                                }
                                $data_list.="<option value='$attrid' $selected>$value1->attr_value</option>";
                            }else if($value->type_id == '4' || $value->type_id == '3'){
                                // for checkbox or radio we need to set value attr as formengine_attribute id
                                if($value->type_id == '4') // checkbox
                                {
                                    $values_checkbox= explode('|',$answer);
                                    $checked_checkbox ="";
                                    if(in_array($attrid,$values_checkbox)){
                                        $checked_checkbox ="checked";
                                    }
                                    $data_list.= "<div class='fieldgroup'>
                                    <input type='$value1->type' name='".$questionid."_1' value='$attrid' id='$questionid' $checked_checkbox  class='inputtext' data-names='$value1->attr_value'>&nbsp; $value1->attr_value</div>";

                                }else{
                                    // radio
                                    $checked_radio ="";
                                    if($attrid == $answer){
                                        $checked_radio ="checked";
                                    }
                                    $data_list.= "<div class='fieldgroup'><input type='$value1->type' name='".$questionid."_1' value='$attrid' data-names='$value1->attr_value' id='$questionid' $checked_radio class='inputtext' >&nbsp;&nbsp;&nbsp;$value1->attr_value</div>";

                                }
                            // $data_list.= "&nbsp; ";
                            }else{
                                // not necessay
                                $data_list.= "<input type='$value1->type' name='$questionid'  id='$questionid' class='inputtext'>&nbsp;$value1->attr_value<br>";
                            }
                        }
                        if($value->type_id == '5'){
                            $data_list.="</select>";
                        }
                    // echo $data_list;
                        $data['option_list'][] = $data_list;
                    }else{
                        $data['option_list'][] ="";
                    }
        }

        $data['patient_data']= $patientData;
        $data['patient_date']= $patientDate;
        $data['patient_miscellaneous_details']= $PatientMiscellaneousDetails;
        $data['patient_miscellaneous']= $patientMiscellaneousData;
        $data['question_data']= $questionData;

      //  return view('webpanel.miscellaneous-print',compact('data'));

        $pdf = \PDF::loadView('webpanel.miscellaneous-print',compact('data'),[
            'format'=>[190, 236]
        ]);

        return  $pdf->stream('filename.pdf');

    }

    /**
     * get image alert
     * @return false|string
     */
    public function getImageAlert(Request $request)
    {
        $gallery=PatientGallery::where('patient_id',$request->patient_id)->orderBy('id','DESC')->first();
            if (is_null($gallery)) {
                return [ 'status'=>1, 'message'=>"Please update your profile image" ];
            }   else {
                return [ 'status'=>3, 'message'=>"" ];
            }

        }


    /**
     * test result chart list
     */
    public function getTestResultChart($pid)
    {
        $patientId =base64_decode($pid);
        $patientData = PatientRegistration::where('id', $patientId)->first();
        $testResultName=TestResults::where('test_results.PatientId',$patientId)
            ->select('test_results.id','test_results.ResultValue','test_results.created_at', 'test_master.TestName','test_master.TestId', 'test_results.is_outside_lab')
            ->leftjoin('test_master', 'test_master.TestId', '=', 'test_results.TestId')
            ->get();

        $testName = [];
        $testDate = [];
        foreach ($testResultName as $item) {
            $testName[$item->TestId]['testName'] = $item->TestName;
//            $testName[$item->TestId]['is_outside_lab'] = $item->is_outside_lab;

            if (is_numeric($item->ResultValue)) {
                if (isset($testName[$item->TestId]['value'][date("d-m-Y", strtotime($item->created_at))])) {
                    $testName[$item->TestId]['value'][date("d-m-Y", strtotime($item->created_at))]['result_value'][] = $item->ResultValue;
                    $testName[$item->TestId]['value'][date("d-m-Y", strtotime($item->created_at))]['is_outside_lab'] = $item->is_outside_lab;
                } else {
                    $testName[$item->TestId]['value'][date("d-m-Y", strtotime($item->created_at))]['result_value'] = [$item->ResultValue];
                    $testName[$item->TestId]['value'][date("d-m-Y", strtotime($item->created_at))]['is_outside_lab'] = $item->is_outside_lab;

                }
            } else {
                $testName[$item->TestId]['value'][date("d-m-Y", strtotime($item->created_at))]['result_value'] = ['--'];
                $testName[$item->TestId]['value'][date("d-m-Y", strtotime($item->created_at))]['is_outside_lab'] = '';

            }
            $testDate[date("d-m-Y", strtotime($item->created_at))] = date("d-m-Y", strtotime($item->created_at));
        }
        $testNameFormatted = $testName;
        foreach ($testName as $key => $test) {
            $diff = array_diff($testDate, array_keys($test['value']));
            foreach ($diff as $diffDate) {
                $testNameFormatted[$key]['value'][$diffDate]['result_value'] = ['--'];
                $testNameFormatted[$key]['value'][$diffDate]['is_outside_lab'] = '';
            }
            ksort($testNameFormatted[$key]['value']);
        }
        ksort($testDate);
        return Parent::page_maker('webpanel.dtms.test-result-chart', [
            'PageName' => 'Visit Chart',
            'testDate' => $testDate,
            'testName' => $testNameFormatted,
            'patient_details' =>$patientData
        ]);
    }

    /**
     *
     * old medicine data
     * @param Request $request
     */
    public function getAllOldMedicineData(Request $request)
    {
        $cond=array();
        array_push($cond,['old_tele_usual_medicines.patient_id',$request->patientId]);
        array_push($cond,['old_tele_usual_medicines.tele_medicinerecord_id',$request->visitId]);
        $patientOldMedicineData = Tele_usual_medicines::select('id', 'tablet_type', 'tablet_name', 'qty', 'dose', 'remark')
            ->where($cond)
            ->orderByDesc('old_tele_usual_medicines.id')
            ->get();
        $output = array(
            "recordsTotal" => count($patientOldMedicineData),
            "recordsFiltered" => count($patientOldMedicineData),
            "data" => $patientOldMedicineData
        );
        echo json_encode($output);
    }


    /**
     * diet history old print data
     * @param Request $request
     * @return mixed
     */
    public function viewOldDietHistory(Request $request)
    {
        $data=[];
        $patientData = PatientRegistration::select('patient_registration.name','patient_registration.age','patient_registration.dob',
            'patient_registration.uhidno','patient_registration.gender','patient_registration.address','patient_registration.mobile_number',
            'specialist_master.specialist_name')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_registration.specialist_id')
            ->where('patient_registration.id', Session::get('dtms_pid'))
            ->first();
        $dietHistory =  OldTeleMedicineAnswerSheet::select('old_tele_medicine_answersheets.student_id','old_tele_medicine_answersheets.questions_id',
            'old_tele_medicine_answersheets.answer', 'old_tele_medicine_questions.question')
            ->leftjoin('old_tele_medicine_questions','old_tele_medicine_questions.id','=','old_tele_medicine_answersheets.questions_id')
            ->where('old_tele_medicine_answersheets.student_id', Session::get('dtms_pid'))
            ->where('old_tele_medicine_questions.paper_nameid',2)
            ->get();
        $patientDate= Carbon::parse($patientData->created_at)->format('d-m-Y');
        $data['patient_data']= $patientData;
        $data['patient_date']= $patientDate;
        $data['diet_history']= $dietHistory;

        $pdf = \PDF::loadView('webpanel.diet-history-old-print',compact('data'),[
            'format'=>[190, 236]
        ]);

        return  $pdf->stream('filename.pdf');

    }

    /**
     * pep history old print data
     * @param Request $request
     * @return mixed
     */
    public function viewOldPepHistory(Request $request)
    {
        $data=[];
        $patientData = PatientRegistration::select('patient_registration.name','patient_registration.age','patient_registration.dob',
            'patient_registration.uhidno','patient_registration.gender','patient_registration.address','patient_registration.mobile_number',
            'specialist_master.specialist_name')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_registration.specialist_id')
            ->where('patient_registration.id', Session::get('dtms_pid'))
            ->first();
        $pepHistory =  OldTeleMedicineAnswerSheet::select('old_tele_medicine_answersheets.student_id','old_tele_medicine_answersheets.questions_id',
            'old_tele_medicine_answersheets.answer', 'old_tele_medicine_questions.question')
            ->leftjoin('old_tele_medicine_questions','old_tele_medicine_questions.id','=','old_tele_medicine_answersheets.questions_id')
            ->where('old_tele_medicine_answersheets.student_id', Session::get('dtms_pid'))
            ->where('old_tele_medicine_questions.paper_nameid',1)
            ->get();
        $patientDate= Carbon::parse($patientData->created_at)->format('d-m-Y');

        $data['patient_data']= $patientData;
        $data['patient_date']= $patientDate;
        $data['pep_history']= $pepHistory;

        $pdf = \PDF::loadView('webpanel.pep-history-old-print',compact('data'),[
            'format'=>[190, 236]
        ]);

        return  $pdf->stream('filename.pdf');

    }

    // To get test details based on visit
    public function getAllVisitById(Request $request)
    {
        $visit_id=$request->visitId;
        if($visit_id>0)
        {

            $testData = DB::table('test_results as tr')
            ->join('test_master as tm', 'tm.TestId', '=', 'tr.TestId')
            ->select('tr.ResultValue','tr.TestId','tm.TestName','tr.is_outside_lab')
            ->where('tr.visit_id',$visit_id)
            ->get();
            $html="";
            if($testData)
            {
                $sl=0;
                foreach($testData as $test)
                {
                    $sl++;
                    $lab=$test->is_outside_lab;
                    if($lab==1) $class="insideLab";
                    else if($lab==2 )  $class="outsideLab";
                    else $class="";

                    $html.='
                    <tr class="'.$class.'">
                    <td>'.$sl.'</td>
                    <td>'.$test->TestName.'</td>
                    <td>'.$test->ResultValue.'</td>
                    </tr>';
                }

           }
            else{
               $html= "<tr>
                <td colspan='3'>
                No data Found
                </td></tr>";
            }
            return  $html;
        }
    }

}

