<?php

namespace App\Http\Controllers\API\MobileApp;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MobileApp\AppUsers;
use Validator;
use App\Http\Controllers\Controller;
// use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\SMSGatewayService;

class AuthController extends Controller
{

    protected $smsGatewayService;

    public function __construct(SMSGatewayService $smsGatewayService)
    {
        // Initialize the SMS Gateway service with your credentials
        $this->smsGatewayService = new SMSGatewayService();
    }

    public function smsTest()
    {
        $phoneNumber="9995495860";
       
        // $message="Your One Time Password (OTP) for login at JDC Care is 123456. Do not share your OTP with anyone. Thank You! JDC Team visit www.jothydev.net, www.sugarcart.in";
        // $templateId="1207169036992189664";

        $message="Your One Time Password (OTP) for login at JDC Care is {#var#}.Do not share your OTP with others. Thank You! JDC Team visit www.jothydev.net, www.sugarcart.in";
        $templateId="1207169088755251452";
        
      
        $response = $this->smsGatewayService->sendSMS($phoneNumber, $message,$templateId);
        echo $response;
    }

    public function signup(Request $request)
    {
        // $credentials = $request->only('email', 'password');

        // //valid credential
        // $validator = Validator::make($credentials, [
        //     'email' => 'required|email',
        //     'password' => 'required|string|min:6|max:50'
        // ]);

        // $validator = Validator::make($request->all(), [
        //     'first_name' => 'required|string',
        //     'mobile_number' => 'required|string|unique:app_users',
        //     'password' => 'required|string',
        // ]);
            if( !$request->first_name){
                $msg='First_name Requireds';
            }else if(!$request->mobile_number){
                $msg='mobile_number Requireds';

            }else if(!$request->password){
                $msg='password Requireds';

            }
            else{
                $phonenumber=AppUsers::select('id','otpverified','mobile_number')->where('mobile_number',$request->mobile_number)->first();
                $otp=rand(100000, 999999);

                if($phonenumber){
                    if($phonenumber->otpverified==1){
                        $msg=' Already Verified';
                        return response()->json(['message' => $msg,'status'=>101,'result'=>null], 200);
                    }
                   else {
                    $ups=array(
                        'otp'=>$otp,
                    );
                    AppUsers::where('id',$phonenumber->id)->update($ups);
                    $message="Your One Time Password (OTP) for login at JDC Care is $otp. Do not share your OTP with anyone. Thank You! JDC Team visit www.jothydev.net, www.sugarcart.in";
                    $templateId="1207169036992189664";
                    $response = $this->smsGatewayService->sendSMS($request->mobile_number, $message,$templateId);

                        //send sms
                        return response()->json(['message' => "Success",'status'=>100,'result'=> $ups], 200);

                    }
                    
                }
                else{

                    $user = new AppUsers([
                        'name' => $request->first_name,
                        'mobile_number' => $request->mobile_number,
                        'password' => bcrypt($request->password),
                        'otp'=>$otp
                    ]);
    
    
    
                    $user->save();
    
                    $message="Your One Time Password (OTP) for login at JDC Care is $otp. Do not share your OTP with anyone. Thank You! JDC Team visit www.jothydev.net, www.sugarcart.in";
                    $templateId="1207169036992189664";
                    $response = $this->smsGatewayService->sendSMS($request->mobile_number, $message,$templateId);
    
                    $otp=array(
                        'otp'=>strval($otp),
                    );

                    return response()->json(['message' => 'Successfully registered','status'=>100,'result'=>$otp], 200);
                }
                
            }
            return response()->json(['message' => $msg,'status'=>101,'result'=>null], 200);

    }

    public function login(Request $request)
    {



        $credentials = $request->only('mobile_number', 'password');

        try {
            if (!Auth::guard('app_users')->attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials','status'=>101,'result'=>null], 200);

            }

            $user = Auth::guard('app_users')->user();
            $token = JWTAuth::fromUser($user);
            $data=array(
                'user_id'=>$user->id,
                'token' => $token,
            );
            return response()->json(['message' => 'Successfully registered','status'=>100,'result'=>$data], 200);
            // $token = JWTAuth::fromUser($user,strtotime('+10 years'));

        } catch (JWTException $e) {
            return response()->json(['message' => 'Failed to create token','status'=>500,'status'=>null], 500);
        }
    }


