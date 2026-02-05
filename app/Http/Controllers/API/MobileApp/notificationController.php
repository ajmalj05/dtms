<?php
namespace App\Http\Controllers\API\MobileApp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MobileApp\AppNotification;
use App\Models\MobileApp\AppNotification_img;
use Carbon\Carbon;
use App\Models\MobileApp\NewsPapper;
use App\Models\MobileApp\NewsPapperImage;
use App\Models\Masters\Product;
use App\Models\Masters\ProductImage;

class notificationController extends Controller

{
public function getNotification(Request $request){
    try{
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('Y-m-d');
        $dataNotification=AppNotification::select('id','titles','locations','display_status','event_date','expiry_date','short_description','detailed_description')
        ->where('expiry_date','>=',$formattedDate)->where('is_deleted',0)->where('display_status',true)->
        orderBy('id','DESC')->get();
        $history=array();

        foreach($dataNotification as $item){
            $history[]=[
                'id'=> $item['id'],
                'titles'=>trim( $item['titles']),
                'locations'=>trim( $item['locations']),
                'event_date'=>$item['event_date'],
                'expiry_date'=>$item['expiry_date'],
                'short_description'=>trim( $item['short_description']),
                'detailed_description'=>trim( $item['detailed_description']),

            ];
         }
        $dataOP=[];
        foreach($history as $itemData){
            $data=$itemData;
             // if($data->expiry_date >= $formattedDate ){
                $productdataimage=AppNotification_img::select('id','notification_img','notification_id')
                ->where('notification_id',$data['id'])->orderBy('id','DESC')->get();

                $imageData=array();
                foreach($productdataimage as $itemimage){
                                $imagepath=[
                                'id'=>$itemimage->id,
                                'image'=>$itemimage->notification_img
                                ];
                                array_push($imageData, $imagepath);
                            }
                        $newKey = 'image';
                         $data[$newKey]= $imageData;
                         array_push($dataOP, $data);

                // }

        }
        $response['status'] = 100;
        $response['message'] = 'success';
        $response['result']=   $dataOP;
        return response()->json($response,200);
    }
    catch (\Exception $e) {
        $response['status'] = 500;
        $response['message'] = 'Server Error';
        return response()->json($response,500);
    }

}
public function getNewspapper(){
    try{
        $newsdata=NewsPapper::select('id','titles','redirect_url','news_date','news_description','images')
        ->where('is_deleted',0)->where('display_status',true)->orderBy('id','DESC')->get();

        //     $dataOP=[];

    // foreach( $newsdata as $item ){
    //     $newsdataimage=NewsPapperImage::select('id','news_id','image')->where('news_id',$item->id)->get();
    //     $imageData=array();

    //     foreach( $newsdataimage as $itemimage){
    //         if($item->id == $itemimage->news_id){
    //             $imagepath=[
    //                 'id'=>$itemimage->id,
    //                 'image'=>$itemimage->image
    //             ];
    //             array_push($imageData, $imagepath);

    //         }
    //     }
    //     $newKey = 'image';
    //     $data[$newKey]= $imageData;
    //     array_push($dataOP, $data);

    // }
        $response['status'] = 100;
        $response['message'] = 'success';
        $response['result']=  $newsdata;
        return response()->json($response,200);

}
catch (\Exception $e) {
    $response['status'] = 500;
    $response['message'] = $e;
    return response()->json($response,500);
}

}
 public function getProductDetails(){
    try{

    $productdata=Product::select('id','product_name','product_description','product_rate','available_quantity','product_discount_percent')
    ->where('is_deleted',0)->where('display_status',1)->orderBy('id','DESC')->get();
    $dataOP=[];
    foreach( $productdata as $item ){
        $data=$item;
        $productdataimage=ProductImage::select('id','product_id','product_image','is_deleted','display_status')
        ->where('product_id',$data->id)->orderBy('id','DESC')->first();
        $imageData=array();
        if($productdataimage->is_deleted==0 && $productdataimage->display_status==1){
                        $imagepath=[
                        'id'=>$productdataimage->id,
                        'image'=>$productdataimage->product_image
                        ];
                        array_push($imageData, $imagepath);
                    }
                 $newKey = 'image';
                 $data[$newKey]= $imageData;
                 array_push($dataOP, $data);
    }
    $response['status'] = 100;
    $response['message'] = 'success';
    $response['result']=  $dataOP;
    return response()->json($response,200);
}
catch (\Exception $e) {
    $response['status'] = 500;
    $response['message'] = $e;
    return response()->json($response,500);
}

 }
}
