<?php

namespace App\Http\Controllers\users;
use App\Models\Masters\SpecialistMaster;
use App\Models\PatientAppointment;
use App\Models\PatientRegistration;
use Carbon\Carbon;
use App\Models\PatientBilling;
use App\Models\TestResultDatas;
use App\Models\PatientserviceItems;
use App\Models\TestResults;
use App\Http\Controllers\HistoryController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
class ClientController extends Controller
{

    public function public_user()
    {

        return view('public_user/user');
    }

    public function account_delete()
    {
        return view('public_user/delete_app_account');
    }

    /**
     * save book appointment
     * @param Request $request
     * @return array
     */
    public function saveBookAppointment(Request $request)
    {
        $validated = $request->validate([
            'salutation'     => 'required',
            'patient_name'     => 'required',
            'sur_name'=> 'required',
            'dob'   => 'required',
            'mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
//            'email'   => 'required',
            'email' => 'regex:/(.+)@(.+)\.(.+)/i',
            'gender'   => 'required',
            'department'   => 'required',
            'specialist'   => 'required',
            'branch'   => 'required',
            'appointment_date'   => 'required',
            'appointment_time'   => 'required',
        ]);
        if($validated) {
            $ins_data = array(
                'salutation_id' => $request->salutation,
                'patientname' => $request->patient_name,
                'last_name' => $request->sur_name,
                'appointment_type' => 1,
                'dob' => $request->dob ? date("Y-m-d", strtotime($request->dob)) : NULL,
                'age' => Carbon::parse($request->dob)->diff(Carbon::now())->y,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'gender' => $request->gender,
                'department_id' => $request->department,
                'specialist_id' => $request->specialist,
                'appointment_date' => $request->appointment_date ? date("Y-m-d", strtotime($request->appointment_date)) : NULL,
                'appointment_time' => $request->appointment_time,
                'created_by' => Auth::id(),
                'branch_id' => $request->branch,
                'patient_id'=>$request->pid
            );

            if ($request->crude == 1) {
                $insert = PatientAppointment::create($ins_data);
                $patient_appointment_id = $insert->id;
                return ['status' => 1, 'message' => "Saved Successfully", 'id' => $patient_appointment_id];

            }
        }else{
            echo 2; // validation error
        }
    }

    /**
     *
     * get consultant list in dropdown
     */
    public function consultantList(Request $request)
    {
        $status = 'false';
        if(! is_null($request->department_id)) {
            $specialists = SpecialistMaster::where('department_id', $request->department_id)
                ->select('specialist_name', 'id')
                ->where('is_deleted',0)
                ->where('display_status', 1)
                ->get();
            $status = 'true';
        }

        return Response::json(['status' => $status, 'data' => $specialists]);
    }



    /**
     *  old registration list
     * @param Request $request
     */
    public function oldRegistrationList(Request $request)
    {
        $status = 'false';
        if( $request->uhid_no != ""){
            if (PatientRegistration::where('uhidno', '=', $request->uhid_no)->exists()) {
                $patientOldRegData = PatientRegistration::select('patient_registration.*','specialist_master.specialist_name')
                    ->leftjoin('specialist_master','specialist_master.id','=','patient_registration.specialist_id')
                    ->where('uhidno',$request->uhid_no)
                    ->orderByDesc('id')
                    ->first();
                $status = 'true';
                return Response::json(['status' => $status, 'data' => $patientOldRegData]);

            } else {
                return Response::json(['status' => $status, 'message' =>'Invalid registration number' ]);

            }
        }
        else{
            return Response::json(['status' => $status, 'message' =>'Invalid registration number' ]);
        }


    }
    public function public_print_result($base_64id){

        $billnum =base64_decode($base_64id);
        $bill_no = intval($billnum);

        $patientDAta=PatientBilling::select('PatientId')->where('id',$bill_no)->first();
        // $bill_no=3;
        $result_data=$this->getresultEntryData($bill_no, $patientDAta->PatientId,$bill_no,0);
         $patient_billing_id=$bill_no;

      //   dd($result_data);

    //   var_dump($result_data);
        $data=[];

        $data['patient_data']=$result_data['patient_data'];

        // print_r($data['patient_data']); exit;

        $data['test_results']=$result_data['test_results'];
        $data['patient_billing_id']=$result_data['patient_billing_id'];
        $data['visit_id']=$result_data['visit_id'];
        $data['colours']=$result_data['colours'];
        $data['clarity']=$result_data['clarity'];
        $data['drName']=$result_data['drname'];


        $data['testreultsDates']  = $result_data['testreultsDates'];
        $tes_dates= $result_data['testreultsDates'];
        $data['is_result_enterd']=$result_data['is_result_enterd'];


        $lab_no= getAfeild("custom_lab_no","patient_billing",[['id',$patient_billing_id]]);
        $data['lab_no']=$lab_no;

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


        //   echo(json_encode($data['test_results'] )); exit;
        $pdf = \PDF::loadView('webpanel.reports.public_result_print',[],['data' => $data]);



       return  $pdf->stream('result_print.pdf');


    }
    private function getresultEntryData($patient_billing_id, $patient_id,$billing_type,$print=0)
    {
        $patient_id=$patient_id;
        $billing_type=$billing_type;
        $patient_billing_id=$patient_billing_id;

        echo $patient_billing_id;

        $patient_data=PatientRegistration::select('id','name','dob','uhidno','gender','address','last_name')->where('id',$patient_id)
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

}