     public function signupOtpVerify(Request $request){
        try{
            $mobileNumber=$request->mobile_number;
            $mobileotp=$request->otp;

            $fcm_token=$request->fcm_token;

            $userData=AppUsers::select('otpverified','otp','id','mobile_number')->where('mobile_number', $mobileNumber)->first();
             if($userData!=null){
                $otpverifieds=$userData->otpverified;

                // if($otpverifieds==1){
                //     $msg='Already Verified';
                // }else
                // {
                    $mobileotpdb=$userData->otp;

                    if($mobileotpdb==$mobileotp){
                        $userDataId=$userData->id;

                        $ups=array(
                            'otpverified'=>1,
                            'fcm_token'=>$fcm_token
                        );
                        AppUsers::where('id', $userDataId)->update($ups);
                        $userDataId=array(
                            'userId'=>$userData->id
                        );
                        $response['status'] = 100;
                        $response['message'] = 'Success';
                        $response['result'] = $userDataId;
                        return response()->json($response,200);
                     }else{
                        $msg='Invalid OTP';
                     }
                // }
             }else{
                $msg='Invalid Mobile Number';
            }
            $response['status'] = 101;
            $response['message'] = $msg;
            return response()->json($response,200);

         }
        catch (\Exception $e)
        {
            // $errorMessage = $e->getMessage();
            $response['status'] = 500;
            $response['message'] ='Server Error';

            return response()->json($response,500);
        }


     }
     public function loginconfirm(Request $request){
        try{
            $mobileNumber=$request->mobile_number;
            $mobileotp=$request->password;
            $userData=AppUsers::select('password','id','api_token')->where('mobile_number',$mobileNumber)->first();
            if($userData){
                $mobileotpDb=$userData->password;
                if(password_verify($mobileotp,$mobileotpDb)){
                    $userDataId=array(
                        'userId'=>$userData->id,
                        'token'=>$userData->api_token
                    );
                    $response['status'] = 100;
                    $response['message'] = 'Success';
                    $response['result'] = $userDataId;
                    return response()->json($response,200);

                }
            }
            $response['status'] = 101;
            $response['message'] = 'Invalid Credentials';
            return response()->json($response,200);

         }
        catch (\Exception $e)
        {
           $errorMessage = $e->getMessage();

           $response['status'] = 500;
           $response['message'] = $errorMessage;

            return response()->json($response,500);
        }

     }
     public function updateProfile(Request $request){
        try{
            $userId=$request->user_id;
            if($userId){
                $data=array(
                    'address'=>$request->address,
                    'email'=>$request->email,
                    'name'=>$request->name,
                );
                $userData=AppUsers::where('id',$userId)->update($data);
                $response['status'] = 100;
                $response['message'] = 'Success';
                $response['result'] = null;
                return response()->json($response,200);
            }
            else{
                $response['status'] = 101;
                $response['message'] = 'fail';
                $response['result'] = null;
                return response()->json($response,200);

            }

             }
        catch (\Exception $e)
        {
            $response['status'] = 500;
            $response['message'] = $e;
            $response['result'] = null;
            return response()->json($response,500);
        }

        }
        public function getProfile(Request $request)
        {
            // $user = Auth::user();
            // return response()->json(['user' => $user], 200);
            try{
                $userid=$request->user_id;
                if($userid){
                    $userData=AppUsers::select('id','address','email','name','mobile_number')->where('id',$userid)->first();
                    $response['status'] = 100;
                    $response['message'] = 'Success';
                    $response['result'] =$userData;
                    return response()->json($response,200);
                }
                else{
                    $response['status'] = 101;
                    $response['message'] = 'fail';
                    $response['result'] = null;
                    return response()->json($response,200);

                }

                 }
            catch (\Exception $e)
            {
                $response['status'] = 500;
                $response['message'] = $e;
                return response()->json($response,500);
            }

        }
    public function changePwdSendOtp(Request $request){
        try{
            $mobiileNumber=$request->mobilenumber;

            $dbMobileNumber=AppUsers::select('id')->where('mobile_number',$mobiileNumber)->where('otpverified',1)->first();

            if($dbMobileNumber!=null){
                $otp=rand(100000, 999999);
                $updateddata=array(
                    'otp'=>$otp,
                    // 'otpverified'=>0
                );
                $data=AppUsers::where('mobile_number',$mobiileNumber)->update($updateddata);
                $otparray=array(
                    'otp'=>strval($otp)
                );
                if( $data){

                $message="We received a request to reset the password on your JDC Care Account.$otp Enter this code to complete the reset. Thank you! JDC Team Visit www.jothydev.net, www.sugarcart.in";
                $templateId="1207169088615541010";
                $out = $this->smsGatewayService->sendSMS($mobiileNumber, $message,$templateId);
                  
                $response['status'] = 100;
                    $response['message'] = 'Success';
                    $response['result'] =$otparray;
                    return response()->json($response,200);

                }
            }
            else{
                $response['status'] = 101;
                $response['message'] = 'Invalid Mobile Number';
                $response['result'] = null;
                return response()->json($response,200);
            }

            }

        catch (\Exception $e)
        {
            $response['status'] = 500;
            $response['message'] = $e;
            return response()->json($response,500);
        }

    }
    public function changePwdVerifyOtp(Request $request){
        try{
            $mobile_number=$request->mobilenumber;
            $new_psw=bcrypt($request->new_password);
            $otp=$request->otp;
            $userData=AppUsers::select('otpverified','otp','id','mobile_number')->where('mobile_number', $mobile_number)->first();
            if($userData!=null){
                $otpverifieds=$userData->otpverified;

                // if($otpverifieds==1){
                //     $msg='Already Verified';
                // }else
                // {
                    $mobileotpdb=$userData->otp;

                    if($mobileotpdb==$otp){
                        $userDataId=$userData->id;

                        $data=AppUsers::where('id', $userDataId)->update(['password'=>$new_psw,'otp'=>0]);
                        if($data){
                            $response['status'] = 100;
                            $response['message'] = 'Success';
                            $response['result'] =null;
                            return response()->json($response,200);
    
                        }
                     }else{
                        $msg='Invalid OTP';
                     }
                // }
             }else{

                $msg='Invalid Mobile Number';
            }
            $response['status'] = 101;
            $response['message'] = $msg;
            return response()->json($response,200);


        }
        catch (\Exception $e)
        {
            $response['status'] = 500;
            $response['message'] = $e;
            return response()->json($response,500);
        }


    }
    public function getContact(){

        $data= [
            [
            "id"=> 1,
            "url"=>"9000000000",
            "type"=> "Call us"
            ],
        [
            "id"=> 2,
            "url"=>"abc@abc.com",
            "type"=> "Email us"
        ],
        [
            "id"=>3,
            "url"=> "9000000000",
            "type"=> "Whatsapp"
        ],
        [
            "id"=> 4,
            "url"=> "www.abc.com",
            "type"=> "Website"
        ]
    ];
            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result']=   $data;
            return response()->json($response,200);


    }
   //// this api no neede
    public function reSentOtp(Request $request){
        try{
            $changeNumber=$request->mobile_number;
            $dbMobileId=AppUsers::select('id')->where('mobile_number',$changeNumber)->first();

            if($changeNumber){
                $otp=rand(100000, 999999);
                $data=AppUsers::where('mobile_number',$changeNumber)->update(['otp'=>$otp,'otpverified'=>0]);
                $otparray=array(
                    'otp'=>strval($otp)
                );
                if( $data){
                    $response['status'] = 100;
                    $response['message'] = 'Success';
                    $response['result'] =$otparray;
                    return response()->json($response,200);

                }
                else{
                    $response['status'] = 101;
                    $response['message'] = 'fail';
                    $response['result'] = null;
                    return response()->json($response,200);
                }
            }

            }

        catch (\Exception $e)
        {
            $response['status'] = 500;
            $response['message'] = $e;
            return response()->json($response,500);
        }

    }
    public function forgotPwdSendOtp(Request $request){
        try{
            $mobiileNumber=$request->mobilenumber;

            $dbMobileNumber=AppUsers::select('id')->where('mobile_number',$mobiileNumber)->first();

            if($dbMobileNumber!=null){
                $otp=rand(100000, 999999);
                $updateddata=array(
                    'otp'=>$otp
                );
                $data=AppUsers::where('mobile_number',$mobiileNumber)->update($updateddata);
                // $otparray=array(
                //     'otp'=>strval($otp)
                // );
                if( $data){
                    $message="We received a request to reset the password on your JDC Care Account.$otp Enter this code to complete the reset. Thank you! JDC Team Visit www.jothydev.net, www.sugarcart.in";
                    $templateId="1207169088615541010";
                    $out = $this->smsGatewayService->sendSMS($mobiileNumber, $message,$templateId);
    
                    $response['status'] = 100;
                    $response['message'] = 'Success';
                    $response['result'] =$updateddata;
                    return response()->json($response,200);

                }
            }
            else{
                $response['status'] = 101;
                $response['message'] = 'Invalid Mobile Number';
                $response['result'] = null;
                return response()->json($response,200);
            }

            }

        catch (\Exception $e)
        {
            $response['status'] = 500;
            $response['message'] = $e;
            return response()->json($response,500);
        }

    }

