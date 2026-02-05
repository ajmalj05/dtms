<?php
namespace App\Http\Controllers\API\MobileApp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masters\TabletTypeMaster;
use App\Models\Masters\MedicineMaster;
use App\Models\MobileApp\OrderMedicine;
use App\Models\MobileApp\OrderMedicineType;
use Illuminate\Support\Str;
use App\Models\MobileApp\RazorPayModel;
use Razorpay\Api\Errors\SignatureVerificationError;

use Razorpay\Api\Api;

use App\Models\MobileApp\Purchase_Product;
use Illuminate\Support\Facades\DB;
class MedicineController extends Controller{
    public function createOrder($order_amount, $purchase_type)
    {

        // $order_amount=1;  // get from request
        // $purchase_type=1; // 1= Product purchase, 2=Medice Purchase

        //Create Transaction Entry

        // generate Order Number
        $ordercount = RazorPayModel::count();

        $ordercount = $ordercount + 1;
        $orderstring = str_pad($ordercount, 6, '0', STR_PAD_LEFT);
        $orderNumber = "JDC-ORD-" . $orderstring;

        $data = array(
            'order_number' => $orderNumber,
            'purchase_type' => $purchase_type,
            'order_amount' => $order_amount * 100
        );


        $insertedData = RazorPayModel::create($data);
        $insertedId = $insertedData->getAttribute('order_master_id');

        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        $razorpayOrder = $api->order->create([
            'amount' => 1 * 100, // Amount in paise (1 INR = 100 paise)
            'currency' => 'INR',
            'receipt' => 'order_' . uniqid(),

        ]);



        $ups_data = array(
            'razorpay_order_id' => $razorpayOrder['id'],
            'r_amount_paid' => $razorpayOrder['amount_paid'],
            'r_amount_due' => $razorpayOrder['amount_due'],
            'status' => $razorpayOrder['status'],

        );
        $update = RazorPayModel::where('order_master_id', $insertedId)->update($ups_data);

        $response = array(
            'order_number' => $orderNumber,
            'order_master_id' => $insertedId,
            'razorpay_order_id' => $razorpayOrder['id'],
            'razor_pay_status' => $razorpayOrder['status']
        );
        return $response;
    }

