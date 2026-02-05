<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PatientRegistration;
use Illuminate\Support\Facades\DB;
use App\Models\PatientserviceItems;
use App\Models\TestResults;
use App\Models\TestResultDatas;
use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class LaboratoryController extends Controller
{


    // Add this method inside the controller
    public function sendSMS(Request $request)
    {
        // Retrieve the raw POST data from the AJAX request
        $data = file_get_contents("php://input");

        // Decode the JSON data to PHP associative array
        $patientData = json_decode($data, true);

        $pName = $patientData['name'];
        $ccontact = $patientData['mobile'];

        // Access patient data (name, age, uhid, contact)
        $patientName = rtrim($pName);
        $patientAge = $patientData['age'];
        $patientUHID = $patientData['uhid'];
        $contact = rtrim($ccontact);

        // Process SMS sending logic here

        // Your authentication key
        $authKey = "203072AnlNG6LV628dbf96P1";

        // Mobile number to send the SMS
        $mobileNumber = $contact;

        // Sender ID
        $senderId = "JOTDEV";

        // Your message with URL encoding
    //Your message to send, Add URL encoding here.
    $message = urlencode("Dear ".$patientName.", Your lab report will be ready in 1 hour. View patient feedback at www.feedback.jothydev.net
Thank you,
Jothydev's
www.jothydev.net");


        // Define route and template ID
        $route = "4";
        $tid = "1207172889030725468";

        // Prepare POST parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route,
            'DLT_TE_ID' => $tid
        );

        // API URL for SMS sending
        $url = "http://tx.kappian.com/api/sendhttp.php";

        // Initialize cURL
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));

        // Execute the cURL request
        $output = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            // Return error response
            return response()->json(['status' => 'error', 'message' => curl_error($ch)]);
        }

        // Close the cURL resource
        curl_close($ch);

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => "SMS sent to patient $patientName (UHID: $patientUHID)"
        ]);
    }


    protected $HistoryController;

    public function __construct(HistoryController $HistoryController)
    {
        $this->HistoryController = $HistoryController;
    }



    public function laboratory()
    {
        $data=array();

        $data['PageName']="Laboratory";
        return Parent::page_maker('webpanel.laboratory',$data);
    }

    public function getPatientLabdetails(Request $request){

        if(isset($request->id)){
            $patientid=$request->id;
        }


        $data['PageName']="Lab Details";
        return Parent::page_maker('webpanel.laboratorydetails',$data);

    }

    public function resultentry(Request $request){

        $data['PageName']="Bills";
        return Parent::page_maker('webpanel.lab_bills',$data);

        // if(isset($request->id)){
        //     $patientid=$request->id;
        // }

        // $data['PageName']="Results";
        // return Parent::page_maker('webpanel.lab_results',$data);

    }

    private function getresultEntryData(Request $request,$print=0)
    {
        //dd($request);
        $patient_id=$request->patient_id;
        $billing_type=$request->billing_type;
        $patient_billing_id=$request->patient_billing_id;

        $patient_data=PatientRegistration::select('id','name','dob','uhidno','gender','mobile_number','address','last_name','salutation_id')->where('id',$patient_id)
        ->orderBy('id','DESC')
        ->first();

        $data['patient_data']=$patient_data;

        $age=Carbon::parse($patient_data->dob)->diff(\Carbon\Carbon::now())->format('%y');

        //get all groups which in the bid
        $gender=0;

        $gend=$patient_data->gender;
        $gend=strtoupper($gend);
      //  echo $gend;

        if (strcmp($gend,'M') ==1 ) $gender=1;
        else if  (strcmp($gend,'F') ==1 ) $gender=2;
        else if  (strcmp($gend,'O') ==1 ) $gender=3;



        //get visit Id

        $visit_id= getAfeild("visit_id","patient_billing",[
            ['id',$patient_billing_id],
        ]);


        $is_result_enterd=getAfeild("is_result_enterd","patient_billing",[
            ['id',$patient_billing_id],
        ]);


        // $departemnt="SELECT DISTINCT tgm.id,tgm.groupname FROM test_group_master tgm
        //         INNER JOIN service_group_master sm on sm.department_id=tgm.id
        //         INNER JOIN test_master tm on tm.group_id=sm.id
        //         INNER JOIN patient_service_items pt on pt.serviceitemid=tm.id
        //         WHERE tm.is_service_item=0 and pt.patientbillingid=$patient_billing_id and pt.hide_in_bill=0
        //         ORDER BY tgm.id ASC
        // ";

        //select department for groups

        if($print)
        {
            $pcond=" AND  pt.test_result != '' ";
        }
        else{
            $pcond="";
        }

        $departemnt='SELECT DISTINCT tgm.id,tgm.groupname FROM test_group_master tgm
                INNER JOIN test_master tm on tm."TestDepartment"=tgm.id
                INNER JOIN patient_service_items pt on pt.serviceitemid=tm.id
                WHERE tm.is_service_item in(0,2) and pt.patientbillingid='.$patient_billing_id.' and pt.is_test_group=1 '.$pcond.'
                ORDER BY tgm.id ASC
        ';

        $departments=DB::select($departemnt);



      $departemnt='SELECT DISTINCT tgm.id,tgm.groupname FROM test_group_master tgm
                INNER JOIN test_master sm on sm."TestDepartment"=tgm.id
                INNER JOIN test_master tm on tm.group_id=sm.id
                INNER JOIN patient_service_items pt on pt.serviceitemid=tm.id
                WHERE tm.is_service_item=0 and pt.patientbillingid='.$patient_billing_id.' and pt.is_test_group=0 '.$pcond.'
                ORDER BY tgm.id ASC
        ';

        $departments2=DB::select($departemnt);



         // merge 2 departments
        //   $departments=array_merge($departments,$departments2);

         $departments=array_unique(array_merge($departments,$departments2), SORT_REGULAR);

           // dd($departments);
        //select department for item


        $resultArray=[];

        foreach($departments as $key)
        {
            $depid=$key->id;
            $depName=$key->groupname;
            $itemArray=[
                'depid'=>$depid,
                'depName'=>$depName
            ];


            // $qry="SELECT DISTINCT sm.id,sm.group_name FROM service_group_master sm
            // INNER JOIN test_master tm on tm.group_id=sm.id
            // INNER JOIN patient_service_items pt on pt.serviceitemid=tm.id
            // WHERE tm.is_service_item=0 and pt.patientbillingid=$patient_billing_id  and pt.hide_in_bill=0 and sm.department_id=$depid
            // ORDER BY sm.id ASC";

     $qry='SELECT DISTINCT sm.id,sm."TestName" as group_name FROM test_master sm
      INNER JOIN test_master tm on tm.group_id=sm.id
      INNER JOIN patient_service_items pt on pt.serviceitemid=tm.id WHERE tm.is_service_item in(0) and
      pt.patientbillingid='.$patient_billing_id.'  AND sm."TestDepartment"='.$depid.' '.$pcond.' ORDER BY sm.id ASC
            ';




            // $qry='SELECT DISTINCT sm.id,sm."TestName" as group_name FROM test_master sm
            // INNER JOIN test_master tm on tm.group_id=sm.id
            // INNER JOIN patient_service_items pt on pt.serviceitemid=tm.id
            // WHERE tm.is_service_item in(0,2) and pt.patientbillingid='.$patient_billing_id.'  and pt.hide_in_bill=0 and pt.is_test_group=0 and  sm."TestDepartment"='.$depid.'
            // ORDER BY sm.id ASC ';


            $groups=DB::select($qry);
            $group_array=[];




            foreach($groups as $key)
            {
                $groupId=$key->id;

                $itemArrayGroup=[
                    'groupId'=>$groupId,
                    'groupName'=>$key->group_name
                ];

                /////////////////// TEST ITEMS




                $test_list='SELECT tm.id,tm."TestName",tm.unit,tm.test_method,pt.test_result,pt.test_result_value,tm.result_type,tm.order_num,tm.report_data FROM test_master tm
                INNER JOIN patient_service_items pt on pt.serviceitemid=tm.id
                WHERE tm.is_service_item=0 and pt.patientbillingid='.$patient_billing_id.'  and pt.is_test_group=0 AND  tm.group_id='.$groupId.' '.$pcond.'
                ORDER BY tm.order_num ASC';

                $items=DB::select($test_list);
                $test_item=[];
                foreach($items as $test)
                {
                    $test_id=$test->id;


                    $config_q="SELECT * FROM test_config WHERE test_id=$test_id AND  gender=$gender AND ($age BETWEEN from_age AND to_age )";
                    $run=DB::select($config_q);
                    if(! $run)
                    {
                        echo " $test->TestName Configuration not found". $config_q; exit;
                    }
                    $result=$run[0];

                    $single_item=[
                        'test_id'=>$test_id,
                        'test_name'=>$test->TestName,
                        'result_type'=>$result->result_type,
                        'from_age'=>$result->from_age,
                        'to_age'=>$result->to_age,
                        'from_range'=>$result->from_range,
                        'to_range'=>$result->to_range,
                        'colour_id'=>$result->colour_id,
                        'clarity_id'=>$result->clarity_id,
                        'positive_negative'=>$result->positive_negative,
                        'unit'=>$test->unit,
                        'result'=>$test->test_result,
                        'test_method'=>$test->test_method,
                        'order_num' =>$test->order_num,
                        'report_data'=>$test->report_data,
                        'test_result_value'=>$test->test_result_value,
                    ];

                    array_push($test_item, $single_item);
                }

                ////////////////// END TEST ITEMS
                $itemArrayGroup['test_items']=$test_item;


                array_push($group_array,$itemArrayGroup);

            }// group array

            $itemArray['groups']=$group_array;

            array_push($resultArray,$itemArray);

        }  // departemnt loop



        $data['test_results']=$resultArray;
        $data['patient_billing_id']=$patient_billing_id;
        $data['visit_id']=$visit_id;

        //drname


         $specialist_id= getAfeild("specialist_id","patient_visits",[
            ['id',$visit_id],
        ]);
        if($specialist_id)
        {
            $drname=getAfeild("specialist_name","specialist_master",[
                ['id',$specialist_id],
            ]);
        }
        else{
            $drname="";
        }

        $data['drname']=$drname;
        $data['colours']=DB::table('test_colours')->get();
        $data['clarity']=DB::table('test_clarity')->get();
        $data['is_result_enterd']=$is_result_enterd;

        $data['testreultsDates']  = TestResultDatas::select('test_result_datas.*','users.name','users.id as user_id')
        ->join('users','users.id','=','test_result_datas.reported_by')
        ->where('bill_id',$patient_billing_id)->first();

        return $data;
    }

    public function manageResult(Request $request){

        $data['PageName']="Result Entry";

        $result_data=$this->getresultEntryData($request);

       $data['patient_data']=$result_data['patient_data'];
       $data['test_results']=$result_data['test_results'];
       $data['patient_billing_id']=$result_data['patient_billing_id'];
       $data['visit_id']=$result_data['visit_id'];
       $data['colours']=$result_data['colours'];
       $data['clarity']=$result_data['clarity'];

       $data['testreultsDates']  = $result_data['testreultsDates'];
       $tes_dates= $result_data['testreultsDates'];
       $data['is_result_enterd']=$result_data['is_result_enterd'];

      $is_resulte_enterd=$result_data['is_result_enterd'];
      if($is_resulte_enterd==0){
        $result_date=date('d-m-Y h:i A');
        $test_date=date('d-m-Y h:i A');
      }
      else{
       // dd(json_encode($tes_dates));

        if(isset($tes_dates))
        {
        $result_date=$tes_dates->result_date;
        $test_date=$tes_dates->test_date;

        $result_date=date("d-m-Y h:i A", strtotime( $result_date));
        $test_date=date("d-m-Y h:i A", strtotime( $test_date));
        }
        else{
            $result_date=date('d-m-Y h:i A');
            $test_date=date('d-m-Y h:i A');
        }

      }

      $data['result_date']=$result_date;
      $data['test_date']=$test_date;

       //  dd($result_date);
        return Parent::page_maker('webpanel.lab_results',$data);
    }
    

    public function printResult(Request $request)
    {

        $result_data=$this->getresultEntryData($request,1);


        $patient_billing_id=$request->patient_billing_id;


        $data['patient_data']=$result_data['patient_data'];
        $data['test_results']=$result_data['test_results'];
        $data['patient_billing_id']=$result_data['patient_billing_id'];
        $data['visit_id']=$result_data['visit_id'];
        $data['colours']=$result_data['colours'];
        $data['clarity']=$result_data['clarity'];
        $data['drName']=$result_data['drname'];


        $data['testreultsDates']  = $result_data['testreultsDates'];

        $data['selectedDepartments']=$request->selectedDepartments;
        $data['selectedGroups']=$request->selectedGroups;

        $lab_no= getAfeild("custom_lab_no","patient_billing",[['id',$patient_billing_id]]);
        $data['lab_no']=$lab_no;

        $result_print_type=$request->result_print_type;
        $data['result_print_type']=$result_print_type;

        if($result_print_type==0)
        {
            $pdf = \PDF::loadView('webpanel.reports.ressult-print',[],['data' => $data],
            [
                'title' => 'Another Title',
                'margin_header' => 50,
                'margin_top' => 70,
                'margin_bottom'=>50
            ]);

        }

       else if($result_print_type==1)
        {

            $branch_id = Session::get('current_branch');
            $branch_details = GetBranchDetails($branch_id);

            $data['branch_details'] = $branch_details;


            $pdf = \PDF::loadView('webpanel.reports.ressult-print',[],['data' => $data],
            [
            'title' => 'Another Title',
            'margin_header' => 10,
            'margin_top' => 70,
            'margin_bottom'=>50
            ]);

            // $pdf->getMpdf()->SetWatermarkText('Watermark Text'); // Set the watermark text
            // $pdf->getMpdf()->showWatermarkText = true; // Show the watermark

        $watermarkPath = public_path('./images/company-logo.png'); // Replace with the actual path to your watermark image
        $pdf->getMpdf()->SetWatermarkImage($watermarkPath);
        $pdf->getMpdf()->showWatermarkImage = true;
        $pdf->getMpdf()->watermarkImageAlpha = 0.3;

        }

       // $pdf->SetHTMLHeaderByName('MyHeader1');

       return  $pdf->stream('result_print.pdf');
    }

    public function saveResult(Request $request)
    {
      $billId=$request->billId;

      if($billId>0)
      {

        // $color_list=DB::table('test_colours')->get();
        // $clarity_list=DB::table('test_clarity')->get();


        //select all testid from patient service items for looping
        //test_result added in  PatientserviceItems
        $test_items =PatientserviceItems::select('patient_service_items.id','patient_service_items.serviceitemid','test_master.TestId')
        ->join('test_master','test_master.id','=','patient_service_items.serviceitemid')
        ->where('patient_service_items.patientbillingid',$billId)->get();
        $insCount=0;
      //  dd(json_encode($test_items));
        foreach($test_items as $key)
        {
            $boxName="test_result_".$key->serviceitemid;
            $test_result=$request->$boxName;
            if($test_result && $test_result!="")
            {

                // $qry='SELECT "TestId" FROM test_master WHERE id='.$key->serviceitemid.'';
                // $qrry=DB::select($qry);
                //  $testId=$qrry[0]->TestId;

                $testId=$key->TestId;
                $type_boxName="result_type_".$key->serviceitemid;

                $result_type=$request->$type_boxName;
                $find_result="";
                $hidden_result="";
                if($result_type==1){
                    // color
                    $find_result= getAfeild("color_name","test_colours",[['id',$test_result]]);
                }
                else if($result_type==2){
                    //rhange
                    $find_result=$test_result;
                }
                else if($result_type==3)
                {
                    // +ve,negative
                    if($test_result==1){
                       $find_result="Positive";
                    }
                    else if($test_result==2){
                        $find_result="Negative";
                     }
                     else if($test_result==3){
                        $find_result="Normal";
                     }
                     else if($test_result==-1){
                        $valu_boxName="test_result_add_".$key->serviceitemid;
                        $result_value_hiden=$request->$valu_boxName;
                        $find_result=$result_value_hiden;


                     }

                }
                else if($result_type==4){
                    //clarity
                    $find_result= getAfeild("clarity_name","test_clarity",[['id',$test_result]]);
                }

                $ins=[
                    'ResultValue'=>$find_result,
                    'TestId'=>$testId,
                    'PatientId'=>$request->pid,
                    'is_outside_lab'=>0,
                    'visit_id'=>$request->visit_id,
                    'is_manual_entry'=>1
                ];

                DB::connection()->enableQueryLog(); // enable qry log
                $results = TestResults::updateOrCreate(
                    [
                       // 'TestId'=> $key->serviceitemid,
                       'TestId'=> $testId,
                        'bill_id'=> $billId
                    ],
                    $ins
                 );

                 // TO GET LAST EXECUTED QRY
                $query = DB::getQueryLog();
                $lastQuery = end($query);
                $sql = $lastQuery['query'];
                $bindings = $lastQuery['bindings'];
                $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                //end of qury

                // LOG  // add parameters based on your need
                $log=array(
                'primarykeyvalue_Id'=>$request->pid,
                'user_id'=>Auth::id(), // userId
                'log_type'=>1, // save
                'table_name'=>'TestResults', // Save result
                'qury_log'=>$sql,
                'description'=>'Labs =>manageResult => Save Result ',
                'created_date'=>date('Y-m-d H:i:s'),
                'patient_id'=>$request->pid,
                );

                $save_history = $this->HistoryController->saveHistory($log);
                ////////////////////////////////////////////////////////////////////////////////////
                // END OF LOG

                 if($results)
                 {
                    //update

                    $up_data=[
                        'test_result'=>$test_result,
                        'test_result_value'=>$find_result
                    ];
                    $update=PatientserviceItems::where('patientbillingid',$billId)->where('serviceitemid',$key->serviceitemid)->update($up_data);

                    $insCount++;
                  $resultdata = TestResultDatas::where('bill_id',$billId)->first();

                    $result_date=$request->from_date;
                    $test_date= $request->test_date;

                    $result_date=date("Y-m-d H:i", strtotime( $result_date));
                    $test_date=date("Y-m-d H:i", strtotime( $test_date));


                  if($resultdata){
                    TestResultDatas::where('bill_id',$billId)->update([
                        'result_date' =>$result_date,
                        'test_date' => $test_date,
                        'reported_by' => $request->reported_by
                    ]);
                  }else{
                    TestResultDatas::insert([
                        'result_date' =>$result_date,
                        'test_date' => $test_date,
                        'bill_id' => $billId,
                        'reported_by' => $request->reported_by
                    ]);
                  }


                 }

            }
        } // foreach

        if($insCount>0)
        {
            //update billing to set resulr enterd=1
            $ups_setings_data=array(
                'is_result_enterd' => 1,
            );
            $update = DB::table('patient_billing')
            ->where('id',$billId)
            ->update($ups_setings_data);

            return ['status' => 1, 'message' => "Saved Successfully"];
        }
        else{
            return ['status' => 3, 'message' => "Add minimum one result to save"];
        }

      }
    }

}


