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
use PDF;
use App\Models\Billing\ServiceGroupMaster;



class ReportController extends Controller
{

public function detailed_bill_report(Request $request)
{
    $data['PageName']="Detailed Bill Report ";

    $cond="";
    if($request->from_date)
    {
        $from_date=$request->from_date;

        $from_date= date("Y-m-d", strtotime($from_date));
        $to_date= date("Y-m-d", strtotime($request->to_date));

        $data['from_date_input']=$request->from_date;
        $data['to_date_input']=$request->to_date;
    }
    else{
        $from_date=date("Y-m-d");
        $to_date=date("Y-m-d");
        $data['from_date_input']=date("d-m-Y");
        $data['to_date_input']=date("d-m-Y");
    }


    $branch_id=Session::get('current_branch');

    $bill_data=array();

    $qry="SELECT pb.created_at::date as billdate,pb.* FROM patient_billing pb
          inner join patient_visits pv on pv.id=pb.visit_id
          where  pb.is_cancelled=0 AND pv.branch_id=$branch_id and  pb.created_at::date>='$from_date' and pb.created_at::date<='$to_date' $cond
          order by pb.id ASC";

    $bills=DB::select($qry);
    foreach($bills as $bill)
    {

        $bid=$bill->id;
        $PatientId=$bill->PatientId;

        $cond=array(
            array('id',$PatientId),
         );

        $patientName=getAfeild("name","patient_registration",$cond);


        $billDetails = DB::table('patient_billing_accounts')->where('PatientBillingId',$bid)->get();

        $bill->patientName=$patientName;
        $bill->accounts=$billDetails;

        // $itemsQry="SELECT ps.*,sm.item_name as item_name FROM patient_service_items ps
        //     INNER JOIN service_item_master sm
        //      on sm.id=ps.serviceitemid
        //   where ps.patientbillingid=$bid";

        //merged with test master

        $itemsQry='SELECT ps.*,sm."TestName" as item_name FROM patient_service_items ps
        INNER JOIN test_master sm
         on sm.id=ps.serviceitemid
       where ps.hide_in_bill=0 and ps.patientbillingid='.$bid;

          $items=DB::select($itemsQry);

         // $newArray=$bill;
          $bill->items=$items;
        //  $billArray=array($newArray);
         if(count($billDetails)>0)
          array_push($bill_data, $bill);
    }
    $data['bill_data']=$bill_data;

    // echo json_encode($bill_data); exit;
    return Parent::page_maker('webpanel.reports.detailedBillReport',$data);
}

public function generate_detailed_report(Request $request)
{

    $data['PageName']="Detailed Bill Report ";
    return Parent::page_maker('webpanel.reports.detailedBillReport',$data);
}


public function collection_report_by_group(Request $request)
    {
       $cond="";

$from_date=$request->from_date;
$to_date=$request->to_date;
$group_search=$request->group_search;
$item_search=$request->item_search;

$billType=$request->billType;
if($billType==2){
    $serch_billType=1;
}
else if($billType==3)
{
    $serch_billType=3;
}


$from_date= date("Y-m-d", strtotime($from_date));
$to_date= date("Y-m-d", strtotime($to_date));

if($group_search)
{
    $cond.=" and sg.id=$group_search";
}
if($item_search)
{
    $cond.=" and sm.id=$item_search";
}

if($billType)
{
    $cond.=" AND pb.billing_type=$serch_billType";
}


$branch_id=Session::get('current_branch');

$type=$request->reportType;  //1= group wise, 2=item wise
if($type==1)
{

//   inner join service_group_master sg on sg.id=sm.group_id


// $qry="select
// round(sum(pi.unit_total ),2) as unit_total,
// round(coalesce(sum(pi.discount_amount ),0),2) as discount_amount,
// round(sum(pi.serviceitemamount ),2) as serviceitemamount,
// round(sum(pi.quantity ))as quantity,
// sg.id as id,
// sg.group_name as name,pb.created_at::date as billdate from patient_service_items pi
// inner join test_master sm on sm.id=pi.serviceitemid
// inner join patient_billing pb on pb.id=pi.patientbillingid
// inner join patient_visits pv on pv.id=pb.visit_id

// where pb.is_cancelled=0 AND pv.branch_id=$branch_id and pb.created_at::date>='$from_date' and pb.created_at::date<='$to_date' AND pi. hide_in_bill=0  $cond
// group by sg.id,sg.group_name,pb.created_at::date";

    $qry="select
      round(sum(pi.unit_total ),2) as unit_total,
      round(coalesce(sum(pi.discount_amount ),0),2) as discount_amount,
      round(sum(pi.serviceitemamount ),2) as serviceitemamount,
      round(sum(pi.quantity ))as quantity,

    sm.\"TestName\" as name,pb.created_at::date as billdate from patient_service_items pi
    inner join test_master sm on sm.id=pi.serviceitemid
    inner join patient_billing pb on pb.id=pi.patientbillingid
    inner join patient_visits pv on pv.id=pb.visit_id

    where pb.is_cancelled=0 AND pv.branch_id=$branch_id and pb.created_at::date>='$from_date' and pb.created_at::date<='$to_date' AND pi. hide_in_bill=0  $cond
    group by sm.id,sm.\"TestName\",pb.created_at::date";

    // echo $qry;
    // exit;
}
else if($type==2)
{

    $qry="select
    round(sum(pi.unit_total ),2) as unit_total,
    round(coalesce(sum(pi.discount_amount ),0),2) as discount_amount,
    round(sum(pi.serviceitemamount ),2) as serviceitemamount,
    round(sum(pi.quantity ))as quantity,
    pi.serviceitemid,";
    $qry.='sm."TestName" as name,';
    $qry.="pb.created_at::date as billdate from patient_service_items pi
    inner join test_master sm on sm.id=pi.serviceitemid
    inner join patient_billing pb on pb.id=pi.patientbillingid
    inner join patient_visits pv on pv.id=pb.visit_id
    inner join service_group_master sg on sg.id=sm.group_id
    where pb.is_cancelled=0 AND pv.branch_id=$branch_id and pb.created_at::date>='$from_date' and pb.created_at::date<='$to_date' $cond
    group by serviceitemid,";

    $qry.='sm."TestName",';

    $qry.="pb.created_at::date";


}

// echo  $qry;
$data=DB::select($qry);

      $output = array(
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
        );
        echo json_encode($output);
    }
    public function generateDeatilpdf(Request $request)
    {
        $cond="";
    if($request->from_date)
    {
        $from_date=$request->from_date;

        $from_date= date("Y-m-d", strtotime($from_date));
        $to_date= date("Y-m-d", strtotime($request->to_date));

        $data['from_date_input']=$request->from_date;
        $data['to_date_input']=$request->to_date;
    }
    else{
        $from_date=date("Y-m-d");
        $to_date=date("Y-m-d");
        $data['from_date_input']=date("d-m-Y");
        $data['to_date_input']=date("d-m-Y");
    }



    $bill_data=array();
    $branch_id=Session::get('current_branch');

    $qry="SELECT pb.created_at::date as billdate,pb.* FROM patient_billing pb
          inner join patient_visits pv on pv.id=pb.visit_id
          where  pb.is_cancelled=0 AND pv.branch_id=$branch_id and  pb.created_at::date>='$from_date' and pb.created_at::date<='$to_date' $cond
          order by pb.id ASC";

    $bills=DB::select($qry);
    foreach($bills as $bill)
    {

        $bid=$bill->id;
        $PatientId=$bill->PatientId;

        $cond=array(
            array('id',$PatientId),
         );

        $patientName=getAfeild("name","patient_registration",$cond);


        $billDetails = DB::table('patient_billing_accounts')->where('PatientBillingId',$bid)->get();

        $bill->patientName=$patientName;
        $bill->accounts=$billDetails;

        // $itemsQry="SELECT ps.*,sm.item_name as item_name FROM patient_service_items ps
        //     INNER JOIN service_item_master sm
        //      on sm.id=ps.serviceitemid
        //   where ps.patientbillingid=$bid";

        //merged with test master

        $itemsQry='SELECT ps.*,sm."TestName" as item_name FROM patient_service_items ps
        INNER JOIN test_master sm
         on sm.id=ps.serviceitemid
      where  ps.hide_in_bill=0 and ps.patientbillingid='.$bid;

          $items=DB::select($itemsQry);

         // $newArray=$bill;
          $bill->items=$items;
        //  $billArray=array($newArray);
          array_push($bill_data, $bill);
    }
    $data['bill_data']=$bill_data;
    $pdf=  \PDF::loadView('exports.exportsdetails',$data,[
        'format'=>[150, 80]
    ]);
    return $pdf->stream('document.pdf',);

    }

