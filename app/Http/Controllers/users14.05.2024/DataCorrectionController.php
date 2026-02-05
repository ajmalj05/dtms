<?php
namespace App\Http\Controllers\users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientRegistration;
use App\Models\PatientVisits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DataCorrectionController extends Controller
{

    public function labData()
    {
        $data=array();
        $data['PageName']="Lab Data Correction ";
        return Parent::page_maker('webpanel.datacorrection.labData',$data);
    }
    public function updateTestResultCorrection(Request $request)
    {
        $ins=array(
            'ResultValue'=>$request->testValue,
            'manual_updated_by'=>Auth::id(),
            'updated_date'=>date('Y-m-d H:i:s')
        );
        $id=$request->id;
        if($id>0)
        {
            $update=DB::table('test_results')->where('id',$id)->update($ins);
            if($update)
            {
                return [ 'status'=>1, 'message'=>" Success"];
            }
            else{
                return [ 'status'=>2, 'message'=>" failed"];
            }
        }
    }
    public function getallResultCorrectionData(Request $request)
    {
        $visit_id=$request->id;

        $testData = DB::table('test_results as tr')
        ->join('test_master as tm', 'tm.TestId', '=', 'tr.TestId')
        ->select('tr.ResultValue','tr.TestId','tm.TestName','tm.dtms_code','tr.is_outside_lab','tr.is_smbg','tr.id')
        ->where('tr.visit_id',$visit_id)
        ->orderBy('tm.dtms_order_no', 'asc')
        ->get();
        $html="";

        if($testData)
        {
            $sl=0;
            foreach($testData as $test)
            {
                $sl++;
                $lab=$test->is_outside_lab;
                if($lab==1) $class="outsideLab_v1";
                else if($lab==2 )  $class="outsideLab_v1";
                else if($test->is_smbg==1) $class="smbg";
                else $class="insideLab";

                $t_name=$test->dtms_code;
                if($t_name=="" || !$t_name)
                {
                    $t_name=$test->TestName;
                }

                $name="test_$test->id";
                $html.='
                <tr class="'.$class.'">
                <td>'.$t_name.'</td>
                <td> <input type="text" class="form-control" name="'.$name.'" id="'.$name.'" value="'.$test->ResultValue.'"></td>
                <td><button type="button" class="btn btn-success btn-sm"  onclick="updateData(\'' . $test->id . '\')">Update</button></td>
                </tr>';
            }

       }
        else{
           $html= "<tr>
            <td colspan='3'>
            No data Found
            </td></tr>";
        }
        return  $html;

    }
    public function getPatientDatabyUhid(Request $request)
    {
        $uhidno=$request->uhidno;
        $patient_data = PatientRegistration::select('patient_registration.*')
            ->where('patient_registration.uhidno',$uhidno)
            ->first();

            if($patient_data)
            {
                //get visit list

                $cond=[];
                array_push($cond,['patient_visits.patient_id','=',$patient_data->id]);

                $filldata = PatientVisits::select('patient_visits.*','visit_type_master.visit_type_name')
                    ->join('visit_type_master','visit_type_master.id','=','patient_visits.visit_type_id')
                    ->where($cond)->orderByDesc('id')
                    ->get();

                   $data=array(
                    'patient_data'=>$patient_data,
                    'visit_data'=>$filldata
                   );
                   return [ 'status'=>1, 'message'=>" data found",'data'=>$data];

            }
            else{
                 return [ 'status'=>2, 'message'=>"No data found"];
            }


    }

}
