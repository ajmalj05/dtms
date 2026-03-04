<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MobileApp\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/patientBill',[App\Http\Controllers\API\RegisterController::class, 'patientBill']);
Route::post('/testMaster',[App\Http\Controllers\API\RegisterController::class, 'testMaster']);
Route::post('/testResults',[App\Http\Controllers\API\RegisterController::class, 'testResults']);


Route::get('app/smsTest',[AuthController::class, 'smsTest']);

Route::post('app/register', [AuthController::class, 'register']);
Route::post('app/login', [AuthController::class, 'login']);
Route::post('app/signup',[AuthController::class, 'signup']);
Route::post('app/signupOtpVerify',[AuthController::class, 'signupOtpVerify']);
Route::post('app/loginconfirm',[AuthController::class, 'loginconfirm']);
Route::get('app/reSentOtp', [AuthController::class,'reSentOtp']);
Route::post('app/forgotPwdSendOtp  ', [AuthController::class,'forgotPwdSendOtp']);
Route::post('app/forgotPwdVerifyOtp  ', [AuthController::class,'forgotPwdVerifyOtp']);

Route::middleware('auth.api:api')->group(function () {
    Route::post('app/profile', [AuthController::class,'getProfile']);
    Route::post('app/patientOtpVerify',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'patientOtpVerify']);
    Route::post('app/getPatientInfo',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'getPatientInfo']);
    Route::post('app/getGender',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'getGender']);
    Route::post('app/patientRegister',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'patientRegister']);
    Route::post('app/PatientUserMapping',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'PatientUserMapping']);
    Route::post('app/patientListInUser',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'patientListInUser']);
    Route::post('app/updateProfile', [AuthController::class,'updateProfile']);
    Route::post('app/changePwdSendOtp', [AuthController::class,'changePwdSendOtp']);
    Route::post('app/changePwdVerifyOtp', [AuthController::class,'changePwdVerifyOtp']);
    Route::get('app/getNotification',[App\Http\Controllers\API\MobileApp\notificationController::class, 'getNotification']);
    Route::get('app/getNewspapper',[App\Http\Controllers\API\MobileApp\notificationController::class, 'getNewspapper']);
    Route::get('app/getProductDetails',[App\Http\Controllers\API\MobileApp\notificationController::class, 'getProductDetails']);
    Route::post('app/purchaseProduct',[App\Http\Controllers\API\MobileApp\productPurchaseController::class, 'purchaseProduct']);
    Route::post('app/userPurchaseHistory',[App\Http\Controllers\API\MobileApp\productPurchaseController::class, 'userPurchaseHistory']);
    Route::post('app/userPurchaseHistoryDetails',[App\Http\Controllers\API\MobileApp\productPurchaseController::class, 'userPurchaseHistoryDetails']);
    Route::get('app/gettabletTypesData',[App\Http\Controllers\API\MobileApp\MedicineController::class, 'gettabletTypesData']);
    Route::post('app/searchMedicineNamesData',[App\Http\Controllers\API\MobileApp\MedicineController::class, 'searchMedicineNamesData']);
    Route::post('app/OrderMedicine',[App\Http\Controllers\API\MobileApp\MedicineController::class, 'OrderMedicine']);
    Route::post('app/getmedicinePurchaseHistory',[App\Http\Controllers\API\MobileApp\MedicineController::class, 'getmedicinePurchaseHistory']);
    Route::post('app/getuserDashboarddata',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'getuserDashboarddata']);
    Route::get('app/getContact', [AuthController::class,'getContact']);
    Route::post('app/saveMessage',[App\Http\Controllers\API\MobileApp\MessageController::class, 'saveMessage']);
    Route::post('app/getReplay',[App\Http\Controllers\API\MobileApp\MessageController::class, 'getReplay']);
    Route::post('app/medicinePurchaseAmount',[App\Http\Controllers\API\MobileApp\MedicineController::class, 'medicinePurchaseAmount']);
    Route::post('app/successOrfailure',[App\Http\Controllers\API\MobileApp\MedicineController::class, 'successOrfailure']);
    Route::post('app/cancelAppoinment',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'cancelAppoinment']);
    Route::post('app/getReminders',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'getReminders']);
    Route::post('app/getDocumentCategory',[App\Http\Controllers\API\MobileApp\MasterController::class, 'getDocumentCategory']);
    Route::post('app/uploadFile',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'uploadFile']);
    Route::post('app/getMedicineInvoice',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'getMedicineInvoice']);
    Route::post('app/getProductInvoice',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'getProductInvoice']);

    //masters

    Route::post('app/allAccountCreatedFor',[App\Http\Controllers\API\MobileApp\MasterController::class, 'allAccountCreatedFor']);
    Route::post('app/allSalutations',[App\Http\Controllers\API\MobileApp\MasterController::class, 'allSalutations']);
    Route::get('app/getGender',[App\Http\Controllers\API\MobileApp\MasterController::class, 'getGender']);


});

//BY AKHIL
Route::middleware('auth.api:api')->group(function () {
    Route::post('app/bookAppointment',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'bookAppointment']);
    Route::post('app/userAppointments',[App\Http\Controllers\API\MobileApp\PatientInfo::class, 'userAppointments']);

    //masters
    Route::post('app/allCenters',[App\Http\Controllers\API\MobileApp\MasterController::class, 'allCenters']);
    Route::post('app/centerDetails',[App\Http\Controllers\API\MobileApp\MasterController::class, 'centerDetails']);

    //dtms
    Route::post('app/getAllPatientVisit',[App\Http\Controllers\API\MobileApp\AppDtmsController::class, 'getAllPatientVisit']);
    Route::post('app/getPrescriptions',[App\Http\Controllers\API\MobileApp\AppDtmsController::class, 'getPrescriptions']);
    Route::post('app/getVitalSigns',[App\Http\Controllers\API\MobileApp\AppDtmsController::class, 'getVitalSigns']);
    Route::post('app/getSMBGTest',[App\Http\Controllers\API\MobileApp\AppDtmsController::class, 'getSMBGTest']);
    Route::post('app/saveBloodGlucose',[App\Http\Controllers\API\MobileApp\AppDtmsController::class, 'saveBloodGlucose']);
    Route::post('app/getBloodGlucose',[App\Http\Controllers\API\MobileApp\AppDtmsController::class, 'getBloodGlucose']);
    Route::post('app/saveBpStatus',[App\Http\Controllers\API\MobileApp\AppDtmsController::class, 'saveBpStatus']);
    Route::post('app/getBpStatus',[App\Http\Controllers\API\MobileApp\AppDtmsController::class, 'getBpStatus']);

    Route::post('app/getTestBills',[App\Http\Controllers\API\MobileApp\AppDtmsController::class, 'getTestBills']);
    Route::post('app/getTestByVisit',[App\Http\Controllers\API\MobileApp\AppDtmsController::class, 'getTestByVisit']);
    Route::post('app/getTestsByPatient',[App\Http\Controllers\API\MobileApp\AppDtmsController::class, 'getTestsByPatient']);
    Route::post('app/getGraphByTest',[App\Http\Controllers\API\MobileApp\AppDtmsController::class, 'getGraphByTest']);


    Route::post('app/createOrder',[App\Http\Controllers\API\MobileApp\productPurchaseController::class, 'createOrder']);

    Route::post('app/getmedicinePurchaseDetails',[App\Http\Controllers\API\MobileApp\MedicineController::class, 'getmedicinePurchaseDetails']);


});