    // LAB INcome Report
    public function lab_income_report()
    {
        $data['PageName']="LAB Income Report ";
        return Parent::page_maker('webpanel.reports.labIncomeReport',$data);
    }
    // Get lab income report by type
    public function get_lab_income_report(Request $request)
    {
        $cond="";

        $from_date=$request->from_date;
        $to_date=$request->to_date;
        $group_search=$request->group_search;
        $item_search=$request->item_search;

        $from_date= date("Y-m-d", strtotime($from_date));
        $to_date= date("Y-m-d", strtotime($to_date));

        if($group_search)
        {
            $cond.=" and sg.id=$group_search";
        }
        if($item_search)
        {
            $cond.=" and sm.id=$item_search";
        }


        $type=$request->reportType;  //1= group wise, 2=item wise
        if($type==1)
        {


            $qry="select
            round(sum(pi.unit_total ),2) as unit_total,
            round(coalesce(sum(pi.discount_amount ),0),2) as discount_amount,
            round(sum(pi.serviceitemamount ),2) as serviceitemamount,
            round(sum(pi.quantity ))as quantity,
            sg.id as id,
            sg.group_name as name,pb.created_at::date as billdate from patient_service_items pi
            inner join test_master sm on sm.id=pi.serviceitemid
            inner join patient_billing pb on pb.id=pi.patientbillingid
            inner join service_group_master sg on sg.id=sm.group_id
            where pb.created_at::date>='$from_date' and pb.created_at::date<='$to_date'  $cond
            group by sg.id,sg.group_name,pb.created_at::date";
        }
        else if($type==2)
        {
            $qry="select
            round(sum(pi.unit_total ),2) as unit_total,
            round(coalesce(sum(pi.discount_amount ),0),2) as discount_amount,
            round(sum(pi.serviceitemamount ),2) as serviceitemamount,
            round(sum(pi.quantity ))as quantity,
            pi.serviceitemid,";
            $qry.='sm."TestName" as name,';
            $qry.="pb.created_at::date as billdate from patient_service_items pi
            inner join test_master sm on sm.id=pi.serviceitemid
            inner join patient_billing pb on pb.id=pi.patientbillingid
            inner join service_group_master sg on sg.id=sm.group_id
            where pb.created_at::date>='$from_date' and pb.created_at::date<='$to_date' $cond
            group by serviceitemid,";

            $qry.='sm."TestName",';

            $qry.="pb.created_at::date";


        }

        $data=DB::select($qry);
        // dd($data);

            $output = array(
                    "recordsTotal" => count($data),
                    "recordsFiltered" => count($data),
                    "data" => $data
                );
                // dd($output);
                echo json_encode($output);
    }


