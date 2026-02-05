<?php

namespace App\Http\Controllers\API\MobileApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MobileApp\Purchase_Product;
use App\Models\MobileApp\Purchase_Product_Details;
use Razorpay\Api\Api;
use App\Models\Masters\Product;
use Illuminate\Support\Facades\DB;
use App\Models\MobileApp\RazorPayModel;

use Razorpay\Api\Errors\SignatureVerificationError;

class productPurchaseController extends Controller

{

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

    public function purchaseProduct(Request $request)
    {
        try {
            $tempproductData = $request->products;
            //INITIALIZE ORDER FOR PAYMENT GATEWAY
            $create_order = $this->createOrder($request->total_amount, 1);
            //  $responseArray = json_decode($create_order, true);

            $productdata = array(
                'user_id' => $request->user_id,
                'patient_id' => $request->patient_id,
                'total_amount' => $request->total_amount,
                // 'product_id'=>$request->id, // check this pls if no need remove this line
                'order_master_id' => $create_order['order_master_id'],
            );
            $data = Purchase_Product::create($productdata);
            $dataDetails = $data->id;

            foreach ($tempproductData as $tempData) {
                // $totalAmount = $tempData['price'] * $tempData['count'];
                $productdataDetails = array(
                    'order_id' => $dataDetails,
                    'product_id' => $tempData['product_id'],
                    'product_price' => $tempData['single_price'],
                    'product_qty' => $tempData['count'],
                    'unit_total' => $tempData['price'],
                );

                $datadetails = Purchase_Product_Details::create($productdataDetails);
                if($tempData['available_quantity']>=$tempData['count']){
                    $quantityrate=$tempData['available_quantity'];
                    $countData=$tempData['count'];
                    $productquantity=$quantityrate-$countData;
                    $ups_data = array(
                        'available_quantity' => $productquantity,
                    );

                  $quantityData=Product::where('id', $tempData['product_id'])->update($ups_data);
                }
            }
            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result'] =  $create_order;
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = $e;
            return response()->json($response, 500);
        }
    }
    public function userPurchaseHistory(Request $request)
    {
        try {
            if($request->patient_id>0){
            $historyData = Purchase_Product::select('app_purchase_product.id', 'app_purchase_product.user_id',
             'app_purchase_product.total_amount', 'app_purchase_product.payment_status',
             'app_purchase_product.order_status','razor_pay_payments.order_number')->join('razor_pay_payments',
             'razor_pay_payments.order_master_id','app_purchase_product.order_master_id')
            ->where('patient_id', $request->patient_id )-> orderBy('id','DESC')->get();
            $history=array();
            foreach( $historyData as $item){
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
                    'user_id'=> $item['user_id'],
                    'amount'=> $item['total_amount'],
                    'payment_status'=>$item['payment_status'],
                    'order_status'=>$status,
                   'order_number'=>$item['order_number']

                ];
            }

            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result'] =   $history;
            return response()->json($response, 200);
        }else{
            $response['status'] = 100;
            $response['message'] = 'fail';
            $response['result'] =[];
            return response()->json($response, 200);
        }

        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response, 500);
        }
    }
    public function userPurchaseHistoryDetails(Request $request)
    {
        try {

            if (isset($request->order_id)) {
                $historyData = Purchase_Product_Details::select(
                    'app_purchase_product_details.id',
                    'app_purchase_product_details.product_price',
                    'app_purchase_product_details.product_qty',
                    'app_purchase_product_details.product_id',

                    // 'app_purchase_product_details.product_qty',

                    // 'products.product_rate',
                    'products.product_name',
                    // 'products_images.product_id',
                    // 'products_images.product_image'
                )
                    ->join('products', 'products.id', 'app_purchase_product_details.product_id')
                    ->where('app_purchase_product_details.order_id', $request->order_id)->get();
                    $data = array();
                    foreach($historyData as $tempData){
                        $imageId= $tempData->product_id;
                        $cond=array(
                            array('product_id',$imageId),
                         );

                        $image=getAfeild("product_image","products_images",$cond);
                        $productdataDetails = [
                            'id' => $tempData['id'],
                            'quantity' => $tempData['product_qty'],
                            // 'total_amount'=>$totalAmount,
                            'name' => $tempData['product_name'],
                            'product_image' =>$image,
                            'cost' => $tempData['product_price'],
                        ];
                        array_push($data, $productdataDetails);
                    }

                //GET BILLING DATA
                //BRANCH DETAILS
               $branch_details= GetBranchDetails(1);
                //read purchased patient id

                $cond2=array(
                    array('id',$request->order_id),
                 );

                $patient_id=getAfeild("patient_id","app_purchase_product",$cond2);

                //GET PATIENT ADDRESS
               $billing_info=DB::table('patient_registration')->select('name','last_name','address')->where('id',$patient_id)->first();



                $response['status'] = 100;
                $response['message'] = 'success';
                $response['result'] =  [
                    'order_details'=>$data,
                    'branch_details'=>$branch_details,
                    'billing_info'=>$billing_info
                ];
                return response()->json($response, 200);
            } else {
                $response['status'] = 101;
                $response['message'] = 'invalid oderId';
                $response['result'] =  [];   // check this also, why need $data in error response
                return response()->json($response, 200);
            }
        } catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = $e;
            return response()->json($response, 500);
        }
    }
}
