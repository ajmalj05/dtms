<?php
namespace App\Http\Controllers\users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\App_PatientRegistration;
use Illuminate\Support\Facades\Session;
use App\Models\PatientRegistration;
use App\Models\MobileApp\AppNotification;
use App\Models\MobileApp\User_Patients_List;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HistoryController;
use App\Models\MobileApp\NewsPapper;
use App\Models\MobileApp\AppNotification_img;
use App\Models\MobileApp\Purchase_Product;
use App\Models\MobileApp\OrderMedicineType;
use App\Models\Masters\MedicineMaster;
use App\Models\MobileApp\OrderMedicine;

use GuzzleHttp\Client;

class AppManagementController extends Controller
{


        public function __construct()
        {

        }


public function sendPushNotification($data)
{
    $client = new Client();
    $firebaseServerKey = env('FIREBASE_SERVER_KEY');


    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $firebaseServerKey, // Replace $VARIABLE with your actual token
    ];


    $data = [
        'to' => '/topics/jdc_notifications',
        'notification' => [
            'title' => $data['title'],
            'body' => $data['short_description'],
            'sound' => 'default',
            'image' => 'https://jdcdtms.in/assets/img/company-logo.png',
        ],
        'notification_id' => $data['id'],
    ];

    // Convert the data to JSON format
    $body = json_encode($data);

    // Make the POST request using Guzzle
        $response = $client->post('https://fcm.googleapis.com/fcm/send', [
            'headers' => $headers,
            'body' => $body,
            'verify' => false, // remove this in server
        ]);


        // Get the response body as a string
    $responseBody = $response->getBody()->getContents();

