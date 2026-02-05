<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\Ipd\IpAdmission;
use App\Models\Masters\SpecialistMaster;
use Illuminate\Http\Request;
use App\Models\PatientAppointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use App\Models\PatientRegistration;
use PDF;


class IpdController extends Controller
{

    /**
     * ip admission list
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function index(){
        $data=array();
        $data['PageName']="IP Admission List";
        return Parent::page_maker('webpanel.ipd.ip-admission-list',$data);
    }

    /**
     * create ip admission
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function createIpAdmission(Request $request)
    {
        $data=array();
        if(isset($request->id)){
            $cond=['id' => $request->id];
            $admission_data = IpAdmission::select('ip_admission.*', 'patient_registration.name', 'patient_registration.uhidno',
                'patient_registration.mobile_number', 'patient_registration.last_name', 'patient_registration.gender','patient_registration.age',
                'patient_registration.address','patient_registration.salutation_id','patient_registration.dob','patient_registration.email')
                ->leftjoin('patient_registration','patient_registration.id','=','ip_admission.patient_id')
                ->leftjoin('specialist_master','specialist_master.id','=','ip_admission.specialist_id')
                ->where('ip_admission.id',$request->id)
                ->orderBy('ip_admission.id','DESC')->first();
            $data['admission_data']=$admission_data;
        }

        $data['PageName']="IP Admission";
        return Parent::page_maker('webpanel.ipd.create-ip-admission',$data);

    }

    /**
     * save ip admission
     * @param Request $request
     * @return array
     */
    public function saveIpAdmission(Request $request) {
        $validated = $request->validate([
            'a_patientname'     => 'required',
            'ip_department_name' => 'required',
            'ip_admit_date' => 'required',
            'ip_ward_number'  => 'required',
            'ip_bed_number'  => 'required',
        ]);
        if($validated)
        {
            $ipAdmissionData = [
                'department_id'=>$request->ip_department_name,
                'specialist_id'=>$request->ip_specialist_name,
                'admission_date'=>$request->ip_admit_date ? date("Y-m-d", strtotime($request->ip_admit_date)) :NULL,
                'policy_number'=>$request->ip_policy_number,
                'ward_number'=>$request->ip_ward_number,
                'bed_number'=>$request->ip_bed_number,
                'patient_id'=>$request->a_pid ?  $request->a_pid : 0,
            ];

           if($request->crude==1){
               $ipAdmissionData['created_at'] =Carbon::now();
               $ipAdmissionData['created_by'] =Auth::id();
               $ipAdmissionData['branch_id'] =Session::get('current_branch');

                $insert = IpAdmission::create($ipAdmissionData);
                $admission_id=$insert->id;
                return [ 'status'=>1, 'message'=>"Saved Successfully",'id'=>$admission_id ];

           }
           else if($request->crude==2){
               $ipAdmissionData['updated_at'] =Carbon::now();
               $ipAdmissionData['updated_by'] =Auth::id();
               IpAdmission::whereId($request->ip_admissionid)->update($ipAdmissionData);
                return [ 'status'=>2, 'message'=>"Updated Successfully" ];
           }

        }else{
            echo 2; // validation error
        }

    }

    /**
     * search ip admission
     * @param Request $request
     */
    public function searchIpAdmission(Request $request)
    {
        $cond=[];

        array_push($cond,['ip_admission.branch_id',Session::get('current_branch') ]);


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
            array_push($cond,['ip_admission.admission_date', '>=', "$start_date"]);
        }
        if( !is_null($request->to_date) ){
            array_push($cond,['ip_admission.admission_date', '<=', "$end_date"]);
        }

        $data = IpAdmission::where($cond)->select('ip_admission.*','departments.department_name','specialist_master.specialist_name',
            'patient_registration.name', 'patient_registration.uhidno', 'patient_registration.mobile_number',
            'patient_registration.last_name', 'patient_registration.gender','patient_registration.age','patient_registration.dob','patient_registration.address')
            ->leftjoin('departments','departments.id','=','ip_admission.department_id')
            ->leftjoin('specialist_master','specialist_master.id','=','ip_admission.specialist_id')
            ->leftjoin('patient_registration','patient_registration.id','=','ip_admission.patient_id')
            ->orderBy('ip_admission.admission_date','DESC')
            ->where('ip_admission.is_discharge', 0)
            ->get();

        $output = array(
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
          );
          echo json_encode($output);

    }



    /**
     *
     * get consultant list in dropdown
     */
    public function getSpecialistList(Request $request)
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
     * update discharge data
     * @param Request $request
     * @return array
     */
    public function updateDischargeData(Request $request)
    {
        $validated = $request->validate([
            'discharge_date' => 'required',
            'discharge_summary' => 'required',
        ]);
        if($validated)
        {
            $ins_data=array(
                'discharge_date'=>$request->discharge_date ? date("Y-m-d", strtotime($request->discharge_date)) :NULL,
                'discharge_summary' => $request->discharge_summary,
                'is_discharge' => 1,
            );

            if($request->crude==2){
                $ins_data['updated_at'] = Carbon::now();
                $ins_data['updated_by']=Auth::id();
                $dischargeData = IpAdmission::where('id',$request->hid_ip_admission_id)->update($ins_data);

                if($dischargeData) {
                    return [ 'status'=>1, 'message'=>"Data updated Successfully" ];
                    // echo 1; //save success
                }
                else{
                    return [ 'status'=>3, 'message'=>"Failed to save" ];

                }

            }
        }else{
            echo 2; // validation error
        }

    }


}
