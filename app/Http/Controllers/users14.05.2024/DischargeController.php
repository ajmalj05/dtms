<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\Ipd\IpAdmission;
use App\Models\Masters\SpecialistMaster;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class DischargeController extends Controller
{

    /**
     * discharge list
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function index(){
        $data=array();
        $data['PageName']="Discharge List";
        return Parent::page_maker('webpanel.discharge.discharge-list',$data);
    }


    /**
     * search discharge
     * @param Request $request
     */
    public function searchDischarge(Request $request)
    {
        $cond=[];
        if( !is_null($request->patient_name) ){
            array_push($cond,['name', 'ILIKE', "%{$request->patient_name}%"]);
        }
        if( !is_null($request->uhid) ){
            array_push($cond,['uhidno', 'ILIKE', "%{$request->uhid}%"]);
        }
        if( !is_null($request->mobile_number) ){
            array_push($cond,['mobile_number', 'ILIKE', "%{$request->mobile_number}%"]);
        }

        if( !is_null($request->last_name) ){
            array_push($cond,['last_name', 'ILIKE', "%{$request->last_name}%"]);
        }
        if( !is_null($request->gender) ){
            array_push($cond,['gender', $request->gender]);
        }
        if( !is_null($request->age) ){
            $cdate=date('Y');
            $birthYear=$cdate-$request->age;
                array_push($cond,[
                DB::raw('EXTRACT(YEAR  FROM dob )'),
                 $birthYear]);

        }
        if( !is_null($request->address) ){
            array_push($cond,['address', 'ILIKE', "%{$request->address}%"]);
        }



        $start_date = Carbon::parse(request()->from_date)->toDateTimeString();
        $end_date = Carbon::parse(request()->to_date)->toDateTimeString();
        $end_date= str_replace("00:00:00","24:00:00",$end_date);

        $wherebtm=[];

        if( !is_null($request->from_date) ){
            array_push($cond,['ip_admission.discharge_date', '>=', "$start_date"]);
        }
        if( !is_null($request->to_date) ){
            array_push($cond,['ip_admission.discharge_date', '<=', "$end_date"]);
        }

        $data = IpAdmission::where($cond)->select('ip_admission.*','departments.department_name','specialist_master.specialist_name',
            'patient_registration.name', 'patient_registration.uhidno', 'patient_registration.mobile_number',
            'patient_registration.last_name', 'patient_registration.gender','patient_registration.age','patient_registration.dob',
            'patient_registration.address', 'patient_registration.specialist_id')
            ->leftjoin('departments','departments.id','=','ip_admission.department_id')
            ->leftjoin('patient_registration','patient_registration.id','=','ip_admission.patient_id')
            ->leftjoin('specialist_master','specialist_master.id','=','ip_admission.specialist_id')
            ->orderBy('ip_admission.discharge_date','DESC')
            ->where('ip_admission.is_discharge', 1)
            ->get();

        $output = array(
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
          );
          echo json_encode($output);

    }


}
