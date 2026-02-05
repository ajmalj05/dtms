<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\Ipd\IpAdmission;
use App\Models\PatientBilling;
use App\Models\PatientBillingAccounts;
use App\Models\PatientBillingPayment;
use App\Models\PatientComplication;
use App\Models\PatientRegistration;
use App\Models\PatientserviceItems;
use App\Models\PatientVisits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\TestMasterExt;
// use \Mpdf\Mpdf as PDF;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;


use PDF;
use App\Models\TestResultDatas;

class PdfController extends Controller
{
    public function billingDocument(Request $request)
    {
        $data=[];
        $branch_id=Session::get('current_branch');
        $branch_details=GetBranchDetails($branch_id);
        $bill_setings=getPrintSettings($branch_id,1);


        $patientData = PatientRegistration::select('patient_registration.name','patient_registration.last_name','patient_registration.age','patient_registration.dob',
            'patient_registration.uhidno','patient_registration.gender','patient_registration.address','patient_registration.mobile_number',
            'specialist_master.specialist_name', 'salutation_master.salutation_name')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_registration.specialist_id')
            ->leftjoin('salutation_master', 'salutation_master.id', '=', 'patient_registration.salutation_id')
            ->where('patient_registration.id', $request->patient_id)
            ->first();

        $billing= PatientBilling::select('patient_billing.id','patient_billing.PatientLabNo',  'patient_billing.created_at','patient_billing.specialist_id',
            'specialist_master.specialist_name')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_billing.specialist_id')
            ->orderBy('patient_billing.id','DESC')
            ->where('patient_billing.billing_type', $request->billing_type)
            ->where('patient_billing.id', $request->patient_billing_id)
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
        ->select('patient_service_items.*', 'test_master.TestName as item_name','test_master.order_num as order_num','test_master.test_code as item_code',
            'test_master.TestRate as item_amount', 'test_master.group_id as service_group_id')
        ->leftjoin('test_master','test_master.id','=','patient_service_items.serviceitemid')
        ->orderBy("test_master.order_num", "asc")
        ->orderBy("patient_service_items.id", "DESC")

        ->where('patientid', $request->patient_id)
        ->get();

        $billingAccounts = PatientBillingAccounts::select('patient_billing_accounts.*', 'payment_mode_master.payment_mode_name')
            ->join('payment_mode_master','payment_mode_master.id','=','patient_billing_accounts.patient_billing_mode_id')
            ->where('PatientBillingId', $request->patient_billing_id)
            ->where('PatientId', $request->patient_id)
            ->orderByDesc('patient_billing_accounts.id')
            ->first();
        $testreultsDates = TestResultDatas::select('test_result_datas.*','users.name','users.id as user_id')
        ->join('users','users.id','=','test_result_datas.reported_by')
        ->where('bill_id',$request->patient_billing_id)->first();

        $number = number_format((float)$billingAccounts->NetAmount, 2, '.', '');
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] . " Paise" : '';

        $data['net_amount_in_words']= $result . "Rupees  " . $points;
        $data['service_item_data']= $serviceItems;
        $data['patient_data']= $patientData;
        $data['bill_no']= $billNo;
        $data['opd_billing_data']= $billing;
        $data['bill_date']= $billingDate;
        $data['billing_account_data']= $billingAccounts;
        $data['testreultsDates'] = $testreultsDates;
        $data['branch_details']=$branch_details;

       //  $pdf = PDF::loadView('webpanel.print',compact('data'))->setPaper('8.5x11', 'landscape');

        //  $pdf = MPDF::loadView('webpanel.pdf.idcard',[],['data' => $data],[
        //     'format'=>['A4']
        // ]);



        //

        $size=$bill_setings->paper_size;  //1= A4, 2=A5

        if($size==2){
            $pSize=[148, 210];
        }
        else if($size==1){
            $pSize=[210 , 297 ];
        }

        $orienTation=$bill_setings->paper_mode;
        if($orienTation==1) $orienTation="P";
        else if($orienTation==1) $orienTation="L";

