<?php
namespace App\Http\Controllers\API\MobileApp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MobileApp\Account_for;
use App\Models\MobileApp\AllSalutations;
use App\Models\MobileApp\Gender;
use App\Models\Masters\SalutationMaster;
use App\Models\MobileApp\DocumentCategory;

use App\Models\MobileApp\Centers;
class MasterController extends Controller

{
    //to get details for branches
    public function allCenters(Request $request){

        try{
            $data=Centers::select('branch_id as id','branch_name as center','branch_code')->orderBy('branch_id', 'asc')->get();
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
   public function allAccountCreatedFor(){
    try{
        $data=Account_for::select('id','account_for')->get();
        foreach ($data as &$item) {
            $item['account_for'] = trim($item['account_for']);
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

    //branch details
    public function centerDetails(Request $request)
    {
        try{
            $center_id=$request->center_id;
            $data=Centers::select('branch_id as id','branch_name as center','branch_code','address_line_1','address_line_2','phone','email')->where('branch_id', $center_id)->first();
            $response['status'] = 100;
            $response['message'] = 'success';
            $response['result']=   $data;
            return response()->json($response);
        }
        catch (\Exception $e) {
            $response['status'] = 500;
            $response['message'] = 'Server Error';
            return response()->json($response,500);
        }
    }


public function allSalutations(){
    try{
        $filldata = SalutationMaster::where('is_deleted',0)->orderByDesc('id')->get();
        foreach ($filldata as &$item) {
            $item['salutation_name'] = trim($item['salutation_name']);
        }
        
        $response['status'] = 100;
        $response['message'] = 'success';
        $response['result']=  $filldata ;
        return response()->json($response,200);
   // DB::raw("TRIM(name) AS Nanameme,")   to trim database value(space)
    }
    catch (\Exception $e) {
        $response['status'] = 500;
        $response['message'] = 'Server Error';
        return response()->json($response,500);
    }


}
public function getGender(){
    try{
        $data=Gender::select('id','gender','code')->get();
        $response['status'] = 100;
        $response['message'] = 'success';
        $response['result']=  $data ;
        return response()->json($response,200);
   // DB::raw("TRIM(name) AS Nanameme,")   to trim database value(space)
    }
    catch (\Exception $e) {
        $response['status'] = 500;
        $response['message'] = 'Server Error';
        return response()->json($response,500);
    }


}
public function getDocumentCategory(){
    try{
        $data=DocumentCategory::select('id','category_name')->where('active_status',1)->get();
        $response['status'] = 100;
        $response['message'] = 'success';
        $response['result']=  $data ;
        return response()->json($response,200);
   // DB::raw("TRIM(name) AS Nanameme,")   to trim database value(space)
    }
    catch (\Exception $e) {
        $response['status'] = 500;
        $response['message'] = 'Server Error';
        return response()->json($response,500);
    }


}

}
