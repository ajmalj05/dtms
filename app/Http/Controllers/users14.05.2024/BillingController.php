<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\Billing\PaymentModeMaster;
use App\Models\Ipd\IpAdmission;
use App\Models\PatientBillingAccounts;
use App\Models\PatientBillingPayment;
use App\Models\PatientRegistration;
use App\Models\PatientserviceItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\PatientVisits;
use App\Models\PatientBilling;
use App\Models\Billing\ServiceItemMaster;
use App\Models\TestMasterExt;
use App\Models\TestResults;
use App\Http\Controllers\HistoryController;
use App\Models\Billing\ServiceGroupMaster;


class BillingController extends Controller
{


    protected $HistoryController;

    public function __construct(HistoryController $HistoryController)
    {
        $this->HistoryController = $HistoryController;
    }


    // Bill type 1= OP, 2=IP, 3=lab

    public function index(Request $request)
    {
        $billing_type=$request->billing_type;

        $ip_id=$request->ipd_id;
        $data['visit_id_main']=$request->visit_id;

        if($ip_id>0)
        {
            $patientData = IpAdmission::select('ip_admission.*', 'specialist_master.specialist_name','patient_registration.name'
            ,'patient_registration.age','patient_registration.dob', 'patient_billing.billing_type')
            ->leftjoin('patient_registration','patient_registration.id','=','ip_admission.patient_id')
            ->leftjoin('specialist_master','specialist_master.id','=','ip_admission.specialist_id')
            ->leftjoin('patient_billing','patient_billing.ipd_id','=','ip_admission.id')
            ->where('ip_admission.patient_id',$request->patient_id)
            ->where('ip_admission.id',$request->ipd_id)
            ->orderByDesc('ip_admission.id')
            ->first();

            Session::put('dtms_pid', $patientData->patient_id);
            Session::put('dtms_ipd_id', $request->ipd_id);
            $data['ip_id']=$ip_id;
            Session::put('dtms_billing_type', $patientData->billing_type);

            $data['details'] = $patientData;
        }
        else{
            $patientVisitList = PatientVisits::select('patient_visits.patient_id','patient_visits.visit_type_id','patient_visits.specialist_id', 'patient_visits.id','patient_visits.visit_code', 'patient_visits.visit_date', 'visit_type_master.visit_type_name','patient_registration.name','patient_registration.age','patient_registration.dob','specialist_master.specialist_name')
            ->join('visit_type_master','visit_type_master.id','=','patient_visits.visit_type_id')
            ->join('patient_registration','patient_registration.id','=','patient_visits.patient_id')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_visits.specialist_id')
            ->where('patient_visits.id',$request->visit_id)
            ->where('patient_visits.patient_id',$request->patient_id)
            ->orderByDesc('patient_visits.id')
            ->first();

            Session::put('dtms_pid', $patientVisitList->patient_id);
            Session::put('dtms_visitid', $patientVisitList->id);
            Session::put('dtms_billing_type', $request->billing_type);
            $data['ip_id']=0;
            $data['details'] = $patientVisitList;
        }






        $data['PageName']="Billing ";
        $data['billing_type'] = $request->billing_type;

//        $data['billno']= str_pad(($billNo->id + 1),3,'0',STR_PAD_LEFT) ;


        $where=array(
            'display_status'=>1,
            'is_service_item'=>2,
            'is_deleted'=>0
        );
        $test_groups= DB::table('test_master')->where($where)->get();
        $data['test_groups']=$test_groups;

        return Parent::page_maker('webpanel.billing.billinghome',$data);

    }
    public function getTestItemList(REQUEST $request)
    {
        $groupId=$request->groupId;
        $filldata = TestMasterExt::select('TestName','TestRate','id','test_code')->where([['is_deleted',0],['display_status',1],['group_id',$groupId]])->orderByDesc('id')->get();
        return $filldata;
    }
    public function getServiceItemList(REQUEST $request){

        $groupId=$request->groupId;
       // $filldata = ServiceItemMaster::select('item_name','item_amount','id','item_code')->where([['is_deleted',0],['display_status',1],['service_group_id',$groupId]])->orderByDesc('id')->get();
       $filldata = TestMasterExt::select('TestName as item_name','TestRate as item_amount','id','test_code as item_code')->where([['is_deleted',0],['display_status',1],['group_id',$groupId]])->orderByDesc('id')->get();

        return $filldata;

    }

    /**
     * save billing data
     * @param Request $request
     * @return array
     */

    public function cancelBill(Request $request)
    {
        $billId=$request->billId;
        if($billId>0)
        {
            $ups_setings_data=array(
                'is_cancelled' => 1,
            );
            $update = DB::table('patient_billing')
            ->where('id',$billId)
            ->update($ups_setings_data);

            //update patientbillaccounts also

            $update2 = DB::table('patient_billing_accounts')
            ->where('PatientBillingId',$billId)
            ->update($ups_setings_data);

            if($update){

                    //check if result enterd or not
                    //if result enterd delete result from test_results
                    // hide canceleed bill from resultentry

                    //is_result_enterd

                    $cond=array(
                        array('id',$billId),
                     );

                    $is_result_enterd=getAfeild("is_result_enterd","patient_billing",$cond);
                     if($is_result_enterd==1)
                     {
                        //delete
                        $deleteTest=TestResults::where('bill_id',$billId)->delete();

                     }

                return ['status' => 1, 'message' => "Cancelled Successfully"];
            }
            else{
                return ['status' => 2, 'message' => "Failed to cancell"];
            }
        }
    }

