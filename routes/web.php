<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\users\LaboratoryController;//athira
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/', function () {
//     return view('welcome');
// });

Route::redirect('/', 'login');
Route::get('/book_appointment', [App\Http\Controllers\users\ClientController::class, 'public_user'])->name('public_user');
Route::get('/account_delete', [App\Http\Controllers\users\ClientController::class, 'account_delete'])->name('account_delete');

Route::post('/save-book-appointment', [App\Http\Controllers\users\ClientController::class, 'saveBookAppointment'])->name('save-book-appointment');
Route::get('/consultant-list', [App\Http\Controllers\users\ClientController::class, 'consultantList'])->name('consultant-list');
Route::post('/get-old-registration-list', [App\Http\Controllers\users\ClientController::class, 'oldRegistrationList'])->name('get-old-registration-list');

Route::get('/book_appointment', [App\Http\Controllers\users\ClientController::class, 'public_user'])->name('public_user');
/////public print result//////////
Route::get('/public_print_result/{patientBillId}', [App\Http\Controllers\users\ClientController::class, 'public_print_result'])->name('public_print_result');

Route::middleware([
    'auth:sanctum',
    // 'prevent-back-history',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\users\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/patientRegistration', [App\Http\Controllers\users\PatientController::class, 'index'])->name('patientRegistration');
    Route::get('/patientSearch', [App\Http\Controllers\users\PatientController::class, 'patientSearch'])->name('patientSearch');
    Route::get('/patientBooks', [App\Http\Controllers\users\PatientController::class, 'patientBooks'])->name('patientBooks');
    Route::post('/patientGallery', [App\Http\Controllers\users\PatientController::class, 'patientGallery'])->name('patientGallery');

    Route::get('/masterData/sectionOne', [App\Http\Controllers\users\MasterDataController::class, 'sectionOne'])->name('sectionOne');
    Route::get('/masterData/sectionTwo', [App\Http\Controllers\users\MasterDataController::class, 'sectionTwo'])->name('sectionTwo');
    Route::get('/masterData/MasterThree', [App\Http\Controllers\users\MasterDataController::class, 'MasterThree'])->name('MasterThree');

    Route::get('/masterData/sectionThree', [App\Http\Controllers\users\MasterDataController::class, 'sectionThree'])->name('sectionThree');
    Route::get('/get-user-list', [App\Http\Controllers\users\MasterDataController::class, 'getUserList'])->name('get-user-list');

    Route::get('/masterData/sectionFour', [App\Http\Controllers\users\MasterDataController::class, 'sectionFour'])->name('sectionFour');
    Route::get('/masterData/sectionFive', [App\Http\Controllers\users\MasterDataController::class, 'sectionFive'])->name('sectionFive');

    Route::get('/masterData/department', [App\Http\Controllers\users\MasterDataController::class, 'department'])->name('department');

    Route::post('/masterData/getEmailsMaster', [App\Http\Controllers\users\MasterDataController::class, 'getEmailsMaster'])->name('getEmailsMaster');
    Route::post('/masterData/saveEmailExt', [App\Http\Controllers\users\MasterDataController::class, 'saveEmailExt'])->name('saveEmailExt');
    Route::post('/masterData/deleteEmailExt', [App\Http\Controllers\users\MasterDataController::class, 'deleteEmailExt'])->name('deleteEmailExt');


    Route::post('/api/inbody', [App\Http\Controllers\users\InBodyController::class, 'apiStore']);
    
    // AI Routes
    Route::post('/api/ai/chat', [App\Http\Controllers\API\AiChatController::class, 'handleChat']);
    Route::post('/api/ai/analyze', [App\Http\Controllers\API\AiChatController::class, 'getSummary'])->name('ai.analyze');
    Route::post('/api/ai/analyze-trend', [App\Http\Controllers\API\AiChatController::class, 'analyzeTrend'])->name('ai.analyze-trend');


    Route::post('/masterData/saveDepartment', [App\Http\Controllers\users\MasterDataController::class, 'saveDepartment'])->name('saveDepartment');
    Route::post('/masterData/getDepartmentDetails', [App\Http\Controllers\users\MasterDataController::class, 'getDepartmentDetails'])->name('getDepartmentDetails');
    Route::post('/masterData/deleteDepartment', [App\Http\Controllers\users\MasterDataController::class, 'deleteDepartment'])->name('deleteDepartment');

    Route::post('/masterData/saveCategory', [App\Http\Controllers\users\MasterDataController::class, 'saveCategoryMaster'])->name('saveCategory');
    Route::post('/masterData/getCategory', [App\Http\Controllers\users\MasterDataController::class, 'getCategory'])->name('getCategory');
    Route::post('/masterData/deleteCategory', [App\Http\Controllers\users\MasterDataController::class, 'deleteCategory'])->name('deleteCategory');

    Route::post('/masterData/saveSubCategory', [App\Http\Controllers\users\MasterDataController::class, 'saveSubCategoryMaster'])->name('saveSubCategory');
    Route::post('/masterData/getSubCategory', [App\Http\Controllers\users\MasterDataController::class, 'getSubCategory'])->name('getSubCategory');
    Route::post('/masterData/deleteSubCategory', [App\Http\Controllers\users\MasterDataController::class, 'deleteSubCategory'])->name('deleteSubCategory');

    Route::post('/masterData/savePatientRef', [App\Http\Controllers\users\MasterDataController::class, 'savePatientRef'])->name('savePatientRef');
    Route::post('/masterData/getPatientRef', [App\Http\Controllers\users\MasterDataController::class, 'getPatientRef'])->name('getPatientRef');
    Route::post('/masterData/deletePatientRef', [App\Http\Controllers\users\MasterDataController::class, 'deletePatientRef'])->name('deletePatientRef');


    Route::post('/masterData/savePatientType', [App\Http\Controllers\users\MasterDataController::class, 'savePatientTypeMaster'])->name('savePatientType');

    Route::post('/masterData/getPatientType', [App\Http\Controllers\users\MasterDataController::class, 'getPatientType'])->name('getPatientType');
    Route::post('/masterData/deletePatientType', [App\Http\Controllers\users\MasterDataController::class, 'deletePatientType'])->name('deletePatientType');


    Route::post('/masterData/saveSalutation', [App\Http\Controllers\users\MasterDataController::class, 'saveSalutation'])->name('saveSalutation');
    Route::post('/masterData/getSalutation', [App\Http\Controllers\users\MasterDataController::class, 'getSalutation'])->name('getSalutation');
    Route::post('/masterData/deleteSalutation', [App\Http\Controllers\users\MasterDataController::class, 'deleteSalutation'])->name('deleteSalutation');

    Route::post('/masterData/saveRelation', [App\Http\Controllers\users\MasterDataController::class, 'saveRelation'])->name('saveRelation');
    Route::post('/masterData/getRelation', [App\Http\Controllers\users\MasterDataController::class, 'getRelation'])->name('getRelation');
    Route::post('/masterData/deleteRelation', [App\Http\Controllers\users\MasterDataController::class, 'deleteRelation'])->name('deleteRelation');

    Route::post('/masterData/saveReligion', [App\Http\Controllers\users\MasterDataController::class, 'saveReligion'])->name('saveReligion');
    Route::post('/masterData/getReligion', [App\Http\Controllers\users\MasterDataController::class, 'getReligion'])->name('getReligion');
    Route::post('/masterData/deleteReligion', [App\Http\Controllers\users\MasterDataController::class, 'deleteReligion'])->name('deleteReligion');


    Route::post('/masterData/saveSubDiv', [App\Http\Controllers\users\MasterDataController::class, 'saveSubDiv'])->name('saveSubDiv');
    Route::post('/masterData/getSubDiv', [App\Http\Controllers\users\MasterDataController::class, 'getSubDiv'])->name('getSubDiv');
    Route::post('/masterData/deleteSubDiv', [App\Http\Controllers\users\MasterDataController::class, 'deleteSubDiv'])->name('deleteSubDiv');

    Route::post('/masterData/saveSpecialist', [App\Http\Controllers\users\MasterDataController::class, 'saveSpecialist'])->name('saveSpecialist');
    Route::post('/masterData/getSpecialist', [App\Http\Controllers\users\MasterDataController::class, 'getSpecialist'])->name('getSpecialist');
    Route::post('/masterData/deleteSpecialist', [App\Http\Controllers\users\MasterDataController::class, 'deleteSpecialist'])->name('deleteSpecialist');

    Route::post('/masterData/savePatientReference', [App\Http\Controllers\users\MasterDataController::class, 'savePatientReference'])->name('savePatientReference');
    Route::post('/masterData/getPatientRefernce', [App\Http\Controllers\users\MasterDataController::class, 'getPatientReference'])->name('getPatientReference');
    Route::post('/masterData/deletePatientReference', [App\Http\Controllers\users\MasterDataController::class, 'deletePatientReference'])->name('deletePatientReference');

    Route::post('/masterData/saveIdProofType', [App\Http\Controllers\users\MasterDataController::class, 'saveIdProofType'])->name('saveIdProofType');
    Route::post('/masterData/getIdProofType', [App\Http\Controllers\users\MasterDataController::class, 'getIdProofType'])->name('getIdProofType');
    Route::post('/masterData/deleteIdProof', [App\Http\Controllers\users\MasterDataController::class, 'deleteIdProof'])->name('deleteIdProof');


    Route::post('/masterData/saveMaritalStatus', [App\Http\Controllers\users\MasterDataController::class, 'saveMaritalStatus'])->name('saveMaritalStatus');
    Route::post('/masterData/getMeritalStatus', [App\Http\Controllers\users\MasterDataController::class, 'getMeritalStatus'])->name('getMeritalStatus');
    Route::post('/masterData/deleteMeritialStatus', [App\Http\Controllers\users\MasterDataController::class, 'deleteMeritialStatus'])->name('deleteMeritialStatus');


    Route::post('/masterData/saveEducation', [App\Http\Controllers\users\MasterDataController::class, 'saveEducation'])->name('saveEducation');
    Route::post('/masterData/getEducations', [App\Http\Controllers\users\MasterDataController::class, 'getEducations'])->name('getEducations');
    Route::post('/masterData/deleteEducation', [App\Http\Controllers\users\MasterDataController::class, 'deleteEducation'])->name('deleteEducation');
    Route::post('/masterData/deleteTestMaster', [App\Http\Controllers\users\MasterDataController::class, 'deleteTestMaster'])->name('deleteTestMaster');


    Route::post('/masterData/saveOccupation', [App\Http\Controllers\users\MasterDataController::class, 'saveOccupation'])->name('saveOccupation');
    Route::post('/masterData/getOccupation', [App\Http\Controllers\users\MasterDataController::class, 'getOccupation'])->name('getOccupation');
    Route::post('/masterData/deleteOccupation', [App\Http\Controllers\users\MasterDataController::class, 'deleteOccupation'])->name('deleteOccupation');



    Route::post('/masterData/saveBloodGroup', [App\Http\Controllers\users\MasterDataController::class, 'saveBloodGroup'])->name('saveBloodGroup');
    Route::post('/masterData/getBloodGroup', [App\Http\Controllers\users\MasterDataController::class, 'getBloodGroup'])->name('getBloodGroup');
    Route::post('/masterData/deleteBloodGroup', [App\Http\Controllers\users\MasterDataController::class, 'deleteBloodGroup'])->name('deleteBloodGroup');

    Route::post('/masterData/saveAnnualIncome', [App\Http\Controllers\users\MasterDataController::class, 'saveAnnualIncome'])->name('saveAnnualIncome');
    Route::post('/masterData/getAnnualIncome', [App\Http\Controllers\users\MasterDataController::class, 'getAnnualIncome'])->name('getAnnualIncome');
    Route::post('/masterData/deleteAnnualIncome', [App\Http\Controllers\users\MasterDataController::class, 'deleteAnnualIncome'])->name('deleteAnnualIncome');


    Route::get('/UserManagement/userGroup', [App\Http\Controllers\users\UserManagementController::class, 'userGroup'])->name('userGroup');
    Route::post('/UserManagement/saveUserGroup', [App\Http\Controllers\users\UserManagementController::class, 'saveUserGroup'])->name('saveUserGroup');
    Route::post('/UserManagement/GetUserGroupMenus', [App\Http\Controllers\users\UserManagementController::class, 'GetUserGroupMenus'])->name('GetUserGroupMenus');
    Route::post('/UserManagement/deleteUserGroup', [App\Http\Controllers\users\UserManagementController::class, 'deleteUserGroup'])->name('deleteUserGroup');

    Route::get('/branch_profile', [App\Http\Controllers\users\UserManagementController::class, 'branch_profile'])->name('branch_profile');
    Route::post('/saveBranchData', [App\Http\Controllers\users\UserManagementController::class, 'saveBranchData'])->name('saveBranchData');
    Route::post('/savePrintSettings', [App\Http\Controllers\users\UserManagementController::class, 'savePrintSettings'])->name('savePrintSettings');
    Route::post('/getPrintSettings', [App\Http\Controllers\users\UserManagementController::class, 'getPrintSettings'])->name('getPrintSettings');


    Route::get('/UserManagement/createUser', [App\Http\Controllers\users\UserManagementController::class, 'createUser'])->name('createUser');
    Route::post('/UserManagement/saveNewUser', [App\Http\Controllers\users\UserManagementController::class, 'saveNewUser'])->name('saveNewUser');
    Route::post('/UserManagement/getUsers', [App\Http\Controllers\users\UserManagementController::class, 'getUsers'])->name('getUsers');
    Route::post('/UserManagement/getGroupByBranch', [App\Http\Controllers\users\UserManagementController::class, 'getGroupByBranch'])->name('getGroupByBranch');
    Route::post('/UserManagement/mapuserGroup', [App\Http\Controllers\users\UserManagementController::class, 'mapuserGroup'])->name('mapuserGroup');
    Route::post('/UserManagement/getMappedGroup', [App\Http\Controllers\users\UserManagementController::class, 'getMappedGroup'])->name('getMappedGroup');
    Route::post('/UserManagement/deletemappedGroup', [App\Http\Controllers\users\UserManagementController::class, 'deletemappedGroup'])->name('deletemappedGroup');
    Route::post('/UserManagement/changeBranch', [App\Http\Controllers\users\UserManagementController::class, 'changeBranch'])->name('changeBranch');
    Route::post('/UserManagement/deleteUser', [App\Http\Controllers\users\UserManagementController::class, 'deleteUser'])->name('deleteUser');



    Route::post('/savePatient', [App\Http\Controllers\users\PatientController::class, 'savePatient'])->name('savePatient');

    Route::post('/searchPatient', [App\Http\Controllers\users\PatientController::class, 'searchPatient'])->name('searchPatient');
    Route::post('/getStates', [App\Http\Controllers\users\MasterDataController::class, 'getStates']);
    Route::post('/getCities', [App\Http\Controllers\users\MasterDataController::class, 'getCities']);
    Route::post('/getCitiesByPin', [App\Http\Controllers\users\MasterDataController::class, 'getCitiesByPin']);
    Route::post('/getStatesByCities', [App\Http\Controllers\users\MasterDataController::class, 'getStatesByCities']);
    Route::post('/getStatesByCitiesReverse', [App\Http\Controllers\users\MasterDataController::class, 'getStatesByCitiesReverse']);
    Route::post('/getcountrybyState', [App\Http\Controllers\users\MasterDataController::class, 'getcountrybyState']);



    Route::post('/getCountriesByCities', [App\Http\Controllers\users\MasterDataController::class, 'getCountriesByCities']);

    Route::get('/PdfGenerator/idcard/{id}', [App\Http\Controllers\users\PdfGeneratorController::class, 'idcard'])->name('idcard');
    Route::post('/saveImages', [App\Http\Controllers\users\PatientController::class, 'saveImages']);
    Route::post('requestOTP', [App\Http\Controllers\users\PatientController::class, 'requestOTP']);
    Route::get('verifyOtp', [App\Http\Controllers\users\PatientController::class, 'verifyOtp']);



    // .km
    Route::get('/masterData/labgroups', [App\Http\Controllers\users\MasterDataController::class, 'labgroups'])->name('labgroups');
    Route::post('/masterData/getlabGroups', [App\Http\Controllers\users\MasterDataController::class, 'getlabGroups'])->name('getlabGroups');
    Route::post('/masterData/deletelabGroups', [App\Http\Controllers\users\MasterDataController::class, 'deletelabGroups'])->name('deletelabGroups');
    Route::post('/masterData/savelabGroups', [App\Http\Controllers\users\MasterDataController::class, 'savelabGroups'])->name('savelabGroups');

    Route::post('/masterData/saveTestMaster', [App\Http\Controllers\users\MasterDataController::class, 'saveTestMaster'])->name('saveTestMaster');
    Route::post('/getTestByGroup', [App\Http\Controllers\users\MasterDataController::class, 'getTestByGroup'])->name('getTestByGroup');
    Route::post('/masterData/saveTestProcedure', [App\Http\Controllers\users\MasterDataController::class, 'saveTestProcedure'])->name('saveTestProcedure');
    Route::post('/masterData/saveSubTestMaster', [App\Http\Controllers\users\MasterDataController::class, 'saveSubTestMaster'])->name('saveSubTestMaster');
    Route::post('/masterData/getTestSubgroups', [App\Http\Controllers\users\MasterDataController::class, 'getTestSubgroups'])->name('getTestSubgroups');


    // settings-- Test procedure--
    Route::get('/masterData/testranges', [App\Http\Controllers\users\MasterDataController::class, 'testranges'])->name('testranges');
    Route::post('/masterData/getTestRanges', [App\Http\Controllers\users\MasterDataController::class, 'getTestRanges'])->name('getTestRanges');
    Route::post('/masterData/deleteTestRanges', [App\Http\Controllers\users\MasterDataController::class, 'deleteTestRanges'])->name('deleteTestRanges');
    Route::post('/masterData/saveTestRanges', [App\Http\Controllers\users\MasterDataController::class, 'saveTestRanges'])->name('saveTestRanges');
    Route::post('/masterData/getTestMasterOptions', [App\Http\Controllers\users\MasterDataController::class, 'getTestMasterOptions'])->name('getTestMasterOptions');





    // lab
    //Route::get('/laboratory', [App\Http\Controllers\users\LaboratoryController::class, 'laboratory'])->name('laboratory');

    Route::get('/laboratory', [App\Http\Controllers\users\DtmsController::class, 'visitlists'])->name('laboratory');
    Route::get('/getPatientLabdetails', [App\Http\Controllers\users\LaboratoryController::class, 'getPatientLabdetails'])->name('getPatientLabdetails');
    Route::get('/resultentry', [App\Http\Controllers\users\LaboratoryController::class, 'resultentry'])->name('resultentry');
    Route::post('/manageResult', [App\Http\Controllers\users\LaboratoryController::class, 'manageResult'])->name('manageResult');
    Route::post('/save-result', [App\Http\Controllers\users\LaboratoryController::class, 'saveResult'])->name('save-result');
    Route::post('/printResult', [App\Http\Controllers\users\LaboratoryController::class, 'printResult'])->name('printResult');
    //sms
    Route::post('/sendSMS', [App\Http\Controllers\users\LaboratoryController::class, 'sendSMS'])->name('sendSMS');



    Route::get('/appointmentList', [App\Http\Controllers\users\AppointmentController::class, 'appointmentList'])->name('appointmentList');
    Route::get('/appointment', [App\Http\Controllers\users\AppointmentController::class, 'index'])->name('appointment');
    Route::post('/saveAppointment', [App\Http\Controllers\users\AppointmentController::class, 'saveAppointment'])->name('saveAppointment');
    Route::post('/searchPatientAppointment', [App\Http\Controllers\users\AppointmentController::class, 'searchPatientAppointment'])->name('searchPatientAppointment');
    Route::post('/linkpatientAppointment', [App\Http\Controllers\users\AppointmentController::class, 'linkpatientAppointment'])->name('linkpatientAppointment');
    Route::get('/dtmshome/{id}', [App\Http\Controllers\users\DtmsController::class, 'index'])->name('dtmshome');
    Route::get('/get-subcomplication-list', [App\Http\Controllers\users\DtmsController::class, 'getSubComplicationList'])->name('get-subcomplication-list');
    Route::get('/get-subdiagnosis-list', [App\Http\Controllers\users\DtmsController::class, 'getSubDiagnosisList'])->name('get-subdiagnosis-list');

    Route::post('/saveComplicationList', [App\Http\Controllers\users\DtmsController::class, 'saveComplicationList'])->name('saveComplicationList');
    Route::post('/saveDiagnosisList', [App\Http\Controllers\users\DtmsController::class, 'saveDiagnosisList'])->name('saveDiagnosisList');
    Route::post('/getComplicationData', [App\Http\Controllers\users\DtmsController::class, 'getComplicationData'])->name('getComplicationData');
    Route::post('/getDiagnosisData', [App\Http\Controllers\users\DtmsController::class, 'getDiagnosisData'])->name('getDiagnosisData');


    Route::post('/savePep', [App\Http\Controllers\users\DtmsController::class, 'savePep'])->name('savePep');

    Route::post('/saveVisit', [App\Http\Controllers\users\DtmsController::class, 'saveVisit'])->name('saveVisit');
    Route::get('/medicalhistory', [App\Http\Controllers\users\DtmsController::class, 'medicalhistory'])->name('medicalhistory');
    Route::post('/saveMedicalHistory', [App\Http\Controllers\users\DtmsController::class, 'saveMedicalHistory'])->name('saveMedicalHistory');
    Route::post('/savePatientDietPlan', [App\Http\Controllers\users\DtmsController::class, 'savePatientDietPlan'])->name('savePatientDietPlan');

    Route::get('/medications', [App\Http\Controllers\users\DtmsController::class, 'medications'])->name('medications');
    Route::get('/pep', [App\Http\Controllers\users\DtmsController::class, 'pep'])->name('pep');
    Route::get('/prescription', [App\Http\Controllers\users\DtmsController::class, 'prescription'])->name('prescription');
    Route::get('/vaccination', [App\Http\Controllers\users\DtmsController::class, 'vaccination'])->name('vaccination');
    Route::get('/miscellaneous', [App\Http\Controllers\users\DtmsController::class, 'miscellaneous'])->name('miscellaneous');
    Route::get('/dtms_dashboard', [App\Http\Controllers\users\DtmsController::class, 'dtms_dashboard'])->name('dtms_dashboard');
    Route::get('/visitlists', [App\Http\Controllers\users\DtmsController::class, 'visitlists'])->name('visitlists');
    // Route::get('/dtms_master', [App\Http\Controllers\users\MasterDataController::class, 'dtms_masterhome'])->name('dtms_master');
    Route::post('/savePhotos', [App\Http\Controllers\users\DtmsController::class, 'savePhotos'])->name('savePhotos');
    Route::post('/getPhotos', [App\Http\Controllers\users\DtmsController::class, 'getPhotos'])->name('getPhotos');
    Route::post('/getCGM', [App\Http\Controllers\users\DtmsController::class, 'getCGM'])->name('getCGM');

    Route::post('/deletePhoto', [App\Http\Controllers\users\DtmsController::class, 'deletePhoto'])->name('deletePhoto');
    Route::post('/getVisitLists', [App\Http\Controllers\users\DtmsController::class, 'getVisitLists'])->name('getVisitLists');
    // dtms master
    Route::get('/dtms_master', [App\Http\Controllers\users\MasterDataController::class, 'dtms_masterhome'])->name('dtms_master');
    Route::post('/saveVisitType', [App\Http\Controllers\users\MasterDataController::class, 'saveVisitType'])->name('saveVisitType');
    Route::post('/getVisitType', [App\Http\Controllers\users\MasterDataController::class, 'getVisitType'])->name('getVisitType');
    Route::post('/deleteVisitType', [App\Http\Controllers\users\MasterDataController::class, 'deleteVisitType'])->name('deleteVisitType');
    
    
    
    
   





    Route::post('/getDiagnosis', [App\Http\Controllers\users\MasterDataController::class, 'getDiagnosis'])->name('getDiagnosis');
    Route::post('/saveDiagnosis', [App\Http\Controllers\users\MasterDataController::class, 'saveDiagnosis'])->name('saveDiagnosis');
    Route::post('/deleteDiagnosis', [App\Http\Controllers\users\MasterDataController::class, 'deleteDiagnosis'])->name('deleteDiagnosis');

    Route::post('/getComplication', [App\Http\Controllers\users\MasterDataController::class, 'getComplication'])->name('getComplication');
    Route::post('/saveComplication', [App\Http\Controllers\users\MasterDataController::class, 'saveComplication'])->name('saveComplication');
    Route::post('/deleteComplication', [App\Http\Controllers\users\MasterDataController::class, 'deleteComplication'])->name('deleteComplication');

    Route::post('/getSubComplication', [App\Http\Controllers\users\MasterDataController::class, 'getSubComplication'])->name('getSubComplication');
    Route::post('/saveSubComplication', [App\Http\Controllers\users\MasterDataController::class, 'saveSubComplication'])->name('saveSubComplication');
    Route::post('/deleteSubComplication', [App\Http\Controllers\users\MasterDataController::class, 'deleteSubComplication'])->name('deleteSubComplication');
//jdc
    Route::post('/getSubDiagnosis', [App\Http\Controllers\users\MasterDataController::class, 'getSubDiagnosis'])->name('getSubDiagnosis');
    Route::post('/saveSubDiagnosis', [App\Http\Controllers\users\MasterDataController::class, 'saveSubDiagnosis'])->name('saveSubDiagnosis');
    Route::post('/deleteSubDiagnosis', [App\Http\Controllers\users\MasterDataController::class, 'deleteSubDiagnosis'])->name('deleteSubDiagnosis');
    Route::post('/new-page', [LaboratoryController::class, 'newPage']);//athira



    //prescription master
    Route::get('/prescription_master', [App\Http\Controllers\users\MasterDataController::class, 'prescription_master'])->name('prescription_master');
    Route::post('/savePrescriptionMaster', [App\Http\Controllers\users\MasterDataController::class, 'savePrescriptionMaster'])->name('savePrescriptionMaster');
    Route::post('/getPrescriptionMaster', [App\Http\Controllers\users\MasterDataController::class, 'getPrescriptionMaster'])->name('getPrescriptionMaster');
    Route::post('/deletePrescriptionMaster', [App\Http\Controllers\users\MasterDataController::class, 'deletePrescriptionMaster'])->name('deletePrescriptionMaster');

    //Questions Master
    Route::post('/getQuestions', [App\Http\Controllers\users\MasterDataController::class, 'getQuestions'])->name('getQuestions');
    Route::post('/saveQuestions', [App\Http\Controllers\users\MasterDataController::class, 'saveQuestions'])->name('saveQuestions');

    Route::post('/deleteQuestions', [App\Http\Controllers\users\MasterDataController::class, 'deleteQuestions'])->name('deleteQuestions');


    Route::get('/getTabletTypesListOptions', [App\Http\Controllers\users\MasterDataController::class, 'getTabletTypesListOptions'])->name('getTabletTypesListOptions');
    Route::post('/savePrescriptionMedicines', [App\Http\Controllers\users\MasterDataController::class, 'savePrescriptionMedicines'])->name('savePrescriptionMedicines');
    Route::post('/getPrescriptionMedicines', [App\Http\Controllers\users\MasterDataController::class, 'getPrescriptionMedicines'])->name('getPrescriptionMedicines');
    Route::post('/deletePrescriptionMedicines', [App\Http\Controllers\users\MasterDataController::class, 'deletePrescriptionMedicines'])->name('deletePrescriptionMedicines');

    //Dtms Home
    Route::post('/saveDtmsData', [App\Http\Controllers\users\DtmsController::class, 'saveDtmsData'])->name('saveDtmsData');
    Route::post('/visitHistory', [App\Http\Controllers\users\DtmsController::class, 'visitHistory'])->name('visitHistory');
    Route::post('/testResultData', [App\Http\Controllers\users\DtmsController::class, 'testResultData'])->name('testResultData');




    Route::post('/getMedicineLists', [App\Http\Controllers\users\MasterDataController::class, 'getMedicineLists'])->name('getMedicineLists');
    Route::post('/getMedicineDoseValue', [App\Http\Controllers\users\MasterDataController::class, 'getMedicineDoseValue'])->name('getMedicineDoseValue');
    Route::post('/visitHistoryById', [App\Http\Controllers\users\DtmsController::class, 'visitHistoryById'])->name('visitHistoryById');
    Route::post('/getDtmsProfileData', [App\Http\Controllers\users\DtmsController::class, 'getDtmsProfileData'])->name('getDtmsProfileData');

    //vaccination
    Route::post('/saveVaccinationData', [App\Http\Controllers\users\DtmsController::class, 'saveVaccinationData'])->name('saveVaccinationData');
    Route::post('/getVaccinationData', [App\Http\Controllers\users\DtmsController::class, 'getVaccinationData'])->name('getVaccinationData');

    //Alert
    Route::post('/saveAlertData', [App\Http\Controllers\users\DtmsController::class, 'saveAlertData'])->name('saveAlertData');
    Route::post('/getAlertData', [App\Http\Controllers\users\DtmsController::class, 'getAlertData'])->name('getAlertData');

    //pump
    Route::post('/getpumpDetailstData', [App\Http\Controllers\users\DtmsController::class, 'getpumpDetailstData'])->name('getpumpDetailstData');
    Route::post('/savepumpData', [App\Http\Controllers\users\DtmsController::class, 'savepumpData'])->name('savepumpData');
    //icon load
    Route::post('/getIconPatientdiet', [App\Http\Controllers\users\DtmsController::class, 'getIconPatientdiet'])->name('getIconPatientdiet');

    //hypo diary
    Route::post('/getHypoDiary', [App\Http\Controllers\users\DtmsController::class, 'getHypoDiary'])->name('getHypoDiary');
    
    // AI Chat for Patient Context
    Route::post('/ai-chat', [App\Http\Controllers\API\AiChatController::class, 'chat'])->name('ai.chat');
    Route::post('/ai-patient-summary', [App\Http\Controllers\API\AiChatController::class, 'getSummary'])->name('ai.summary');
    
    //Remainder
    Route::post('/saveRemainder', [App\Http\Controllers\users\DtmsController::class, 'saveRemainder'])->name('saveRemainder');
    Route::post('/getremainerData', [App\Http\Controllers\users\DtmsController::class, 'getremainerData'])->name('getremainerData');


    Route::post('/getSubQuestions', [App\Http\Controllers\users\MasterDataController::class, 'getSubQuestions'])->name('getSubQuestions');

    Route::post('/searchMedicineNames', [App\Http\Controllers\users\MasterDataController::class, 'searchMedicineNames'])->name('searchMedicineNames');

    //Prescription-Vaccination
    Route::post('/savePrescriptionVaccination', [App\Http\Controllers\users\MasterDataController::class, 'savePrescriptionVaccination'])->name('savePrescriptionVaccination');
    Route::post('/getPrescriptionVaccination', [App\Http\Controllers\users\MasterDataController::class, 'getPrescriptionVaccination'])->name('getPrescriptionVaccination');
    Route::post('/deletePrescriptionVaccination', [App\Http\Controllers\users\MasterDataController::class, 'deletePrescriptionVaccination'])->name('deletePrescriptionVaccination');


    Route::post('/generateformfields', [App\Http\Controllers\users\MasterDataController::class, 'generateformfields'])->name('generateformfields');
    Route::post('/saveform_engine', [App\Http\Controllers\users\MasterDataController::class, 'saveform_engine'])->name('saveform_engine');
    Route::post('/getMiscellaneousQuestions', [App\Http\Controllers\users\MasterDataController::class, 'getMiscellaneousQuestions'])->name('getMiscellaneousQuestions');
    Route::post('/calculategfr', [App\Http\Controllers\users\DtmsController::class, 'calculategfr'])->name('calculategfr');
    Route::post('/getQuestionsGroup', [App\Http\Controllers\users\MasterDataController::class, 'getQuestionsGroup'])->name('getQuestionsGroup');
    Route::post('/getSubQuestionsGroup', [App\Http\Controllers\users\MasterDataController::class, 'getSubQuestionsGroup'])->name('getSubQuestionsGroup');
    Route::post('/deleteMiscellaneousQs', [App\Http\Controllers\users\MasterDataController::class, 'deleteMiscellaneousQs'])->name('deleteMiscellaneousQs');


    Route::post('/updatePatientData', [App\Http\Controllers\users\DtmsController::class, 'updatePatientData'])->name('updatePatientData');
    Route::post('/saveMiscellaneousData', [App\Http\Controllers\users\DtmsController::class, 'saveMiscellaneousData'])->name('saveMiscellaneousData');
    Route::post('/getHeriditarydetails', [App\Http\Controllers\users\DtmsController::class, 'getHeriditarydetails'])->name('getHeriditarydetails');


    //Abroad Details
    Route::post('/saveAbroadData', [App\Http\Controllers\users\DtmsController::class, 'saveAbroadData'])->name('saveAbroadData');
    Route::post('/getAbroadData', [App\Http\Controllers\users\DtmsController::class, 'getAbroadData'])->name('getAbroadData');
    Route::post('/deleteAbroadData', [App\Http\Controllers\users\DtmsController::class, 'deleteAbroadData'])->name('deleteAbroadData');
    Route::post('/deleteVaccinationData', [App\Http\Controllers\users\DtmsController::class, 'deleteVaccinationData'])->name('deleteVaccinationData');

    Route::post('/deleteDiagnosisData', [App\Http\Controllers\users\DtmsController::class, 'deleteDiagnosisData'])->name('deleteDiagnosisData');
    Route::post('/deleteComplicationData', [App\Http\Controllers\users\DtmsController::class, 'deleteComplicationData'])->name('deleteComplicationData');

    //Patient Reminders
    Route::post('/savePatientReminders', [App\Http\Controllers\users\DtmsController::class, 'savePatientReminders'])->name('savePatientReminders');
    Route::post('/getPatientReminders', [App\Http\Controllers\users\DtmsController::class, 'getPatientReminders'])->name('getPatientReminders');
    Route::post('/deletePatientReminders', [App\Http\Controllers\users\DtmsController::class, 'deletePatientReminders'])->name('deletePatientReminders');
    Route::post('/getPatientGFRdetails', [App\Http\Controllers\users\DtmsController::class, 'getPatientGFRdetails'])->name('getPatientGFRdetails');

    // Create New Visit
    Route::post('/saveNewVisitData', [App\Http\Controllers\users\DtmsController::class, 'saveNewVisitData'])->name('saveNewVisitData');
    Route::post('/getNewVisitData', [App\Http\Controllers\users\DtmsController::class, 'getNewVisitData'])->name('getNewVisitData');

    //visit List
    Route::post('/updateVisitListData', [App\Http\Controllers\users\DtmsController::class, 'updateVisitListData'])->name('updateVisitListData');

    Route::post('/saveIpVisit', [App\Http\Controllers\users\DtmsController::class, 'saveIpVisit'])->name('saveIpVisit');

    // App Notification
    Route::get('/app_notification', [App\Http\Controllers\users\MasterDataController::class, 'app_notification'])->name('app_notification');
    Route::post('/loadcomplicationDropdown', [App\Http\Controllers\users\MasterDataController::class, 'loadcomplicationDropdown'])->name('loadcomplicationDropdown');
        //jdc
    Route::post('/loaddiagnosisDropdown', [App\Http\Controllers\users\MasterDataController::class, 'loaddiagnosisDropdown'])->name('loaddiagnosisDropdown');

    
    Route::post('/saveNotificationData', [App\Http\Controllers\users\MasterDataController::class, 'saveNotificationData'])->name('saveNotificationData');
    Route::post('/getAppNotificationMaster', [App\Http\Controllers\users\MasterDataController::class, 'getAppNotificationMaster'])->name('getAppNotificationMaster');
    Route::post('/deleteAppNotificationMaster', [App\Http\Controllers\users\MasterDataController::class, 'deleteAppNotificationMaster'])->name('deleteAppNotificationMaster');
    Route::post('/opdbilling', [App\Http\Controllers\users\BillingController::class, 'index'])->name('opdbilling');
    Route::post('/getServiceItemList', [App\Http\Controllers\users\BillingController::class, 'getServiceItemList'])->name('getServiceItemList');
    Route::get('/getSpecialistUserDropdown', [App\Http\Controllers\users\MasterDataController::class, 'getSpecialistUserDropdown'])->name('getSpecialistUserDropdown');
    Route::post('/getTestItemList', [App\Http\Controllers\users\BillingController::class, 'getTestItemList'])->name('getTestItemList');
    Route::post('/getAllItemListBygrp', [App\Http\Controllers\users\BillingController::class, 'getAllItemListBygrp'])->name('getAllItemListBygrp');

    //Products
    Route::get('/product', [App\Http\Controllers\users\MasterDataController::class, 'productData'])->name('product');
    Route::post('/saveProductData', [App\Http\Controllers\users\MasterDataController::class, 'saveProductData'])->name('saveProductData');
    Route::post('/getProductData', [App\Http\Controllers\users\MasterDataController::class, 'getProductData'])->name('getProductData');
    Route::post('/deleteProductData', [App\Http\Controllers\users\MasterDataController::class, 'deleteProductData'])->name('deleteProductData');
    Route::post('/get-product-images/{product}', [App\Http\Controllers\users\MasterDataController::class, 'getProductImage'])->name('get-product-images');
    Route::post('/delete-picture', [App\Http\Controllers\users\MasterDataController::class, 'deletePicture'])->name('delete-picture');

    //Service Item Master
    Route::get('/service-item-master', [App\Http\Controllers\users\MasterDataController::class, 'serviceItemData'])->name('service-item-master');
    Route::post('/get-service-item-data', [App\Http\Controllers\users\MasterDataController::class, 'getServiceItemMaster'])->name('get-service-item-data');
    Route::post('/save-service-item-data', [App\Http\Controllers\users\MasterDataController::class, 'saveServiceItemMaster'])->name('save-service-item-data');
    Route::post('/delete-service-item-data', [App\Http\Controllers\users\MasterDataController::class, 'deleteServiceItemMaster'])->name('delete-service-item-data');

    //Billing
    Route::post('/save-billing-data', [App\Http\Controllers\users\BillingController::class, 'saveBillingData'])->name('save-billing-data');
    Route::post('/get-billing-data', [App\Http\Controllers\users\BillingController::class, 'getBillingData'])->name('get-billing-data');
    Route::post('/get-outstanding-data', [App\Http\Controllers\users\BillingController::class, 'getOutStandingData'])->name('get-outstanding-data');
    Route::post('/save-outstanding-data', [App\Http\Controllers\users\BillingController::class, 'saveOutStandingData'])->name('save-outstanding-data');
    Route::post('/pdf-billing-data', [App\Http\Controllers\users\PdfController::class, 'billingDocument'])->name('pdf-billing-data');
    Route::post('/get-total-outstanding-data', [App\Http\Controllers\users\BillingController::class, 'getTotalOutstandingData'])->name('get-total-outstanding-data');
    Route::post('/pdf-outstanding-data', [App\Http\Controllers\users\PdfController::class, 'totalOutstandingDocument'])->name('pdf-outstanding-data');
    Route::post('/get-billing-data-byid', [App\Http\Controllers\users\BillingController::class, 'get_billingdata_byid'])->name('get-billing-data-byid');
    Route::post('/cancelBill', [App\Http\Controllers\users\BillingController::class, 'cancelBill'])->name('cancelBill');


    //Appointment
    Route::post('/get-consultant-list', [App\Http\Controllers\users\AppointmentController::class, 'getConsultantList'])->name('get-consultant-list');

    //IP Admission
    Route::get('/ip-admission-list', [App\Http\Controllers\users\IpdController::class, 'index'])->name('ip-admission-list');
    Route::get('/create-ip-admission', [App\Http\Controllers\users\IpdController::class, 'createIpAdmission'])->name('create-ip-admission');
    Route::post('/save-ip-admission', [App\Http\Controllers\users\IpdController::class, 'saveIpAdmission'])->name('save-ip-admission');
    Route::get('/get-ip-specialist-list', [App\Http\Controllers\users\IpdController::class, 'getSpecialistList'])->name('get-ip-specialist-list');
    Route::post('/search-ip-admission', [App\Http\Controllers\users\IpdController::class, 'searchIpAdmission'])->name('search-ip-admission');

    Route::post('/ipd-billing', [App\Http\Controllers\users\BillingController::class, 'ipdBillingIndex'])->name('ipd-billing');
    Route::post('/save-ipd-billing-data', [App\Http\Controllers\users\BillingController::class, 'saveIpdBillingData'])->name('save-ipd-billing-data');
    Route::post('/get-ipd-billing-data', [App\Http\Controllers\users\BillingController::class, 'getIpdBillingData'])->name('get-ipd-billing-data');
    Route::post('/ipd-pdf-billing-data', [App\Http\Controllers\users\PdfController::class, 'billingIpdDocument'])->name('ipd-pdf-billing-data');
    Route::post('/update-discharge-data', [App\Http\Controllers\users\IpdController::class, 'updateDischargeData'])->name('update-discharge-data');

    Route::post('/view_discharge_summary', [App\Http\Controllers\users\PdfController::class, 'view_discharge_summary'])->name('view_discharge_summary');

    // stock Management
    Route::post('/save-stock-data', [App\Http\Controllers\users\MasterDataController::class, 'saveStockData'])->name('save-stock-data');

    //history
    Route::get('/view_history', [App\Http\Controllers\users\MasterDataController::class, 'view_history'])->name('view_history');

    Route::post('/masterData/getHistoryDetails', [App\Http\Controllers\users\MasterDataController::class, 'getHistoryDetails'])->name('getHistoryDetails');

    // Discharge
    Route::get('/discharge-list', [App\Http\Controllers\users\DischargeController::class, 'index'])->name('discharge-list');
    Route::post('/search-discharge', [App\Http\Controllers\users\DischargeController::class, 'searchDischarge'])->name('search-discharge');

    //Service Item Group
    Route::get('/service-item-group', [App\Http\Controllers\users\MasterDataController::class, 'serviceItemGroupData'])->name('service-item-group');
    Route::post('/get-service-item-group-data', [App\Http\Controllers\users\MasterDataController::class, 'getServiceItemGroup'])->name('get-service-item-group-data');
    Route::post('/save-service-item-group-data', [App\Http\Controllers\users\MasterDataController::class, 'saveServiceItemGroup'])->name('save-service-item-group-data');
    Route::post('/delete-service-item-group-data', [App\Http\Controllers\users\MasterDataController::class, 'deleteServiceItemGroup'])->name('delete-service-item-group-data');

    // Test Master
    Route::get('/test-master', [App\Http\Controllers\users\MasterDataController::class, 'index'])->name('test-master');
    Route::post('/update-test-in-dtms', [App\Http\Controllers\users\MasterDataController::class, 'updateTestInDtms'])->name('update-test-in-dtms');
    Route::post('/update-test-in-target', [App\Http\Controllers\users\MasterDataController::class, 'updateTestInTarget'])->name('update-test-in-target');
    Route::post('/update-target-default-value', [App\Http\Controllers\users\MasterDataController::class, 'updateTargetDefaultValue'])->name('update-target-default-value');
    Route::post('/masterData/getTestMasterData', [App\Http\Controllers\users\MasterDataController::class, 'getTestMasterData'])->name('getTestMasterData');
    Route::post('/masterData/getTestConfig', [App\Http\Controllers\users\MasterDataController::class, 'getTestConfig'])->name('getTestConfig');
    Route::post('/update-test-orderno', [App\Http\Controllers\users\MasterDataController::class, 'updateTestOrderNo'])->name('update-test-orderno');


    Route::get('/view-all-test-results/{pid}', [App\Http\Controllers\users\DtmsController::class, 'getAllTestResults'])->name('view-all-test-results');
    Route::post('/view-all-test-results/{pid}', [App\Http\Controllers\users\DtmsController::class, 'getAllTestResults'])->name('view-all-test-results');

    // Target
    Route::post('/search-test-names', [App\Http\Controllers\users\DtmsController::class, 'searchTestNames'])->name('search-test-names');
    Route::post('/save-target-data', [App\Http\Controllers\users\DtmsController::class, 'saveTargetData'])->name('save-target-data');

    // Patient Gallery
    Route::post('/save-patient-gallery', [App\Http\Controllers\users\DtmsController::class, 'savePatientGallery'])->name('save-patient-gallery');
    Route::post('/get-patient-gallery', [App\Http\Controllers\users\DtmsController::class, 'getPatientGallery'])->name('get-patient-gallery');
    Route::post('/delete-patient-gallery', [App\Http\Controllers\users\DtmsController::class, 'deletePatientGallery'])->name('delete-patient-gallery');

    // View Chart
    Route::post('/get-test-filter-data', [App\Http\Controllers\users\DtmsController::class, 'getTestFilterData'])->name('get-test-filter-data');

    // Diet History
    Route::post('/get-diet-history-answer-sheet', [App\Http\Controllers\users\DtmsController::class, 'getDietHistoryAnswerSheetData'])->name('get-diet-history-answer-sheet');
    Route::post('/diet-history-print-data', [App\Http\Controllers\users\DtmsController::class, 'dietHistoryPrintData'])->name('diet-history-print-data');

    // PEP Module
    Route::post('/get-pep-answer-sheet', [App\Http\Controllers\users\DtmsController::class, 'getPepAnswerSheetData'])->name('get-pep-answer-sheet');
    Route::post('/pep-history-print-data', [App\Http\Controllers\users\DtmsController::class, 'pepPrintData'])->name('pep-history-print-data');

    //  Miscellaneous Module
    Route::post('/get-miscellaneous-module', [App\Http\Controllers\users\DtmsController::class, 'getMiscellaneousData'])->name('get-miscellaneous-module');
    Route::post('/miscellaneous-print-data', [App\Http\Controllers\users\DtmsController::class, 'miscellaneousPrintData'])->name('miscellaneous-print-data');

    //Alert
    Route::post('/get-image-alert', [App\Http\Controllers\users\DtmsController::class, 'getImageAlert'])->name('get-image-alert');

    //Visit Chart
    Route::get('/view-test-result-chart/{pid}', [App\Http\Controllers\users\DtmsController::class, 'getTestResultChart'])->name('view-test-result-chart');

    //Prescription Print
    Route::get('/prescription-print-data/{print_type?}/{print_model?}', [App\Http\Controllers\users\DtmsController::class, 'prescriptionDocument'])->name('prescription-print-data');

    Route::post('/get-all-old-medicine-data', [App\Http\Controllers\users\DtmsController::class, 'getAllOldMedicineData'])->name('get-all-old-medicine-data');

    Route::post('/view-old-diet-history-print-data', [App\Http\Controllers\users\DtmsController::class, 'viewOldDietHistory'])->name('view-old-diet-history-print-data');
    Route::post('/view-old-pep-history-print-data', [App\Http\Controllers\users\DtmsController::class, 'viewOldPepHistory'])->name('view-old-pep-history-print-data');
    Route::post('/precription/delete', [App\Http\Controllers\users\DtmsController::class, 'deletePrescription'])->name('delete_prescription');


    //get test based on visit

    Route::post('/getAllVisitById', [App\Http\Controllers\users\DtmsController::class, 'getAllVisitById'])->name('getAllVisitById');

    // Vital Print
    Route::get('/vital-print-data', [App\Http\Controllers\users\DtmsController::class, 'vitalDocument'])->name('vital-print-data');

    //  Outside Lab
    Route::post('/search-all-test-names', [App\Http\Controllers\users\DtmsController::class, 'searchAllTestName'])->name('search-all-test-names');
    Route::post('/save-outside-lab-data', [App\Http\Controllers\users\DtmsController::class, 'saveOutsideLabData'])->name('save-outside-lab-data');
    Route::post('/get-outside-lab-data', [App\Http\Controllers\users\DtmsController::class, 'getOutsideLabData'])->name('get-outside-lab-data');

    //reports
    Route::get('/collection-report', [App\Http\Controllers\users\BillingController::class, 'collection_report'])->name('collection_report');
    Route::post('/get-collection-by-group', [App\Http\Controllers\users\ReportController::class, 'collection_report_by_group'])->name('collection_report_by_group');
    Route::get('/detailed-bill-report', [App\Http\Controllers\users\ReportController::class, 'detailed_bill_report'])->name('detailed_bill_report');
    Route::post('/detailed-bill-report', [App\Http\Controllers\users\ReportController::class, 'detailed_bill_report'])->name('detailed_bill_report');

    Route::get('/cancell-bill-report', [App\Http\Controllers\users\ReportController::class, 'cancel_bill_report'])->name('cancel_bill_report');
    Route::post('/generateCancellBillReport', [App\Http\Controllers\users\ReportController::class, 'generateCancellBillReport'])->name('generateCancellBillReport');


    Route::post('/generateDeatilpdf', [App\Http\Controllers\users\ReportController::class, 'generateDeatilpdf'])->name('generateDeatilpdf');

    Route::get('/lab-income-report', [App\Http\Controllers\users\ReportController::class, 'lab_income_report'])->name('lab_income_report');
    Route::post('/get-lab-income-report', [App\Http\Controllers\users\ReportController::class, 'get_lab_income_report'])->name('get_lab_income_report');

    Route::post('/getlabItemList', [App\Http\Controllers\users\ReportController::class, 'getlabItemList'])->name('getlabItemList');

    Route::get('/day-collection-report', [App\Http\Controllers\users\ReportController::class, 'day_collection_report'])->name('day_collection_report');
    Route::post('/day-collection-report', [App\Http\Controllers\users\ReportController::class, 'day_collection_report'])->name('day_collection_report');
    Route::post('/get-day-collection-report', [App\Http\Controllers\users\ReportController::class, 'get_day_collection_report'])->name('get_day_collection_report');


    //Vital Chart
    Route::get('/view-vital-chart/{pid}', [App\Http\Controllers\users\DtmsController::class, 'getVitalSignChart'])->name('view-vital-chart');
    Route::post('/get-vital-filter-data', [App\Http\Controllers\users\DtmsController::class, 'getVitalFilterData'])->name('get-vital-filter-data');

    //Inside Lab
    Route::post('/save-inside-lab-data', [App\Http\Controllers\users\DtmsController::class, 'saveInsideLabData'])->name('save-inside-lab-data');
    Route::post('/get-inside-lab-data', [App\Http\Controllers\users\DtmsController::class, 'getInsideLabData'])->name('get-inside-lab-data');

    Route::post('/save-smbg-lab-data', [App\Http\Controllers\users\DtmsController::class, 'saveSmbgLabData'])->name('save-smbg-lab-data');
    Route::post('/get-smbg-lab-data', [App\Http\Controllers\users\DtmsController::class, 'getSmbgLabData'])->name('get-smbg-lab-data');

    Route::get('/lab_bill_integration', [App\Http\Controllers\users\BillingController::class, 'lab_bill_integration'])->name('lab_bill_integration');
    Route::post('/visit_bill_update', [App\Http\Controllers\users\BillingController::class, 'visit_bill_update'])->name('visit_bill_update');


    //data correction

    Route::get('/dataCorrection/labData', [App\Http\Controllers\users\DataCorrectionController::class, 'labData'])->name('dataCorrection/labData');
    Route::post('/getPatientDatabyUhid', [App\Http\Controllers\users\DataCorrectionController::class, 'getPatientDatabyUhid'])->name('getPatientDatabyUhid');
    Route::post('/getallResultCorrectionData', [App\Http\Controllers\users\DataCorrectionController::class, 'getallResultCorrectionData'])->name('getallResultCorrectionData');
    Route::post('/updateTestResultCorrection', [App\Http\Controllers\users\DataCorrectionController::class, 'updateTestResultCorrection'])->name('updateTestResultCorrection');


    //MOBILE APP PAGES
    Route::get('/patientVerification', [App\Http\Controllers\users\AppManagementController::class, 'patientVerification'])->name('patientVerification');
    Route::post('/appManagement/getPatientVerification', [App\Http\Controllers\users\AppManagementController::class, 'getPatientVerification'])->name('getPatientVerification');
    Route::post('/appManagement/patientVerificationApp', [App\Http\Controllers\users\AppManagementController::class, 'patientVerificationApp'])->name('patientVerificationApp');
    Route::get('/appNotification', [App\Http\Controllers\users\AppManagementController::class, 'appNotification'])->name('appNotification');
    Route::post('/appManagement/saveNotification', [App\Http\Controllers\users\AppManagementController::class, 'saveNotification'])->name('saveNotification');
    //---- list Notifications to datatable app Notification-----//
    Route::post('/appManagement/getNotificationImages', [App\Http\Controllers\users\AppManagementController::class, 'getNotificationImages'])->name('getNotificationImages');
    Route::post('/appManagement/deletenotificationImage', [App\Http\Controllers\users\AppManagementController::class, 'deletenotificationImage'])->name('deletenotificationImage');

    Route::post('/appManagement/listNotifications', [App\Http\Controllers\users\AppManagementController::class, 'listNotifications'])->name('listNotifications');
    //delete Notifications to database table app Notification-----//
    Route::post('/appManagement/deleteNotification', [App\Http\Controllers\users\AppManagementController::class, 'deleteNotification'])->name('deleteNotification');



    // -----News Letters--------------------------------//
    Route::get('/newsLetters', [App\Http\Controllers\users\AppManagementController::class, 'newsLetters'])->name('newsLetters');
    Route::post('/appManagement/saveNewsLetters', [App\Http\Controllers\users\AppManagementController::class, 'saveNewsLetters'])->name('saveNewsLetters');
    Route::post('/appManagement/listNewsLetters', [App\Http\Controllers\users\AppManagementController::class, 'listNewsLetters'])->name('listNewsLetters');
    Route::post('/appManagement/deleteNewsLetter', [App\Http\Controllers\users\AppManagementController::class, 'deleteNewsLetter'])->name('deleteNewsLetter');

    //----product purchase--------//
    Route::get('/productPurchase', [App\Http\Controllers\users\AppManagementController::class, 'productPurchase'])->name('productPurchase');
    Route::post('/appManagement/productListing', [App\Http\Controllers\users\AppManagementController::class, 'productListing'])->name('productListing');
    Route::post('/appManagement/getSelectedItems', [App\Http\Controllers\users\AppManagementController::class, 'getSelectedItems'])->name('getSelectedItems');
    Route::post('/appManagement/productPurchaseStatus', [App\Http\Controllers\users\AppManagementController::class, 'productPurchaseStatus'])->name('productPurchaseStatus');
    Route::post('/appManagement/productInvoiceStatus', [App\Http\Controllers\users\AppManagementController::class, 'productInvoiceStatus'])->name('productInvoiceStatus');

    //----Medicine Purchase-----//
    Route::get('/medicinePurchasePage', [App\Http\Controllers\users\AppManagementController::class, 'medicinePurchasePage'])->name('medicinePurchasePage');
    Route::post('/appManagement/medicineListing', [App\Http\Controllers\users\AppManagementController::class, 'medicineListing'])->name('medicineListing');
    Route::post('/appManagement/medicineOrderList', [App\Http\Controllers\users\AppManagementController::class, 'medicineOrderList'])->name('medicineOrderList');
    Route::post('/appManagement/medicinePurchaseStatus', [App\Http\Controllers\users\AppManagementController::class, 'medicinePurchaseStatus'])->name('medicinePurchaseStatus');
    Route::post('/appManagement/UpdatepaymentStatus', [App\Http\Controllers\users\AppManagementController::class, 'UpdatepaymentStatus'])->name('UpdatepaymentStatus');
    Route::post('/appManagement/getUhidNumber', [App\Http\Controllers\users\AppManagementController::class, 'getUhidNumber'])->name('getUhidNumber');
    Route::post('/appManagement/saveorderMedicine', [App\Http\Controllers\users\AppManagementController::class, 'saveorderMedicine'])->name('saveorderMedicine');
    Route::post('/appManagement/medicineInvoiceStatus', [App\Http\Controllers\users\AppManagementController::class, 'medicineInvoiceStatus'])->name('medicineInvoiceStatus');



    //-----live chat------------------------//
    Route::get('/liveChat', [App\Http\Controllers\users\ChatManagementController::class, 'liveChat'])->name('liveChat');
    Route::post('/ChatManagement/getLIveChat', [App\Http\Controllers\users\ChatManagementController::class, 'getLIveChat'])->name('getLIveChat');
    Route::post('/ChatManagement/getmessage', [App\Http\Controllers\users\ChatManagementController::class, 'getmessage'])->name('getmessage');
    Route::post('/ChatManagement/savemessage', [App\Http\Controllers\users\ChatManagementController::class, 'savemessage'])->name('savemessage');
    Route::post('/ChatManagement/searchUserData', [App\Http\Controllers\users\ChatManagementController::class, 'searchUserData'])->name('searchUserData');


    //--------dtms verification-----------
    Route::get('/dtmsVerification', [App\Http\Controllers\users\DTMSManagementController::class, 'dtmsVerification'])->name('dtmsVerification');
    Route::post('/dtmsVerification/getbpStatusVerification', [App\Http\Controllers\users\DTMSManagementController::class, 'getbpStatusVerification'])->name('getbpStatusVerification');
    Route::post('/dtmsVerification/getsmbgStatusVerification', [App\Http\Controllers\users\DTMSManagementController::class, 'getsmbgStatusVerification'])->name('getsmbgStatusVerification');
    Route::post('/dtmsVerification/bpStatusVerification', [App\Http\Controllers\users\DTMSManagementController::class, 'bpStatusVerification'])->name('bpStatusVerification');
    Route::post('/dtmsVerification/smbgStatusVerification', [App\Http\Controllers\users\DTMSManagementController::class, 'smbgStatusVerification'])->name('smbgStatusVerification');
});



// 1. test_master // for test , and service items
// 2. test_group_master : department
// 3. service_group_master , for lab sub groups,




// drop test_procedure
// drop test_ranges_master
// test_sub_group : no Need now
// test_subranges_master : No Need