        $pdf = \PDF::loadView('webpanel.print',[],['data' => $data],[
            'format'=>$pSize,
            'orientation' => $orienTation
        ]);

       //  $pdf = MPDF::loadView('webpanel.print');

        // $pdf->set_paper(array(0,0,204,650));

        // $pdf->set_option('dpi', 72);
        // $pdf->set_paper('DEFAULT_PDF_PAPER_SIZE', 'landscape');

        // $customPaper = array(0,0,700,400);
        // $pdf->set_paper($customPaper);
       return  $pdf->stream('filename.pdf');

        // return $pdf->download('report.pdf');


    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function totalOutstandingDocument(Request $request)
    {
        $data=[];
        $patientData = PatientRegistration::select('patient_registration.name','patient_registration.age',
            'patient_registration.dob','patient_registration.uhidno','patient_registration.gender',
            'patient_registration.address','patient_registration.mobile_number','specialist_master.specialist_name')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_registration.specialist_id')
            ->where('patient_registration.id', $request->patient_id)
            ->first();
        $billingPayment= PatientBillingPayment::select('patient_billing_payments.*','payment_mode_master.payment_mode_name')
            ->leftjoin('payment_mode_master','payment_mode_master.id','=','patient_billing_payments.payment_mode')
            ->orderBy('patient_billing_payments.id','DESC')
            ->where('patient_billing_payments.id', $request->patient_billing_payment_id)
            ->take(1)
            ->first();
        $billingPaymentDate =Carbon::parse($billingPayment->created_at)->format('d-m-Y');
        $receiptNo =str_pad(('receipt-' . $billingPayment->id),3,'0',STR_PAD_LEFT) ;
        $data['billing_payment_data']= $billingPayment;
        $data['patient_data']= $patientData;
        $data['receipt_no']= $receiptNo;
        $data['bill_payment_date']= $billingPaymentDate;

       // $pdf = \PDF::loadView('webpanel.print-outstanding-receipt',compact('data'))->setPaper('8.5x11', 'landscape');

        // $pdf = \PDF::loadView('webpanel.print-outstanding-receipt',[],['data' => $data],[
        //     'format'=>[190, 236]
        // ]);

        $pdf = \PDF::loadView('webpanel.print-outstanding-receipt',[],['data' => $data],[
            'format'=>[190, 236]
        ]);

       return  $pdf->stream('filename.pdf');

    }

    /**
     * ipd billing document
     * @param Request $request
     * @return mixed
     */
    public function billingIpdDocument(Request $request)
    {
        $data=[];
        $patientData = PatientRegistration::select('patient_registration.name','patient_registration.age','patient_registration.dob',
            'patient_registration.uhidno','patient_registration.gender','patient_registration.address','patient_registration.mobile_number',
            'specialist_master.specialist_name', 'salutation_master.salutation_name')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_registration.specialist_id')
            ->leftjoin('salutation_master', 'salutation_master.id', '=', 'patient_registration.salutation_id')
            ->where('patient_registration.id', $request->patient_id)
            ->first();

        $billing= PatientBilling::select('patient_billing.*', 'ip_admission.ward_number', 'ip_admission.bed_number',
            'ip_admission.policy_number','ip_admission.admission_date','ip_admission.discharge_date', 'specialist_master.specialist_name')
            ->leftjoin('ip_admission','ip_admission.id','=','patient_billing.ipd_id')
            ->leftjoin('specialist_master','specialist_master.id','=','patient_billing.specialist_id')
            ->where('patient_billing.ipd_id', $request->ipd_id)
            ->where('patient_billing.billing_type', $request->billing_type)
            ->where('patient_billing.PatientId', $request->patient_id)
            ->where('patient_billing.id', $request->patient_billing_id)
            ->orderBy('patient_billing.id','DESC')
            ->take(1)
            ->first();

        $billingDate =Carbon::parse($billing->created_at)->format('d-m-Y');
        $ipAdmitDate =Carbon::parse($billing->admission_date)->format('d-m-Y');
        $ipDischargeDate = (! is_null($billing->discharge_date) && '' != $billing->discharge_date) ? Carbon::parse($billing->discharge_date)->format('d-m-Y'): '';
        $billNo =str_pad(($billing->id + 1),3,'0',STR_PAD_LEFT) ;
        $IpNo =str_pad(($billing->ipd_id + 1),3,'0',STR_PAD_LEFT) ;
        $serviceItems = PatientserviceItems::where('patientbillingid',$request->patient_billing_id)
            ->select('patient_service_items.*', 'service_item_master.item_name','service_item_master.item_code',
                'service_item_master.item_amount', 'service_item_master.service_group_id')
            ->leftjoin('service_item_master','service_item_master.id','=','patient_service_items.serviceitemid')
            ->orderByDesc('patient_service_items.id')
            ->where('patientid', $request->patient_id)
            ->get();

        $billingAccounts = PatientBillingAccounts::select('patient_billing_accounts.*', 'payment_mode_master.payment_mode_name')
            ->join('payment_mode_master','payment_mode_master.id','=','patient_billing_accounts.patient_billing_mode_id')
            ->where('PatientBillingId', $request->patient_billing_id)
            ->where('PatientId', $request->patient_id)
            ->orderByDesc('patient_billing_accounts.id')
            ->first();
        $number = number_format((float)$billingAccounts->NetAmount, 2, '.', '');
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] . " Paise" : '';