    public function gettabletTypesData(){
    try{
        $filldata= TabletTypeMaster::select('id','tablet_type_name','display_status')->where([['is_deleted',0],['display_status',1]])
        ->orderBy('id','DESC')
        ->get();

        $response['status'] = 100;
        $response['message'] = 'success';
        $response['result']=   $filldata;
        return response()->json($response,200);
    }
    catch (\Exception $e) {
        $response['status'] = 500;
        $response['message'] = 'Server Error';
        return response()->json($response,500);
    }

}
public function searchMedicineNamesData(Request $request){
    try{
        $data=array();
        if($request->searchTerm){
            $cond=[];
            array_push($cond,['is_deleted', '=', 0]);

            array_push($cond,['medicine_name', 'ilike', '%' . $request->searchTerm . '%']);

            if( !is_null($request->typeid) ){

                if($request->typeid>0){
                    array_push($cond,['tablet_type_id', '=', "$request->typeid"]);
                }

            }

            $filldata= MedicineMaster::where($cond)->limit(25)->get();


            if($filldata){
                foreach ($filldata as $key => $value) {

                    $cond=array(
                        array('id',$value['tablet_type_id']),

                     );
                    $getTabletTypeName=getAfeild("tablet_type_name","tablet_type_master",$cond);




                    $data[]=array(
                                'id'=>$value['id'],
                                'text'=>$value['medicine_name'],
                                'tablet_typeid'=>$value['tablet_type_id'],
                                'tablet_type_name'=>$getTabletTypeName,
                            );
                  }
            }

        }

        $response['status'] = 100;
        $response['message'] = 'success';
        $response['result']=   $data;
        return response()->json($response,200);
    }
    catch (\Exception $e) {
        $response['status'] = 500;
        $response['message'] = 'Server Error';
        return response()->json($response,500);
    }
}
public function OrderMedicine(Request $request){
    try{
        $prescriptionImage=$request->prescription_image;
        $prescriptionpdf=$request->prescription_pdf;
        $prescriptionSelect=$request->medicines;

        $dataarray=array(
            'user_id'=>$request->user_id,
            'patient_id'=>$request->patient_id,
            'remarks'=>$request->remarks
        );
        $medicineData=OrderMedicine::create($dataarray);
        $medicineid= $medicineData->id;
        if(count($prescriptionImage)>0){
            foreach( $prescriptionImage as $imageData){
                $convertdata=$imageData['image'];
                $imageData = explode(',', $convertdata)[1];
                $decodedImage = base64_decode($imageData);
                $fileName = Str::random(10) . '.png';
                $fullimgpath='images/mobileApp/medicinePurchase/'.$fileName;
                $folderPath = public_path($fullimgpath);
                file_put_contents($folderPath, $decodedImage);
                $imageData=array(
                    'medicine_type'=>1,
                    'order_id'=>$medicineid,
                    'image_path'=> $fullimgpath
                );
                $datadetails=OrderMedicineType::insert($imageData);

            }
        }
        if(count($prescriptionpdf)>0){
            $prescriptionImage=$request->prescription_pdf;
            foreach( $prescriptionImage as $imageData){
                $convertdata=$imageData['pdf'];
                ///new changes
                $parts = explode(',', $convertdata);
                $base64Data = $parts[0];
                $path='pdf';
                $position=strpos($base64Data,$path);
                $imageData = explode(',', $convertdata)[1];
                $decodedImage = base64_decode($imageData);

                if($position==false){
                    $fileName = Str::random(10) . '.png';

                }else{
                    $fileName = Str::random(10) . '.pdf';

                }
                // $imageData = explode(',', $convertdata)[1];
                // $decodedImage = base64_decode($imageData);
                // $fileName = Str::random(10) . '.pdf';
                $fullimgpath='images/mobileApp/medicinePurchase/'.$fileName;
                $folderPath = public_path($fullimgpath);

                // $image->storeAs('images/uploadImage', $decodedImage, 'public/');
                file_put_contents($folderPath, $decodedImage);

                $imageData=array(
                    'medicine_type'=>2,
                    'order_id'=>$medicineid,
                    'image_path'=> $fullimgpath
                );

                $datadetails=OrderMedicineType::insert($imageData);

            }
        }
         if(count($prescriptionSelect)>0){
            $prescriptionImage=$request->medicines;
            foreach( $prescriptionImage as $imageData){
                $imageData=array(
                    'medicine_type'=>3,
                    'order_id'=>$medicineid,
                    'image_path'=>$imageData['medicine_name'] ,
                    'select_id'=>$imageData['id'],
                    'medicine_qty'=>$imageData['quantity']
                );

                $datadetails=OrderMedicineType::insert($imageData);

            }
        }

        $response['status'] = 100;
        $response['message'] = 'success';
        $response['result']= null;
        return response()->json($response,200);
    }
    catch (\Exception $e) {
        $response['status'] = 500;
        $response['message'] = $e;
        return response()->json($response,500);
    }

}
public function getmedicinePurchaseHistory(Request $request){
    try{
        if( $request->patient_id){
            $data=OrderMedicine::select('id','order_amt','order_status','created_at','user_id','payment_status',
            'payment_remarks')->where('patient_id',$request->patient_id)->
            orderBy('id','DESC')->get();
            $history=array();
            foreach($data as $item){
                if($item['order_status']==0){
                    $status='pending';
                }else if($item['order_status']==1){
                    $status='Cancel';

                }else if($item['order_status']==2){
                    $status='Processed';

                }else if($item['order_status']==3){
                    $status='Dispatched';

                }else if($item['order_status']==4){
                    $status='Delivered';

                }
                $history[]=[
                    'id'=> $item['id'],
                    'status'=> $status,
                    'date'=> $item['created_at'],
                    'amount'=> $item['order_amt'],
                    'user_id'=>$item['user_id'],
                    'is_paid'=>$item['payment_status'],
                    'payment_remarks'=>$item['payment_remarks']

                ];
            }
            $response['status'] = 100;
            $response['message'] = 'Sucess';
            $response['result']=  $history;
            return response()->json($response,200);

        }
        $response['status'] = 101;
        $response['message'] = 'fail';
        $response['result']=  null;
        return response()->json($response,200);
    }
    catch (\Exception $e) {
        $response['status'] = 500;
        $response['message'] = 'Server Error';
        return response()->json($response,500);
    }

}
public function medicinePurchaseAmount(Request $request){
    try{
        $purchaseId = $request->order_id;
        $totalAmount = $request->Total_amount;
        $create_order = $this->createOrder( $totalAmount, 1);
        $medicinedata = array(
            'order_master_id' => $create_order['order_master_id'],
        );
        $data=OrderMedicine::where('id', $purchaseId )->update($medicinedata);
        if( $data){
            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result'] =  $create_order;
            return response()->json($response, 200);

        }else{
            $response['status'] = 101;
            $response['message'] = 'fail';
            $response['result'] = [];
            return response()->json($response, 200);
        }

    }
    catch (\Exception $e) {
        $response['status'] = 500;
        $response['message'] = $e->getMessage();
        return response()->json($response,500);
    }

}
public function successOrfailure(Request $request){
    try{
    if($request->isPaid==true && $request->type==2){
        $order_id=$request->order_master_id;
        $signature=$request->signature;

        $medicinedata = array(
            'payment_status' =>1,
        );
        $data=OrderMedicine::where('order_master_id', $order_id )->update($medicinedata);
        $signatureData = array(
            'signature' => $signature,
        );

        $updateSignature=RazorPayModel::where('razorpay_order_id', $request->order_id )->update($signatureData);

    }else if($request->isPaid==true && $request->type==1){
        $order_id=$request->order_master_id;
        $signature=$request->signature;
        $purchasedata = array(
            'payment_status' =>1,
        );

        $data=Purchase_Product::where('order_master_id', $order_id )->update($purchasedata);
        $signatureData = array(
            'signature' =>$signature,
        );
        $updateSignature=RazorPayModel::where('razorpay_order_id', $request->order_id )->update($signatureData);

    }
    $response['status'] = 100;
    $response['message'] ='success';
    $response['result'] = [];
    return response()->json($response, 200);


}
    catch (\Exception $e) {
        $response['status'] = 500;
        $response['message'] = $e;
        return response()->json($response,500);
    }
}

