<?php

namespace App\Http\Controllers\users;
use App\Models\Masters\CitiesMaster;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\PatientRegistration;
use App\Models\PatientCategory;
use Log;
use App\Models\ApiLogs;
use App\Models\PatientSubCategory;
use App\Models\PatientGallery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\HistoryController;

class PatientController extends Controller

{
    // public function __construct()
    // {

    // }

    protected $HistoryController;

    public function __construct(HistoryController $HistoryController)
    {
        $this->HistoryController = $HistoryController;
    }

    public function index(Request $request)
    {
        $data=array();

        $data['PageName']="Patient Registration";
        $patient_data=null;
        $gallery=null;
        if(isset($request->id)){
            $cond=['id' => $request->id];
            // $patient_data=getASingleValue("patient_registration", $cond);
            $patient_data=PatientRegistration::where('id',$request->id)
                ->orderBy('id','DESC')
                ->first();
            $patient_category=PatientCategory::where('patient_id',$request->id)->orderBy('id','DESC')->get();
            $patient_sub_category=PatientSubCategory::where('patient_id',$request->id)->orderBy('id','DESC')->get();

            $patient_sub_categoryA=[];
            foreach ($patient_sub_category as $value) {

                array_push( $patient_sub_categoryA,$value->category_id);
            }

            $patient_categoryA=[];
            foreach ($patient_category as $value) {

                array_push( $patient_categoryA,$value->category);
            }

            $gallery=PatientGallery::where('patient_id',$request->id)->orderBy('id','DESC')->first();
            $uhidNos=$patient_data->uhidno;


        }
        else{
            $patient_categoryA=[];
            $patient_sub_categoryA=[];
         //   $uhidno= PatientRegistration::select('id')->where('branch_id',Session::get('current_branch'))->orderBy('id','DESC')->take(1)->first();
         $uhidno=GenerateUhid(Session::get('current_branch'))  ;

         $uhidNos=Session::get('current_branch_code').' -'.($uhidno + 1);

        }

        $data['patient_data']=$patient_data;
        $data['patient_category']=$patient_categoryA;
        $data['patient_sub_category']=$patient_sub_categoryA;
        $data['gallery']=$gallery;
        $data['uhidNo']=$uhidNos;

        return Parent::page_maker('webpanel.patientRegistration',$data);
    }

    public function patientSearch()
    {
        $data=array();

        $data['PageName']="Patient Search";
        // $response=json_encode('');
        // $data['data']=$this->searchPatient($response);
        return Parent::page_maker('webpanel.patientSearch',$data);
    }

    public function patientBooks(Request $request)
    {
        $patientBookSearch = base64_decode($request->search);
        $data=[];
        $ordby=[];
        $sliders=[];
        $patientData = PatientRegistration::select('*','patient_registration.id as id')
            ->leftjoin('extension_master','extension_master.id','=','patient_registration.email_extension');
        if ($patientBookSearch) {
            $patientData = $patientData->where(function ($query) use ($request) {
                $patientBookSearch = base64_decode($request->search);
                $query->where('patient_registration.name', 'ilike', '%' . $patientBookSearch . '%')
                    ->orWhere('patient_registration.mobile_number', 'ilike', '%' . $patientBookSearch . '%')
                    ->orWhere('patient_registration.uhidno', 'ilike', '%' . $patientBookSearch . '%')
                    ->orWhere('patient_registration.email', 'ilike', '%' . $patientBookSearch . '%');
            });
        }
        $patientData = $patientData->orderBy('patient_registration.id','DESC')->take(12)->get();

        // $patient_data=PatientRegistration::select('*','patient_registration.id as id')->leftjoin('patient_gallery','patient_gallery.patient_id','=','patient_registration.id')->leftjoin('extension_master','extension_master.id','=','patient_registration.email_extension')->orderBy('patient_registration.id','DESC')->take(12)->get();

        // foreach($patient_data as $data){
        //     $sliders=PatientGallery::where('patient_id',$data->id)->orderBy('id','DESC')->get();
        // }

        //$sliders=PatientGallery::orderBy('id','DESC')->get();


        $data['PageName']="Patient Books";
        $data['patient_data']=$patientData;
      //  $data['sliders']=$sliders;

        return Parent::page_maker('webpanel.patientBooks',$data);
    }
    public function patientGallery(Request $request)
    {
       $pid=$request->id;
       if($pid>0)
       {
        $data =PatientGallery::where('patient_id',$pid)->orderBy('id','DESC');
        echo($data);
       }
    }
    public function saveImages(Request $request)
    {


        if($request->has('patient_image')){

            $imageName = time().'.'.$request->patient_image->extension();
            $file_path= $request->patient_image->move(public_path('images'), $imageName);
            PatientGallery::insert([
                'patient_id'=>$request->patient_id,
                'image'=>$imageName,
//                'is_main'=>1,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s')
            ]);
            return redirect('/patientBooks')->with('message','Success! Image Uploaded Successfully');
      }
      else{
        return redirect('/patientBooks')->with('error','Error! Invalid Image');
      }

    }