    public function saveBillingData(Request $request)
    {
        $validated = $request->validate([
            'payment_mode_name' => 'required',
            'total_paid' => 'required',
          //  'group_search' => 'required'
        ]);
        if($validated)
        {
            if ($request->total_paid <= $request->totalbill_amount) {

                    // $auto_increment_value=GenerateUhid(Session::get('current_branch'))  ;
                    //generate bill no automaticaly

                $auto_increment_value=GenerateBillNo(Session::get('current_branch'))  ;
                $billNo = Session::get('current_branch_code') . '-B' . ($auto_increment_value + 1);

                if($request->billing_type==3)
                {
                $lab_auto_increment_value=GenerateLabNo(Session::get('current_branch'))  ;
                $custom_lab_no = Session::get('current_branch_code') . '-LB' . ($lab_auto_increment_value + 1);
                }
                else{
                    $custom_lab_no="";
                }

                 $ip_id=0;
                 $visit_id=0;
                 if($request->id_data>0)
                 {
                    $ip_id=$request->id_data;
                 }

                 $visit_id= $request->visit_id;

                $patientBillData = [
                    'PatientLabNo' => $billNo,
                    'custom_lab_no'=>$custom_lab_no,
                    'visit_id' => $visit_id,
                    'is_service_external' => 0,
                    'PatientId' => $request->patient_id,
                    'specialist_id' => $request->specialist_id,
                    'ipd_id' => $ip_id,
                    'billing_type' => $request->billing_type,
                    'bill_remarks'=>$request->bill_remarks,
                    'branch_id'=>Session::get('current_branch')
                ];
            //    dd($patientBillData);
                if ($request->crude == 1) {
                    $patientBilling = PatientBilling::create($patientBillData);
                    if($patientBilling)
                    {
                         //update setting///////////////////////////////////////////////////////////
                         $ups_setings_data=array(
                            'last_generated_number' => $auto_increment_value + 1,
                        );
                        $update = DB::table('billno_settings')
                        ->where('branch_id',Session::get('current_branch'))
                        ->where('bill_type',1)
                        ->update($ups_setings_data);
                        ////////////////////////////////////////////////////////////////////////////////

                        /////UpdateSettings2
                        if($request->billing_type==3)
                        {
                        $ups_setings_data2=array(
                            'last_generated_number' => $lab_auto_increment_value + 1,
                        );
                        $update = DB::table('billno_settings')
                        ->where('branch_id',Session::get('current_branch'))
                        ->where('bill_type',3)
                        ->update($ups_setings_data2);
                     }
                    }


               /*     $patientBalanceAmountData = [];
                    $patientBalanceAmountData = [
//                    'patient_billing_id' => $patientBilling->id,
                        'patient_id' => $request->patient_id,
                        'total_paid' => $request->total_paid,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];

                    PatientBillingPayment::insert($patientBalanceAmountData);*/

                    $patientServiceItemData = [];
                    if ($request->service_item_id != "") {
                        foreach ($request->service_item_id as $key => $billing) {
                            if ($billing) {
                                $patientServiceItemData[] = [
                                    'patientlabno' => null,
                                    'patientbillingid' => $patientBilling->id,
                                    'patientid' => $request->patient_id,
                                    'serviceitemamount' => $request->amount[$key],
                                    'is_outside_service' => 0,
                                    'quantity' => $request->quantity[$key],
                                    'serviceitem_discount' => $request->discount_percentage[$key] ?? 0,
                                    'serviceitemid' => $billing,
                                    'display_status' => 1,
                                    'branch_id' => Session::get('current_branch'),
                                    'created_by' => Auth::id(),
                                    'is_deleted' => 0,
                                    'unit_rate'=>$request->test_rate[$key],
                                    'unit_total'=>$request->test_amount[$key],
                                    'discount_amount'=>$request->discount_amount[$key],
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                    'is_test_group'=>$request->item_type[$key],
                                    'hide_in_bill'=>0
                                ];

                                if($request->item_type[$key]==1)
                                {
                                    // select and insert all sub test in biling with hiden flag
                                    $cond=array(
                                        'group_id'=>$billing,
                                        'is_deleted'=>0,
                                        'display_status'=>1

                                    );
                                    $filldata= TestmasterExt::select('test_master.id')->where($cond)->get();

                                        foreach($filldata as $i_item)
                                        {

                                            $patientServiceItemData[] = [
                                                'patientlabno' => null,
                                                'patientbillingid' => $patientBilling->id,
                                                'patientid' => $request->patient_id,
                                                'serviceitemamount' => 0,
                                                'is_outside_service' => 0,
                                                'quantity' => 1,
                                                'serviceitem_discount' =>0,
                                                'serviceitemid' => $i_item->id,
                                                'display_status' => 1,
                                                'branch_id' => Session::get('current_branch'),
                                                'created_by' => Auth::id(),
                                                'is_deleted' => 0,
                                                'unit_rate'=>0,
                                                'unit_total'=>0,
                                                'discount_amount'=>0,
                                                'created_at' => Carbon::now(),
                                                'updated_at' => Carbon::now(),
                                                'is_test_group'=>0,
                                                'hide_in_bill'=>1,
                                            ];
                                        } // foreach sub test items

                                } // if item is a group

                            }
                        }
                        //dd($patientServiceItemData);
                        PatientserviceItems::insert($patientServiceItemData);



                    }

                    //insert in part payemnt for collection
                    if($request->total_paid>0)
                    {

                        $recpt_auto_increment_value=GenerateReceiptNo(Session::get('current_branch'))  ;
                        $receiptNo = Session::get('current_branch_code') . '-R-ADV' . ($recpt_auto_increment_value + 1);


                        $patientBillingPayment = PatientBillingPayment::create([
                            'payment_mode' => $request->payment_mode_name,
                            'total_paid' => $request->total_paid,
                            'patient_id' => $request->patient_id,
                            'reference_number' => $request->reference_number,
                            'created_by' => Auth::id(),
                            'receipt_number'=>$receiptNo,
                            'branch_id'=>Session::get('current_branch'),
                        ]);
                            if($patientBillingPayment)
                            {
                                //update setting///////////////////////////////////////////////////////////
                                    $ups_setings_data_recp=array(
                                        'last_generated_number' => $recpt_auto_increment_value + 1,
                                    );
                                    $update = DB::table('billno_settings')
                                    ->where('branch_id',Session::get('current_branch'))
                                    ->where('bill_type',2)
                                    ->update($ups_setings_data_recp);
                                 ////////////////////////////////////////////////////////////////////////////////
                            }

                    }//end of part payment insertion


                    $discountAmount = ($request->discount_in_percentage > 0) ? ($request->total_amount * $request->discount_in_percentage) / 100 : 0;
                    $balanceAmount = $request->totalbill_amount - $request->total_paid;

                    DB::connection()->enableQueryLog(); // enable qry log

                    PatientBillingAccounts::create([
                        'PatientLabNo' => null,
                        'PatientBillingId' => $patientBilling->id,
                        'TotalAmount' => $request->total_amount,
                        'serviceCharge' => null,
                        'discount_in_percentage' => $request->discount_in_percentage,
                        'Discamount' => $discountAmount,
                        'Grossamount' => $request->totalbill_amount,
                        'NetAmount' => $request->totalbill_amount,
                        'PatientId' => $request->patient_id,
                        'patient_billing_mode_id' => $request->payment_mode_name,
                        'total_paid' => $request->total_paid,
                        'balance_amount' => $balanceAmount,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                        // TO GET LAST EXECUTED QRY
                        $query = DB::getQueryLog();
                        $lastQuery = end($query);
                        $sql = $lastQuery['query'];
                        $bindings = $lastQuery['bindings'];
                        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                        //end of qury

                        // LOG  // add parameters based on your need
                        $log=array(
                        'primarykeyvalue_Id'=>$patientBilling->id,
                        'user_id'=>Auth::id(), // userId
                        'log_type'=>1, // save
                        'table_name'=>'PatientBillingAccounts', // Save billing data
                        'qury_log'=>$sql,
                        'description'=>'DTMS =>OP Billing => Save Billing Data ',
                        'created_date'=>date('Y-m-d H:i:s'),
                        'patient_id'=>$request->patient_id,
                        );

                        $save_history = $this->HistoryController->saveHistory($log);
                        ////////////////////////////////////////////////////////////////////////////////////
                        // END OF LOG

                    $dataArray = [
                        'patient_id' => $request->patient_id,
                        'patient_billing_id' => $patientBilling->id,
                        'billing_type' => $request->billing_type,
                    ];
                    if ($patientBilling) {
                        return ['status' => 1, 'message' => "Saved Successfully", 'dataArray' => $dataArray];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];

                    }
                    return ['status' => 1, 'message' => 'Saved Successfully'];

                }
                else if ($request->crude == 2)
                {
                    $patientBilling = PatientBilling::where('id',$request->id_data)->first();

                    $bill_remarks=$request->bill_remarks;
                    if($bill_remarks)
                    {
                         $update_reamrsk=array(
                            'bill_remarks' => $bill_remarks,
                            );
                          $billUpdate_remarks = PatientBilling::where('id',$request->id_data)->update($update_reamrsk);
                    }


                    if($request->id_data){



                        PatientserviceItems::where('patientbillingid', $request->id_data)->delete();
                    }

                      $patientServiceItemData = [];

                    if ($request->service_item_id != "") {

                            foreach ($request->service_item_id as $key => $billing) {
                                if ($billing) {
                                        $patientServiceItemData[] = [
                                            'patientlabno' => null,
                                            'patientbillingid' => $request->id_data,
                                            'patientid' => $request->patient_id,
                                            'serviceitemamount' => $request->amount[$key],
                                            'is_outside_service' => 0,
                                            'quantity' => $request->quantity[$key],
                                            'serviceitem_discount' => $request->discount_percentage[$key] ?? 0,
                                            'serviceitemid' => $billing,
                                            'display_status' => 1,
                                            'branch_id' => Session::get('current_branch'),
                                            'created_by' => Auth::id(),
                                            'is_deleted' => 0,
                                            'unit_rate'=>$request->test_rate[$key],
                                            'unit_total'=>$request->test_amount[$key],
                                            'discount_amount'=>$request->discount_amount[$key],
                                            'created_at' => Carbon::now(),
                                            'updated_at' => Carbon::now(),
                                            'is_test_group'=>$request->item_type[$key],
                                            'hide_in_bill'=>0
                                        ];

                                        if($request->item_type[$key]==1)
                                        {
                                            // select and insert all sub test in biling with hiden flag
                                            $cond=array(
                                                'group_id'=>$billing
                                            );
                                            $filldata= TestmasterExt::select('test_master.id')->where($cond)->get();

                                                foreach($filldata as $i_item)
                                                {

                                                    $patientServiceItemData[] = [
                                                        'patientlabno' => null,
                                                        'patientbillingid' => $patientBilling->id,
                                                        'patientid' => $request->patient_id,
                                                        'serviceitemamount' => 0,
                                                        'is_outside_service' => 0,
                                                        'quantity' => 1,
                                                        'serviceitem_discount' =>0,
                                                        'serviceitemid' => $i_item->id,
                                                        'display_status' => 1,
                                                        'branch_id' => Session::get('current_branch'),
                                                        'created_by' => Auth::id(),
                                                        'is_deleted' => 0,
                                                        'unit_rate'=>0,
                                                        'unit_total'=>0,
                                                        'discount_amount'=>0,
                                                        'created_at' => Carbon::now(),
                                                        'updated_at' => Carbon::now(),
                                                        'is_test_group'=>0,
                                                        'hide_in_bill'=>1,
                                                    ];
                                                } // foreach sub test items

                                        } // if item is a group


                                }
                            }
                        PatientserviceItems::insert($patientServiceItemData);

                    }


                    $discountAmount = ($request->discount_in_percentage > 0) ? ($request->total_amount * $request->discount_in_percentage) / 100 : 0;
                    $balanceAmount = $request->totalbill_amount - $request->total_paid;
                    DB::connection()->enableQueryLog(); // enable qry log

                    PatientBillingAccounts::where('PatientBillingId',$request->id_data)->update([
                        'PatientLabNo' => null,
                        'PatientBillingId' => $request->id_data,
                        'TotalAmount' => $request->total_amount,
                        'serviceCharge' => null,
                        'discount_in_percentage' => $request->discount_in_percentage,
                        'Discamount' => $discountAmount,
                        'Grossamount' => $request->totalbill_amount,
                        'NetAmount' => $request->totalbill_amount,
                        'PatientId' => $request->patient_id,
                        'patient_billing_mode_id' => $request->payment_mode_name,
                        'total_paid' => $request->total_paid,
                        'balance_amount' => $balanceAmount,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                     // TO GET LAST EXECUTED QRY
                        $query = DB::getQueryLog();
                        $lastQuery = end($query);
                        $sql = $lastQuery['query'];
                        $bindings = $lastQuery['bindings'];
                        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                        //end of qury

                        // LOG  // add parameters based on your need
                        $log=array(
                        'primarykeyvalue_Id'=>$request->id_data,
                        'user_id'=>Auth::id(), // userId
                        'log_type'=>2, // Update
                        'table_name'=>'PatientBillingAccounts', // update billing data
                        'qury_log'=>$sql,
                        'description'=>'DTMS =>Op billing => Update billing data ',
                        'created_date'=>date('Y-m-d H:i:s'),
                        'patient_id'=>$request->id_data,
                        );

                        $save_history = $this->HistoryController->saveHistory($log);
                        ////////////////////////////////////////////////////////////////////////////////////
                        // END OF LOG


                    $dataArray = [
                        'patient_id' => $request->patient_id,
                        'patient_billing_id' => $request->id_data,
                        'billing_type' => $request->billing_type,
                    ];

                    if ($patientBilling) {
                        return ['status' => 1, 'message' => "Updated Successfully", 'dataArray' => $dataArray];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to Updated"];

                    }
                    return ['status' => 1, 'message' => 'Updated Successfully'];








                }
            } else {
                return ['status' => 3, 'message' => "Total paid amount should be less than total bill Amount"];
            }
        } else {
            echo 2; // validation error
        }
    }

    /**
     * get billing data
     */
    public function getBillingData(Request $request)
    {
        $cond=array();

        array_push($cond,['patient_visits.branch_id',Session::get('current_branch')]);



        if($request->avoidCancelled)
        {
            array_push($cond,['patient_billing.is_cancelled',0]);
        }

        if($request->patientId)
        array_push($cond,['patient_billing.PatientId',$request->patientId]);

        if($request->visitId)
        array_push($cond,['patient_billing.visit_id',$request->visitId]);

        if($request->billingType)
        array_push($cond,['patient_billing.billing_type',$request->billingType]);

        if($request->bill_from_date)
        {
            $from_date= date("Y-m-d", strtotime($request->bill_from_date));
            $to_date= date("Y-m-d", strtotime($request->bill_to_date));

            // array_push($cond,['patient_billing.created_at','>',$from_date]);
            // array_push($cond,['patient_billing.created_at','<=',$to_date]);

        }


        $patientBilling = PatientBilling::select('patient_billing.*','patient_billing_accounts.TotalAmount','patient_billing_accounts.serviceCharge',
        'payment_mode_master.payment_mode_name',
            'patient_billing_accounts.Discamount','patient_billing_accounts.Grossamount','patient_billing_accounts.NetAmount'
            ,'patient_billing_accounts.patient_billing_mode_id','patient_billing_accounts.TotalAmount','patient_billing_accounts.total_paid',
            'patient_billing_accounts.balance_amount','patient_billing_accounts.discount_in_percentage','patient_billing_accounts.total_paid', 'patient_visits.id as patient_visit_id', 'patient_visits.visit_date','patient_registration.name','patient_registration.uhidno')
            ->join('patient_billing_accounts','patient_billing_accounts.PatientBillingId','=','patient_billing.id')
            ->join('payment_mode_master','payment_mode_master.id','=','patient_billing_accounts.patient_billing_mode_id')
            ->join('patient_visits','patient_visits.id','=','patient_billing.visit_id')
            ->join('patient_registration','patient_registration.id','=','patient_billing.PatientId')
            ->where($cond);

            if($request->bill_from_date)
            {
                $from_date= date("Y-m-d", strtotime($request->bill_from_date));
                $to_date= date("Y-m-d", strtotime($request->bill_to_date.' +1 day'));

                // array_push($cond,['patient_billing.created_at','>',$from_date]);
                // array_push($cond,['patient_billing.created_at','<=',$to_date]);

               // dd($to_date);

                $patientBilling= $patientBilling->whereBetween('patient_billing.created_at', [$from_date, $to_date]);
            }


          $patientBilling= $patientBilling->orderByDesc('patient_billing.id')
            ->get();
        $output = array(
            "recordsTotal" => count($patientBilling),
            "recordsFiltered" => count($patientBilling),
            "data" => $patientBilling
        );
        echo json_encode($output);
    }

    /**
     * outstanding amount
     * @param Request $request
     */
    public function getOutStandingData(Request $request)
    {


        $totalOutstanding =  PatientBillingAccounts::where('patient_billing_accounts.PatientId',$request->patientId)
            ->where('patient_billing.is_cancelled',0)
            ->join('patient_billing','patient_billing.id','=','patient_billing_accounts.PatientBillingId')
            ->sum('balance_amount');

        echo json_encode($totalOutstanding);

    }

    /**
     * store outstanding data in db
     * @param Request $request
     * @return array
     */
    public function saveOutStandingData(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => 'required',
            'payment_mode' => 'required',
            'amount_paid' => 'required',
        ]);


        if($validated) {
            if($request->crude==1) {
                // $getId = getAfeild("id","patient_billing_payments",[
                //     ['patient_id',$request->patient_id],
                // ]);
                $getId=1;
                if ($getId) {

                    // $totalOutstandingAmount =  PatientBillingAccounts::where('patient_billing_accounts.PatientId',$request->patientId)
                    // ->where('patient_billing_accounts.is_cancelled',0)
                    // ->sum('balance_amount');

            $totalOutstandingAmount=PatientBillingAccounts::where('patient_billing_accounts.PatientId',$request->patient_id)
            ->where('patient_billing.is_cancelled',0)
            ->join('patient_billing','patient_billing.id','=','patient_billing_accounts.PatientBillingId')
            ->sum('balance_amount');

                 //   dd($totalOutstandingAmount);
                    // $totalOutstandingAmount = PatientBillingAccounts::where('patient_billing_accounts.PatientId',$request->patient_id)->sum('balance_amount');

                    if ($totalOutstandingAmount >= $request->amount_paid) {


                        $recpt_auto_increment_value=GenerateReceiptNo(Session::get('current_branch'))  ;
                        $receiptNo = Session::get('current_branch_code') . '-R-OLD' . ($recpt_auto_increment_value + 1);

                        DB::connection()->enableQueryLog(); // enable qry log

                        $patientBillingPayment = PatientBillingPayment::create([
                            'payment_mode' => $request->payment_mode,
                            'total_paid' => $request->amount_paid,
                            'patient_id' => $request->patient_id,
                            'reference_number' => $request->reference_number,
                            'created_by' => Auth::id(),
                            'receipt_number'=>$receiptNo,
                            'branch_id'=>Session::get('current_branch'),
                        ]);


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
                            'log_type'=>1, // Save
                            'table_name'=>'PatientBillingPayment', // Save Part Payment
                            'qury_log'=>$sql,
                            'description'=>'DTMS => Op billing, Save Part Payment',
                            'created_date'=>date('Y-m-d H:i:s'),
                            'patient_id'=>$request->patient_id,
                            );

                            $save_history = $this->HistoryController->saveHistory($log);
                            ////////////////////////////////////////////////////////////////////////////////////
                            // END OF LOG

                        if($patientBillingPayment)
                            {
                                //update setting///////////////////////////////////////////////////////////
                                    $ups_setings_data_recp=array(
                                        'last_generated_number' => $recpt_auto_increment_value + 1,
                                    );
                                    $update = DB::table('billno_settings')
                                    ->where('branch_id',Session::get('current_branch'))
                                    ->where('bill_type',2)
                                    ->update($ups_setings_data_recp);
                                 ////////////////////////////////////////////////////////////////////////////////
                            }


                        $billings = PatientBillingAccounts::select('patient_billing_accounts.*')
                            ->where('PatientId',$request->patient_id)
                            ->where('is_cancelled',0)
                            ->where('balance_amount', '>',0)->orderBy('balance_amount','ASC')->get();
                        $amountBalanceInCollectedCash = $request->amount_paid;
                        foreach ($billings as $item) {
                            if ($amountBalanceInCollectedCash >= $item->balance_amount) {
                                $amountBalanceInCollectedCash = $amountBalanceInCollectedCash - $item->balance_amount;
                                PatientBillingAccounts::where('id', $item->id)->update([
                                    'balance_amount' => 0,
                                    'total_paid' => $item->total_paid + $item->balance_amount,
                                ]);
                            } else {
                                if ($amountBalanceInCollectedCash > 0) {
                                    PatientBillingAccounts::where('id', $item->id)->update([
                                        'balance_amount' => $item->balance_amount - $amountBalanceInCollectedCash,
                                        'total_paid' => $item->total_paid + $amountBalanceInCollectedCash,
                                    ]);
                                    $amountBalanceInCollectedCash = 0;
                                }
                            }
                        }
                        $outstandingData = [
                            'patient_id' => $request->patient_id,
                            'patient_billing_payment_id' => $patientBillingPayment->id,
                        ];
                        return [
                            'status'=>1,
                            'message'=>"Saved Successfully" ,
                            'total_balance' => PatientBillingAccounts::where('patient_billing_accounts.PatientId',$request->patient_id)->sum('balance_amount'),
                            'out_standing_data' => $outstandingData
                        ];
                    } else {
                        return [ 'status'=>3, 'message'=>"Enter amount less than outstanding bill" ];
                    }
                } else {
                    return [ 'status'=>4, 'message'=>"Invalid request" ];
                }
            }

        }
        else{
            echo 2;
        }

    }

