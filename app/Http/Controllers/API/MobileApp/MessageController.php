<?php
namespace App\Http\Controllers\API\MobileApp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MobileApp\Message_data;
use Carbon\Carbon;

class MessageController extends Controller{
    public function saveMessage(Request $request){
        try{
            $currentDate=Carbon::now();
            $messageData=array(
                'user_id'=>$request->user_id,
                'message_text'=>$request->messageText,
                'patient_id'=>$request->patient_id,
                'created_at'=>Carbon::now(),
            );

                $data=Message_data::insert($messageData);
                $response['status'] = 100;
                $response['message'] = 'success';
                $response['result']=  null;
                return response()->json($response,200);
    
           
        }
        catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response,500);
        }


    }
    public function getReplay(Request $request){
        try{
            $patient_id= $request->patient_id;
            $data=Message_data::select('message_text','admin_id','created_at')->where('patient_id',$patient_id)->
            orderBy('id','asc')->get();
            $arrayValue=[];
            foreach($data as $item){
                $dateva=$item['created_at'];
                $formatedDate=$dateva->format('d-m-Y  h:i a');
                $messageData=array(
                'message_text'=>$item['message_text'],
                'admin_id'=>$item['admin_id'],
                'created_at'=>$formatedDate,
            );
            array_push( $arrayValue,$messageData);
            }
            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result']=   $arrayValue;
            return response()->json($response,200);
        }
        catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response,500);
        }

      
    }
}