    public function searchPatient(Request $request)
    {


        $cond=[];
        $is_condition=0;
        if( !is_null($request->patient_name) ){
            $is_condition=1;
            array_push($cond,['name', 'ILIKE', "%{$request->patient_name}%"]);
        }
        if( !is_null($request->uhid) ){
            $is_condition=1;
            array_push($cond,['uhidno', 'ILIKE', "%{$request->uhid}%"]);
        }
        if( !is_null($request->mobile_number) ){
            array_push($cond,['mobile_number', 'ILIKE', "%{$request->mobile_number}%"]);
        }
        if( !is_null($request->patient_type) ){
            $is_condition=1;
            array_push($cond,['patient_type', $request->patient_type]);
        }
        if( !is_null($request->last_name) ){
            $is_condition=1;
            array_push($cond,['last_name', 'ILIKE', "%{$request->last_name}%"]);
        }
        if( !is_null($request->gender) ){
            $is_condition=1;
            array_push($cond,['gender', $request->gender]);
        }
        if( !is_null($request->age) ){
            $is_condition=1;
            $cdate=date('Y');
            $birthYear=$cdate-$request->age;
                array_push($cond,[
                DB::raw('EXTRACT(YEAR  FROM dob )'),
                 $birthYear]);

        }
        if( !is_null($request->address) ){
            $is_condition=1;
            array_push($cond,['address', 'ILIKE', "%{$request->address}%"]);
        }



        $start_date = Carbon::parse(request()->from_date)->toDateTimeString();
        $end_date = Carbon::parse(request()->to_date)->toDateTimeString();
        $end_date= str_replace("00:00:00","24:00:00",$end_date);

        $wherebtm=[];

        if( !is_null($request->from_date) ){
            $is_condition=1;
            array_push($cond,['created_at', '>=', "$start_date"]);
        }
        if( !is_null($request->to_date) ){
            array_push($cond,['created_at', '<=', "$end_date"]);
        }


       // array_push($cond,['branch_id', '=', Session::get('current_branch')]);

        // $cond=[['name', 'LIKE', '%{$request->patient_name}%'],['id', 'LIKE', "%{$request->uhid}%"],['mobile_number', 'LIKE', "%{$request->mobile_number}%"]];


       // DB::enableQueryLog();
        // $data = PatientRegistration::where($cond)->whereBetween('created_at', [$start_date, $end_date])->orderBy('id','DESC')->get();
        // return $result;
      //  dd(DB::getQueryLog());

        // $data = getSearchValue('patient_registration',$cond);

        if($is_condition==1)
        {
            $data = PatientRegistration::orwhere($cond)->orderBy('id','DESC')->get();
        }
        else{
            $data = PatientRegistration::orwhere($cond)->orderBy('id','DESC')->skip(0)->take(30)->get();

        }


        $output = array(
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
          );
          echo json_encode($output);


        // return response()->json($data);
    }
    public function requestOtp(Request $request)
    {
           $otp = rand(1000,9999);
           $cond=['id' => $request->email_extension];

           $value=getAfeild("extension","extension_master", $cond);


           Log::info("otp = ".$otp);

           $user = PatientRegistration::where('email','=',$request->email_address)->update(['otp' => $otp]);


            \Mail::to($request->email_address.''.$value)->send(new \App\Mail\Mailer('Your Email Verification Code is:'.$otp,'Mail.mailVerification'));

            return response(["status" => 200, "message" => "OTP sent successfully"]);


       }
       public function verifyOtp(Request $request){

        $result  = PatientRegistration::where([['id','=',$request->patient_email_verify_id],['otp','=',$request->otp]])->first();

        if($result){
            PatientRegistration::where([['id','=',$request->patient_email_verify_id]])->update(['is_email_verify' => 1]);
            return [ 'status'=>1, 'message'=>"OTP Verification Completed",'id'=>$request->patient_email_verify_id ];
        }
        else{
            return [ 'status'=>3, 'message'=>"OTP Verification failed",'id'=>$request->patient_email_verify_id ];
        }
    }
    public function savePatient(Request $request)
    {


        // print_r($request->patient_image);
        // return;
        $patient_id='';
        $validated = $request->validate([
            'patient_name' => 'required',
//            'mobile_number'=>'required|min:10|numeric',
            'mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'patient_type'=>'required',
            'salutation'=>'required',
           // 'surname'=>'required',
            'gender'=>'required',
            'dob'=>'required',
//            'age'=>'required',
            'address'=>'required',
            'category'=>'required',
            // 'whatsapp_number'=>'regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10'
        ]);
        if($validated)
        {
            // $continueValidation = false;
            // if ('' != trim($request->pincode)) {
            //     $citiesMaster=CitiesMaster::where('pincode','=',$request->pincode)->distinct()->count();
            //     if ($citiesMaster > 0) {
            //         $continueValidation = true;
            //     } else {
            //         $continueValidation = false;
            //         return [ 'status'=>3, 'message'=>"Invalid pin code" ];
            //     }
            // } else {
            //     $continueValidation = true;
            // }
            $continueValidation = true;
            if ($continueValidation) {

            //    $auto_increment_value = PatientRegistration::max('id')->where('branch_id',Session::get('current_branch'));

					if($request->dob)
					{
						$newDate = $request->dob;
						$newDate = str_replace('/', '-', $newDate);
						$newDate = date("Y-m-d", strtotime($newDate));
					}
					else{
						$newDate=null;
					}

                  $ins_data = array(
                    'mobile_number' => $request->mobile_number,
                    'whatsapp_number' => $request->whatsapp_number,
                    'email_extension' => $request->email_extension,
                    'email' => $request->email,

                    'alternative_number_1_number' => $request->alternative_number_1_number,
                    'alternative_number_1_type' => $request->alternative_number_1_type,
                    'alternative_number_1_name' => $request->alternative_number_1_name,

                    'alternative_number_2_number' => $request->alternative_number_2_number,
                    'alternative_number_2_type' => $request->alternative_number_2_type,
                    'alternative_number_2_name' => $request->alternative_number_2_name,

                    'patient_type' => $request->patient_type,
                    'sub_division_id' => $request->sub_division_id,
                    'salutation_id' => $request->salutation,
                    'name' => $request->patient_name,
                    'last_name' => $request->surname,
                    'gender' => $request->gender,
//                'dob'=> date("Y-m-d", strtotime($request->dob)),
                    'dob' => $newDate,
                    // 'age' => Carbon::parse($request->dob)->diff(Carbon::now())->y,
                    'education' => $request->education,
                    'occupation' => $request->occupation,
                    'caregiver_relation' => $request->caregiver_relation,
                    'caregiver_name' => $request->caregiver_name,
                    'caregiver_pid' => $request->caregiver_pid,
                    'marital_status' => $request->marital_status,
                    'department_id' => $request->department_id,
                    'specialist_id' => $request->specialist_id,
                    'token_number' => $request->token_number,
                    'blood_group_id' => $request->blood_group_id,
                    'annual_income' => $request->annual_income,

                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'place_id' => $request->place_id,
                    'pincode' => $request->pincode,
                    'address' => $request->address,

                    'patient_reference_type_id' => $request->patient_reference_type_id,
                    'patient_reference_name' => $request->patient_reference_name,
                    'religion_id' => $request->religion_id,
                    'id_proof_type' => $request->id_proof_type,
                    'id_proof_number' => $request->id_proof_number,
                    'empanelment_no' => $request->empanelment_no,
                    'claim_id' => $request->claim_id,
                    'created_by' => Auth::id(),
                    'branch_id' => Session::get('current_branch'),
                    // 'uhidno'=> Session::get('current_branch_code').'-'.($auto_increment_value + 1),
                    'status' => $request->status,


                );

                if ($request->crude == 1) {

                    $auto_increment_value=GenerateUhid(Session::get('current_branch'))  ;

                    $ins_data['uhidno'] = Session::get('current_branch_code') . '-' . ($auto_increment_value + 1);
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert = PatientRegistration::create($ins_data);
                    // dd($insert);
                     // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log=array(
                    'primarykeyvalue_Id'=>$insert->id,
                    'user_id'=>Auth::id(), // userId
                    'log_type'=>1, // Save
                    'table_name'=>'PatientRegistration', // Save Patient
                    'qury_log'=>$sql,
                    'description'=>'Patient Registration ,Save Patient Details',
                    'created_date'=>date('Y-m-d H:i:s'),
                    'patient_id'=>$insert->id,
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    $patient_id = $insert->id;
                    $uhidno = Session::get('current_branch_code') . '-' . ($auto_increment_value + 1);
                    if($insert)
                    {
                        //update setting///////////////////////////////////////////////////////////
                        $ups_setings_data=array(
                            'last_generated_number' => $auto_increment_value + 1,
                        );
                        $update = DB::table('uhidno_settings')
                        ->where('branch_id',Session::get('current_branch'))
                        ->update($ups_setings_data);
                        ////////////////////////////////////////////////////////////////////////////////
                    }

                } else if ($request->crude == 2) {

                    try {
                        DB::connection()->enableQueryLog(); // enable qry log
                        $update = PatientRegistration::whereId($request->patient_id)->update($ins_data);
                         // TO GET LAST EXECUTED QRY
                        $query = DB::getQueryLog();
                        $lastQuery = end($query);
                        $sql = $lastQuery['query'];
                        $bindings = $lastQuery['bindings'];
                        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                        //end of qury

                        // LOG  // add parameters based on your need
                        $log=array(
                        'primarykeyvalue_Id'=>$request->patient_id,
                        'user_id'=>Auth::id(), // userId
                        'log_type'=>2, // Update
                        'table_name'=>'PatientRegistration', // Update Patient
                        'qury_log'=>$sql,
                        'description'=>'Patient Registration ,Update Patient Details',
                        'created_date'=>date('Y-m-d H:i:s'),
                        'patient_id'=>$request->patient_id,
                        );

                        $save_history = $this->HistoryController->saveHistory($log);
                        ////////////////////////////////////////////////////////////////////////////////////
                        // END OF LOG
                        $res = PatientCategory::where('patient_id', $request->patient_id)->delete();
                        $res = PatientSubCategory::where('patient_id', $request->patient_id)->delete();
                        $patient_id = $request->patient_id;
                        // $uhidno =PatientRegistration::select('uhid')->where('patient_id',$request->patient_id)
                        $cond = ['id' => $request->patient_id];
                        $uhidno = getAfeild("uhidno", "patient_registration", $cond);

                    } catch (\Exception $e) {
                        //  DB::rollback();
                        return ['status' => 4, 'message' => "Failed to Update"];

                    }


                }

                if (isset($patient_id)) {

                    if (isset($request->category)) {
                        foreach ($request->category as $category_data) {

                            PatientCategory::insert([
                                'patient_id' => $patient_id,
                                'category' => $category_data,
                                'created_at' => date('Y-m-d h:i:s')
                            ]);
                        }
                    }

                    if (isset($request->subCategory)) {
                        foreach ($request->subCategory as $sub_category_data) {
                            PatientSubCategory::insert([
                                'patient_id' => $patient_id,
                                'category_id' => $sub_category_data,
                                'created_at' => date('Y-m-d h:i:s')
                            ]);
                        }

                    }

                    $imageName = "";
                    if ($request->has('patient_image')) {
                        $imageName = time() . '.' . $request->patient_image->extension();
                        $file_path = $request->patient_image->move(public_path('images'), $imageName);

                    }
                    if ($request->has('patient_snapshot') && $request->patient_snapshot != "") {
                        $img = $request->patient_snapshot;
                        $image_parts = explode(";base64,", $img);
                        $image_type_aux = explode("image/", $image_parts[0]);
                        $image_type = $image_type_aux[1];
                        $image_base64 = base64_decode($image_parts[1]);
                        $imageName = time() . '.png';
                        $file = $imageName;
                        Storage::disk('public_two')->put($file, $image_base64);


                    }

                    if (isset($imageName)) {

                        $imgData = array(
                            'patient_id' => $patient_id,
                            'image' => $imageName,
//                        'is_main'=>1,
                            'created_at' => date('Y-m-d h:i:s'),
                            'updated_at' => date('Y-m-d h:i:s')
                        );
                        // if($request->crude==1){
                        if ($imageName != "") {
                            PatientGallery::insert($imgData);
                        }

                        // }else{

                        // PatientGallery::where('patient_id',$patient_id)->update($imgData);

                        // }
                    }

                    /** API Integration Process */
                    $status = PatientRegistration::whereId($patient_id)->value('is_api_verify');


                    $date = date("Y-m-d", strtotime($request->dob));
                    $cond = ['id' => $request->email_extension];
                    $extension = getAfeild("extension", "extension_master", $cond);

					 // GENDER SETUP FOR API ICON
                    $sendGender="";
                    if($request->gender=="m")  $sendGender="M";
                    else if($request->gender=="f")  $sendGender="F";
                    else if($request->gender=="o")  $sendGender="O";

                    $data = array(
                        "PatientId" => $patient_id,
                        "PatientName" => $request->patient_name . ' ' . $request->surname,
                        "Status" => $status,
                        "DOB" => $newDate ,
                        "PhoneNo" => $request->mobile_number,
                        "Email" => $request->email == null ? "" : $request->email . '' . $extension,
                        "RefNo" => $uhidno,
						"Gender"=> $sendGender
                    );
                    $jsonData = json_encode($data);// response()->json($data);

                    $callUrl=config('global.icon.url');
                    $ch = curl_init($callUrl. '/api/patient');
                    $options = array(
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HTTPHEADER => array('Content-type: application/json'),
                        CURLOPT_POSTFIELDS => $jsonData
                    );
                    curl_setopt_array($ch, $options);
                    $result = curl_exec($ch);
                    $response = json_decode($result);
                    curl_close($ch);


                    if($response)
                    {
                    if ($response->Message == "Saved") {
                        $update = PatientRegistration::whereId($patient_id)->update([
                            'is_api_verify' => 1
                        ]);
                    } else {
                        $update = PatientRegistration::whereId($patient_id)->update([
                            'is_api_verify' => 0
                        ]);

                    }
                    $res_msg= $response->Message;
                 }
                 else{
                    $res_msg=$response;
                 }
                    $log_data = array(
                        'ip' => env('API_BASE_URL'),
                        'method' => 'POST',
                        'duration' => "",
                        'url' => env('API_BASE_URL') . '/api/patient',
                        'request_body' => json_encode($data, true),
                        'response' => $res_msg,
                        'created_at' => date('Y-m-d H:i:s')
                    );

                    $api_insert = ApiLogs::insert($log_data);
                    /*
                    Complete API Integration
                     */


                    try {
                        if ($request->crude == 1 && $request->email && $request->email != '') {
                            \Mail::to($request->email . '' . $extension)->send(new \App\Mail\Mailer('JDC Registration', 'Mail.welcomeMail'));
                        }
                    } catch (\Exception $e) {
                        //  DB::rollback();
                        return ['status' => 1, 'message' => "Saved Successfully & Email Cannot be sent", 'id' => $patient_id];

                    }


                    if ($request->crude == 1) {
                        return ['status' => 1, 'message' => "Saved Successfully", 'id' => $patient_id];
                    } else {
                        return ['status' => 2, 'message' => "Updated Successfully"];
                    }

                } else {
                    return ['status' => 3, 'message' => "Failed to save"];
                }


                // if(isset($insert) )
                // {

                //     //category insertion
                //     if(isset($request->category))
                //     {


                //         foreach($request->category as $category_data)
                //         {

                //             PatientCategory::insert([
                //                 'patient_id'=>$insert->id,
                //                 'category'=>$category_data,
                //                 'created_at'=>date('Y-m-d h:i:s')
                //             ]);
                //         }
                //     }

                //     // sube category insertion
                //     if(isset($request->subCategory))
                //     {
                //         foreach($request->subCategory as $sub_category_data)
                //         {
                //             PatientSubCategory::insert([
                //                 'patient_id'=>$insert->id,
                //                 'category_id'=>$sub_category_data,
                //                 'created_at'=>date('Y-m-d h:i:s')
                //             ]);
                //         }
                //     }

                //     if($request->has('patient_image')){
                //         $imageName = time().'.'.$request->patient_image->extension();
                //         $file_path= $request->patient_image->move(public_path('images'), $imageName);
                //         PatientGallery::insert([
                //             'patient_id'=>$insert->id,
                //             'image'=>$imageName,
                //             'is_main'=>1,
                //             'created_at'=>date('Y-m-d h:i:s'),
                //             'updated_at'=>date('Y-m-d h:i:s')
                //         ]);

                //   }
                //   $cond=['id' => $request->email_extension];

                //   $value=getAfeild("extension","extension_master", $cond);

                //      \Mail::to($request->email.''.$value)->send(new \App\Mail\Mailer('JDC Registration','Mail.welcomeMail'));
                //     return [ 'status'=>1, 'message'=>"Saved Successfully",'id'=>$insert->id ];
                // }
                // else if(isset($update)){
                //     return [ 'status'=>2, 'message'=>"Updated Successfully" ];
                // }
                // else{
                //     return [ 'status'=>3, 'message'=>"Failed to save" ];
                // }
            }
        }
        else{
            echo 2; // validation error
        }
    }


}
?>