    // print_r($responseBody);
}


        public function patientVerification(){
        {
            $data=array();
            $userDetails=null;
            $userData =array();

            $data['PageName']="Patient Verification";
            return Parent::page_maker('webpanel.appManagement.patientVerification',$data);

        }


        }

        public function getPatientVerification()
        {
            $branch_id=Session::get('current_branch');
            $filldata = App_PatientRegistration::where('is_verified',0)->where('branch_id', $branch_id)
            ->orderBy('id','DESC')->get();

            $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
        }


        public function patientVerificationApp(Request $request)

        {

            // data from app_patientsRegistration;//
            $patent_id=$request ->input('id');
            $patient_data=App_PatientRegistration::where('id',$patent_id)->first();
            if($patient_data->gender_id==1){
                $gender_value='m';
            }
            else if($patient_data->gender_id==2){
                $gender_value='f';
            }
            else if($patient_data->gender_id==3){
                $gender_value='o';
            }

            if(isset($patient_data)){

                    $dataArray=array(
                    'name'=> $patient_data->first_name,
                    'last_name'=>$patient_data->last_name,
                    'mobile_number'=>$patient_data->mobile_number,
                    'address'=>$patient_data->perm_address,

                    'whatsapp_number'=>$patient_data->whatsapp_number,
                    'dob'=>$patient_data->date_of_birth,
                    'created_by'=>$patient_data->created_by,
                    'salutation_id'=>$patient_data->salutation,

                    'email'=>$patient_data->email,
                    'created_at'=>$patient_data->created_at,
                    'gender'=>$gender_value,
                    'branch_id'=>$patient_data->branch_id

                    );
                    //-----Generate uhidno--//

                $auto_increment_value=GenerateUhid(Session::get('current_branch'))  ;

                $dataArray['uhidno'] = Session::get('current_branch_code') . '-' . ($auto_increment_value + 1);

                $insert = PatientRegistration::create($dataArray);

                $patient_id = $insert->id;
                if($insert)
                    {
                        //update setting///////////////////////////////////////////////////////////
                        $ups_setings_data=array(
                            'last_generated_number' => $auto_increment_value + 1,
                        );
                        $update = DB::table('uhidno_settings')
                        ->where('branch_id',Session::get('current_branch'))
                        ->update($ups_setings_data);


                            $data=array(
                                'is_verified'=>1,
                                'verified_date'=>date('Y-m-d h:i:s'),
                                'verified_by'=> Auth::id(),
                                'patient_id'=>$patient_id,
                            );

                        $update = DB::table('app_patientsRegistration')
                        ->where('id',$patent_id)
                        ->update($data);


                        // ----save data ----//

                         $idUpdate=array(
                            'app_userid'=> $patient_data->use_id,
                            'patient_id'=> $patient_id,

                         );
                         if($idUpdate){
                            $useyData=User_Patients_List::insert($idUpdate);
                         }



                        ////////////////////////////////////////////////////////////////////////////////
                    }

                return response()->json([

                    'status' => 1,
                    'message' => 'Data inserted successfully.',

                ]);

            }
            return response()->json([
                'status' => 0,
                'message' => 'Temporary data not found.',
            ]);



        }
        //------ appNotification---//

        public function appNotification(){
            {
                $data=array();
                $userDetails=null;
                $userData =array();

                $data['PageName']="App Notification";
                return Parent::page_maker('webpanel.appManagement.appNotification',$data);

            }


            }

        // ---save notifications---//
        public function saveNotification(Request $request){
            $validated = $request->validate([
                'notification_title' => 'required',

            ]);

            if($validated){
                $ins=array(
               'titles' => $request->notification_title,
               'short_description' => $request->short_description,
               'detailed_description' => $request->detailed_description,
               'locations' => $request->location,
               'event_date' => $request->event_date,
               'expiry_date' => $request->expiry_date,
               'display_status' => ($request->display_status == 'on') ? "1" : "0",
               'created_at' => Carbon::now(),

                );

              //  print_r($ins);
                $crude=$request->input('crude');
                if($crude==1){
                    $ins['created_by']=Auth::id();
                    $notificationCreated= AppNotification::create($ins);
                    $notificationCreated= $notificationCreated->id;
                }
                else if($crude==2){
                    $updatedId=$request->hidid;
                    $ins['updated_by']=Auth::id();
                    $ins['updated_at']=Carbon::now();
                    $notificationCreated=AppNotification::select('id')->where('id',$updatedId)->update($ins);
                    $notificationCreated=$updatedId;

                }
                $files_array= [];
                if($request->TotalFiles > 0){
                    for ($x = 0; $x < $request->TotalFiles; $x++){
                        if ($request->hasFile('files'.$x)){
                            $file   = $request->file('files'.$x);
                            $name =time().'.'.$file->extension();


                            $file->move(public_path('images/notifications'), $name);
                            $file_name="images/notifications/";
                            $img=$file_name.$name;

                            array_push($files_array,$img);


                    }

                        $newsImages = [];
                        //---Notification Image---//
                        foreach($files_array as $fileName){
                            $newsImages[] = [
                                'notification_id' =>$notificationCreated,

                                'notification_img'=>$fileName,

                            ];
                            AppNotification_img::insert($newsImages);

                            };
                           //--insert newsletter images into AppNotification Image--//







                }


                }


                if($notificationCreated){



                        // for Sending Push Notification
                        if($crude==1){

                            $push_notification_data=array(
                                'id'=>$notificationCreated,
                                'title'=> $request->notification_title,
                                'short_description' => $request->short_description,
                            );
                            if($request->display_status == 'on')
                            {
                                $this->sendPushNotification($push_notification_data);
                            }


                        }



                    return [ 'status'=>1, 'message'=>"Data saved successfully" ];
                }
                else{

                  return [ 'status'=>2, 'message'=>"Failed to delete data" ];
                }


            }
            else{
                echo 2;
            }



        }




        //list data from App notification to dataTable of AppNotifications//
        public function listNotifications(Request $request){
            $filldata = AppNotification::where('is_deleted',0)->get();

            $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);


        }

        // -----delete data from dataTable App Notification --------------------------------//
        public function deleteNotification(Request $request){
            $id=$request->id;
            if($id)
            {
                $ins=array(
                    'is_deleted' =>1,
                );
                $insert_id= AppNotification::whereId($id)->update($ins);
                    if($insert_id) {
                        return [ 'status'=>1, 'message'=>"Data deleted successfully" ];
                    }
                    else{
                        return [ 'status'=>3, 'message'=>"Failed to delete data" ];
                    }
            }
        }