    public function getlabItemList(Request $request)
    {


        $groupType=$request->grouptype;

       if($groupType==3){
       $filldata = ServiceGroupMaster::where([
        ['display_status',1],
        ['is_deleted',0 ],
        ['is_lab_group',1 ]
         ])->orderBy('id', 'asc')->get();
       }else{
        $filldata = ServiceGroupMaster::where([
            ['display_status',1],
            ['is_deleted',0 ],
            ['is_lab_group',0 ]
        ])->orderBy('id', 'asc')->get();
       }
        return $filldata;


            }

    public function day_collection_report(Request $request)
    {
        $data['PageName']="Day Collection Report ";

        if($request->from_date){
            $from_date=$request->from_date;
            $from_date= date("Y-m-d", strtotime($from_date));

        }
        else{
            $from_date=date('Y-m-d');
        }

        if($request->to_date){
            $to_date=$request->to_date;
            $to_date= date("Y-m-d", strtotime($to_date));
        }
        else{
            $to_date=date('Y-m-d');
        }

        $paymentModes=PaymentModeMaster::select('id','payment_mode_name')->where('display_status',1)->where('is_deleted',0)->get();
        $data['payment_modes']=$paymentModes;



 $qr_string="";
$branch_id=Session::get('current_branch');
 $cols=array();
        foreach ($paymentModes as $key) {
            $mode_id=$key->id;
            $payment_mode_name=$key->payment_mode_name;
            $payment_mode_name=str_replace('/', '_', $payment_mode_name);
            $payment_mode_name=str_replace(' ', '_', $payment_mode_name);
            $payment_mode_name=strtolower($payment_mode_name);
            array_push( $cols,$payment_mode_name);
            $qr_string.="  , SUM(CASE WHEN payment_mode =  $mode_id THEN total_paid END) as $payment_mode_name ";
        }

        $qry="SELECT date(created_at) as bill_date
        $qr_string
        FROM  patient_billing_payments
        where date(created_at)>='$from_date' AND date(created_at)<='$to_date' and branch_id=$branch_id
        GROUP BY date(created_at)
        order by date(created_at)";

         $paid_data=DB::select($qry);
         $data['payemt_Details']=$paid_data;
         $data['sel_cols']=$cols;

        return Parent::page_maker('webpanel.reports.daycollection',$data);
    }
    public function cancel_bill_report(Request $request)
    {
        $data['PageName']="Bill Cancellation Report ";
        return Parent::page_maker('webpanel.reports.billcancellation',$data);
    }

