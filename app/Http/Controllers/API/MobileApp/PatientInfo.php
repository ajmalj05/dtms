<?php
namespace App\Http\Controllers\API\MobileApp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PatientRegistration;
use App\Models\PatientDocument;
use App\Models\MobileApp\OrderMedicine;
use App\Models\MobileApp\Purchase_Product;

use App\Models\App_PatientRegistration;
use App\Models\MobileApp\User_Patients_List;
use App\Models\MobileApp\AllSalutations;
use App\Models\MobileApp\Gender;
use App\Models\MobileApp\Centers;
use App\Models\MobileApp\BloodGlucose;
use App\Models\MobileApp\BpStatus;
use App\Models\TestResults;
use App\Models\Remainder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\PatientGallery;

use Carbon\Carbon;
use App\Models\PatientAppointment;

use App\Services\SMSGatewayService;

class PatientInfo extends Controller
{
    protected $smsGatewayService;

    public function __construct(SMSGatewayService $smsGatewayService)
    {
        // Initialize the SMS Gateway service with your credentials
        $this->smsGatewayService = new SMSGatewayService();
    }

    public function getPatientInfo(Request $request){
        try{

            $otp=rand(100000, 999999);
            if($request->uhid){
                $UserData=PatientRegistration::select('id','mobile_number')->where('uhidno', $request->uhid)
                ->orWhere('mobile_number', $request->uhid)->first();
              
                if($UserData!=null){
                    if($UserData->mobile_number && $UserData->mobile_number!=""){
                        $otpverify=PatientRegistration::where('uhidno', $request->uhid)
                        ->orWhere('mobile_number', $request->uhid)->update(['app_otp'=> $otp]);
        
                    if($otpverify){

                        //sendsms
                        $message="Your One Time Password (OTP) for login at JDC Care is $otp.Do not share your OTP with others. Thank You! JDC Team visit www.jothydev.net, www.sugarcart.in";
                        $templateId="1207169088755251452";
                        $out = $this->smsGatewayService->sendSMS($UserData->mobile_number, $message,$templateId);
        

                        $getotp=array('otp'=>strval($otp));

                        $response['status'] = 100;
                        $response['message'] = 'success';
                        $response['result']= $getotp;
                        return response()->json($response,200);
                    }
        
                    }else{
                        $response['status'] = 101;
                        $response['message'] = 'There is No Mobile Number';
                        $response['result'] = null;
                        return response()->json($response,200);
        
                    }
                } else{
                    $response['status'] = 101;
                    $response['message'] = 'Invalid UHID/Phone Number';
                    $response['result'] = null;
                    return response()->json($response,200);
    
                }
                
    
           
        }

        }
        catch (\Exception $e)
        {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response,500);
        }

    }
   public function  patientOtpVerify(Request $request){
        try{

            $uhId=$request->uhid;
            $userOtp=$request->otp;

                $patientData=PatientRegistration::select('id','uhidno','name','last_name','mobile_number','address','email','whatsapp_number','dob','salutation_id','app_otp','gender')
                ->where('uhidno',$uhId)->orWhere('mobile_number', $uhId)->orderBy('id','DESC')->first();
                if($patientData->salutation_id>0){
                    $cond=array(
                        array('id',$patientData->salutation_id),
                     );

                    $specialistId=getAfeild("salutation_name","salutation_master",$cond);
                 }
                 if($patientData->gender =='m '){
                    $gender='Male';
                 }else if($patientData->gender =='f '){
                    $gender='Female';
                }
                 else{
                    $gender='Other';
                 }
                if($patientData!=null){
                $dbOtp=$patientData->app_otp;
                if($dbOtp==$userOtp){

                  $patientDetails=array(
                    "patient_id"=>trim($patientData['id']),
                    'uhidno'=> trim( $patientData['uhidno']),
                    'name'=> trim( $patientData['name']),
                    'last_name'=> trim( $patientData['last_name']),
                    'mobile_number'=> trim( $patientData['mobile_number']),
                    'address'=> trim( $patientData['address']),
                    'email'=>trim( $patientData['email']),
                    'whatsapp_number'=> trim( $patientData['whatsapp_number']),
                    'dob'=> $patientData['dob']==""?null:trim( $patientData['dob']),
                    'salutation_id'=> trim( $patientData['salutation_id']),
                    'app_otp'=>trim( $patientData['app_otp']),
                    'salutation_value'=>$patientData->salutation_id>0?trim( $specialistId):null,
                    'gender_value'=>$gender,

                  );

                    $response['status'] = 100;
                    $response['message'] = 'success';
                    $response['result']=   $patientDetails;
                    return response()->json($response);
                }else{
                    $response['status'] = 101;
                    $response['message'] = 'invalid OTP';
                    $response['result'] = null;
                    return response()->json($response);
                }
                }
            else{
                $response['status'] = 101;
                $response['message'] = 'invalid uhid';
                $response['result'] = null;

                return response()->json($response);

            }

        }
        catch (\Exception $e)
        {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response,500);
        }

    }
    public function patientRegister(Request $request){
            try{

                if($request->date_of_birth){

                    $date = date("Y-m-d", strtotime($request->date_of_birth));
                }
            $data=array(
                'salutation'=> $request->salutation_value,
                'first_name'=> $request->first_name,
                'last_name'=>$request->last_name,
                'mobile_number'=>$request->mobile_number,
                'perm_address'=> $request->perm_address,
                'email'=> $request->email,
                'relationship_id'=> $request->relationship_id,
                'whatsapp_number'=> $request->whatsapp_number,
                'date_of_birth'=>$date,
                'use_id'=>$request->user_id,
                'gender_id'=>$request->gender_id,
                'branch_id'=>$request->branch_id
            );

            if($data){
                $patientData=App_PatientRegistration::create($data);
                $idData = App_PatientRegistration::select('id')->latest()->first();
                $idData=array('patient_id'=>$idData->id);
                $response['status'] = 100;
                $response['message'] = 'Success';
                $response['result'] = $idData;
                return response()->json($response,200);


            }
         }
        catch (\Exception $e)
        {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response,500);
        }
     }
    public function PatientUserMapping(Request $request){
        try{
            $validated=$request->validate([
                'user_id' => 'required',
                'patient_id' => 'required',

            ]);

            if($validated){
                $alreadyRegistered=User_Patients_List::select('patient_id')->where('patient_id',$request->patient_id)->first();
                if(!isset($alreadyRegistered->patient_id)){
                $usermapping=array(
                    'app_userid'=>$request->user_id,
                    'patient_id'=>$request->patient_id,
                    );

                $useyData=User_Patients_List::insert($usermapping);
                $response['status'] = 100;
                $response['message'] = 'Success';
                $response['result'] = null;
                return response()->json($response,200);
                }else{
                    $response['status'] = 101;
                    $response['message'] = 'Already Registered';
                    $response['result'] = null;
                    return response()->json($response,200);

                }
            }
            else{
                $response['status'] = 101;
                $response['message'] = 'failed';
                $response['result'] = null;
                return response()->json($response,200);

            }


     }
    catch (\Exception $e)
    {
        $response['status'] = 500;
        $response['message'] =  $e;
        return response()->json($response,500);
    }
}





    /////////bookAppointment
    public function bookAppointment(Request $request)
    {

        try{
            $appointment_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
            $patient_id=$request->patient_id;
            $patientData=PatientRegistration::select('uhidno','name','last_name','mobile_number','address',
            'email','whatsapp_number','dob','salutation_id','app_otp','gender')->where('id', $patient_id)->first();
            // $patientTime=PatientAppointment::select('patient_id')->where('appointment_time',$request->time)
            // ->where('appointment_date',$appointment_date)->where('is_cancellled',0)
            // ->first();

            // if(!isset($patientTime->patient_id)){
            if($patientData)
            {

                $ins_data=array(
                    'salutation_id'=>$patientData->salutation_id,
                    'patientname'  => $patientData->name,
                    'last_name'    => $patientData-> last_name,
                    'appointment_type' => 3, // mobile
                    'dob'=>$patientData->dob,
                    'age' => Carbon::parse($patientData->dob)->diff(Carbon::now())->y,
                    'mobile_number'=>$patientData->mobile_number,
                    'email'=>$patientData->email,
                    'gender'=>$patientData->gender,
                    'appointment_date'=>$appointment_date,
                    'appointment_time'=>$request->time,
                    'created_by'=>$request->user_id,
                    'branch_id'=>$request->center,
                    'patient_id'=>$patient_id,
                );

                $insert=PatientAppointment::create($ins_data);
                if($insert)
                {
                    $response['status'] = 100;
                    $response['message'] = 'Success';
                    $response['result'] = null;
                    return response()->json($response,200);
                }
                else{
                    $response['status'] = 101;
                    $response['message'] = 'Failed to save Appointment';
                    $response['result'] = null;
                    return response()->json($response,200);
                }

            }
            else{
                $response['status'] = 101;
                $response['message'] = "Invalid PatientId";
                return response()->json($response,200);
            }
        // }else{
        //     $response['status'] = 101;
        //     $response['message'] = "already boooked";
        //     return response()->json($response,200);
        // }

        }
        catch (\Exception $e)
        {
            $response['status'] = 500;
            $response['message'] = $e;
            return response()->json($response,500);
        }
 }

    //GET APPOINTMENTS
    public function userAppointments(Request $request)
    {
        try{
            $user_id=$request->user_id;
            $patient_id=$request->patient_id;
            $data=PatientAppointment::select('patient_appointment.id','patient_appointment.patient_id','patient_appointment.branch_id as center_id',
            'branch_master.branch_name as center_name','patient_appointment.appointment_date as date',
            'patient_appointment.appointment_time as time')
            ->join('branch_master', 'patient_appointment.branch_id', '=', 'branch_master.branch_id')
            ->where('patient_appointment.appointment_type',3)
            ->where('patient_appointment.created_by',$user_id)
            ->where('patient_appointment.patient_id',$patient_id)
            ->where('patient_appointment.is_cancellled',0)
            ->orderBy('id','DESC')->get();

            $response['status'] = 100;
            $response['message'] = "Success";
            $response['result'] = $data;
            return response()->json($response);

        }
        catch (\Exception $e)
        {
            $response['status'] = 500;
            $response['message'] = $e;
            return response()->json($response,500);
        }
    }
    public function patientListInUser(Request $request){
        try{
        if($request->user_id){

            $data=User_Patients_List::select('patient_registration.uhidno','patient_registration.id',
          DB::raw("CONCAT(TRIM(patient_registration.name), ' ', TRIM(patient_registration.last_name)) AS name"))
          ->join('patient_registration','patient_registration.id','app_patients_user_mapping.patient_id')
          ->where('app_patients_user_mapping.app_userid',$request->user_id)
          ->get();

          $result = [];

          foreach ($data as $patient) {
            $latestImage=PatientGallery::where('patient_id',$patient->id)->orderBy('id','DESC')->first();
            if($latestImage){
                $patient->latest_image_url ='/images/'.$latestImage->image;
            }
            else{
                $patient->latest_image_url="";
            }


            // Add the patient to the result array
            $result[] = $patient;
           }


            $response['status'] = 100;
            $response['message'] = "Success";
            $response['result'] = $result;
            return response()->json($response,200);
            }
            else{
                $response['status'] = 101;
                $response['message'] = "failed";
                $response['result'] = null;
                return response()->json($response,200);
            }
         }
    catch (\Exception $e)
    {
        $response['status'] = 500;
        $response['message'] = 'Server Error';
        return response()->json($response,500);
    }

    }
    public function getuserDashboarddata(Request $request){
        try{
            $pid=$request->patient_id;
            if($pid>0){
            $currentDate = Carbon::now();
            $formattedDate = $currentDate->format('Y-m-d');
            $appointmentData=PatientAppointment::select('patient_appointment.id','patient_appointment.branch_id',
            'patient_appointment.appointment_date','patient_appointment.appointment_time','branch_master.branch_name')
            ->join('branch_master','patient_appointment.branch_id','branch_master.branch_id')
            ->where('patient_appointment.patient_id',$pid )
            ->where('patient_appointment.appointment_date','>=',$formattedDate)
            ->where('patient_appointment.is_cancellled',0)
            ->orderBy('patient_appointment.appointment_date', 'asc')->limit(1)->get();
            $data=array();
            if( count($appointmentData)>0){
                $demo1=$appointmentData[0];
                $newKey = 'appointment';
                $data[$newKey]= $demo1;
            }
            $glucose_readings=BloodGlucose::select('app_patient_blood_glucose.autoId','app_patient_blood_glucose.blood_glucose',
            'app_patient_blood_glucose.reading_date','app_patient_blood_glucose.reading_time','test_master.TestName')
            ->join('test_master','test_master.TestId', 'app_patient_blood_glucose.TestId')
            ->where('app_patient_blood_glucose.patientid',$pid)
            ->orderBy('autoId','DESC')->limit(1)->get();

            if(count($glucose_readings)>0){
            $demo2=$glucose_readings[0];
            $newKey = 'glucose_readings';
            $data[$newKey]= $demo2;
            }

            $bpstausDAta=BpStatus::select('id','reading_date','reading_time','bpd','bps','pulse')
            ->where('patientId',$request->patient_id) ->orderBy('id','DESC')->limit(1)->get();
            if(count($bpstausDAta)>0){
            $datas=[
                'id'=>intval($bpstausDAta[0]->id),
                'reading_date'=>strval($bpstausDAta[0]->reading_date),
                'reading_time'=>strval($bpstausDAta[0]->reading_time),
                'bpd'=>strval($bpstausDAta[0]->bpd),
                'bps'=>strval($bpstausDAta[0]->bps),
                'pulse'=>strval($bpstausDAta[0]->pulse),
            ];
            $demo3= $datas;
            $newKey = 'bp_status';
            $data[$newKey]= $demo3;
           }
           $RemarksData=Remainder::select('id','remainder_text as reminder')
          ->where('patient_id',$request->patient_id) ->orderBy('id','DESC')->limit(1)->get();
          if(count($RemarksData)>0){
            $demo4=$RemarksData[0];
            $newKey = 'reminder';
            $data[$newKey]= $demo4;
          }
          if($data){
            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result']= $data;
            return response()->json($response);

          }else{
            $response['status'] = 101;
            $response['message'] = 'No Data';
            $response['result']= $data;
            return response()->json($response);
 
          }

    }else{
        $response['status'] = 101;
        $response['message'] = 'No Patient Available';
        $response['result']= [];
        return response()->json($response);

    }
        }
        catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response,500);
        }
     }
     public function cancelAppoinment(Request $request){
        try{
            $currentDate = Carbon::now();
            $formattedDate = $currentDate->format('Y-m-d');

            $patient_id=$request->patient_id;
            $user_id=$request->user_id;
            $appointment_id=$request->appointment_id;


            $cond=array(
                array('id',$appointment_id),
             );

            $appn_date=getAfeild("appointment_date","patient_appointment",$cond);
            $current_date = date('Y-m-d');

            if ($appn_date >= $current_date) {
                $data=PatientAppointment::where('id',$appointment_id)->where('patient_id', $patient_id)->update(['is_cancellled'=>1]);

                if($data){
                    $response['status'] = 100;
                    $response['message'] = 'Appointment canceled successfully.';
                    $response['result']=  $data;
                    return response()->json($response);
                }
                else{
                    $response['status'] = 101;
                    $response['message'] = 'Failed to Cancel Appointment';
                    $response['result']= [];
                    return response()->json($response);
                }

            } else {

                $response['status'] = 101;
                $response['message'] = 'Unable to cancel appointment because the date is in the past.';
                $response['result']= [];
                return response()->json($response);

            }

     }

    catch (\Exception $e) {
        $response['status'] = 500;
        $response['message'] = 'Server Error';
        return response()->json($response,500);
    }
     }
     public function getReminders(Request $request){
        try{
            $pid= $request->patient_id;
            if($pid>0){
                $data=Remainder::select('id','remainder_text as reminder')->where('patient_id',$pid)
                ->orderBy('id','DESC')->get();
                $response['status'] = 100;
                $response['message'] = 'Sucess';
                $response['result']=  $data;
                return response()->json($response);
            }
            $response['status'] = 101;
            $response['message'] = 'fail';
            $response['result']= [];
            return response()->json($response);
        }
        catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response,500);
        }
     }
     public function uploadFile(Request $request){
        try{
            $image= $request->image;
            $pid=$request->patient_id ;

            if($pid>0){
                $convertdata= $image;
                $fileType = explode(',', $convertdata)[0];
                $dataType = explode('/', explode(':', $fileType)[1])[1];
                $finalType = explode(';', $dataType)[0];
                $imageData = explode(',', $convertdata)[1];
                $decodedImage = base64_decode($imageData);
                $fileName = Str::random(10) . '.'.$finalType;
                $fullimgpath='images/patient_documents/'.$fileName ;
                $path='patient_documents/'.$fileName ;
                $folderPath = public_path($fullimgpath);
                file_put_contents($folderPath, $decodedImage);
                $cond=array(
                    array('id',$pid),
                 );

            $branchId=getAfeild("branch_id","patient_registration",$cond);

                $imgData=array(
                    'image'=>  $path,
                    'remarks' => $request->remarks,
                    'category_id' => $request->document_category,
                    'subcategory_id' => $request->subdocument_category,
                    'display_status' => 1,
                    'created_by'=>0,
                    'branch_id'=> $branchId,
                    'is_deleted' => 0,
                    'patient_id'=>$request->patient_id,
                    'created_at'=>date('Y-m-d h:i:s'),
                    'updated_at'=>date('Y-m-d h:i:s')
                );
                $datadetails=PatientDocument::insert($imgData);
                $response['status'] = 100;
                $response['message'] = 'Sucess';
                $response['result']= [];
                return response()->json($response);
            }
            $response['status'] = 101;
            $response['message'] = 'Invalid Patient Id';
            $response['result']= [];
            return response()->json($response);
        }
        catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response,500);
        }
     }
     public function getMedicineInvoice(Request $request){
        try{
            $orderId= $request->orderId;

            if($orderId>0){

                $data=OrderMedicine::select('invoice_medicine_path')->where('id',$orderId)->first();
                if($data){
                    $response['status'] = 100;
                    $response['message'] = 'Sucess';
                    $response['result']=  $data;
                    return response()->json($response);
    
                }
            }
            $response['status'] = 101;
            $response['message'] = 'fail';
            $response['result']= [];
            return response()->json($response);
        }
        catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response,500);
        }
     }

     public function getProductInvoice(Request $request){
        try{
            $orderId= $request->orderId;

            if($orderId>0){

                $data=Purchase_Product::select('invoice_product_path')->where('id',$orderId)->first();
                if($data){
                    $response['status'] = 100;
                    $response['message'] = 'Sucess';
                    $response['result']=  $data;
                    return response()->json($response);
    
                }
            }
            $response['status'] = 101;
            $response['message'] = 'fail';
            $response['result']= [];
            return response()->json($response);
        }
        catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response,500);
        }
     }

}