    public function forgotPwdVerifyOtp(Request $request){
        try{
            $otp=$request->otp;
            $mobilenumber=$request->mobilenumber;
            $new_password=bcrypt($request->new_password);
            $userData=AppUsers::select('otpverified','otp','id','mobile_number')->where('mobile_number', $mobilenumber)->first();

            if($userData!=null){
                $mobileotpdb=$userData->otp;


                if($mobileotpdb==$otp){

                    $userDataId=$userData->id;
                $data=array(
                    'password'=>$new_password,
                    'otp'=>0
                );


                $data1=AppUsers:: where('mobile_number',$mobilenumber)->where('otp',$otp)->update($data);
                $response['status'] = 100;
                        $response['message'] = 'Success';
                        $response['result'] =null;
                        return response()->json($response,200);

                 }else{
                    $response['status'] = 101;
                    $response['message'] = 'Invalid OTP';
                    $response['result'] = null;
                    return response()->json($response,200);
    
                 }


            }
            else{
                $response['status'] = 101;
                $response['message'] = 'Invalid Mobile Number';
                $response['result'] = null;
                return response()->json($response,200);
            }

            }

        catch (\Exception $e)
        {
            $response['status'] = 500;
            $response['message'] = $e;
            return response()->json($response,500);
        }

    }

     }