  public function getmedicinePurchaseDetails(Request $request)
  {
    try{
        if($request->id){
            $medicineDetails=OrderMedicine::select('app_purchase_medicine.remarks','app_purchase_medicine_type.medicine_type'
            ,'app_purchase_medicine_type.image_path','app_purchase_medicine_type.id','app_purchase_medicine_type.select_id'
            ,'app_purchase_medicine_type.medicine_qty')->join('app_purchase_medicine_type',
            'app_purchase_medicine_type.order_id','app_purchase_medicine.id')->where('app_purchase_medicine.id',$request->id)
            ->get();

            $medicineData=[];
        //     $medicineData=[
        //         'remarks'=>$medicineDetails['remarks'],
        // ];
            //  dd($medicineDetails);
            if(!$medicineDetails->isEmpty()){

                $medicineImageData = [];
                $medicineImpdfData = [];
                $medicineSelectedData = [];
                foreach($medicineDetails as $item){
                    if($item['medicine_type']==1){

                        $history=array(
                            'id'=> $item['id'],
                            'imagePath'=> $item['image_path'],
                        );

                        array_push($medicineImageData, $history);
                    }
                    else if($item['medicine_type']==2){

                        $history=array(
                            'id'=> $item['id'],
                            'filePath'=> $item['image_path'],
                        );

                        array_push($medicineImpdfData, $history);
                    }else if($item['medicine_type']==3){
                        $medicinename=MedicineMaster::select('tablet_type_master.tablet_type_name')->
                        join('tablet_type_master','medicine_master.tablet_type_id','tablet_type_master.id')
                        ->where('medicine_master.id', $item['select_id'])->first();
                        $history=array(
                            'id'=> $item['id'],
                            'text'=> $item['image_path'],
                            'tabletTypeid'=> $item['select_id'],
                            'count'=> $item['medicine_qty'],
                            'tabletTypeName'=> $medicinename->tablet_type_name,

                        );
                        array_push($medicineSelectedData, $history);
                    }


                }


                $branch_details= GetBranchDetails(1);

                $cond2=array(
                    array('id',$request->id),
                 );

                $patient_id=getAfeild("patient_id","app_purchase_medicine",$cond2);
                $billing_info=DB::table('patient_registration')->select('name','last_name','address')->where('id',$patient_id)->first();

                $data = [
                    'status' => 100,
                    'message' => 'success',
                    'result' => [
                        'remarks' => $medicineDetails[0]['remarks'],
                        'medicines' => $medicineSelectedData,
                        'prescriptionPdf' => $medicineImpdfData,
                        'prescriptionImage' => $medicineImageData,
                        'branch_details'=>$branch_details,
                        'billing_info'=>$billing_info
                    ]
                ];




                // $response['status'] = 100;
                // $response['message'] ='success';
                // $response['result'] = $medicineSelectedData;
                return response()->json( $data);

            }else{
                $response['status'] = 200;
                $response['message'] ='No Data';
                $response['result'] = [];
                return response()->json($response, 200);
            
            }
        }
    }
    catch (\Exception $e) {
        $response['status'] = 500;
        $response['message'] = $e;
        return response()->json($response,500);
    }

  }

}
