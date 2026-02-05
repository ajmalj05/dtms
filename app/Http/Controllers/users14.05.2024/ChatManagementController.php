<?php
namespace App\Http\Controllers\users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MobileApp\Message_data;
use App\Models\MobileApp\AppUsers;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PatientRegistration;
use Illuminate\Support\Facades\DB;

class ChatManagementController extends Controller
{
    public function liveChat(){
        {
            $data=array();
            $userDetails=null;
            $userData =array();

            $data['PageName']="Live Chat";
            return Parent::page_maker('webpanel.appManagement.chat',$data);

        }


        }
        public function getLIveChat(){
            $chatData=Message_data::select('app_message_data.user_id','app_message_data.message_text'
            ,'app_message_data.patient_id','app_message_data.created_at','app_message_data.read_status',
            DB::raw("CONCAT(patient_registration.name,patient_registration.last_name) AS name"))
            ->join('patient_registration','patient_registration.id','app_message_data.patient_id')
            ->where('app_message_data.read_status',0)->orderBy('app_message_data.id','DESC')->limit(10)->get();
            $uniqueData = [];
             foreach ($chatData as $tempData) {
            if (!isset($uniqueData[$tempData['patient_id']])) {
                $dateva=$tempData['created_at'];
                $formatedDate=$dateva->format('d-m-Y H:i');

            $productDataDetails = [
            'user_id' => $tempData['user_id'],
            'message_text' => $tempData['message_text'],
            'created_at' => $formatedDate,
            'name' => $tempData['name'],
            'patient_id'=> $tempData['patient_id']
             ];
             $uniqueData[$tempData['patient_id']] = $productDataDetails;
            }
            }

        $data = array_values($uniqueData);
            return response ()->json($data);
        }

    public function getmessage(Request $request){

        $patientid=$request->user_id;
        $userChatData=Message_data::select('message_text','created_at','admin_id','patient_id')
        ->where('patient_id',$patientid)->orderBy('id','asc')->get();

        $uniqueData = [];

        foreach ($userChatData as $tempData) {

            $dateva=$tempData['created_at'];
            $formatedDate=$dateva->format('d-m-Y h:i a');

            $productDataDetails = [
            'message_text' => $tempData['message_text'],
            'created_at' => $formatedDate,
            'admin_id' => $tempData['admin_id'],
             ];
             array_push($uniqueData,$productDataDetails);
            }

        return response ()->json($uniqueData);

    }
    public function savemessage(Request $request){
        $adminId=Auth::id();
        $dataValue=$request->inputValue;
        $createTime=date('Y-m-d H:i:s');
        if($dataValue!=null){
            $datas=array(
                'message_text'=>$dataValue,
                'admin_id'=>$adminId,
                'user_id'=>$request->user_id,
                'created_at'=> $createTime,
                'read_status'=>1,
                'patient_id'=>$request->patientId,

            );
            $userChatData=Message_data::insert($datas);
            $updateStatus=Message_data::where('patient_id',$request->patientId)->where('read_status',0)->update(['read_status'=>1]);

        }
        return response ()->json($userChatData);

    }
    public function searchUserData(Request $request){
        $validated=$request->validate([
            'inputValue' => 'required',

        ]);
        if($validated){
        $textValue=$request->inputValue;
        $users = Message_data::select('app_message_data.user_id','app_message_data.message_text',
            'app_message_data.created_at','app_message_data.read_status',
            DB::raw("CONCAT(patient_registration.name,patient_registration.last_name) AS name"))
            ->join('patient_registration','patient_registration.id','app_message_data.patient_id')
            ->where('patient_registration.name', 'ILIKE', '%'.$textValue.'%')->get();

        }
        return response ()->json($users);

     }
}