        $data['net_amount_in_words']= $result . "Rupees  " . $points;
        $data['service_item_data']= $serviceItems;
        $data['patient_data']= $patientData;
        $data['bill_no']= $billNo;
        $data['ip_no']= $IpNo;
        $data['bill_date']= $billingDate;
        $data['ip_admit_date']= $ipAdmitDate;
        $data['ip_discharge_date']= $ipDischargeDate;
        $data['ipd_billing_data']= $billing;
        $data['billing_account_data']= $billingAccounts;

     //   $pdf = \PDF::loadView('webpanel.ipd-receipt',compact('data'))->setPaper('8.5x11', 'portrait');

     $pdf = \PDF::loadView('webpanel.ipd-receipt',[],['data' => $data],[
        'format'=>[190, 236]
    ]);

        return  $pdf->stream('filename.pdf');

    }




    public function view_discharge_summary(Request $request)
    {
        $data=[];


        $ip_admission_id=$request->ip_admission_id;


        $ipData=IpAdmission::where('id',$ip_admission_id)->first();


        $patient_id=$ipData->patient_id;
        $patientData=PatientRegistration::select('patient_registration.name','patient_registration.age','patient_registration.dob','patient_registration.gender',
        'patient_registration.uhidno','patient_registration.gender','patient_registration.address','patient_registration.mobile_number',
        'specialist_master.specialist_name')
        ->leftjoin('specialist_master','specialist_master.id','=','patient_registration.specialist_id')
        ->where('patient_registration.id', $patient_id)
        ->first();


        $data['hid_ip_admission_id']=$ip_admission_id;
        $data['patientData']=$patientData;
        $data['admission_data']=$ipData;

        $birthdate = Carbon::createFromFormat('Y-m-d', $patientData->dob);
        $currentDate = Carbon::now();
        $age = $birthdate->diffInYears($currentDate);
        $data['age']=$age;

        $data['ad_date']=$dateDMY = Carbon::createFromFormat('Y-m-d', $ipData->admission_date)->format('d-m-Y');
        $data['dc_date']=$dateDMY = Carbon::createFromFormat('Y-m-d', $ipData->discharge_date)->format('d-m-Y');

        $pdf = \PDF::loadView('webpanel.discharge-print',[],['data' => $data],[
            'format'=>[190, 236]
        ]);

            return  $pdf->stream('dischargeSummary.pdf');

        //    return $pdf->stream('dischargeSummary.pdf');
    }

}
