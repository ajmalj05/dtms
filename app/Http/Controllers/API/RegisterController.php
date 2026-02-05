<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Models\TestMaster;
use App\Models\TestResults;
use App\Models\PatientBilling;
// use App\Models\PatientTests;
use App\Models\PatientserviceItems;

use App\Models\PatientBillingAccounts;






class RegisterController extends Controller
{

    public function patientBill(Request $request)
    {
        // $content=json_decode($request->getContent());

        // $validator = Validator::make($request->all(), [
        //     'patientName' => 'required|regex:/^[\pL\s\-]+$/u',
        //     'phone' => 'required|numeric|digits:10',
        //     'TestId' => 'required|numeric',
        //     'TestRate' => 'required',
        //     'PatientLabNo' => 'required',
        //     'TotalAmount'  => 'required',
        //     'email' => 'nullable|email:rfc,dns',
        //     'serviceCharge' => 'nullable',
        //     'Discamount'=> 'nullable',
        //     'Grossamount'=> 'nullable',
        //     'NetAmount' => 'nullable',
        //     'Age' => 'nullable',
        //     'Gender'=> 'nullable',
        //     'Address' => 'nullable',
        //     'DoctorName' => 'nullable',
        //     'TestName' => 'nullable',
        //     ]
        // );
        // if ($validator->fails()) {
        //     $response['status']  = '400';
        //     $response['message'] = $validator->messages()->first();
        //     return response()->json($response);
        // }
        try {
            $value=json_decode($request->getContent());


            // insert or update by checking labno.
            $results = PatientBilling::updateOrCreate(
                [
                'PatientLabNo'   => $value->PatientLabNo,
                ],
                [
                    'patientName'    => $value->patientName,
                    'phone'          => $value->phone,
                    'email'          => $value->email,
                    'Age'            => $value->Age,
                    'Gender'         => $value->Gender,
                    'Address'        => $value->Address,
                    'DoctorName'     => $value->DoctorName,
                    'PatientId'     => $value->PatientId,
                    'is_service_external'     => 1,
                    
                ],
            );

            if($results){

                $PatientBillingId = $results->id;
                $testdatas=$value->testdetails;
                if($testdatas){
                    PatientserviceItems::where('patientlabno',$value->PatientLabNo)->delete();

                    foreach ($testdatas as $key => $testd) {


                         // if this testid doesnot exists in master test.. then add this test
                         $TestIddata = TestMaster::where('TestId', '=', $testd->TestId)->first();
                         if ($TestIddata === null) {
                             $testMaster = TestMaster::insertGetId(
                                 [
                                     'TestId'           =>$testd->TestId,
                                     'TestName'         =>$testd->TestName,
                                     'TestRate'         =>$testd->TestRate,
                                     'created_at'       => date('Y-m-d H:i:s'),
                                     'TestDepartment'    => NULL,
                                 ],

                             );

                             $serviceitemId=$testMaster;
                         }else{
                            $serviceitemId=$TestIddata->id;
                         }




                         PatientserviceItems::insert([
                                'patientlabno'     =>$value->PatientLabNo,
                                'patientbillingid' =>$PatientBillingId,
                                'serviceitemid'    =>$serviceitemId,
                                // 'TestName'         =>$testd->TestName,
                                'serviceitemamount'  =>$testd->TestRate,
                                'is_outside_service' =>1,
                                'display_status'=>1,
                                'created_at'       => date('Y-m-d H:i:s'),
                                'patientid'     => $value->PatientId,
                                'is_deleted' =>0,
                            ]
                        );






                    }
                }



                // add billing amount details to patient_billing_accounts  update /insert using labno
                PatientBillingAccounts::updateOrCreate(
                                        [
                                        'PatientLabNo'   => $value->PatientLabNo,
                                        ],
                                        [
                                        'PatientLabNo'      =>$value->PatientLabNo,
                                        'PatientBillingId'  =>$PatientBillingId,
                                        'TotalAmount'       =>$value->TotalAmount,
                                        'serviceCharge'     =>$value->serviceCharge,
                                        'Discamount'        =>$value->Discamount,
                                        'Grossamount'       =>$value->Grossamount,
                                        'NetAmount'         =>$value->NetAmount,
                                        'created_at'       => date('Y-m-d H:i:s'),
                                        'PatientId'     => $value->PatientId,
                                    ]
                );

                $response['status'] = '200';
                $response['message'] = 'success';
                $response['data']   =[];




            }else{
                $response['status'] = '400';
                $response['message'] = 'failed';
                $response['data']   =[];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            $response['status'] = '500';
            $response['message'] = 'Server Error';
            return response()->json($response);
            // return response()->json( $e->getMessage());
        }

    }





    public function testMaster(Request $request){

        try {
            $content=json_decode($request->getContent());

            $returnarray=array();
            $updcount=0;$insertcount=0;$rowexists=0;
            $results="";
            foreach ($content as $key => $value) {


                $results = TestMaster::updateOrCreate(
                    [
                    'TestId'   => $value->TestId,
                    ],
                    [
                        'TestName'          => $value->TestName,
                        'TestRate'          => $value->TestRate,
                        'TestDepartment'    => $value->TestDepartment,
                    ],
                );

                if(!$results->wasRecentlyCreated && $results->wasChanged()){
                    $updcount++;
                }
                if($results->wasRecentlyCreated){
                    $insertcount++;
                }

                if(!$results->wasRecentlyCreated && !$results->wasChanged()){
                    $rowexists++;
                }




            }
            $returnarray[]=array('updatecount'=>$updcount,'insertcount'=>$insertcount,'rowexists'=>$rowexists);

            if($results){
                $response['status'] = '200';
                $response['message'] = 'success';
                $response['data']   =$returnarray;

            }else{
                $response['status'] = '400';
                $response['message'] = 'failed';
                $response['data']   =$returnarray;
            }


            return response()->json($response);
        } catch (\Exception $e) {
            $response['status'] = '500';
            $response['message'] = 'Server Error';
            return response()->json($response);

        }



    }


    public function testResults(Request $request){

        try{

            $content=json_decode($request->getContent());

            $returnarray=array();
            $updcount=0;$insertcount=0;$rowexists=0;
            $results="";
            foreach ($content as $key => $value) {


                if(isset($value->PatientId) && $value->PatientId!=""){


                    $results = TestResults::updateOrCreate(
                        [
                            'TestId'   => $value->TestId,'Labno'     => $value->Labno],
                        [
                            // 'Labno'          => $value->Labno,
                            'TestNrml'       => $value->TestNrml,
                            'ResultValue'    => $value->ResultValue,
                            'unit'           => $value->unit,
                            'PatientId'      => $value->PatientId ? $value->PatientId :0,
                        ],
                    );

                    if(!$results->wasRecentlyCreated && $results->wasChanged()){
                        $updcount++;
                    }
                    if($results->wasRecentlyCreated){
                        $insertcount++;
                    }

                    if(!$results->wasRecentlyCreated && !$results->wasChanged()){
                        $rowexists++;
                    }
                }




            }
            $returnarray[]=array('updatecount'=>$updcount,'insertcount'=>$insertcount,'rowexists'=>$rowexists);

            if($results){
                $response['status'] = '200';
                $response['message'] = 'success';
                $response['data']   =$returnarray;

            }else{
                $response['status'] = '400';
                $response['message'] = 'failed';
                $response['data']   =$returnarray;
            }


            return response()->json($response);
        } catch (\Exception $e) {
            $response['status'] = '500';
            $response['message'] = 'Server Error';
            return response()->json($response);
            // return response()->json( $e->getMessage());
        }



    }





}