    /**
     * get total outstanding data
     */
    public function getTotalOutstandingData(Request $request)
    {
        $cond=array();
        array_push($cond,['patient_billing_payments.patient_id',$request->patientId]);
        $patientTotalOutstandingData = PatientBillingPayment::select('patient_billing_payments.*', 'payment_mode_master.payment_mode_name')
            ->join('payment_mode_master','payment_mode_master.id','=','patient_billing_payments.payment_mode')
            ->where($cond)
            ->orderByDesc('patient_billing_payments.id')
            ->get();
        $output = array(
            "recordsTotal" => count($patientTotalOutstandingData),
            "recordsFiltered" => count($patientTotalOutstandingData),
            "data" => $patientTotalOutstandingData
        );
        echo json_encode($output);
    }

    /**
     * ipd billing
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function ipdBillingIndex(Request $request)
    {
        $patientData = IpAdmission::select('ip_admission.*', 'specialist_master.specialist_name','patient_registration.name'
            ,'patient_registration.age','patient_registration.dob', 'patient_billing.billing_type')
            ->leftjoin('patient_registration','patient_registration.id','=','ip_admission.patient_id')
            ->leftjoin('specialist_master','specialist_master.id','=','ip_admission.specialist_id')
            ->leftjoin('patient_billing','patient_billing.ipd_id','=','ip_admission.id')
            ->where('ip_admission.patient_id',$request->patient_id)
            ->where('ip_admission.id',$request->ipd_id)
            ->orderByDesc('ip_admission.id')
            ->first();
        Session::put('dtms_pid', $patientData->patient_id);
        Session::put('dtms_ipd_id', $patientData->id);
        Session::put('dtms_billing_type', $patientData->billing_type);
        $data['PageName']="Billing ";
        $data['billing_type'] = $request->billing_type;
        $data['details'] = $patientData;
        return Parent::page_maker('webpanel.ipd.billinghome',$data);

    }

    /**
     * save ipd billing data
     * @param Request $request
     * @return array
     */
    public function saveIpdBillingData(Request $request)
    {
        $validated = $request->validate([
            'payment_mode_name' => 'required',
            'total_paid' => 'required',
            'group_search' => 'required'
        ]);
        if($validated)
        {
            if ($request->total_paid <= $request->totalbill_amount) {
                $patientBillData = [
                    'PatientLabNo' => null,
                    'visit_id' => null,
                    'ipd_id' => $request->ipd_id,
                    'billing_type' => $request->billing_type,
                    'is_service_external' => 0,
                    'PatientId' => $request->patient_id,
                    'specialist_id' => $request->specialist_id,

                ];
                if($request->crude==1)
                {
                    $patientBilling = PatientBilling::create($patientBillData);

                    $patientBalanceAmountData = [];
                    $patientBalanceAmountData = [
                        'patient_id' => $request->patient_id,
                        'total_paid' => $request->total_paid,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];

                    PatientBillingPayment::insert($patientBalanceAmountData);

                    $patientServiceItemData= [];
                    if($request->service_item_id !=""){
                        foreach ($request->service_item_id as $key => $billing) {
                            if($billing){
                                $patientServiceItemData[] = [
                                    'patientlabno' => null,
                                    'patientbillingid' => $patientBilling->id,
                                    'patientid' => $request->patient_id,
                                    'serviceitemamount' =>$request->amount[$key],
                                    'is_outside_service' => 0,
                                    'quantity' => $request->quantity[$key],
                                    'serviceitem_discount'  => $request->discount_percentage[$key]?? 0,
                                    'serviceitemid' =>$billing,
                                    'display_status' => 1,
                                    'branch_id' => Session::get('current_branch'),
                                    'created_by' => Auth::id(),
                                    'is_deleted' => 0,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ];
                            }
                        }
                        PatientserviceItems::insert($patientServiceItemData);
                    }


                    $discountAmount = ($request->discount_in_percentage > 0) ? ($request->total_amount * $request->discount_in_percentage) / 100 : 0;
                    $balanceAmount = $request->totalbill_amount - $request->total_paid;
                    PatientBillingAccounts::create([
                        'PatientLabNo' => null,
                        'PatientBillingId' => $patientBilling->id,
                        'TotalAmount' => $request->total_amount,
                        'serviceCharge' => null,
                        'discount_in_percentage' => $request->discount_in_percentage,
                        'Discamount' => $discountAmount,
                        'Grossamount' =>$request->totalbill_amount,
                        'NetAmount' => $request->totalbill_amount,
                        'PatientId' => $request->patient_id,
                        'patient_billing_mode_id' => $request->payment_mode_name,
                        'total_paid' => $request->total_paid,
                        'balance_amount' => $balanceAmount,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $dataArray = [
                        'patient_id' => $request->patient_id,
                        'patient_billing_id' => $patientBilling->id,
                        'ipd_id' => $request->ipd_id,
                        'billing_type' => $request->billing_type,
                    ];
                    if ($patientBilling) {
                        return ['status' => 1, 'message' => "Saved Successfully", 'dataArray' => $dataArray];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];

                    }
                    return [ 'status'=> 1, 'message' => 'Saved Successfully' ];

                }
            } else {
                return ['status' => 3, 'message' => "Total paid amount should be less than total bill Amount"];
            }
        } else {
            echo 2; // validation error
        }
    }

    /**
     * get ipd billing data
     */
    public function getIpdBillingData(Request $request)
    {
        $cond=array();
        array_push($cond,['patient_billing.PatientId',$request->patientId]);
        array_push($cond,['patient_billing.ipd_id',$request->ipdId]);
        array_push($cond,['patient_billing.billing_type',$request->billingType]);
        $patientBilling = PatientBilling::select('patient_billing.*','patient_billing_accounts.TotalAmount','patient_billing_accounts.serviceCharge',
            'patient_billing_accounts.Discamount','patient_billing_accounts.Grossamount','patient_billing_accounts.NetAmount'
            ,'patient_billing_accounts.patient_billing_mode_id','patient_billing_accounts.TotalAmount','patient_billing_accounts.total_paid',
            'patient_billing_accounts.balance_amount', 'ip_admission.admission_date', 'ip_admission.id as ipd_id')
            ->join('patient_billing_accounts','patient_billing_accounts.PatientBillingId','=','patient_billing.id')
            ->join('payment_mode_master','payment_mode_master.id','=','patient_billing_accounts.patient_billing_mode_id')
            ->join('ip_admission','ip_admission.id','=','patient_billing.ipd_id')
            ->where($cond)
            ->orderByDesc('patient_billing.id')
            ->get();
        $output = array(
            "recordsTotal" => count($patientBilling),
            "recordsFiltered" => count($patientBilling),
            "data" => $patientBilling
        );
        echo json_encode($output);
    }


    public function collection_report()
    {

        $data['PageName']="Collection Report ";
        return Parent::page_maker('webpanel.reports.collectionReport',$data);
    }

    public function lab_bill_integration()
    {
        $data['PageName']="Lab Bill Integration";

      //  $qry=SELECT * from patient_billing where visit_id=null and PatientLabNo!=''";


        $bills=PatientBilling::select('patient_billing.*')->whereNull('visit_id')
        ->whereNotNull('PatientLabNo')->get();
        $data['bills']=$bills;

        return Parent::page_maker('webpanel.billing.labBillIntegration',$data);
    }

    public function visit_bill_update(Request $request)
    {
        $ins_data=array(
            'visit_id' => $request->visitId,
        );
        $billUpdate = PatientBilling::where('id',$request->billId)->where('PatientId',$request->pid)->update($ins_data);
        if($billUpdate)
        {
            //visit update
            //patientLabNo
            $ins_data=array(
                'visit_id' => $request->visitId,
            );

            $resultupdate = TestResults::where('Labno',$request->patientLabNo)->where('PatientId',$request->pid)->update($ins_data);
            if($resultupdate)
            {
                return [ 'status'=>1, 'message'=>"Data updated Successfully" ];
            }
            else{
                return [ 'status'=>3, 'message'=>"Failed to update test" ];
            }
        }
        else{
            return [ 'status'=>3, 'message'=>"Failed to update bill master" ];
        }
    }

    public function get_billingdata_byid(Request $request)
    {
        $data=[];
        $patientData = PatientRegistration::select('patient_registration.name','patient_registration.age','patient_registration.dob',
            'patient_registration.uhidno','patient_registration.gender','patient_registration.address','patient_registration.mobile_number',
            'specialist_master.specialist_name')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_registration.specialist_id')
            ->where('patient_registration.id', $request->patient_id)
            ->first();
        $billing= PatientBilling::select('patient_billing.id', 'patient_billing.created_at','patient_billing.specialist_id',
            'specialist_master.specialist_name')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_billing.specialist_id')
            ->orderBy('patient_billing.id','DESC')
            ->where('patient_billing.billing_type', $request->billing_type)
            ->take(1)
            ->first();
        $billingDate =Carbon::parse($billing->created_at)->format('d-m-Y');
        $billNo =str_pad(($billing->id + 1),3,'0',STR_PAD_LEFT) ;

        // $serviceItems = PatientserviceItems::where('patientbillingid',$request->patient_billing_id)
        //     ->select('patient_service_items.*', 'service_item_master.item_name','service_item_master.item_code',
        //         'service_item_master.item_amount', 'service_item_master.service_group_id')
        //     ->leftjoin('service_item_master','service_item_master.id','=','patient_service_items.serviceitemid')
        //     ->orderByDesc('patient_service_items.id')
        //     ->where('patientid', $request->patient_id)
        //     ->get();
        //Service Item Merged with test master

        $serviceItems = PatientserviceItems::where('patientbillingid',$request->patient_billing_id)->where('hide_in_bill',0)
        ->select('patient_service_items.*', 'test_master.TestName as item_name','test_master.test_code as item_code',
            'test_master.TestRate as item_amount', 'test_master.group_id as service_group_id')
        ->leftjoin('test_master','test_master.id','=','patient_service_items.serviceitemid')
        ->orderByDesc('patient_service_items.id')
        ->where('patientid', $request->patient_id)
        ->get();

        $billingAccounts = PatientBillingAccounts::select('patient_billing_accounts.*', 'payment_mode_master.payment_mode_name')
            ->join('payment_mode_master','payment_mode_master.id','=','patient_billing_accounts.patient_billing_mode_id')
            ->where('PatientBillingId', $request->patient_billing_id)
            ->where('PatientId', $request->patient_id)
            ->orderByDesc('patient_billing_accounts.id')
            ->first();

            $output = array(
                // "recordsTotal" => count($patientBilling),
                // "recordsFiltered" => count($patientBilling),
                "data" => ['patientData'=>$patientData,'billingAccounts'=>$billingAccounts,'serviceItems'=>$serviceItems,'billing'=>$billing]
            );
            echo json_encode($output);
    }


    public function getAllItemListBygrp(Request $request)
    {
//dd($request);

        $data=array();
        if($request->searchTerm){

            $searchItem=strtoupper($request->searchTerm);

            $cond=[];
            $cond2=[];

            array_push($cond,['TestName', 'ilike', '%' . $request->searchTerm . '%']);
            array_push($cond2,['test_code', 'ilike', '%' . $request->searchTerm . '%']);

            $testName='"TestName"';
            $testRate='"TestRate"';

            $qry="SELECT id,$testName,test_code,$testRate FROM test_master WHERE is_service_item=0 AND is_deleted=0 AND display_status=1
             AND ( UPPER($testName) LIKE '%$searchItem%' OR  test_code  LIKE '%$searchItem%') limit 25";



            // $filldata= TestmasterExt::select('test_master.id','test_master.TestName','test_master.test_code','test_master.TestRate')

            // ->where(function($query,$cond,$cond2){
            //     $query->orWhere($cond);
            //     $query->orWhere($cond2);

            // })
            // ->where('is_service_item',0)
            // ->where('is_deleted',0)
            // ->where('display_status',1)
            // ->limit(25)->get();
            $filldata=DB::select($qry);

            if($filldata){
                foreach ($filldata as $key => $value) {

                    $tescode=trim($value->test_code);

                    $data[]=array(
                                'id'=>$value->id,
                                'text'=>$value->TestName . " ($tescode) ",
                                'itemcode' =>$value->test_code,
                                    'itemamt'=>$value->TestRate,

                            );
                  }
            }

        }




        echo json_encode($data);




    }
}