//image load notification
        public function getNotificationImages(Request $request){
            $data=AppNotification_img::select('id','notification_img')->where('notification_id', $request->id)
            ->where('is_deleted',0)->get();
            $output = array(
                "recordsTotal" => count($data),
                "recordsFiltered" => count($data),
                "data" => $data
            );
            echo json_encode($output);

        }

        public function deletenotificationImage(Request $request){
           $data=AppNotification_img::where('id',$request->id)->update(['is_deleted'=>1]);
           if($data) {
               return [ 'status'=>1, 'message'=>"Data deleted successfully" ];
           }
           else{
               return [ 'status'=>3, 'message'=>"Failed to delete data" ];
           }

        }



        //------------- News Letters ----------newsLetters--------//
        public function newsLetters(){
            {
                $data=array();
                $userDetails=null;
                $userData =array();

                $data['PageName']="News Letters";
                return Parent::page_maker('webpanel.appManagement.newsLetters',$data);

            }


        }





        //save news Letters information to database table app_news//
        public function saveNewsLetters(Request $request){
            $validated=$request->validate([
                'letter_title' => 'required',

            ]);
            if($validated){
                $newsData=array(
                    'titles' => $request->letter_title,
                    'news_description' => $request->news_description,
                    'news_date' => $request->select_date,
                    'redirect_url' => $request->letter_url,
                    'display_status' => ($request->display_status == 'on') ? "1" : "0",
                    'created_at'=>Carbon::now(),

                );
                // print_r($newsData);
                $crude=$request->input('crude');

                if($request->hasFile('images')){
                    $file   = $request->file('images');
                    $name =time().'.'.$file->extension();
                    $file->move(public_path('images/newsLetter'), $name);
                    $file_name="images/newsLetter/";
                    $img=$file_name.$name;
                    $newsData['images']= $img;
                            //--insert newsletter images into NewsPaperImage--//

                    }
                    if($crude==1){
                        $newsData['created_by']=Auth::id();
                        $newsCreated= NewsPapper::create($newsData);

                    }else if($crude==2){
                        $updatedId=$request->hidid;
                        $newsData['updated_by']=Auth::id();
                        $newsData['updated_at']=Carbon::now();
                        $newsCreated=DB::table('app_newsletters')->where('id',$updatedId)->update($newsData);



                    }
                    if($newsCreated) {
                        return [ 'status'=>1, 'message'=>"Data saved successfully" ];


                    } else{
                        return [ 'status'=>3, 'message'=>"Failed to save the data" ];
                    }

            }
            //--inserting news letter data into table NewsPaper--//


        }

        //--News letter Data table -----//

        public function listNewsLetters(Request $request){
            $filldata = NewsPapper::where('is_deleted',0)->get();

            $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);

        }


        //--delete button news letter--//
       public function deleteNewsLetter(Request $request){
            $id=$request->id;
            if($id){
            $ins=array(
                'is_deleted' =>1,
            );
            $insert_id= NewsPapper::whereId($id)->update($ins);
            if($insert_id) {
                return [ 'status'=>1, 'message'=>"Data deleted successfully" ];
            }
            else{
                return [ 'status'=>3, 'message'=>"Failed to delete data" ];
            }
        }




        }


        public function productPurchase(){
            {
                $data=array();
                $userDetails=null;
                $userData =array();

                $data['PageName']="Product Purchase";
                return Parent::page_maker('webpanel.appManagement.ProductPurchase',$data);

            }


            }
            //Purchase product list into dataTable  //

    public function productListing(Request $request){
        $from_date = Carbon::parse(request()->from_date)->toDateTimeString();
        $from_date= str_replace("00:00:00","01:00:00",$from_date);
        $to_date = Carbon::parse(request()->to_date)->toDateTimeString();
        $to_date= str_replace("00:00:00","24:00:00",$to_date);
        if($request->from_date==null){
            $yesterday = Carbon::yesterday();
            $from_date= str_replace("00:00:00","01:00:00",$yesterday);
        }

        $dataIn = DB::table('patient_registration')
        ->join('app_purchase_product', 'patient_registration.id', '=', 'app_purchase_product.patient_id')->
        join('razor_pay_payments','razor_pay_payments.order_master_id','=','app_purchase_product.order_master_id')
        ->select('app_purchase_product.id', 'app_purchase_product.created_at',
        'app_purchase_product.total_amount','app_purchase_product.payment_status','app_purchase_product.order_status',
        'razor_pay_payments.order_number','app_purchase_product.remarks',
        DB::raw("CONCAT(patient_registration.name,patient_registration.last_name) AS name"))
        ->whereBetween('app_purchase_product.created_at', [ $from_date, $to_date])
        ->orderBy('app_purchase_product.id','DESC')->get();
        // $productData = [];
            // foreach($dataIn as $item){
            //     if($item->order_status!=4){
            //     $status=$item->payment_status;
            //     if( $status==0){
            //         $msg="Not Paid";
            //     }else {
            //         $msg="Paid";
            //     }
            //     $data=[
            //         'id'=>$item->id,
            //         'name'=>$item->name,
            //         'created_at'=>$item->created_at,
            //         'total_amount'=>$item->total_amount,
            //         'payment_status'=>$msg,
            //         'order_status'=>$item->order_status

            //     ];
            //     array_push($productData,$data);
            // }
            // }

            $output = array(
            "recordsTotal" => count($dataIn),
            "recordsFiltered" => count($dataIn),
            "data" => $dataIn,
        );
        echo json_encode($output);


    }
    public function getSelectedItems(Request $request){
        $sId=$request->dataid;


        $dataOut = DB::table('products')
        ->join('app_purchase_product_details', 'products.id', '=', 'app_purchase_product_details.product_id')
        ->select('products.id','products.product_name','products.product_rate',
        'app_purchase_product_details.product_price','app_purchase_product_details.product_qty',
        'app_purchase_product_details.unit_total')
        ->where('app_purchase_product_details.order_id',$sId)
        ->get();


        $output = array(
            "recordsTotal" => count( $dataOut),
            "recordsFiltered" => count ($dataOut),
            "data" =>  $dataOut,
        );
        echo json_encode($output);
    }
    public function productPurchaseStatus(Request $request){
        $validated=$request->validate([
            'dataId' => 'required',

        ]);

        $inputId=$request->dataId;
        $remarksOfStatus= $request->remarks;

        $order_status=$request->statusUpdate;
        $currentDateTime = date('Y-m-d H:i:s');

        if($validated){
            $ins=array(
                'order_status'=>$order_status,
                'remarks' => $request->remarks,
                'status_date'=>$currentDateTime ,
            );
            $insert_id1=Purchase_Product::whereId($inputId)->update($ins);
            if($insert_id1){
                return [ 'status'=>1, 'message'=>"status saved successfully" ];
            }
            else{

                return [ 'status'=>2, 'message'=>"failed to save status data" ];
            }


        }
        else{
            echo 2;
        }




    }
    //Medicine Purchase submenu under App management menu//
    public function medicinePurchasePage(){
        {
            $data=array();
            $userDetails=null;
            $userData =array();

            $data['PageName']="Medicine Purchase ";
            return Parent::page_maker('webpanel.appManagement.medicinePurchase',$data);

        }


        }


        //Medicine order listing in Data table under Medicine Purchase submenu//
        public function medicineListing(Request $request){
            $from_date = Carbon::parse(request()->from_date)->toDateTimeString();
            $from_date= str_replace("00:00:00","01:00:00",$from_date);
            $to_date = Carbon::parse(request()->to_date)->toDateTimeString();
            $to_date= str_replace("00:00:00","24:00:00",$to_date);
            if($request->from_date==null){
                $yesterday = Carbon::yesterday();
                $from_date= str_replace("00:00:00","01:00:00",$yesterday);
            }
            $dataIn = DB::table('app_purchase_medicine')
            ->join('patient_registration', 'app_purchase_medicine.patient_id', '=', 'patient_registration.id')
            ->select('app_purchase_medicine.id', 'app_purchase_medicine.created_at','app_purchase_medicine.patient_id',
            'app_purchase_medicine.order_amt','app_purchase_medicine.payment_status', 'app_purchase_medicine.payment_remarks',
            'app_purchase_medicine.remarks','app_purchase_medicine.order_status','app_purchase_medicine.order_remark',
            DB::raw("CONCAT(patient_registration.name,patient_registration.last_name) AS name"))->whereBetween('app_purchase_medicine.created_at', [ $from_date, $to_date])
            ->orderBy('app_purchase_medicine.id','DESC')->get();
            $productData = [];

            // foreach($dataIn as $item){
            //     if($item->order_status!=4){

            //     $status=$item->payment_status;
            //     if($status==0){
            //         $msg="Not Paid";
            //     }else {
            //         $msg="Paid";
            //     }
            //     $data=[
            //         'id'=>$item->id,
            //         'name'=>$item->name,
            //         'created_at'=>$item->created_at,
            //         'order_amt'=>$item->order_amt,
            //         'payment_status'=>$msg

            //     ];
            //     array_push($productData,$data);
            // }


            $output = array(
            "recordsTotal" => count($dataIn),
            "recordsFiltered" => count($dataIn),
            "data" => $dataIn,
        );
        echo json_encode($output);
        }

        //medicine order listing in the view pop-up menu//
        public function medicineOrderList(Request $request){

            $validated=$request->validate([
                'id' => 'required',

            ]);
            if($validated){
                $orderId=$request->id;
                $getElement=OrderMedicineType::select('order_id','medicine_type','image_path','select_id',
                'medicine_name','medicine_status','medicine_amt','medicine_qty')->where('order_id',$orderId)->get();
                $items=[];
                foreach ($getElement as $element) {
                    $medicineType = $element->medicine_type;
                    $imagePath = $element->image_path;
                    $select_id=$element->select_id;
                    $listArray = [
                        'medicine_name'=>'',
                        'medicineTypeImagePath' => '',
                        'medicineAmt' => '',
                        'medicineQty' => ''
                    ];

                    if($medicineType== '1'){
                        $listArray['medicineTypeImagePath'] = $imagePath;

                    }

                    else if($medicineType== '2'){
                        $listArray['medicineTypeImagePath'] = $imagePath;

                    }
                    else if($medicineType== '3'){
                        $listArray['medicineAmt'] = $element->medicine_amt;
                        $listArray['medicineQty']=$element->medicine_qty;
                        $rawData=MedicineMaster::select('medicine_name')->where('id',$select_id)->get();
                        foreach($rawData as $data){
                            $listArray['medicine_name'] = $data->medicine_name;

                        }

                    }
                    array_push( $items,$listArray);
            }


            }

            return response()->json($items);

        }
        public function medicinePurchaseStatus(Request $request){
            $validated=$request->validate([
                'dataId' => 'required',

            ]);

            $inputId=$request->dataId;
            $remarksOfStatus= $request->remarks;

            $order_status=$request->statusUpdate;
            $currentDateTime = date('Y-m-d H:i:s');

            if($validated){
                $ins=array(
                    'order_status'=>$order_status,
                    'order_remark' => $request->remarks,
                    'updated_at'=>$currentDateTime ,
                );
                $insert_id1=OrderMedicine::whereId($inputId)->update($ins);
                if($insert_id1){
                    return [ 'status'=>1, 'message'=>"status saved successfully" ];
                }
                else{

                    return [ 'status'=>2, 'message'=>"failed to save status data" ];
                }


            }
            else{
                echo 2;
            }
        }
        public function UpdatepaymentStatus(Request $request){
            $validate=$request->validate([
                'orderId' => 'required',

            ]);

            $inputId=$request->orderId;
            $order_amt=$request->paymentamount;


            if($validate){
                $data=array(
                    'order_amt'=>$order_amt,
                    'payment_remarks'=>$request->remarks
                );
                $value_id3=OrderMedicine::whereId($inputId)->update($data);
                if($value_id3){
                    return [ 'status'=>1, 'message'=>"Payment updated successfully" ];
                }
                else{

                    return [ 'status'=>2, 'message'=>"failed to update payment" ];
                }


            }
            else{
                echo 2;
            }

        }
        public function getUhidNumber(Request $request){
            $patientData=PatientRegistration::select('id','uhidno','name','last_name','mobile_number','dob','gender')
            ->where('uhidno', $request->uhidValue)->first();
            if($patientData==null){
                $patientData=[];
            }
                return response ()->json($patientData);
    }
    public function saveorderMedicine(Request $request){
        $validated = $request->validate([
            'pid' => 'required',
            'medicine_options_name_' => 'required|array|min:1',

        ]);
        if($validated){
        $prescriptionImage=$request->selectedTabletType;

        $dataarray=array(
            'patient_id'=>$request->pid,
            'user_id'=>0,
            'order_amt'=>$request->medicineAmount
        );
        $medicineData=OrderMedicine::create($dataarray);
        $medicineid= $medicineData->id;
        $user_id=Auth::id();

        $medicineData = $request->input('medicine_options_name_');
        foreach ($medicineData as $index => $medicineName) {
            $id = $request->input('medicine_id')[$index];
            $name = $medicineName;
            $dose = $request->input('dose')[$index];
            $medicineDetails=array(
                            'medicine_type'=>3,
                            'order_id'=>$medicineid,
                            'image_path'=> $name ,
                            'select_id'=> $id,
                            'medicine_qty'=> $dose,
                            'created_by_admin'=> $user_id
                        );
                        $datadetails=OrderMedicineType::insert($medicineDetails);



        }
        if($datadetails){
            return [ 'status'=>1, 'message'=>"Medicine Added Successfully" ];

        }else{
            return [ 'status'=>2, 'message'=>"OOPs sorry..." ];

        }

        }else{
            echo 2;
        }




        }
        public function productInvoiceStatus(Request $request){
            $validated = $request->validate([
                'invoiceImg' => 'required|mimes:pdf',
    
            ]);
            if( $validated){
            $inputId=$request->dataId;
                $file   = $request->file('invoiceImg');

                $name =time().'.'.$file->extension();
                $file->move(public_path('images/productInvoice'), $name);
                $file_name="images/productInvoice/";
                $img=$file_name.$name;

                $newsData['invoice_product_path']= $img;                            
                $insert_id1=Purchase_Product::whereId($inputId)->update($newsData);
                if($insert_id1){
                return [ 'status'=>1, 'message'=>"Invoice Added Successfully" ];
    
            }else{
                return [ 'status'=>2, 'message'=>"OOPs sorry..." ];
    
            }
        }else{
            echo 2;
        }
    
    
    
    
    
            }

            public function medicineInvoiceStatus(Request $request){
                $validated = $request->validate([
                    'invoiceImg' => 'required|mimes:pdf',
        
                ]);
                if( $validated){
                $inputId=$request->dataId;
                    $file   = $request->file('invoiceImg');
    
                    $name =time().'.'.$file->extension();
                    $file->move(public_path('images/medicineInvoice'), $name);
                    $file_name="images/medicineInvoice/";
                    $img=$file_name.$name;
    
                    $newsData['invoice_medicine_path']= $img;                            
                    $insert_id1=OrderMedicine::whereId($inputId)->update($newsData);

                    if($insert_id1){
                    return [ 'status'=>1, 'message'=>"Invoice Added Successfully" ];
        
                }else{
                    return [ 'status'=>2, 'message'=>"OOPs sorry..." ];
        
                }
            }else{
                echo 2;
            }
        
        
        
        
        
                }
    

    }
        // public function Displaypaymentstatus(Request $request){
        //     $display=$request->validate([
        //         'orderId' => 'required',

        //     ]);

        //     $inputId=$request->orderId;
        //     $order_amt=$request->paymentamount;
        //     $data=array(
        //         'order_amt'=>$order_amt,
        //     );
        //     $value=OrderMedicine::



        // }













?>