    public function generateCancellBillReport(Request $request)
    {

        $cond=array();
        array_push($cond,['patient_visits.branch_id',Session::get('current_branch')]);
        array_push($cond,['patient_billing.is_cancelled',1]);

        $patientBilling = PatientBilling::select('patient_billing.*','patient_billing_accounts.TotalAmount','patient_billing_accounts.serviceCharge',
        'patient_billing_accounts.Discamount','patient_billing_accounts.Grossamount','patient_billing_accounts.NetAmount'
        ,'patient_billing_accounts.patient_billing_mode_id','patient_billing_accounts.TotalAmount','patient_billing_accounts.total_paid',
        'patient_billing_accounts.balance_amount','patient_billing_accounts.discount_in_percentage','patient_billing_accounts.total_paid', 'patient_visits.id as patient_visit_id', 'patient_visits.visit_date','patient_registration.name','patient_registration.uhidno')
        ->join('patient_billing_accounts','patient_billing_accounts.PatientBillingId','=','patient_billing.id')
        ->join('payment_mode_master','payment_mode_master.id','=','patient_billing_accounts.patient_billing_mode_id')
        ->join('patient_visits','patient_visits.id','=','patient_billing.visit_id')
        ->join('patient_registration','patient_registration.id','=','patient_billing.PatientId')
        ->where($cond);

        $from_date= date("Y-m-d", strtotime($request->bill_from_date));
        $to_date= date("Y-m-d", strtotime($request->bill_to_date));

        $patientBilling= $patientBilling->whereBetween('patient_billing.created_at', [$from_date, $to_date]);

        $patientBilling= $patientBilling->orderByDesc('patient_billing.id')->get();;


        $output = array(
            "recordsTotal" => count($patientBilling),
            "recordsFiltered" => count($patientBilling),
            "data" => $patientBilling
          );
          echo json_encode($output);
    }

    public function get_day_collection_report(Request $request)
    {
        $cond="";

        $from_date=$request->from_date;
        $to_date=$request->to_date;
        $group_search=$request->group_search;
        $item_search=$request->item_search;

        $from_date= date("Y-m-d", strtotime($from_date));
        $to_date= date("Y-m-d", strtotime($to_date));

        // $qry="select * from patient_billing_accounts
        // inner join payment_mode_master  on payment_mode_master.id=patient_billing_accounts.patient_billing_mode_id
        // where patient_billing_accounts.created_at::date>='$from_date' and patient_billing_accounts.created_at::date<='$to_date'  $cond";

        $data =PatientBillingAccounts::select('patient_billing_accounts.*')->join('payment_mode_master', 'patient_billing_accounts.patient_billing_mode_id', '=','payment_mode_master.id')
        ->whereBetween('patient_billing_accounts.created_at', [$from_date, $to_date])
        ->distinct('created_at')
        ->get();
        // $data=DB::select($qry);
        $output = array(
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
        );
        //  dd($output);
        echo json_encode($output);

    }
}
?>
