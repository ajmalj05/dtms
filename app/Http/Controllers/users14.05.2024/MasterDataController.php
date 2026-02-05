<?php

namespace App\Http\Controllers\users;

use App\Models\Billing\ServiceGroupMaster;
use App\Models\Billing\SubTestMaster;

use App\Models\Billing\ServiceItemMaster;
use App\Models\Dtms\FormEngineAnswers;
use App\Models\Ipd\IpAdmission;
use App\Models\Masters\AppNotificationMaster;
use App\Models\Masters\Product;
use App\Models\Masters\ProductImage;
use App\Models\Masters\StockManagement;
use App\Models\Masters\VaccinationMaster;
use App\Models\PatientAnswerSheet;
use App\Models\TestMaster;
use App\Models\TestMasterExt;

use App\Models\History_log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Masters\CategoryMaster;
use App\Models\Masters\DepartmentMaster;
use App\Models\Masters\DietQuestionSub;
use App\Models\Masters\SubCategoryMaster;
use App\Models\Masters\PatientTypeMaster;
use App\Models\Masters\SalutationMaster;
use App\Models\Masters\DietQuestionMaster;
use App\Models\Masters\RelationMaster;
use App\Models\Masters\ReligionMaster;
use App\Models\Masters\SpecialistMaster;
use App\Models\Masters\PatientReference;
use App\Models\Masters\PatientRefMaster;
use App\Models\Masters\IdProodTypeMaster;
use App\Models\Masters\MeritalStatusMaster;
use App\Models\Masters\EducationMaster;
use App\Models\Masters\StateMaster;
use App\Models\Masters\CitiesMaster;
use App\Models\Masters\CountryMaster;
use App\Models\Masters\OccupationMaster;
use App\Models\Masters\BloodGroupMaster;
use App\Models\Masters\SubDivisionMaster;
use App\Models\Masters\AnnualIncomeMaster;
use App\Models\Masters\TestGroupMaster;
use App\Models\Masters\TestRangesMaster;
use App\Models\Masters\TestSubRangesMaster;
use App\Models\Masters\VisitTypeMaster;
use App\Models\Masters\DiagnosisMaster;
use App\Models\Masters\ComplicationMaster;
use App\Models\Masters\SubComplicationMaster;
use App\Models\Masters\EmailExtMaster;

use App\Models\User;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\Masters\TabletTypeMaster;
use App\Models\Masters\MedicineMaster;
use App\Models\Masters\FormEngineQuestions;
use App\Models\Masters\FormEngineAttributes;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HistoryController;



class MasterDataController extends Controller
{
    // public function __construct()
    // {

    // }

    protected $HistoryController;

    public function __construct(HistoryController $HistoryController)
    {
        $this->HistoryController = $HistoryController;
    }


    public function sectionOne()
    {
        $data = array();
        $data['PageName'] = "Master Data Section 1";
        return Parent::page_maker('webpanel.masters.sectionOne', $data);
    }

    public function sectionTwo()
    {
        $data = array();
        $data['PageName'] = "Master Data Section 2";
        return Parent::page_maker('webpanel.masters.sectionTwo', $data);
    }

    public function MasterThree()
    {
        $data = array();
        $data['PageName'] = "Master Data Section 3";
        return Parent::page_maker('webpanel.masters.masterThree', $data);
    }

    public function sectionThree()
    {
        $data = array();
        $userDetails = null;
        $userData = array();

        // $userDetails= User::where('role_id',3)
        // ->where('active_status',1)
        // ->where('is_deleted',0)
        // ->orderBy('id','DESC')->get();
        // $details =[];
        // foreach ($userDetails as $value) {
        //     $userEmail = $value->email;
        //     if (SpecialistMaster::where([['email', $value->email],['is_deleted',0]])->exists()) {
        //     } else {
        //         $details[]=array('userid'=>$value->id,
        //                     'name'=>$value->name);
        //     }

        // }

        // $data['user_data']=$details;
        // $data['user_email']=$userEmail;
        $data['PageName'] = "Specialists";
        return Parent::page_maker('webpanel.masters.sectionThree', $data);
    }

    public function getSpecialistUserDropdown()
    {
        $userDetails = User::where('role_id', 3)
            ->where('active_status', 1)
            ->where('is_deleted', 0)
            ->orderBy('id', 'DESC')->get();
        $details = [];
        foreach ($userDetails as $value) {
            $userEmail = $value->email;
            if (SpecialistMaster::where([['email', $value->email], ['is_deleted', 0]])->exists()) {
            } else {
                $details[] = array(
                    'userid' => $value->id,
                    'name' => $value->name
                );
            }
        }

        return $details;
    }

    /***
     * get user list
     */
    public function getUserList(Request $request)
    {
        $status = 'false';
        $users = null;
        if (!is_null($request->user_id)) {
            $users = User::where('id', $request->user_id)->select('name', 'email', 'id')->get();
            $status = 'true';
        }

        return Response::json(['status' => $status, 'data' => $users]);
    }


    public function sectionFour()
    {
        $data = array();
        $data['PageName'] = "Master Data Section 4";
        return Parent::page_maker('webpanel.masters.sectionFour', $data);
    }
    public function department()
    {
        $data = array();
        $data['PageName'] = "Department";
        return Parent::page_maker('webpanel.masters.department', $data);
    }
    public function sectionFive()
    {
        $data = array();
        $data['PageName'] = "Master Data Section 5";
        return Parent::page_maker('webpanel.masters.sectionFive', $data);
    }
    public function deletePatientType(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = PatientTypeMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, //  Delete
                'table_name' => 'PatientTypeMaster',
                'qury_log' => $sql,
                'description' => 'Master Data Section 1, Delete  Patient Type',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    public function deleteSubCategory(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = SubCategoryMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, //  Delete
                'table_name' => 'SubCategoryMaster',
                'qury_log' => $sql,
                'description' => 'Master Data Section 1, Delete  Division',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    public function deleteDepartment(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = DepartmentMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'DepartmentMaster',
                'qury_log' => $sql,
                'description' => 'Master Data Section 4, Delete Department ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    public function deleteCategory(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = CategoryMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, //  Delete
                'table_name' => 'CategoryMaster', //Delete category master
                'qury_log' => $sql,
                'description' => 'Master Data Section 1, Delete category master',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    public function deleteSubDiv(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = SubDivisionMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'SubDivisionMaster',
                'qury_log' => $sql,
                'description' => 'Master Data Section 1 ,Delete  Sub Division',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }


    public function savePatientTypeMaster(Request $request)
    {
        $validated = $request->validate([
            'patient_type_name' => 'required',
        ]);
        if ($validated) {
            $insert_id = "";
            $ins = array(
                'patient_type_name' => $request->patient_type_name,
                'display_status' => ($request->pt_display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {

                $cond = array(
                    array(DB::raw('upper(patient_type_name)'), strtoupper($request->patient_type_name)),
                    array('is_deleted', 0),
                );
                $getId = getAfeild("id", "patient_type_master", $cond);
                $user_id = Session::get('userID');

                if ($getId) {
                    return ['status' => 4, 'message' => "Patient type already exist"];
                } else {

                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = PatientTypeMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'PatientTypeMaster', // Save Patient Type
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 1 ,Save Patient Type',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(patient_type_name)'), strtoupper($request->patient_type_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hidpatid),
                );
                $getId = getAfeild("id", "patient_type_master", $cond);
                if ($getId) {
                    return ['status' => 4, 'message' => "Patient type  already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = PatientTypeMaster::whereId($request->hidpatid)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, //  Update
                        'table_name' => 'PatientTypeMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 1, Update  Patient Type',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }
    ////////////////////////////////////////////////////////////////////////

    public function saveSubCategoryMaster(Request $request)
    {
        $validated = $request->validate([
            'division_name' => 'required',
        ]);
        if ($validated) {
            $insert_id = "";
            $ins = array(
                'sub_category_name' => $request->division_name,
                'display_status' => ($request->sub_display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {


                $cond = array(
                    array(DB::raw('upper(sub_category_name)'), strtoupper($request->division_name)),
                    array('is_deleted', 0),
                );


                // $cond=[,];
                $getId = getAfeild("id", "sub_category_master", $cond);



                if ($getId) {
                    return ['status' => 4, 'message' => "Division name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = SubCategoryMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'SubCategoryMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 1 ,Save Division',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                DB::connection()->enableQueryLog(); // enable qry log
                $insert_id = SubCategoryMaster::whereId($request->hidsub_catid)->update($ins);
                // TO GET LAST EXECUTED QRY
                $query = DB::getQueryLog();
                $lastQuery = end($query);
                $sql = $lastQuery['query'];
                $bindings = $lastQuery['bindings'];
                $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                //end of qury

                // LOG  // add parameters based on your need
                $log = array(
                    'primarykeyvalue_Id' => $insert_id,
                    'user_id' => Auth::id(), // userId
                    'log_type' => 2, //update
                    'table_name' => 'SubCategoryMaster',
                    'qury_log' => $sql,
                    'description' => 'Master Data Section 1, Update  Division',
                    'created_date' => date('Y-m-d H:i:s')
                );

                $save_history = $this->HistoryController->saveHistory($log);
                ////////////////////////////////////////////////////////////////////////////////////
                // END OF LOG
                if ($insert_id) {
                    return ['status' => 1, 'message' => "Data updated Successfully"];
                } else {
                    return ['status' => 3, 'message' => "Failed to update data"];
                }
            }
        } else {
            echo 2;
        }
    }
    public function saveSubDiv(Request $request)
    {
        $validated = $request->validate([
            'sub_division_name' => 'required',
        ]);
        if ($validated) {
            $insert_id = "";
            $ins = array(
                'sub_division_name' => $request->sub_division_name,
                'display_status' => ($request->div_display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(sub_division_name)'), strtoupper($request->sub_division_name)),
                    array('is_deleted', 0),
                );

                $getId = getAfeild("id", "sub_division", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Sub division name already exist"];
                } else {
                    $ins['created_at'] = carbon::now();
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = SubDivisionMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'SubDivisionMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 1 ,Save Sub Division',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(sub_division_name)'), strtoupper($request->sub_division_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hidsub_divid),
                );
                $getId = getAfeild("id", "sub_division", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Sub division name already exist"];
                } else {
                    $ins['updated_at'] = carbon::now();
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = SubDivisionMaster::whereId($request->hidsub_divid)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, //  Update
                        'table_name' => 'SubDivisionMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 1 ,Update  Sub Division',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }
    public function saveDepartment(Request $request)
    {

        $validated = $request->validate([
            'department_name' => 'required',
        ]);
        if ($validated) {
            $insert_id = "";
            $ins = array(
                'department_name' => $request->department_name,
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(department_name)'), strtoupper($request->department_name)),
                    array('is_deleted', 0),
                );



                $getId = getAfeild("id", "departments", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Department Name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = DepartmentMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'DepartmentMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 4 Save Department ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(department_name)'), strtoupper($request->department_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_dep_id),
                );



                $getId = getAfeild("id", "departments", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Department Name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = DepartmentMaster::whereId($request->hid_dep_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_dep_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'DepartmentMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 4, Update Department ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function saveEmailExt(Request $request)
    {

        $validated = $request->validate([
            'extension' => 'required',
        ]);
        if ($validated) {
            $insert_id = "";
            $ins = array(
                'extension' => $request->extension,
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
            );
            if ($request->crude == 1) {
                $ins['created_by'] = Auth::id();
                $cond = array(
                    array(DB::raw('upper(extension)'), strtoupper($request->extension)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "extension_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Extention  already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = EmailExtMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'EmailExtMaster',
                        'qury_log' => $sql,
                        'description' => ' Save Email Master ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {

                $cond = array(
                    array(DB::raw('upper(extension)'), strtoupper($request->extension)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hidextid),
                );


                $getId = getAfeild("id", "extension_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Extention name already exist"];
                } else {
                    $ins['updated_by'] = Auth::id();
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = EmailExtMaster::whereId($request->hidextid)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hidextid,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'EmailExtMaster',
                        'qury_log' => $sql,
                        'description' => ' Update Email Master ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function saveCategoryMaster(Request $request)
    {

        $validated = $request->validate([
            'category_name' => 'required',
        ]);
        if ($validated) {
            $insert_id = "";
            $ins = array(
                'category_name' => $request->category_name,
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(category_name)'), strtoupper($request->category_name)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "category_master", $cond);
                // dd($getId);
                if ($getId) {
                    return ['status' => 4, 'message' => "Category name already exist"];
                } else {

                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = CategoryMaster::insertGetId($ins);
                    // dd($request);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'CategoryMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 1 ,Save category master',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG

                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {

                $cond = array(
                    array(DB::raw('upper(category_name)'), strtoupper($request->category_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hidcatid),
                );


                $getId = getAfeild("id", "category_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "category name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = CategoryMaster::whereId($request->hidcatid)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hidcatid,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'CategoryMaster', // Update category master
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 1 ,Update category master',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }
    public function savePatientRef(Request $request)
    {

        $validated = $request->validate([
            'patient_ref_name' => 'required',
        ]);
        if ($validated) {
            $insert_id = "";




            $ins = array(
                'patient_ref_name' => $request->patient_ref_name,
                'display_status' => ($request->ref_display_status == 'on') ? "1" : "0",
                'is_deleted' => 0,
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(patient_ref_name)'), strtoupper($request->patient_ref_name)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "patient_ref_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Patient Ref name already exist"];
                } else {
                    $insert_id = PatientRefMaster::insert($ins);
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(patient_ref_name)'), strtoupper($request->patient_ref_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hidrefid),

                );

                $getId = getAfeild("id", "patient_ref_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Patient Ref name already exist"];
                } else {
                    $insert_id = PatientRefMaster::whereId($request->hidrefid)->update($ins);
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }
    public function getCategory()
    {
        $filldata = CategoryMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function getEmailsMaster()
    {
        $filldata = EmailExtMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function getSubCategory()
    {
        $filldata = SubCategoryMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }
    public function getSubDiv()
    {
        $filldata = SubDivisionMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }
    public function getPatientRef()
    {
        $cond = ['is_deleted', 0];

        $filldata = PatientRefMaster::where('is_deleted', 0)->orderByDesc('id')->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }
    public function getDepartmentDetails()
    {
        $filldata = DepartmentMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function getStates(Request $request)
    {
        $data = StateMaster::where('country_id', $request->selected)->orderByDesc('id')->get();

        return response()->json($data);
    }
    public function getCities(Request $request)
    {
        $data = CitiesMaster::where('state_id', $request->selected)->orderByDesc('id')->get();


        return response()->json($data);
    }
    public function getCitiesByPin(Request $request)
    {
        $data = CitiesMaster::where('pincode', '=', $request->pincode)->distinct()->get();


        return response()->json($data);
    }
    public function getStatesByCities(Request $request)
    {

        $city_data = CitiesMaster::where('cities.id', '=', $request->place_id)->first();
        // $data=StateMaster::where('id',$city_data->state_id)->orderByDesc('id')->get();

        return response()->json($city_data);
    }

    public function getStatesByCitiesReverse(Request $request)
    {

        $city_data = CitiesMaster::where('cities.id', '=', $request->place_id)->first();
        $data = StateMaster::where('id', $city_data->state_id)->orderByDesc('id')->get();

        return response()->json($data);
    }

    public function getcountrybyState(Request $request)
    {
        $state_data = StateMaster::where('states.id', '=', $request->state_id)->first();
        $data = CountryMaster::where('id', $state_data->country_id)->orderByDesc('id')->get();
        return response()->json($data);
    }
    public function getCountriesByCities(Request $request)
    {

        $state_data = StateMaster::where('states.id', '=', $request->state_id)->first();
        $data = CountryMaster::where('id', $state_data->country_id)->orderByDesc('id')->get();

        return response()->json($data);
    }
    public function getPatientType()
    {
        $filldata = PatientTypeMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    //////////////////////////////////////////////////////////////////////////////////
    //SECTION 2 masters

    public function saveSalutation(Request $request)
    {

        $validated = $request->validate([
            'salutation_name' => 'required',
        ]);
        if ($validated) {
            $insert_id = "";
            $ins = array(
                'salutation_name' => $request->salutation_name,
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(salutation_name)'), strtoupper($request->salutation_name)),
                    array('is_deleted', 0),

                );


                $getId = getAfeild("id", "salutation_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Salutation  name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = SalutationMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'SalutationMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 2, Save Salutations',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {

                $cond = array(
                    array(DB::raw('upper(salutation_name)'), strtoupper($request->salutation_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_salutation_id),

                );
                $getId = getAfeild("id", "salutation_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Salutation  name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = SalutationMaster::whereId($request->hid_salutation_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_salutation_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'SalutationMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 2, Update Salutations ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }


    public function getSalutation()
    {
        $filldata = SalutationMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function deleteSalutation(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log

            $insert_id = SalutationMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'SalutationMaster',
                'qury_log' => $sql,
                'description' => 'Master Data Section 2, Delete Salutations',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function saveRelation(Request $request)
    {

        $validated = $request->validate([
            'relation_name' => 'required',
        ]);
        if ($validated) {
            $insert_id = "";
            $ins = array(
                'relation_name' => $request->relation_name,
                'display_status' => ($request->rel_display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(relation_name)'), strtoupper($request->relation_name)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "relation_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Relation name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = RelationMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'RelationMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 2 ,Save Relation Master',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(relation_name)'), strtoupper($request->relation_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_relation_id),
                );

                $getId = getAfeild("id", "relation_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Relation name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = RelationMaster::whereId($request->hid_relation_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_relation_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'RelationMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 2 ,Update Relation Master',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function getRelation()
    {
        $filldata = RelationMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function deleteRelation(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log

            $insert_id = RelationMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'RelationMaster',
                'qury_log' => $sql,
                'description' => 'Master Data Section 2 ,Delete Relation Master',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function saveReligion(Request $request)
    {

        $validated = $request->validate([
            'religion_name' => 'required',
        ]);
        if ($validated) {
            $insert_id = "";
            $ins = array(
                'religion_name' => $request->religion_name,
                'display_status' => ($request->rgn_display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(religion_name)'), strtoupper($request->religion_name)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "religion_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Religion name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = ReligionMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'ReligionMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 2, Save Religion Master',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(religion_name)'), strtoupper($request->religion_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_regn_id),
                );

                $getId = getAfeild("id", "religion_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Religion name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = ReligionMaster::whereId($request->hid_regn_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_regn_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'ReligionMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 2, update Religion Master',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function getReligion()
    {
        $filldata = ReligionMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function deleteReligion(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log

            $insert_id = ReligionMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'ReligionMaster',
                'qury_log' => $sql,
                'description' => 'Master Data Section 2, Delete Religion Master',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////
    public function saveSpecialist(Request $request)
    {
        $validated = $request->validate([
            'user' => 'required',
            'department' => 'required',

        ]);
        if ($validated) {
            $insert_id = "";
            $ins = array(
                'specialist_name' => $request->specialist_name,
                'email' => $request->specialist_email,
                'user_id' => $request->user,
                'department_id' => $request->department,
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id(),
                'is_deleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(specialist_name)'), strtoupper($request->specialist_name)),
                    array('is_deleted', 0),

                );
                $getId = getAfeild("id", "specialist_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Specialist name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = SpecialistMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'SpecialistMaster',
                        'qury_log' => $sql,
                        'description' => 'Save Specialist ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(specialist_name)'), strtoupper($request->specialist_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hidid),
                );




                $getId = getAfeild("id", "specialist_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Specialist name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = SpecialistMaster::whereId($request->hidid)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hidid,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'SpecialistMaster',
                        'qury_log' => $sql,
                        'description' => 'Upate Specialist ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function getSpecialist()
    {
        $filldata = SpecialistMaster::select('specialist_master.*', 'users.name', 'departments.department_name')
            ->leftjoin('users', 'users.id', '=', 'specialist_master.user_id')
            ->leftjoin('departments', 'departments.id', '=', 'specialist_master.department_id')
            ->where('specialist_master.is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function deleteSpecialist(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log

            $insert_id = SpecialistMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'SpecialistMaster',
                'qury_log' => $sql,
                'description' => 'Delete Specialist ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    ///////////////////////////////////////////////////////////////////////////

    public function savePatientReference(Request $request)
    {

        $validated = $request->validate([
            'patient_reference_name' => 'required',
        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'patient_reference_name' => $request->patient_reference_name,
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(patient_reference_name)'), strtoupper($request->patient_reference_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hidid),
                );

                $getId = getAfeild("id", "patient_reference_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Patient Reference already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = PatientReference::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'PatientReference',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 4 Save Patient Reference ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(patient_reference_name)'), strtoupper($request->patient_reference_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_ref_id),
                );

                $getId = getAfeild("id", "patient_reference_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Patient Reference already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = PatientReference::whereId($request->hid_ref_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_ref_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'PatientReference',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 4, Update Patient Reference ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function getPatientReference()
    {
        $filldata = PatientReference::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function deletePatientReference(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = PatientReference::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'PatientReference',
                'qury_log' => $sql,
                'description' => 'Master Data Section 4, Delete Patient Reference ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    public function deletePatientRef(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
                'updated_at' => date('Y-m-d'),
            );
            $insert_id = PatientRefMaster::whereId($id)->update($ins);
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    /////////////////////////////////////////////////////////////////////////////////////
    public function saveIdProofType(Request $request)
    {

        $validated = $request->validate([
            'id_proof_name' => 'required',
        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'id_proof_name' => $request->id_proof_name,
                'display_status' => ($request->display_status_proof == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(id_proof_name)'), strtoupper($request->id_proof_name)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "id_proof_type_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Id proof  type already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = IdProodTypeMaster::insertGetId($ins);

                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'IdProodTypeMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 4 Save Id proof type ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(id_proof_name)'), strtoupper($request->id_proof_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_proof_id)
                );

                $getId = getAfeild("id", "id_proof_type_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Id proof  type already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = IdProodTypeMaster::whereId($request->hid_proof_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_proof_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'IdProodTypeMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 4, Update Id proof type ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function getIdProofType()
    {
        $filldata = IdProodTypeMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function deleteIdProof(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = IdProodTypeMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'IdProodTypeMaster',
                'qury_log' => $sql,
                'description' => 'Master Data Section 4, Delete Id proof type ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    ////////////////////////////////////////////////////////////////////////////

    public function saveMaritalStatus(Request $request)
    {

        $validated = $request->validate([
            'marital_status_name' => 'required',
        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'merital_status_name' => $request->marital_status_name,
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(merital_status_name)'), strtoupper($request->marital_status_name)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "merital_status_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Marital status already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = MeritalStatusMaster::insertGetId($ins);

                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'MeritalStatusMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 5 Save Marital Status ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {

                $cond = array(
                    array(DB::raw('upper(merital_status_name)'), strtoupper($request->marital_status_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_met_id),
                );

                $getId = getAfeild("id", "merital_status_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Marital status already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = MeritalStatusMaster::whereId($request->hid_met_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_met_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'MeritalStatusMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 5, Update Marital Status ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function getMeritalStatus()
    {
        $filldata = MeritalStatusMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function deleteMeritialStatus(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = MeritalStatusMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'MeritalStatusMaster',
                'qury_log' => $sql,
                'description' => 'Master Data Section 5, Delete Marital Status ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    public function deleteQuestions(Request $request)
    {
        $id = $request->id;

        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = DietQuestionMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'DietQuestionMaster',
                'qury_log' => $sql,
                'description' => 'DTMS Master, Delete Questionnaries ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    ////////////////////////////////////////////////////////////


    public function saveEducation(Request $request)
    {

        $validated = $request->validate([
            'education_name' => 'required',
        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'education_name' => $request->education_name,
                'display_status' => ($request->display_status_edu == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {

                $cond = array(
                    array(DB::raw('upper(education_name)'), strtoupper($request->education_name)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "education_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Education  already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = EducationMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'EducationMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 5, Save Education ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {

                $cond = array(
                    array(DB::raw('upper(education_name)'), strtoupper($request->education_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_edu_id),
                );

                $getId = getAfeild("id", "education_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Education  already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = EducationMaster::whereId($request->hid_edu_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_edu_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'EducationMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 5, Update Education ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function getEducations()
    {
        $filldata = EducationMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function deleteEducation(Request $request)
    {

        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = EducationMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'EducationMaster',
                'qury_log' => $sql,
                'description' => 'Master Data Section 5, Delete Education ',
                // 'description' => 'Labs => Test Groups, Delete Tests/Procedures  ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    public function deleteTestMaster(Request $request)
    {

        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = TestMasterExt::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'TestMasterExt',
                'qury_log' => $sql,
                'description' => 'Labs => Test Groups, Delete Tests/Procedures  ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    //////////////////////////////////////////////////////////////////////////////////

    public function saveOccupation(Request $request)
    {

        $validated = $request->validate([
            'occupation_name' => 'required',
        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'occupation_name' => $request->occupation_name,
                'display_status' => ($request->display_status_occ == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(occupation_name)'), strtoupper($request->occupation_name)),
                    array('is_deleted', 0),

                );


                $getId = getAfeild("id", "occupation_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Occupation  already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = OccupationMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'OccupationMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 5 Save Occupation ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(occupation_name)'), strtoupper($request->occupation_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_occ_id),
                );


                $getId = getAfeild("id", "occupation_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Occupation  already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = OccupationMaster::whereId($request->hid_occ_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_occ_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'OccupationMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 5, Update Occupation ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function getOccupation()
    {
        $filldata = OccupationMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function deleteOccupation(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = OccupationMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'OccupationMaster',
                'qury_log' => $sql,
                'description' => 'Master Data Section 5, Delete Occupation ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////

    public function saveBloodGroup(Request $request)
    {

        $validated = $request->validate([
            'blood_group_name' => 'required',
        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'blood_group_name' => $request->blood_group_name,
                'display_status' => ($request->display_status_blood == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(blood_group_name)'), strtoupper($request->blood_group_name)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "blood_group_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Blood group  already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = BloodGroupMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'BloodGroupMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 5 Save Blood Group ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(blood_group_name)'), strtoupper($request->blood_group_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_blood_id),
                );

                $getId = getAfeild("id", "blood_group_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Blood group  already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = BloodGroupMaster::whereId($request->hid_blood_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_blood_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'BloodGroupMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 5, Update Blood Group ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function getBloodGroup()
    {
        $filldata = BloodGroupMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function deleteBloodGroup(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = BloodGroupMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'BloodGroupMaster',
                'qury_log' => $sql,
                'description' => 'Master Data Section 5, Delete Blood Group ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }


    //////////////////////////////////////////////////////////////////////////////////////

    public function saveAnnualIncome(Request $request)
    {

        $validated = $request->validate([
            'annual_income_name' => 'required',
        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'annual_income_name' => $request->annual_income_name,
                'display_status' => ($request->display_status_incomef == 'on') ? "1" : "0",
                'created_by' => Auth::id()
            );
            if ($request->crude == 1) {

                $cond = array(
                    array(DB::raw('upper(annual_income_name)'), strtoupper($request->annual_income_name)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "annual_income_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Annual income  already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = AnnualIncomeMaster::insertGetId($ins);

                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'AnnualIncomeMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 5 Save Annual Income ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(annual_income_name)'), strtoupper($request->annual_income_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_incom_id),
                );

                $getId = getAfeild("id", "annual_income_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Annual income  already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = AnnualIncomeMaster::whereId($request->hid_incom_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_incom_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'AnnualIncomeMaster',
                        'qury_log' => $sql,
                        'description' => 'Master Data Section 5, Update Annual Income ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function getAnnualIncome()
    {
        $filldata = AnnualIncomeMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function deleteAnnualIncome(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log

            $insert_id = AnnualIncomeMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'AnnualIncomeMaster',
                'qury_log' => $sql,
                'description' => 'Master Data Section 5, Delete Annual Income ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }



    //    keerthi

    /////// TEST GROUPS ///////////////////////

    public function labgroups()
    {
        $data = array();
        $data['PageName'] = "Test Groups";

        $colors = DB::table('test_colours')->get();
        $options = "<option value='0'>----</option>";
        foreach ($colors as $key) {
            $options .= "<option value='$key->id'>$key->color_name</option>";
        }

        $clarity = DB::table('test_clarity')->get();
        $clarity_options = "<option value='0'>----</option>";
        foreach ($clarity as $key) {
            $clarity_options .= "<option value='$key->id'>$key->clarity_name</option>";
        }
        $where = array(
            'display_status' => 1,
            'is_service_item' => 2,
            'is_deleted' => 0
        );
        $test_groups = DB::table('test_master')->where($where)->get();

        $data['colours'] = $options;
        $data['clarity'] = $clarity_options;
        $data['test_groups'] = $test_groups;


        return Parent::page_maker('webpanel.masters.labgroups', $data);
    }


    public function getlabGroups()
    {
        $filldata = TestGroupMaster::where('is_deleted', 0)->orderByDesc('id')->get();
        //   $filldata = ServiceGroupMaster::where('is_deleted',0)->where('is_lab_group',1)->orderByDesc('id')->get();


        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function saveSubTestMaster_old(Request $request)
    {
        $validated = $request->validate(
            [
                'sub_test_name' => 'required',

            ],
            [
                'sub_test_name.required' => 'Group Name is Required'
            ]
        );

        if ($validated) {
            $insert_id = "";
            $ins = array(
                'sub_group_name' => strtoupper($request->sub_test_name),
                'departemrnt_id' => $request->departemrnt_id,
                'display_status' => ($request->display_status_3 == 'on') ? "1" : "0",
                'is_deleted' => 0,
                'branch_id' => Session::get('current_branch'),
            );
            if ($request->crude == 1) {
                $ins['created_at'] = Carbon::now();
                $ins['created_by'] = Auth::id();

                $cond = [
                    [DB::raw('upper(sub_group_name)'), strtoupper($request->sub_test_name)],
                    ['is_deleted', 0],
                ];

                $getId = getAfeild("id", "test_sub_group", $cond);
                if ($getId) {
                    return ['status' => 4, 'message' => "Group already exist"];
                } else {
                    $insert_id = SubTestMaster::insert($ins);
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data Saved Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to save data"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(sub_group_name)'), strtoupper($request->sub_test_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_sub_test_id),
                );

                $getId = getAfeild("id", "test_sub_group", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Group already exist"];
                } else {
                    $insert_id = SubTestMaster::whereId($request->hid_sub_test_id)->update($ins);
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function getTestSubgroups(Request $request)
    {
        //     $filldata = SubTestMaster::where('is_deleted',0)->orderByDesc('id')->get();
        //      $output = array(
        //         "recordsTotal" => count($filldata),
        //         "recordsFiltered" => count($filldata),
        //         "data" => $filldata
        //     );
        //   echo json_encode($output);

        $filldata = TestMasterExt::where('is_deleted', 0)->where('is_service_item', 2)->orderByDesc('id')->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    //used for epartment master
    public function savelabGroups(Request $request)
    {
        $validated = $request->validate([
            'group_name' => 'required',
        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'groupname' => strtoupper($request->group_name),
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'is_deleted' => 0,
                'branch_id' => Session::get('current_branch'),
            );
            if ($request->crude == 1) {
                $ins['created_at'] = Carbon::now();
                $ins['created_by'] = Auth::id();

                $cond = [
                    [DB::raw('upper(groupname)'), strtoupper($request->service_group_name)],
                    ['is_deleted', 0],
                ];

                $getId = getAfeild("id", "test_group_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Department already exist"];
                } else {
                    //  $insert_id = ServiceGroupMaster::insert($ins);
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = TestGroupMaster::insertGetId($ins);

                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'TestGroupMaster', // Save Department in labs
                        'qury_log' => $sql,
                        'description' => 'Labs => Test Groups => Save Department',
                        'created_date' => date('Y-m-d H:i:s'),
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {

                $cond = array(
                    array(DB::raw('upper(groupname)'), strtoupper($request->group_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_met_id),
                );

                $getId = getAfeild("id", "test_group_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Group already exist"];
                } else {
                    // $insert_id=ServiceGroupMaster::whereId($request->hid_met_id)->update($ins);
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = TestGroupMaster::whereId($request->hid_met_id)->update($ins);

                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_met_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'TestGroupMaster', // Update Department in labs
                        'qury_log' => $sql,
                        'description' => 'Labs => Test Groups => Update Group',
                        'created_date' => date('Y-m-d H:i:s'),
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function saveSubTestMaster(Request $request)
    {

        $validated = $request->validate(
            [
                //  'group_name' => 'required',
                'sub_test_name' => 'required',
                'departemrnt_id' => 'required',
                'group_code' => 'required',
                'group_amount' => 'required',

            ],
            [
                'sub_test_name.required' => 'Group Name is Required'
            ]
        );


        if ($validated) {
            $serviceData = [
                'group_id' => 0,
                'TestName' => $request->sub_test_name,
                'test_code' => $request->group_code,
                'TestRate' => $request->group_amount,
                'display_status' => ($request->display_status_3 == 'on') ? "1" : "0",
                'is_deleted' => 0,
                'is_service_item' => 2,  // for test group
                'TestDepartment' => $request->departemrnt_id
            ];
            if ($request->crude == 1) {
                $serviceData['created_at'] = Carbon::now();
                $serviceData['created_by'] = Auth::id();
                $serviceData['TestId'] = 0;

                $cond = [
                    [DB::raw('upper("TestName")'), strtoupper($request->item_name)],
                    ['is_deleted', 0], ['is_service_item', 2]
                ];
                $getId = getAfeild("id", "test_master", $cond);
                if ($getId) {
                    return ['status' => 4, 'message' => "Group exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log

                    $service = TestMasterExt::insertGetId($serviceData);

                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $service,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'test_master', // Save Group
                        'qury_log' => $sql,
                        'description' => 'Labs =>Save Test Groups => Save Group',
                        'created_date' => date('Y-m-d H:i:s'),
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG

                    if ($service) {
                        //update test id
                        $upsData = [
                            'TestId' => $service
                        ];
                        $service = TestMasterExt::whereId($service)->update($upsData);

                        return ['status' => 1, 'message' => "Saved Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } //save
            else if ($request->crude == 2) {
                $serviceData['updated_at'] = Carbon::now();
                $cond = [
                    [DB::raw('upper("TestName")'), strtoupper($request->item_name)],
                    ['is_deleted', 0], ['is_service_item', 2],
                    ['id', '!=', $request->hid_sub_test_id]
                ];

                $getId = getAfeild("id", "test_master", $cond);
                if ($getId) {
                    return ['status' => 4, 'message' => "Service already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log

                    $service = TestMasterExt::whereId($request->hid_sub_test_id)->update($serviceData);

                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_sub_test_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'test_master', // Update Department in labs
                        'qury_log' => $sql,
                        'description' => 'Labs => Test Groups => Update Groups',
                        'created_date' => date('Y-m-d H:i:s'),
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($service) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            } //update

        } else {
            echo 2;
        }
    }


    public function deletelabGroups(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log

            $insert_id = TestGroupMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'TestGroupMaster', // delete Department in labs
                'qury_log' => $sql,
                'description' => 'Labs => Test Groups => Delete Department',
                'created_date' => date('Y-m-d H:i:s'),
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    /////////////////TEST RANGES////////////////////////

    public function testranges()
    {
        $data = array();
        $data['PageName'] = "Test Configuration";
        return Parent::page_maker('webpanel.masters.testranges', $data);
    }


    public function getTestRanges()
    {
        // $filldata = TestGroupMaster::where('is_deleted',0)->orderByDesc('id')->get();

        $filldata = TestRangesMaster::select('test_ranges_master.*', 'test_subranges_master.id as subid', 'test_subranges_master.test_subrange')->leftjoin('test_subranges_master', 'test_subranges_master.test_range_id', '=', 'test_ranges_master.id')->where('test_ranges_master.is_deleted', 0)->where('test_subranges_master.is_deleted', 0)->orderBy('test_ranges_master.id', 'DESC')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }
    public function saveTestRanges(Request $request)
    {



        $validated = $request->validate([
            'test_range' => 'required',
        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'test_range' => $request->test_range,
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id(),
                'branch_id' => Session::get('current_branch'),
            );
            if ($request->crude == 1) {



                $cond = array(
                    array(DB::raw('upper(test_range)'), strtoupper($request->test_range)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "test_ranges_master", $cond);

                if ($getId && !is_numeric($request->test_range)) {
                    return ['status' => 4, 'message' => "Test Range already exist"];
                } else {

                    // if coming value is an integer ... then  value already exists else not
                    if (!is_numeric($request->test_range)) {
                        $insert_id = TestRangesMaster::insertGetId($ins);
                    } else {
                        $insert_id = $request->test_range;
                    }
                    // test_subranges_master
                    if ($insert_id) {

                        foreach ($request->ranges_value as $value) {

                            $inssub = array(
                                'test_range_id' => $insert_id,
                                'test_subrange' => strtoupper($value),
                                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                                'created_by' => Auth::id(),
                                'branch_id' => Session::get('current_branch'),
                            );

                            TestSubRangesMaster::insert($inssub);
                        }



                        return ['status' => 1, 'message' => "Saved Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {

                $cond = array(
                    array(DB::raw('upper(test_range)'), strtoupper($request->test_range)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_met_id),
                );

                $getId = getAfeild("id", "test_ranges_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Test Range already exist"];
                } else {

                    // IF VALUE IS AN INTEGER THEN NO NEED TO UPDATE

                    if (!is_numeric($request->test_range)) {

                        $insert_id = TestRangesMaster::whereId($request->hid_met_id)->update($ins);
                    } else {
                        $insert_id = $request->hid_met_id;
                    }



                    if ($insert_id) {

                        foreach ($request->ranges_value as $value) {



                            $inssub = array(
                                'test_range_id' => $insert_id,
                                'test_subrange' => strtoupper($value),
                                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                                'created_by' => Auth::id(),
                                'branch_id' => Session::get('current_branch'),
                            );

                            TestSubRangesMaster::whereId($request->hid_met_subid)->update($inssub);
                        }


                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }


    public function deleteTestRanges(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            $insert_id = TestSubRangesMaster::whereId($id)->update($ins);
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    public function getTestMasterOptions()
    {


        $filldata = TestRangesMaster::where('is_deleted', 0)->orderByDesc('id')->get();
        $options = "<option value=''>Select</option>";
        if ($filldata) {

            foreach ($filldata as $value) {
                $options .= "<option value='$value->id'>$value->test_range</option>";
            }
        }

        echo $options;
    }

    /**
     *
     * dtms master home
     */
    public function dtms_masterhome()
    {
        $data = array();
        $data['PageName'] = "DTMS Masters";
        $data['form_types'] =  DB::table('formengine_types')->get();
        return Parent::page_maker('webpanel.masters.dtms-master', $data);
    }

    /**
     *
     * save visit type
     */
    public function saveVisitType(Request $request)
    {
        $validated = $request->validate([
            'visit_type_name' => 'required',
        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'branch_id' => Session::get('current_branch'),
                'visit_type_name' => $request->visit_type_name,
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id(),
                'is_deleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(visit_type_name)'), strtoupper($request->visit_type_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_visit_type_id),
                );

                $getId = getAfeild("id", "visit_type_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Visit type name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = VisitTypeMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'VisitTypeMaster',
                        'qury_log' => $sql,
                        'description' => 'DTMS Master Save Visit Type',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(visit_type_name)'), strtoupper($request->visit_type_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_visit_type_id),
                );

                $getId = getAfeild("id", "visit_type_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Visit type name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = VisitTypeMaster::whereId($request->hid_visit_type_id)->update($ins);
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_visit_type_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'VisitTypeMaster',
                        'qury_log' => $sql,
                        'description' => 'DTMS Master, Update Visit Type',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    /**
     *
     * get visit type
     */
    public function getVisitType()
    {
        $filldata = VisitTypeMaster::where('is_deleted', 0)->orderByDesc('id')->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    /**
     *
     * delete visit type
     */
    public function deleteVisitType(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = VisitTypeMaster::whereId($id)->update($ins);
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'VisitTypeMaster',
                'qury_log' => $sql,
                'description' => 'DTMS Master, Delete Visit Type',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    /**
     *
     * get diagnosis
     */
    public function getDiagnosis()
    {
        $filldata = DiagnosisMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    /**
     *
     * save diagnosis
     */
    public function saveDiagnosis(Request $request)
    {
        $validated = $request->validate([
            'diagnosis_name' => 'required',
            //            'diagnosis_code' => 'unique:diagnosis_master,code',


        ]);

        if ($validated) {
            $insert_id = "";
            $ins = array(
                'diagnosis_name' => $request->diagnosis_name,
                'code' => $request->diagnosis_code,
                'branch_id' => Session::get('current_branch'),
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id(),
                'is_deleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(diagnosis_name)'), strtoupper($request->diagnosis_name)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "diagnosis_master", $cond);

                $condcode = array(
                    array(DB::raw('upper(code)'), strtoupper($request->diagnosis_code)),
                    array('is_deleted', 0),

                );
                $getIdCode = getAfeild("id", "diagnosis_master", $condcode);

                if ($getId) {
                    return ['status' => 4, 'message' => "Diagnosis already exist"];
                } else if ($getIdCode) {
                    return ['status' => 4, 'message' => "Diagnosis code already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = DiagnosisMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'DiagnosisMaster',
                        'qury_log' => $sql,
                        'description' => 'DTMS Master Save Diagnosis ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(diagnosis_name)'), strtoupper($request->diagnosis_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_diagnosis_id)
                );

                $getId = getAfeild("id", "diagnosis_master", $cond);
                $condcode = array(
                    array(DB::raw('upper(code)'), strtoupper($request->diagnosis_code)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_diagnosis_id)

                );
                $getIdCode = getAfeild("id", "diagnosis_master", $condcode);
                if ($getId) {
                    return ['status' => 4, 'message' => "Diagnosis already exist"];
                } else if ($getIdCode) {
                    return ['status' => 4, 'message' => "Diagnosis code already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = DiagnosisMaster::whereId($request->hid_diagnosis_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_diagnosis_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'DiagnosisMaster',
                        'qury_log' => $sql,
                        'description' => 'DTMS Master, Update Diagnosis ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    /**
     *
     * delete diagnosis
     */
    public function deleteDiagnosis(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = DiagnosisMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'DiagnosisMaster',
                'qury_log' => $sql,
                'description' => 'DTMS Master, Delete Diagnosis ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    /**
     *
     * get complication
     */
    public function getComplication()
    {
        $filldata = ComplicationMaster::where('is_deleted', 0)->orderByDesc('id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    /**
     *
     * save complication
     */
    public function saveComplication(Request $request)
    {
        $validated = $request->validate([
            'complication_name' => 'required',
            //            'complication_code' => 'unique:complication_master,code',

        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'complication_name' => $request->complication_name,
                'code' => $request->complication_code,
                'branch_id' => Session::get('current_branch'),
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id(),
                'is_deleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(complication_name)'), strtoupper($request->complication_name)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "complication_master", $cond);

                $condcode = array(
                    array(DB::raw('upper(code)'), strtoupper($request->complication_code)),
                    array('is_deleted', 0),

                );
                $getIdCode = getAfeild("id", "complication_master", $condcode);


                if ($getId) {
                    return ['status' => 4, 'message' => "Complication already exist"];
                } else if ($getIdCode) {
                    return ['status' => 4, 'message' => "Complication code already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = ComplicationMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'ComplicationMaster',
                        'qury_log' => $sql,
                        'description' => 'DTMS Master Save Complication',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(complication_name)'), strtoupper($request->complication_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_complication_id)
                );

                $getId = getAfeild("id", "complication_master", $cond);

                $condcode = array(
                    array(DB::raw('upper(code)'), strtoupper($request->complication_code)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_complication_id)

                );
                $getIdCode = getAfeild("id", "complication_master", $condcode);

                if ($getId) {
                    return ['status' => 4, 'message' => "Complication already exist"];
                } else if ($getIdCode) {
                    return ['status' => 4, 'message' => "Complication code already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = ComplicationMaster::whereId($request->hid_complication_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_complication_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'ComplicationMaster',
                        'qury_log' => $sql,
                        'description' => 'DTMS Master, Update Complication',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }


    public function deleteEmailExt(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = EmailExtMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'EmailExtMaster',
                'qury_log' => $sql,
                'description' => ' Delete Email Master ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }


    /**
     *
     * delete complication
     */
    public function deleteComplication(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log

            $insert_id = ComplicationMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'ComplicationMaster',
                'qury_log' => $sql,
                'description' => 'DTMS Master, Delete Complication',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    /**
     *
     * get subcomplication
     */
    public function getSubComplication()
    {
        // $filldata = SubComplicationMaster::where('is_deleted',0)->orderByDesc('id')->get();
        $filldata = SubComplicationMaster::select('subcomplication_master.*', 'complication_master.id as complication_id', 'complication_master.complication_name')
            ->leftjoin('complication_master', 'subcomplication_master.complication_id', '=', 'complication_master.id')
            ->where('subcomplication_master.is_deleted', 0)
            ->orderBy('subcomplication_master.id', 'DESC')
            ->get();


        // $filldata= ComplicationMaster::select('subcomplication_master.*','subcomplication_master.id as sub_complication_id','complication_master.complication_name')->leftjoin('subcomplication_master','subcomplication_master.complication_id','=','complication_master.id')->where('complication_master.is_deleted',0)->where('subcomplication_master.is_deleted',0)->orderBy('complication_master.id','DESC')->get();
        // dd(filldata);


        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    /**
     *
     * save subcomplication
     */
    public function saveSubComplication(Request $request)
    {
        $validated = $request->validate([
            'complication' => 'required',
            'sub_complication_name' => 'required',
            //            'sub_complication_code' => 'unique:subcomplication_master,code',

        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'complication_id' => $request->complication,
                'subcomplication_name' => $request->sub_complication_name,
                'code' => $request->sub_complication_code,
                'branch_id' => Session::get('current_branch'),
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id(),
                'is_deleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            if ($request->crude == 1) {
                $subcomplicationCode = getAfeild("code", "subcomplication_master", [
                    ['code', $request->sub_complication_code],
                ]);
                if ($subcomplicationCode) {
                    return ['status' => 3, 'message' => "The code has already been taken"];
                } else {
                    $cond = array(
                        array(DB::raw('upper(subcomplication_name)'), strtoupper($request->sub_complication_name)),
                        array('is_deleted', 0),

                    );

                    $getId = getAfeild("id", "subcomplication_master", $cond);

                    if ($getId) {
                        return ['status' => 4, 'message' => "Sub complication already exist"];
                    } else {
                        DB::connection()->enableQueryLog(); // enable qry log
                        $insert_id = SubComplicationMaster::insertGetId($ins);
                        // TO GET LAST EXECUTED QRY
                        $query = DB::getQueryLog();
                        $lastQuery = end($query);
                        $sql = $lastQuery['query'];
                        $bindings = $lastQuery['bindings'];
                        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                        //end of qury

                        // LOG  // add parameters based on your need
                        $log = array(
                            'primarykeyvalue_Id' => $insert_id,
                            'user_id' => Auth::id(), // userId
                            'log_type' => 1, // Save
                            'table_name' => 'SubComplicationMaster',
                            'qury_log' => $sql,
                            'description' => 'DTMS Master Save Sub Complication ',
                            'created_date' => date('Y-m-d H:i:s')
                        );

                        $save_history = $this->HistoryController->saveHistory($log);
                        ////////////////////////////////////////////////////////////////////////////////////
                        // END OF LOG  FILE
                        if ($insert_id) {
                            return ['status' => 1, 'message' => "Saved Successfully"];
                            // echo 1; //save success
                        } else {
                            return ['status' => 3, 'message' => "Failed to save"];
                        }
                    }
                }
            } else if ($request->crude == 2) {
                $subcomplicationCode = getAfeild("code", "subcomplication_master", [
                    ['code', $request->sub_complication_code],
                    ['id', '!=', $request->hid_subcomplication_id],
                ]);
                if ($subcomplicationCode) {
                    return ['status' => 3, 'message' => "The code has already been taken"];
                } else {
                    $cond = array(
                        array(DB::raw('upper(subcomplication_name)'), strtoupper($request->sub_complication_name)),
                        array('is_deleted', 0),
                        array('id', '!=', $request->hid_subcomplication_id)
                    );

                    $getId = getAfeild("id", "subcomplication_master", $cond);

                    if ($getId) {
                        return ['status' => 4, 'message' => "Subcomplication already exist"];
                    } else {
                        DB::connection()->enableQueryLog(); // enable qry log
                        $insert_id = SubComplicationMaster::whereId($request->hid_subcomplication_id)->update($ins);
                        // TO GET LAST EXECUTED QRY
                        $query = DB::getQueryLog();
                        $lastQuery = end($query);
                        $sql = $lastQuery['query'];
                        $bindings = $lastQuery['bindings'];
                        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                        //end of qury

                        // LOG  // add parameters based on your need
                        $log = array(
                            'primarykeyvalue_Id' => $request->hid_subcomplication_id,
                            'user_id' => Auth::id(), // userId
                            'log_type' => 2, // Update
                            'table_name' => 'SubComplicationMaster',
                            'qury_log' => $sql,
                            'description' => 'DTMS Master, Update Sub Complication ',
                            'created_date' => date('Y-m-d H:i:s')
                        );

                        $save_history = $this->HistoryController->saveHistory($log);
                        ////////////////////////////////////////////////////////////////////////////////////
                        // END OF LOG  FILE
                        if ($insert_id) {
                            return ['status' => 1, 'message' => "Data updated Successfully"];
                        } else {
                            return ['status' => 3, 'message' => "Failed to update data"];
                        }
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    /**
     *
     * delete subcomplication
     */
    public function deleteSubComplication(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = SubComplicationMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'SubComplicationMaster',
                'qury_log' => $sql,
                'description' => 'DTMS Master, Delete Sub Complication ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    public function getTabletTypesListOptions()
    {
        $filldata = TabletTypeMaster::select('id', 'tablet_type_name', 'display_status')->where([['is_deleted', 0], ['display_status', 1]])
            ->orderBy('id', 'DESC')
            ->get();


        $options = "<option value=''>Select</option>";
        foreach ($filldata as $key => $value) {
            $options .= "<option value='$value->id'>$value->tablet_type_name</option>";
        }

        echo $options;
    }

    public function prescription_master()
    {
        $data = array();
        $data['PageName'] = "Prescription Master";
        return Parent::page_maker('webpanel.masters.prescription', $data);
    }

    /**
     *
     * save prescription master
     */
    public function savePrescriptionMaster(Request $request)
    {
        $validated = $request->validate([
            'tablet_type_name' => 'required',
        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'branch_id' => Session::get('current_branch'),
                'tablet_type_name' => $request->tablet_type_name,
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id(),
                'is_deleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(tablet_type_name)'), strtoupper($request->tablet_type_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_tablet_type_id),
                );

                $getId = getAfeild("id", "tablet_type_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Tablet type name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = TabletTypeMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'TabletTypeMaster',
                        'qury_log' => $sql,
                        'description' => 'Precription Master Save Tablet Type ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(tablet_type_name)'), strtoupper($request->tablet_type_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_tablet_type_id),
                );

                $getId = getAfeild("id", "tablet_type_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Tablet type name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = TabletTypeMaster::whereId($request->hid_tablet_type_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_tablet_type_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'TabletTypeMaster',
                        'qury_log' => $sql,
                        'description' => 'Precription Master, Update Tablet Type ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    /**
     *
     * get prescription master
     */
    public function getPrescriptionMaster()
    {
        $filldata = TabletTypeMaster::where('is_deleted', 0)->orderByDesc('id')->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    /**
     *
     * delete prescription master
     */
    public function deletePrescriptionMaster(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = TabletTypeMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'TabletTypeMaster',
                'qury_log' => $sql,
                'description' => 'Precription Master, Delete Tablet Type ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    /**
     *
     * save prescription medicines
     */
    public function savePrescriptionMedicines(Request $request)
    {
        $validated = $request->validate([
            'tablet_type' => 'required',
            'medicine_name' => 'required',
            // 'notes' => 'required',
            //            'dose' => 'required',
        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'tablet_type_id' => $request->tablet_type,
                'medicine_name' => $request->medicine_name,
                'generic_name' => $request->generic_name,
                'notes' => $request->notes,
                //                'dose' => $request->dose,
                'branch_id' => Session::get('current_branch'),
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'created_by' => Auth::id(),
                'is_deleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(medicine_name)'), strtoupper($request->medicine_name)),
                    array('is_deleted', 0),

                );

                $getId = getAfeild("id", "medicine_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Medicine already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = MedicineMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'MedicineMaster',
                        'qury_log' => $sql,
                        'description' => 'Precription Master Save Medicines ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(medicine_name)'), strtoupper($request->medicine_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_medicine_id)
                );

                $getId = getAfeild("id", "medicine_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Medicine already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = MedicineMaster::whereId($request->hid_medicine_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_medicine_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'MedicineMaster',
                        'qury_log' => $sql,
                        'description' => 'Precription Master, Update Medicines ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    /**
     *
     * delete  prescription medicines
     */
    public function deletePrescriptionMedicines(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = MedicineMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'MedicineMaster',
                'qury_log' => $sql,
                'description' => 'Precription Master, Delete Medicines ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    /**
     *
     * get  Questions
     */
    public function getQuestions(Request $request)
    {
        $cond = [];
        array_push($cond, ['is_deleted', 0]);
        array_push($cond, ['type', $request->type]);
        $filldata = DietQuestionMaster::select('id', 'question', 'order_no', 'display_status', 'type')
            ->orderBy('id', 'DESC')
            ->where($cond)
            ->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }
    /**
     *
     * save  Questions
     */
    public function saveQuestions(Request $request)
    {
        $validated = $request->validate(
            [
                'question_type' => 'required',
                'question' => 'required',
                'order_no' => 'required',

                'label' => 'required',
            ],
            [
                'question_type.required' => ' Please select a question type.',

            ]
        );


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'type' => $request->question_type,
                'question' => $request->question,
                'order_no' => $request->order_no,
                'display_status' => ($request->display_status_question == 'on') ? "1" : "0",
                'created_by' => Auth::id(),
                'is_deleted' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            );


            if ($request->crude == 1) {
                $orderNo = getAfeild("order_no", "questionnaire_master", [
                    ['type', $request->question_type],
                    ['order_no', $request->order_no],
                    ['question', $request->question],
                ]);
                if ($orderNo) {
                    return ['status' => 3, 'message' => "The order no has already been taken"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    // dd('hii');
                    $insert_id = DietQuestionMaster::insertGetId($ins);

                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'DietQuestionMaster',
                        'qury_log' => $sql,
                        'description' => 'DTMS Master Save Questionnaries ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    //// chnage this code to   $question_id=$insert_id->id
                    $question_id = $insert_id;
                    foreach ($request->label as $label) {
                        $sub_ins = array(
                            'question_id' => $question_id,
                            'label' => $label,
                            'display_status' => "1",
                            'created_by' => Auth::id(),
                            'is_deleted' => 0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'branch_id' => Session::get('current_branch'),
                        );
                        DB::connection()->enableQueryLog(); // enable qry log

                        DietQuestionSub::insert(
                            $sub_ins
                        );
                        // TO GET LAST EXECUTED QRY
                        $query = DB::getQueryLog();
                        $lastQuery = end($query);
                        $sql = $lastQuery['query'];
                        $bindings = $lastQuery['bindings'];
                        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                        //end of qury

                        // LOG  // add parameters based on your need
                        $log = array(
                            'primarykeyvalue_Id' => $question_id,
                            'user_id' => Auth::id(), // userId
                            'log_type' => 1, // Save
                            'table_name' => 'DietQuestionSub', // save DietQuestionSub
                            'qury_log' => $sql,
                            'description' => 'DTMS Master, Save Questionnaires Label ',
                            'created_date' => date('Y-m-d H:i:s'),
                        );

                        $save_history = $this->HistoryController->saveHistory($log);
                        ////////////////////////////////////////////////////////////////////////////////////
                        // END OF LOG
                    }
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $orderNo = getAfeild("order_no", "questionnaire_master", [
                    ['type', $request->question_type],
                    ['order_no', $request->order_no],
                    ['question', $request->question],
                    ['id', '!=', $request->hid_question_id],
                ]);
                if ($orderNo) {
                    return ['status' => 3, 'message' => "The order no has already been taken"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log

                    $insert_id = DietQuestionMaster::whereId($request->hid_question_id)->update($ins);

                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_question_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'DietQuestionMaster',
                        'qury_log' => $sql,
                        'description' => 'DTMS Master, Update Questionnaries ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    DietQuestionSub::where('question_id', $request->hid_question_id)->delete();
                    foreach ($request->label as $label) {
                        $sub_ins = array(
                            'question_id' => $request->hid_question_id,
                            'label' => $label,
                            'display_status' => "1",
                            'created_by' => Auth::id(),
                            'is_deleted' => 0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'branch_id' => Session::get('current_branch'),
                        );
                        DietQuestionSub::insert(
                            $sub_ins
                        );

                        // TO GET LAST EXECUTED QRY
                        $query = DB::getQueryLog();
                        $lastQuery = end($query);
                        $sql = $lastQuery['query'];
                        $bindings = $lastQuery['bindings'];
                        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                        //end of qury

                        // LOG  // add parameters based on your need
                        $log = array(
                            'primarykeyvalue_Id' => $request->hid_question_id,
                            'user_id' => Auth::id(), // userId
                            'log_type' => 2, // Update
                            'table_name' => 'DietQuestionSub', // update DietQuestionSub
                            'qury_log' => $sql,
                            'description' => 'DTMS Master, Update Questionnaires Label ',
                            'created_date' => date('Y-m-d H:i:s'),
                        );

                        $save_history = $this->HistoryController->saveHistory($log);
                        ////////////////////////////////////////////////////////////////////////////////////
                        // END OF LOG
                    }
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }
    //get sub questions
    public function getSubQuestions(Request $request)
    {


        $data = DietQuestionSub::where('question_id', $request->id)->select('id', 'label')->get();


        return response()->json($data);
    }
    /**
     *
     * delete Data
     */
    /**
     *
     * get  prescription medicines
     */
    public function getPrescriptionMedicines()
    {
        $filldata = MedicineMaster::select('medicine_master.*', 'tablet_type_master.id as tablet_type_id', 'tablet_type_master.tablet_type_name')
            ->leftjoin('tablet_type_master', 'medicine_master.tablet_type_id', '=', 'tablet_type_master.id')
            ->where('medicine_master.is_deleted', 0)
            ->orderBy('medicine_master.id', 'DESC')
            ->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function getMedicineLists(Request $request)
    {
        $type_id = $request->type_id;

        $filldata = MedicineMaster::where([['is_deleted', 0], ['tablet_type_id', $type_id]])
            ->orderBy('id', 'DESC')
            ->get();

        $options = "<option value='0'>Select</option>";
        foreach ($filldata as $med) {
            $options .= "<option value='$med->id'>$med->medicine_name</option>";
        }

        echo $options;
    }
    public function getMedicineLists_2(Request $request)
    {
        $type_id = $request->type_id;

        $filldata = MedicineMaster::where([['is_deleted', 0], ['tablet_type_id', $type_id]])
            ->orderBy('id', 'DESC')
            ->get();

        $items = $itemlist = array();
        foreach ($filldata as $med) {
            $itemlist = array(
                'id' => $med->id,
                'name' => $med->medicine_name,
                "full_name" => $med->medicine_name,
            );

            array_push($items, $itemlist);
        }

        // $data=array(
        //         'total_count'=>
        //         )

        // $options="<option value='0'>Select</option>";
        // foreach ($filldata as $med){
        //     $options.="<option value='$med->id'>$med->medicine_name</option>";
        // }

        // echo $options;

    }

    public function getMedicineDoseValue(Request $request)
    {
        $medid = $request->medid;

        $filldata = MedicineMaster::where([['id', $medid]])
            ->orderBy('id', 'DESC')
            ->first();

        echo json_encode($filldata);
    }


    public function searchMedicineNames(Request $request)
    {


        $data = array();
        if ($request->searchTerm) {



            $cond = [];
            array_push($cond, ['is_deleted', '=', 0]);

            array_push($cond, ['medicine_name', 'ilike', '%' . $request->searchTerm . '%']);

            if (!is_null($request->typeid)) {
                array_push($cond, ['tablet_type_id', '=', "$request->typeid"]);
            }

            $filldata = MedicineMaster::where($cond)->limit(25)->get();


            if ($filldata) {
                foreach ($filldata as $key => $value) {

                    $cond = array(
                        array('id', $value['tablet_type_id']),

                    );
                    $getTabletTypeName = getAfeild("tablet_type_name", "tablet_type_master", $cond);




                    $data[] = array(
                        'id' => $value['id'],
                        'text' => $value['medicine_name'],
                        'tablet_typeid' => $value['tablet_type_id'],
                        'tablet_type_name' => $getTabletTypeName,
                    );
                }
            }
        }




        echo json_encode($data);
    }

    /**
     * get Prescription Vaccination Data
     */
    public function getPrescriptionVaccination()
    {
        $filldata = VaccinationMaster::where('is_deleted', 0)->orderByDesc('id')->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    /**
     *
     * save prescription vaccination
     */
    public function savePrescriptionVaccination(Request $request)
    {
        $validated = $request->validate([
            'vaccination_name' => 'required',
        ]);

        if ($validated) {
            $insert_id = "";
            $ins = array(
                'vaccination_name' => $request->vaccination_name,
                'display_status' => ($request->display_status_vaccination == 'on') ? "1" : "0",
                'is_deleted' => 0,
            );
            if ($request->crude == 1) {
                $cond = array(
                    array(DB::raw('upper(vaccination_name)'), strtoupper($request->vaccination_name)),
                    array('is_deleted', 0),
                );

                $getId = getAfeild("id", "vaccination_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Vaccination already exist"];
                } else {
                    $ins['created_at'] = Carbon::now();
                    $ins['created_by'] = Auth::id();
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = VaccinationMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'VaccinationMaster',
                        'qury_log' => $sql,
                        'description' => 'Precription Master Save Vaccination ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $cond = array(
                    array(DB::raw('upper(vaccination_name)'), strtoupper($request->vaccination_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_vaccination_id)
                );

                $getId = getAfeild("id", "vaccination_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Vaccination already exist"];
                } else {
                    $ins['updated_at'] = Carbon::now();
                    $ins['updated_by'] = Auth::id();
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = VaccinationMaster::whereId($request->hid_vaccination_id)->update($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_vaccination_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'VaccinationMaster',
                        'qury_log' => $sql,
                        'description' => 'Precription Master, Update Vaccination ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    /**
     *
     * delete  prescription vaccinations
     */
    public function deletePrescriptionVaccination(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = VaccinationMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'VaccinationMaster',
                'qury_log' => $sql,
                'description' => 'Precription Master, Delete Vaccination ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    public function generateformfields(Request $request)
    {
        $inputtype = $request->inputtype;
        $inputclass = $request->inputclass;

        $Field = "";

        switch ($inputtype) {
            case 'checkbox':
                $Field = "<input type='text' name='attributestext[]' placeholder='Option 1' class='no-border form-control' id='1'>
                <a><input type='text' name='attributestext[]' placeholder='Add Option' id='2' class='no-border form-control' onclick=addOptions(this,'$inputtype');></a>";
                break;
            case 'radio':
                $Field = "<input type='text' name='attributestext[]' placeholder='Option 1' class='no-border form-control' id='1'>
                <a><input type='text' name='attributestext[]' placeholder='Add Option' id='2' class='no-border form-control' onclick=addOptions(this,'$inputtype');></a>";
                break;
            case 'select':

                // <input type='$inputtype' name=''  >&nbsp;
                $Field = "<input type='text' name='attributestext[]' placeholder='Option 1' class='no-border form-control' id='1'>
              <a><input type='text' name='attributestext[]' placeholder='Add Option' id='2' class='no-border form-control' onclick=addOptions(this,'$inputtype');></a>";

                break;

            case 'textarea':
                $Field = "<textarea  class='$inputclass' name='' ></textarea> ";
                break;


            default:

                $Field = "<input type='$inputtype'  class='$inputclass' name=''  >";
                break;
        }




        echo $Field;
    }


    public function saveform_engine(Request $request)
    {


        $validated = $request->validate([
            'querys' => 'required',
            'question_type_id' => 'required',
        ]);

        if (!$validated) {
            echo 2;
            return;
        }



        $question = $request->querys;
        $inputtext = $request->question_type_id;
        $tenderId = $request->tenderId;
        $inputarray = explode("!", $inputtext);
        $type_id = $inputarray[1];



        if (($type_id == 1) || ($type_id == 2) || ($type_id == 3)) {

            if ($request->attributestext[0] == null || $request->attributestext[1] == null) {
                return ['status' => 5, 'message' => "Failed to save data"];
                return;
            }
        }



        $QuestionArray = array(
            'question' => $question,
            'type_id'   => $type_id,

        );

        if ($request->crude == 1) {
            DB::connection()->enableQueryLog(); // enable qry log
            $ins = FormEngineQuestions::insertGetId($QuestionArray);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $ins,
                'user_id' => Auth::id(), // userId
                'log_type' => 1, // Save
                'table_name' => 'FormEngineQuestions', // Save Miscellanous
                'qury_log' => $sql,
                'description' => 'DTMS Master  Save Miscellanous',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($ins) {

                if ($request->attributestext) {
                    foreach ($request->attributestext as  $value) {


                        if ($value) {
                            $InsertArray = array(
                                'type_id' => $type_id,
                                'attr_name' => 'option',
                                'attr_value' => $value,
                                'question_id' => $ins,
                            );
                            DB::connection()->enableQueryLog(); // enable qry log

                            FormEngineAttributes::insert($InsertArray);

                            // TO GET LAST EXECUTED QRY
                            $query = DB::getQueryLog();
                            $lastQuery = end($query);
                            $sql = $lastQuery['query'];
                            $bindings = $lastQuery['bindings'];
                            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                            //end of qury

                            // LOG  // add parameters based on your need
                            $log = array(
                                'primarykeyvalue_Id' => $type_id,
                                'user_id' => Auth::id(), // userId
                                'log_type' => 1, // Save
                                'table_name' => 'FormEngineAttributes', // Save FormEngineAttributes
                                'qury_log' => $sql,
                                'description' => 'DTMS Master, Save Miscellaneous Attributes Options ',
                                'created_date' => date('Y-m-d H:i:s'),
                            );

                            $save_history = $this->HistoryController->saveHistory($log);
                            ////////////////////////////////////////////////////////////////////////////////////
                            // END OF LOG
                        }
                    }
                }


                return ['status' => 1, 'message' => "Data Saved Successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to save data"];
            }
        } else {
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = FormEngineQuestions::whereId($request->hid_question_id5)->update($QuestionArray);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $request->hid_question_id5,
                'user_id' => Auth::id(), // userId
                'log_type' => 2, // Update
                'table_name' => 'FormEngineQuestions', // Save Miscellanous
                'qury_log' => $sql,
                'description' => 'DTMS Master, Update Miscellanous',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($request->attributestext) {

                FormEngineAttributes::where('question_id', $request->hid_question_id5)->delete();


                foreach ($request->attributestext as  $value) {


                    if ($value) {
                        $InsertArray = array(
                            'type_id' => $type_id,
                            'attr_name' => 'option',
                            'attr_value' => $value,
                            'question_id' => $request->hid_question_id5,
                        );
                        DB::connection()->enableQueryLog(); // enable qry log

                        FormEngineAttributes::insert($InsertArray);
                        // TO GET LAST EXECUTED QRY
                        $query = DB::getQueryLog();
                        $lastQuery = end($query);
                        $sql = $lastQuery['query'];
                        $bindings = $lastQuery['bindings'];
                        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                        //end of qury

                        // LOG  // add parameters based on your need
                        $log = array(
                            'primarykeyvalue_Id' => $type_id,
                            'user_id' => Auth::id(), // userId
                            'log_type' => 2, // Update
                            'table_name' => 'FormEngineAttributes', // Update FormEngineAttributes
                            'qury_log' => $sql,
                            'description' => 'DTMS Master, Update Miscellaneous Attributes Options ',
                            'created_date' => date('Y-m-d H:i:s'),
                        );

                        $save_history = $this->HistoryController->saveHistory($log);
                        ////////////////////////////////////////////////////////////////////////////////////
                        // END OF LOG
                    }
                }
            }

            return ['status' => 1, 'message' => "Data Updated Successfully"];
        }
    }

    public function getMiscellaneousQuestions()
    {


        // $filldata=TestRangesMaster::select('test_ranges_master.*','test_subranges_master.id as subid','test_subranges_master.test_subrange')->leftjoin('test_subranges_master','test_subranges_master.test_range_id','=','test_ranges_master.id')->where('test_ranges_master.is_deleted',0)->where('test_subranges_master.is_deleted',0)->orderBy('test_ranges_master.id','DESC')->get();


        $resArray = FormEngineQuestions::select('formengine_questions.*', 'formengine_types.type')
            ->join('formengine_types', 'formengine_types.id', '=', 'formengine_questions.type_id')->where([['is_deleted', 0]])->get();


        $data_list = '';
        $data['option_list'] = '';
        $data['answers'] = '';
        $data = array();
        $i = 1;
        foreach ($resArray as $key => $value) {
            $answer = '';
            $data['question'][] = $value->question; //print_r($data['question']);exit;

            $data['questionid'][] = $value->id;
            $data['type'][] = $value->type;
            $questionid = $value->id;
            // $qry="SELECT fa.*,ft.type FROM formengine_attributes fa INNER JOIN  formengine_types ft on ft.id=fa.type_id where fa.type_id='$typeid' AND question_id='$questionid' AND attr_name='option'";
            // $qrry=$this->db->query($qry);
            $checkOptionsExists = FormEngineAttributes::select('formengine_attributes.*', 'formengine_types.type')
                ->JOIN('formengine_types', 'formengine_types.id', '=', 'formengine_attributes.type_id')
                ->where([['formengine_attributes.type_id', $value->type_id], ['question_id', $questionid], ['attr_name', 'option']])->get();

            // $checkOptionsExists=$this->get_OptionsByTYpeId($value->type_id,$value->id);  // typeId & QuestionId
            $questionid = $value->id;
            $data_list = '';
            $patientAnswerSheet = PatientAnswerSheet::where('patient_id', Session::get('dtms_pid'))->orderByDesc('id')->first();
            if (!is_null($patientAnswerSheet)) {
                $answersArray = FormEngineAnswers::where('question_id', $questionid)
                    ->where('patient_id', Session::get('dtms_pid'))
                    ->where('answer_sheet_id', $patientAnswerSheet->id)
                    ->orderByDesc('id')
                    ->first();
            } else {
                $answersArray = null;
            }

            if ($answersArray) {
                $answer = $answersArray['answer'];
            }

            // // check if ans exists for this question

            // if($userid){
            //     $query = $this->db->query("SELECT * FROM formengine_registration tq  $where and question_id='$questionid'");

            //     $rows=$query->num_rows();
            //     if($rows>0)
            //     {
            //         $row = $query->row();

            //         $answer=$row->answer;

            //     }

            // }

            $data['answers'][] = $answer;
            if ($value->type_id == '8') {  // boolean
                $checked = "";
                $checked2 = "";
                if ($answer == 'Yes') {
                    $checked = "checked";
                } else if ($answer == 'No') {
                    $checked2 = "checked";
                }
                $data_list = "<div class='fieldgroup'><input type='radio' name='$questionid' class='$questionid inputtext'   value='Yes' $checked data-names='Yes'> Yes <input type='radio' class='$questionid inputtext' name='$questionid' value='No' $checked2 data-names='No'> No   </div>";

                $data['option_list'][] = $data_list;

                continue;
            }
            $i++;

            if (sizeof($checkOptionsExists)) {
                if ($value->type_id == '3') {
                    $data_list = "<select class='form-control inputtext' id='$questionid' name='" . $questionid . "_1'>";
                    $data_list .= "<option value='0'>Choose</option>";
                }

                foreach ($checkOptionsExists as $key => $value1) {
                    $typeName = $value1->type;
                    $attrid = $value1->id;
                    if ($value->type_id == '3') {
                        $selected = "";
                        if ($attrid == $answer) {
                            $selected = "selected";
                        }
                        $data_list .= "<option value='$attrid' $selected>$value1->attr_value</option>";
                    } else if ($value->type_id == '1' || $value->type_id == '2') {
                        // for checkbox or radio we need to set value attr as formengine_attribute id
                        if ($value->type_id == '1') // checkbox
                        {
                            $values_checkbox = explode(',', $answer);
                            $checked_checkbox = "";
                            if (in_array($attrid, $values_checkbox)) {
                                $checked_checkbox = "checked";
                            }
                            $data_list .= "<div class='fieldgroup'>
                            <input type='$value1->type' name='" . $questionid . "_1' value='$attrid' id='$questionid' $checked_checkbox  class='inputtext' data-names='$value1->attr_value'>&nbsp; $value1->attr_value</div>";
                        } else {
                            // radio
                            $checked_radio = "";
                            if ($attrid == $answer) {
                                $checked_radio = "checked";
                            }
                            $data_list .= "<div class='fieldgroup'><input type='$value1->type' name='" . $questionid . "_1' value='$attrid' data-names='$value1->attr_value' id='$questionid' $checked_radio class='inputtext' >&nbsp;&nbsp;&nbsp;$value1->attr_value</div>";
                        }
                        // $data_list.= "&nbsp; ";
                    } else {
                        // not necessay
                        $data_list .= "<input type='$value1->type' name='$questionid'  id='$questionid' class='inputtext'>&nbsp;$value1->attr_value<br>";
                    }
                }
                if ($value->type_id == '3') {
                    $data_list .= "</select>";
                }
                // echo $data_list;
                $data['option_list'][] = $data_list;
            } else {
                $data['option_list'][] = "";
            }
        }

        echo json_encode($data);
    }

    public function getQuestionsGroup()
    {
        $filldata = FormEngineQuestions::select('formengine_questions.*', 'formengine_types.type', 'formengine_types.label as typelabel')
            ->join('formengine_types', 'formengine_types.id', '=', 'formengine_questions.type_id')->where([['is_deleted', 0]])->orderBy('id', 'DESC')->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    public function getSubQuestionsGroup(Request $request)
    {

        $checkOptionsExists = FormEngineAttributes::select('formengine_attributes.*', 'formengine_types.type')
            ->JOIN('formengine_types', 'formengine_types.id', '=', 'formengine_attributes.type_id')
            ->where([['formengine_attributes.type_id', $request->type_id], ['question_id', $request->id], ['attr_name', 'option']])->orderBy('id', 'DESC')->get();
        if (sizeof($checkOptionsExists)) {
            echo  json_encode($checkOptionsExists);
            return;
        }
    }


    public function deleteMiscellaneousQs(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = FormEngineQuestions::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'FormEngineQuestions', // Save Miscellanous
                'qury_log' => $sql,
                'description' => 'DTMS Master, Delete Miscellanous',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }


    public function app_notification()
    {
        $data = array();
        $data['PageName'] = "App Settings";
        return Parent::page_maker('webpanel.masters.app-notification', $data);
    }


    public function loadcomplicationDropdown()
    {
        $filldata = ComplicationMaster::where('is_deleted', 0)->where('display_status', 1)->orderByDesc('id')->get();
        $options = "<option value=''>Select</option>";
        if ($filldata) {

            foreach ($filldata as $value) {
                $options .= "<option value='$value->id'>$value->complication_name</option>";
            }
        }

        echo $options;
    }
    /**
     *
     * save notification master
     */
    public function saveNotificationData(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required',
        ]);


        if ($validated) {
            $insert_id = "";
            $ins = array(
                'branch_id' => Session::get('current_branch'),
                'message' => $request->message,
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'is_deleted' => 0,
            );
            if ($request->crude == 1) {
                $ins['created_at'] = Carbon::now();
                $ins['created_by'] = Auth::id();
                $cond = array(
                    array(DB::raw('upper(message)'), strtoupper($request->message)),
                    array('is_deleted', 0),
                    //                    array('id','!=',$request->hid_message_id),
                );

                $getId = getAfeild("id", "app_notification_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Message already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = AppNotificationMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'AppNotificationMaster',
                        'qury_log' => $sql,
                        'description' => 'App Settings Save Notification Settings',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $ins['updated_at'] = Carbon::now();
                $ins['updated_by'] = Auth::id();
                $cond = array(
                    array(DB::raw('upper(message)'), strtoupper($request->message)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_message_id),
                );

                $getId = getAfeild("id", "app_notification_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Message already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert_id = AppNotificationMaster::whereId($request->hid_message_id)->update($ins);
                    // $insert_id = AppNotificationMaster::insertGetId($ins);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_message_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'AppNotificationMaster',
                        'qury_log' => $sql,
                        'description' => 'App Settings, Update Notification Settings',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    /**
     *
     * get notification master
     */
    public function getAppNotificationMaster()
    {
        $filldata = AppNotificationMaster::where('is_deleted', 0)->orderByDesc('id')->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    /**
     *
     * delete notification  master
     */
    public function deleteAppNotificationMaster(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = AppNotificationMaster::whereId($id)->update($ins);
            // $insert_id = AppNotificationMaster::insertGetId($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'AppNotificationMaster',
                'qury_log' => $sql,
                'description' => 'App Settings, Delete Notification Settings',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    /**
     * product data
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function productData()
    {
        $data = array();
        $data['PageName'] = "Products";
        return Parent::page_maker('webpanel.masters.product', $data);
    }


    /**
     *
     * save products
     */
    public function saveProductData(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required',
            'product_description' => 'required',
            'product_rate' => 'required',
            'product_discount_percentage' => 'required',
            'images' => 'required',
            'tax' => 'required',
        ]);


        if ($validated) {

            $productData = [
                'product_name' => $request->product_name,
                'product_description' => $request->product_description,
                'product_rate' => $request->product_rate,
                'product_discount_percent' => $request->product_discount_percentage,
                'branch_id' => Session::get('current_branch'),
                'display_status' => ($request->display_status_product == 'on') ? "1" : "0",
                'is_deleted' => 0,
                'tax_id' => $request->tax,
            ];

            if ($request->crude == 1) {
                $productData['created_at'] = Carbon::now();
                $productData['created_by'] = Auth::id();
                $productData['available_quantity'] = 0;
                $cond = [
                    [DB::raw('upper(product_name)'), strtoupper($request->product_name)],
                    ['is_deleted', 0],
                ];

                $getId = getAfeild("id", "products", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Product already exist"];
                } else {

                    DB::connection()->enableQueryLog(); // enable qry log

                    $product = Product::create($productData);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $product->id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'Product',
                        'qury_log' => $sql,
                        'description' => ' Master Data, Save Products ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    StockManagement::insert([
                        'product_id' => $product->id,
                        'stock' => 0,
                        'display_status' => 1,
                        'is_deleted' => 0,
                        'created_by' => Auth::id(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);



                    $files_array = [];

                    if ($request->TotalFiles > 0) {

                        for ($x = 0; $x < $request->TotalFiles; $x++) {

                            if ($request->hasFile('files' . $x)) {
                                $file   = $request->file('files' . $x);
                                $name = $file->getClientOriginalName();
                                // $name =time().'.'.$file->extension();

                                $file->move(public_path('images/product'), $name);
                                $file_name = "images/product/";
                                $img = $file_name . $name;
                                // $file->move(public_path('images'), $name);

                                array_push($files_array, $img);
                            }
                        }
                        $productImages = [];
                        foreach ($files_array as $fileName) {
                            $productImages[] = [
                                'product_id' => $product->id,
                                'product_image' => $fileName,
                                'branch_id' => Session::get('current_branch'),
                                'display_status' => 1,
                                'is_deleted' => 0,
                                'created_by' => Auth::id(),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        };
                        DB::connection()->enableQueryLog(); // enable qry log

                        // $product=ProductImage::insert($productImages);
                        ProductImage::insert($productImages);

                        // TO GET LAST EXECUTED QRY
                        $query = DB::getQueryLog();
                        $lastQuery = end($query);
                        $sql = $lastQuery['query'];
                        $bindings = $lastQuery['bindings'];
                        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                        //end of qury

                        // LOG  // add parameters based on your need
                        $log = array(
                            'primarykeyvalue_Id' => $product->id,
                            'user_id' => Auth::id(), // userId
                            'log_type' => 1, // Save
                            'table_name' => 'ProductImage',
                            'qury_log' => $sql,
                            'description' => ' Master Data, Save Products Image ',
                            'created_date' => date('Y-m-d H:i:s')
                        );

                        $save_history = $this->HistoryController->saveHistory($log);
                        ////////////////////////////////////////////////////////////////////////////////////
                        // END OF LOG  FILE

                    }

                    if ($product) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $productData['updated_at'] = Carbon::now();
                $cond = array(
                    array(DB::raw('upper(product_name)'), strtoupper($request->product_name)),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hid_product_id)
                );

                $getId = getAfeild("id", "products", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Product already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    Product::whereId($request->hid_product_id)->update($productData);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_product_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'Product',
                        'qury_log' => $sql,
                        'description' => ' Update Products ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE

                    $files_array = [];

                    if ($request->TotalFiles > 0) {

                        for ($x = 0; $x < $request->TotalFiles; $x++) {

                            if ($request->hasFile('files' . $x)) {
                                $file   = $request->file('files' . $x);
                                $name = $file->getClientOriginalName();
                                $file->move(public_path('images/product'), $name);
                                $file_name = "images/product/";
                                $img = $file_name . $name;

                                // $file->move(public_path('images'), $name);

                                array_push($files_array, $img);
                            }
                        }
                        $productImages = [];
                        foreach ($files_array as $fileName) {
                            $productImages[] = [
                                'product_id' => $request->hid_product_id,
                                'product_image' => $fileName,
                                'branch_id' => Session::get('current_branch'),
                                'display_status' => 1,
                                'is_deleted' => 0,
                                'created_by' => Auth::id(),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        };
                        DB::connection()->enableQueryLog(); // enable qry log

                        ProductImage::insert($productImages);

                        // TO GET LAST EXECUTED QRY
                        $query = DB::getQueryLog();
                        $lastQuery = end($query);
                        $sql = $lastQuery['query'];
                        $bindings = $lastQuery['bindings'];
                        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                        //end of qury

                        // LOG  // add parameters based on your need
                        $log = array(
                            'primarykeyvalue_Id' => $request->hid_product_id,
                            'user_id' => Auth::id(), // userId
                            'log_type' => 2, // Update
                            'table_name' => 'ProductImage',
                            'qury_log' => $sql,
                            'description' => ' Update Product Image ',
                            'created_date' => date('Y-m-d H:i:s')
                        );

                        $save_history = $this->HistoryController->saveHistory($log);
                        ////////////////////////////////////////////////////////////////////////////////////
                        // END OF LOG  FILE
                    }

                    return ['status' => 1, 'message' => "Data updated Successfully"];
                }
            }
        } else {
            echo 2;
        }
    }

    /**
     *
     * get products
     */
    public function getProductData()
    {
        $filldata = Product::where('products.is_deleted', 0)
            ->orderBy('products.id', 'DESC')
            ->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    /**
     *
     * delete product
     */
    public function deleteProductData(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = Product::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'Product',
                'qury_log' => $sql,
                'description' => ' Delete Products ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }


    /**
     * get product image list
     * @param Request $request
     */
    public function getProductImage(Product $product)
    {

        $cond = array();
        array_push($cond, ['is_deleted', 0]);
        array_push($cond, ['product_id', $product->id]);
        $filldata = ProductImage::where($cond)
            ->orderByDesc('id')
            ->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    /**
     * delete picture
     * @param Request $request
     * @return array
     */
    public function deletePicture(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            $insert_id = ProductImage::whereId($id)->update($ins);
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    /**
     * service master data
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function serviceItemData()
    {
        $data = array();
        $data['PageName'] = "Service Item Master";
        return Parent::page_maker('webpanel.masters.service-item-master', $data);
    }
    //Get Test Master Data

    public function getTestConfig(Request $request)
    {
        $testId = $request->test_id;
        $configs = DB::table('test_config')->where('test_id', $testId)->get();
        // dd($configs);
        echo json_encode($configs);
    }
    public function getTestMasterData()
    {
        $cond = array();
        array_push($cond, ['test_master.is_deleted', 0], ['test_master.is_service_item', 0]);
        $filldata = TestMasterExt::select('test_master.*', 'service_group_master.group_name')
            ->leftjoin('service_group_master', 'service_group_master.id', '=', 'test_master.group_id')
            ->where($cond)
            ->orderBy('test_master.id', 'DESC')
            ->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    /**
     *
     * get  service item master
     */
    public function getServiceItemMaster()
    {
        $cond = array();

        /*  array_push($cond,['service_item_master.is_deleted',0]);
        $filldata= ServiceItemMaster::select('service_item_master.*','service_group_master.group_name')
            ->leftjoin('service_group_master','service_group_master.id','=','service_item_master.service_group_id')
            ->where($cond)
            ->orderBy('service_item_master.id','DESC')
            ->get();*/

        //service item merged with test master
        array_push($cond, ['test_master.is_deleted', 0], ['test_master.is_service_item', 1]);
        $filldata = TestMasterExt::select('test_master.*', 'service_group_master.group_name')
            ->leftjoin('service_group_master', 'service_group_master.id', '=', 'test_master.group_id')
            ->where($cond)
            ->orderBy('test_master.id', 'DESC')
            ->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    /**
     *
     * save service item master
     */

    public function saveServiceItemMaster(Request $request)
    {
        $validated = $request->validate([
            'service_group' => 'required',
            'item_name' => 'required',
            'item_code' => 'required',
            'item_amount' => 'required',
        ]);
        if ($validated) {
            $serviceData = [
                'group_id' => $request->service_group,
                'TestName' => $request->item_name,
                'test_code' => $request->item_code,
                'TestRate' => $request->item_amount,
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'is_deleted' => 0,
                'is_service_item' => 1
            ];
            if ($request->crude == 1) {
                $serviceData['created_at'] = Carbon::now();
                $serviceData['created_by'] = Auth::id();
                $serviceData['TestId'] = 0;

                $cond = [
                    [DB::raw('upper("TestName")'), strtoupper($request->item_name)],
                    ['is_deleted', 0], ['is_service_item', 1]
                ];
                $getId = getAfeild("id", "test_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Service  already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $service = TestMasterExt::insertGetId($serviceData);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $service,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'test_master',
                        'qury_log' => $sql,
                        'description' => ' Save service item Master ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($service) {
                        //update test id
                        $upsData = [
                            'TestId' => $service
                        ];
                        $service = TestMasterExt::whereId($service)->update($upsData);

                        return ['status' => 1, 'message' => "Saved Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } //save
            else if ($request->crude == 2) {
                $serviceData['updated_at'] = Carbon::now();
                $cond = [
                    [DB::raw('upper("TestName")'), strtoupper($request->item_name)],
                    ['is_deleted', 0], ['is_service_item', 1],
                    ['id', '!=', $request->hid_service_id]
                ];

                $getId = getAfeild("id", "test_master", $cond);
                if ($getId) {
                    return ['status' => 4, 'message' => "Service already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $service = TestMasterExt::whereId($request->hid_service_id)->update($serviceData);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_service_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'test_master',
                        'qury_log' => $sql,
                        'description' => ' Update service item Master ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($service) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            } //update
        } else {
            echo 2;
        }
    }

    /* public function saveServiceItemMaster(Request $request)
    {
        $validated = $request->validate([
            'service_group' => 'required',
            'item_name' => 'required',
            'item_code' => 'required',
            'item_amount' => 'required',
        ]);

        if($validated)
        {
            $serviceData = [
                'service_group_id' => $request->service_group,
                'item_name' => $request->item_name,
                'item_code' => $request->item_code,
                'item_amount' => $request->item_amount,
                'branch_id' =>Session::get('current_branch'),
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'is_deleted' => 0,
            ];
            if($request->crude==1)
            {
                $serviceData['created_at'] =Carbon::now();
                $serviceData['created_by'] =Auth::id();
                $cond=[
                    [DB::raw('upper(item_name)'),strtoupper($request->item_name)],
                    ['is_deleted',0],
                ];

                $getId=getAfeild("id","service_item_master",$cond);

                if($getId)
                {
                    return [ 'status'=>4, 'message'=>"Service  already exist" ];
                }
                else{
                    $service = ServiceItemMaster::insert($serviceData);
                    if($service) {
                        return [ 'status'=>1, 'message'=>"Saved Successfully" ];
                        // echo 1; //save success
                    }
                    else{
                        return [ 'status'=>3, 'message'=>"Failed to save" ];

                    }
                }
            }
            else if($request->crude==2){
                $serviceData['updated_at'] =Carbon::now();
                $cond= [
                    [DB::raw('upper(item_name)'),strtoupper($request->item_name)],
                    ['is_deleted',0],
                    ['id','!=',$request->hid_service_id]
                ];

                $getId=getAfeild("id","service_item_master",$cond);

                if($getId)
                {
                    return [ 'status'=>4, 'message'=>"Service already exist" ];
                }
                else{
                    $service = ServiceItemMaster::whereId($request->hid_service_id)->update($serviceData);
                    if($service) {
                        return [ 'status'=>1, 'message'=>"Data updated Successfully" ];
                    }
                    else{
                        return [ 'status'=>3, 'message'=>"Failed to update data" ];
                    }
                }


            }

        }
        else{
            echo 2;
        }

    }*/

    /**
     *
     * delete  service item master
     */
    public function deleteServiceItemMaster(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            //   $insert_id= ServiceItemMaster::whereId($id)->update($ins); // merged with test master
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = TestMasterExt::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Data
                'table_name' => 'test_master',
                'qury_log' => $sql,
                'description' => ' Delete service item Master ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    /**
     * save stock data
     * @param Request $request
     * @return array
     */
    public function saveStockData(Request $request)
    {
        $validated = $request->validate([
            'new_availability' => 'required',
        ]);
        if ($validated) {
            $ins_data = array(
                'product_id' => $request->hid_pd_id,
                'stock' => $request->new_availability,
                'display_status' => 1,
                'is_deleted' => 0,
            );

            if ($request->crude == 1) {
                // insert
                $ins_data['created_at'] = Carbon::now();
                $ins_data['created_by'] = Auth::id();

                DB::connection()->enableQueryLog(); // enable qry log

                $stockData = StockManagement::create($ins_data);

                // TO GET LAST EXECUTED QRY
                $query = DB::getQueryLog();
                $lastQuery = end($query);
                $sql = $lastQuery['query'];
                $bindings = $lastQuery['bindings'];
                $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                //end of qury

                // LOG  // add parameters based on your need
                $log = array(
                    'primarykeyvalue_Id' => $request->hid_pd_id,
                    'user_id' => Auth::id(), // userId
                    'log_type' => 1, // Save
                    'table_name' => 'StockManagement',
                    'qury_log' => $sql,
                    'description' => ' Master Data=>Products, Save StockManagement ',
                    'created_date' => date('Y-m-d H:i:s')
                );

                $save_history = $this->HistoryController->saveHistory($log);
                ////////////////////////////////////////////////////////////////////////////////////
                // END OF LOG  FILE

                Product::whereId($request->hid_pd_id)->update([
                    'available_quantity' => $stockData->stock,
                ]);


                if ($stockData) {
                    return ['status' => 1, 'message' => "Saved Successfully"];
                    // echo 1; //save success
                } else {
                    return ['status' => 3, 'message' => "Failed to save"];
                }
            }
        } else {
            echo 2; // validation error
        }
    }

    /**
     * service item group data
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function serviceItemGroupData()
    {
        $data = array();
        $data['PageName'] = "Service Item Group";
        return Parent::page_maker('webpanel.masters.service-item-group', $data);
    }

    /**
     *
     * get  service item group
     */
    public function getServiceItemGroup()
    {
        $cond = array();
        array_push($cond, ['service_group_master.is_deleted', 0], ['is_lab_group', 0]);
        $filldata = ServiceGroupMaster::select('service_group_master.*')
            ->where($cond)
            ->orderBy('service_group_master.id', 'DESC')
            ->get();
        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }

    /**
     *
     * save service item group
     */
    public function saveServiceItemGroup(Request $request)
    {
        $validated = $request->validate([
            'service_group_name' => 'required',
        ]);

        if ($validated) {
            $serviceGroupData = [
                'group_name' => $request->service_group_name,
                'branch_id' => Session::get('current_branch'),
                'display_status' => ($request->display_status == 'on') ? "1" : "0",
                'is_deleted' => 0,
            ];
            if ($request->crude == 1) {
                $serviceGroupData['created_at'] = Carbon::now();
                $serviceGroupData['created_by'] = Auth::id();
                $cond = [
                    [DB::raw('upper(group_name)'), strtoupper($request->service_group_name)],
                    ['is_deleted', 0],
                ];

                $getId = getAfeild("id", "service_group_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Service group already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $service = ServiceGroupMaster::insertGetId($serviceGroupData);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $service,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'ServiceGroupMaster',
                        'qury_log' => $sql,
                        'description' => ' Save Service Item Group  ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($service) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {
                $serviceGroupData['updated_at'] = Carbon::now();
                $cond = [
                    [DB::raw('upper(group_name)'), strtoupper($request->service_group_name)],
                    ['is_deleted', 0],
                    ['id', '!=', $request->hid_service_group_id]
                ];

                $getId = getAfeild("id", "service_group_master", $cond);

                if ($getId) {
                    return ['status' => 4, 'message' => "Service group already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $service = ServiceGroupMaster::whereId($request->hid_service_group_id)->update($serviceGroupData);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $request->hid_service_group_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 2, // Update
                        'table_name' => 'ServiceGroupMaster',
                        'qury_log' => $sql,
                        'description' => ' Update service item group data ',
                        'created_date' => date('Y-m-d H:i:s')
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG  FILE
                    if ($service) {
                        return ['status' => 1, 'message' => "Data updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to update data"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    /**
     *
     * delete  service item group
     */
    public function deleteServiceItemGroup(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log
            $insert_id = ServiceGroupMaster::whereId($id)->update($ins);
            // TO GET LAST EXECUTED QRY
            $query = DB::getQueryLog();
            $lastQuery = end($query);
            $sql = $lastQuery['query'];
            $bindings = $lastQuery['bindings'];
            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
            //end of qury

            // LOG  // add parameters based on your need
            $log = array(
                'primarykeyvalue_Id' => $id,
                'user_id' => Auth::id(), // userId
                'log_type' => 3, // Delete
                'table_name' => 'ServiceGroupMaster',
                'qury_log' => $sql,
                'description' => ' Delete service item group data ',
                'created_date' => date('Y-m-d H:i:s')
            );

            $save_history = $this->HistoryController->saveHistory($log);
            ////////////////////////////////////////////////////////////////////////////////////
            // END OF LOG  FILE
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }

    /**
     * test master list
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function index(Request $request)
    {
        $testNameSearch = base64_decode($request->search);
        $data = array();
        $data['PageName'] = "Test Master List";
        $testMasterData = new TestMaster();
        if ($testNameSearch) {
            $testMasterData = $testMasterData->where('TestName', 'ilike', '%' . $testNameSearch . '%');
        }
        $testMasterData = $testMasterData->where('is_service_item', 0)->orderByDesc('id')->paginate(10);

        $data['test_master_data'] = $testMasterData;
        return Parent::page_maker('webpanel.masters.test-master', $data);
    }

    /**
     * update test in dtms
     * @param Request $request
     * @return array
     */
    public function updateTestInDtms(Request $request)
    {
        $ins_data = array(
            'show_test_in_dtms' => $request->showInDtms,
        );
        DB::connection()->enableQueryLog(); // enable qry log
        $testMasterData = TestMaster::where('id', $request->testId)->update($ins_data);
        // TO GET LAST EXECUTED QRY
        $query = DB::getQueryLog();
        $lastQuery = end($query);
        $sql = $lastQuery['query'];
        $bindings = $lastQuery['bindings'];
        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
        //end of qury

        // LOG  // add parameters based on your need
        $log = array(
            'primarykeyvalue_Id' => $request->testId,
            'user_id' => Auth::id(), // userId
            'log_type' => 2, // Update
            'table_name' => 'TestMaster', // Save test
            'qury_log' => $sql,
            'description' => 'Labs => Test Master => Update Show in DTMS ',
            'created_date' => date('Y-m-d H:i:s'),
        );

        $save_history = $this->HistoryController->saveHistory($log);
        ////////////////////////////////////////////////////////////////////////////////////
        // END OF LOG

        if ($testMasterData) {
            return ['status' => 1, 'message' => "Data updated Successfully"];
            // echo 1; //save success
        } else {
            return ['status' => 3, 'message' => "Failed to save"];
        }
    }

    /**
     * update test in target
     * @param Request $request
     * @return array
     */
    public function updateTestInTarget(Request $request)
    {
        DB::connection()->enableQueryLog(); // enable qry log
        if ($request->showInTarget == 0) {
            TestMaster::where('id', $request->testId)->update([
                'show_test_in_targets' => 0,
                'target_default_value' => null,
            ]);
        } else {
            TestMaster::where('id', $request->testId)->update([
                'show_test_in_targets' => 1,
            ]);
        }
        // TO GET LAST EXECUTED QRY
        $query = DB::getQueryLog();
        $lastQuery = end($query);
        $sql = $lastQuery['query'];
        $bindings = $lastQuery['bindings'];
        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
        //end of qury

        // LOG  // add parameters based on your need
        $log = array(
            'primarykeyvalue_Id' => $request->testId,
            'user_id' => Auth::id(), // userId
            'log_type' => 2, // Update
            'table_name' => 'TestMaster', // Save show in Target
            'qury_log' => $sql,
            'description' => 'Labs => Test Master => Update Show in Target ',
            'created_date' => date('Y-m-d H:i:s'),
        );

        $save_history = $this->HistoryController->saveHistory($log);
        ////////////////////////////////////////////////////////////////////////////////////
        // END OF LOG
    }

    /**
     * update target default value
     * @param Request $request
     * @return array
     */
    public function updateTargetDefaultValue(Request $request)
    {
        $ins_data = array(
            'target_default_value' => $request->targetDefaultValue,
        );
        DB::connection()->enableQueryLog(); // enable qry log
        $testMasterData = TestMaster::where('id', $request->testId)->update($ins_data);
        // TO GET LAST EXECUTED QRY
        $query = DB::getQueryLog();
        $lastQuery = end($query);
        $sql = $lastQuery['query'];
        $bindings = $lastQuery['bindings'];
        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
        //end of qury

        // LOG  // add parameters based on your need
        $log = array(
            'primarykeyvalue_Id' => $request->testId,
            'user_id' => Auth::id(), // userId
            'log_type' => 2, // Update
            'table_name' => 'TestMaster', // Save target default value
            'qury_log' => $sql,
            'description' => 'Labs => Test Master => Update target-default-value ',
            'created_date' => date('Y-m-d H:i:s'),
        );

        $save_history = $this->HistoryController->saveHistory($log);
        ////////////////////////////////////////////////////////////////////////////////////
        // END OF LOG

        if ($testMasterData) {
            return ['status' => 1, 'message' => "Data updated Successfully"];
            // echo 1; //save success
        } else {
            return ['status' => 3, 'message' => "Failed to save"];
        }
    }

    public function updateTestOrderNo(Request $request)
    {
        $ins_data = array(
            'dtms_order_no' => $request->dtmsOrderNo,
        );
        DB::connection()->enableQueryLog(); // enable qry log
        $testMasterData = TestMaster::where('id', $request->testId)->update($ins_data);
        // TO GET LAST EXECUTED QRY
        $query = DB::getQueryLog();
        $lastQuery = end($query);
        $sql = $lastQuery['query'];
        $bindings = $lastQuery['bindings'];
        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
        //end of qury

        // LOG  // add parameters based on your need
        $log = array(
            'primarykeyvalue_Id' => $request->testId,
            'user_id' => Auth::id(), // userId
            'log_type' => 2, // Update
            'table_name' => 'TestMaster', // Save update-test-orderno
            'qury_log' => $sql,
            'description' => 'Labs => Test Master => Update Test Order No ',
            'created_date' => date('Y-m-d H:i:s'),
        );

        $save_history = $this->HistoryController->saveHistory($log);
        ////////////////////////////////////////////////////////////////////////////////////
        // END OF LOG
        if ($testMasterData) {
            return ['status' => 1, 'message' => "Data updated Successfully"];
            // echo 1; //save success
        } else {
            return ['status' => 3, 'message' => "Failed to save"];
        }
    }
    /////////////////////////////////////////////////////////////////////////////////////////
    public function getTestByGroup(Request $request)
    {
        $groupId = $request->groupId;
        if ($groupId > 0) {
            $filldata = TestMasterExt::select('id', 'TestName')->where('is_deleted', 0)->where('display_status', 1)->where('group_id', $groupId)->orderByDesc('id')->get();
            $options = "<option value=''>Select</option>";
            if ($filldata) {

                foreach ($filldata as $value) {
                    $options .= "<option value='$value->id'>$value->TestName</option>";
                }
            }

            echo $options;
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    public function saveTestProcedure(Request $request)
    {

        $validated = $request->validate([
            'test_id' => 'required',
            'procedure_name' => 'required',
            'test_unit' => 'required',
        ]);
        if ($validated) {
            $insData = [
                'test_id' => $request->test_id,
                'procedure_name' => $request->procedure_name,
                'test_unit' => $request->test_unit,
                'report_data' => $request->report_data,
                'method' => $request->method,
            ];
            if ($request->crude == 1) {
                $insData['created_at'] = Carbon::now();
                $insData['created_by'] = Auth::id();

                $cond = [
                    ['test_id', $request->test_id],
                ];

                $getId = getAfeild("id", "test_procedures", $cond);
                if ($getId) {
                    return ['status' => 4, 'message' => "Procedure  already added for selected test"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log
                    $insert = DB::table('test_procedures')->insertGetId($insData);
                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'test_procedures', // Save text procedure
                        'qury_log' => $sql,
                        'description' => 'labs =>Test Configuration, Save Test Procedure ',
                        'created_date' => date('Y-m-d H:i:s'),
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG
                    if ($insert) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } //save
        } else {
            echo 2;
        }
    }

    //////////////////////////////////////////////////////////////////////

    public function saveTestMaster(Request $request)
    {
        $crude = $request->crude;

        $group_id = $request->group_id;
        $test_name = $request->test_name;
        $test_amount = $request->test_amount;
        $item_code = $request->item_code;
        $result_typeid = $request->result_typeid;
        //  $item_code=$request->item_code;
        $ordr_num = $request->order_num;
        $dtms_code = $request->dtms_code;

        if ($test_name && $item_code && $result_typeid && $group_id > 0) {
            // insert in test master

            $ins_data = [
                'TestName' => $test_name,
                'TestRate' => $request->test_amount,
                //  'show_test_in_dtms'=>0,
                'show_test_in_targets' => 0,
                'group_id' => $group_id,
                'result_type' => $request->result_typeid,
                'test_code' => $item_code,
                'display_status' => ($request->display_status_edu == 'on') ? "1" : "0",
                'is_deleted' => 0,
                'unit' => $request->unit,
                'report_data' => $request->report_data,
                'test_method' => $request->test_method,
                'order_num' => $ordr_num,
                'dtms_code' => $dtms_code,
                'chart_order_no' => $request->chart_order_num,
            ];

            if ($crude == 1) {
                $ins_data['TestId'] = 0;   // icon ID, update this id with our primary key
                $ins_data['created_at'] = Carbon::now();
                // dd( $ins);
                DB::connection()->enableQueryLog(); // enable qry log


                $ins = TestMasterExt::create($ins_data)->id;

                // TO GET LAST EXECUTED QRY
                $query = DB::getQueryLog();
                $lastQuery = end($query);
                $sql = $lastQuery['query'];
                $bindings = $lastQuery['bindings'];
                $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                //end of qury

                // LOG  // add parameters based on your need
                $log = array(
                    'primarykeyvalue_Id' => $ins,
                    'user_id' => Auth::id(), // userId
                    'log_type' => 1, // Save
                    'table_name' => 'test_master', // Save text procedure
                    'qury_log' => $sql,
                    'description' => 'labs =>Test Groups => Save Test/Procedure ',
                    'created_date' => date('Y-m-d H:i:s'),
                );

                $save_history = $this->HistoryController->saveHistory($log);
                ////////////////////////////////////////////////////////////////////////////////////
                // END OF LOG

                $insId = $ins;
                if ($ins) {
                    $ups_data = [
                        'TestId' => $insId
                    ];
                    $insUpdate = DB::table('test_master')->where('id', $ins)->update($ups_data);
                }
            } else if ($crude == 2) {
                $test_id = $request->hid_test_id;
                DB::connection()->enableQueryLog(); // enable qry log

                $ins = TestMasterExt::where('id', $test_id)->update($ins_data);

                // TO GET LAST EXECUTED QRY
                $query = DB::getQueryLog();
                $lastQuery = end($query);
                $sql = $lastQuery['query'];
                $bindings = $lastQuery['bindings'];
                $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                //end of qury

                // LOG  // add parameters based on your need
                $log = array(
                    'primarykeyvalue_Id' => $test_id,
                    'user_id' => Auth::id(), // userId
                    'log_type' => 2, // update
                    'table_name' => 'test_master', // Save text procedure
                    'qury_log' => $sql,
                    'description' => 'labs =>Test Groups => Update Test/Procedure ',
                    'created_date' => date('Y-m-d H:i:s'),
                );

                $save_history = $this->HistoryController->saveHistory($log);
                ////////////////////////////////////////////////////////////////////////////////////
                // END OF LOG
                $insId = $test_id;
            }


            //    dd($ins);
            if ($ins) {
                //insertconfig
                $rows = $request->no_rows;
                if ($rows > 0) {
                    for ($i = 1; $i <= $rows; $i++) {
                        $from_age = "from_age_" . $i;
                        $to_age = "to_age_" . $i;
                        $gender = "gender_" . $i;
                        $from_range = "from_range_" . $i;
                        $from_range = "from_range_" . $i;
                        $to_range = "to_range_" . $i;
                        $color = "color_" . $i;
                        $clarity = "clarity_" . $i;
                        $postive_negative = "postive_negative_" . $i;


                        if ($request->result_typeid == 1) // color
                        {
                            if ($request->$color != '') {


                                $config_data[] = [
                                    'test_id' => $insId,
                                    'result_type' => $request->result_typeid,
                                    'from_age' => $request->$from_age,
                                    'to_age' => $request->$to_age,
                                    'gender' => $request->$gender,
                                    'colour_id' => $request->$color,
                                ];
                            }
                        } else if ($request->result_typeid == 4) // clarity
                        {
                            if ($request->$clarity != "") {

                                $config_data[] = [
                                    'test_id' => $insId,
                                    'result_type' => $request->result_typeid,
                                    'from_age' => $request->$from_age,
                                    'to_age' => $request->$to_age,
                                    'gender' => $request->$gender,
                                    'clarity_id' => $request->$clarity,
                                ];
                            }
                        } else if ($request->result_typeid == 2) // rhange
                        {
                            if ($request->$from_range != '') {


                                $config_data[] = [
                                    'test_id' => $insId,
                                    'result_type' => $request->result_typeid,
                                    'from_age' => $request->$from_age,
                                    'to_age' => $request->$to_age,
                                    'gender' => $request->$gender,
                                    'from_range' => $request->$from_range,
                                    'to_range' => $request->$to_range,
                                ];
                            }
                        } else if ($request->result_typeid == 3) // +ve,-ve
                        {
                            if ($request->$postive_negative != "") {
                                $config_data[] = [
                                    'test_id' => $insId,
                                    'result_type' => $request->result_typeid,
                                    'from_age' => $request->$from_age,
                                    'to_age' => $request->$to_age,
                                    'gender' => $request->$gender,
                                    'positive_negative' => $request->$postive_negative,
                                ];
                            }
                        }
                    } // end of data collection

                    if (!empty($config_data)) {
                        //delete existing conifg data
                        $deleted = DB::table('test_config')->where('test_id', $insId)->delete();

                        DB::connection()->enableQueryLog(); // enable qry log

                        DB::table('test_config')->insert($config_data);

                        // TO GET LAST EXECUTED QRY
                        $query = DB::getQueryLog();
                        $lastQuery = end($query);
                        $sql = $lastQuery['query'];
                        $bindings = $lastQuery['bindings'];
                        $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                        //end of qury

                        // LOG  // add parameters based on your need
                        $log = array(
                            'primarykeyvalue_Id' => $test_id,
                            'user_id' => Auth::id(), // userId
                            'log_type' => 1, // Save
                            'table_name' => 'test_config', // Save test config
                            'qury_log' => $sql,
                            'description' => 'labs =>Test Groups => Test/Procedures, Save Result Type ',
                            'created_date' => date('Y-m-d H:i:s'),
                        );

                        $save_history = $this->HistoryController->saveHistory($log);
                        ////////////////////////////////////////////////////////////////////////////////////
                        // END OF LOG
                        $msg = "";
                    } else {
                        $msg = "No configuration found.";
                    }


                    if ($ins) {
                        if ($crude == 1)
                            return ['status' => 1, 'message' => "Saved Successfully $msg"];
                        else if ($crude == 2)
                            return ['status' => 1, 'message' => "Updated Successfully $msg"];

                        // echo 1; //save success
                    }
                }
            } else {
                return ['status' => 3, 'message' => "Failed to save"];
            }


            //update icon id as our id// set a new limit to ID in DB for No conflict

        }
    }


    // history
    public function view_history(Request $request)
    {
        //
        $data = array();
        $data['PageName'] = "View History";

        $data['tables'] = DB::table('histroy_log')
            ->select('table_name')
            ->distinct()
            ->pluck('table_name');


        return Parent::page_maker('webpanel.masters.view_history', $data);
    }
    public function getHistoryDetails(Request $request)
    {

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $timestamp = strtotime($from_date);
        $from_date = date("Y-m-d", $timestamp);

        $timestamp = strtotime($to_date);
        $to_date = date("Y-m-d", $timestamp);

        $cond = [];

        $seat_id = session('current_seat');  // seat Id
        // array_push($cond, ['forwarded_to', $seat_id]);

        $service_id = $request->service_id;

        if ($service_id > 0) {
            array_push($cond, ['id.service_id', $service_id]);
        }

        $uhid_no = $request->uhid_no;
        $pid = 0;
        if ($uhid_no != "") {
            $cond2 = array(
                array('uhidno', $uhid_no),
            );
            $pid = getAfeild("id", "patient_registration", $cond2);
        }

        if ($pid > 0) {
            array_push($cond, ['hl.patient_id', $pid]);
        }

        $tbl_name = $request->tbl_name;
        if ($tbl_name && $tbl_name != "") {
            array_push($cond, ['hl.table_name', $tbl_name]);
        }

        $data = DB::table('histroy_log as hl')
            ->select('hl.id', 'hl.log_type', 'hl.created_date', 'hl.description', 'hl.qury_log', 'hl.table_name', 'us.name')
            ->join('users as us', 'us.id', '=', 'hl.user_id')
            ->whereBetween('hl.created_date', [$from_date, $to_date . ' 23:59:59'])
            ->where($cond)
            ->orderBy('hl.id', 'desc')
            ->get();

        $output = array(
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
        );
        echo json_encode($output);
    }

    // public function getHistoryDetails1(Request $request)
    // {

    //     $fromD = $request->from_date;
    //     $toD = $request->to_date;
    //     $formattedFromDate = date('Y-m-d', strtotime($fromD));
    //     $formattedToDate = date('Y-m-d', strtotime($toD));
    //     $filldata = History_log::whereDate('created_date', '>=', $formattedFromDate)
    //         ->whereDate('created_date', '<=', $formattedToDate)
    //         ->get();
    //     $finalData = [];
    //     foreach ($filldata  as $data) {
    //         $tempdate = $data->created_date;
    //         $newDate = date('d-m-Y H:m:s A', strtotime($tempdate));
    //         $outputData = [
    //             'id' => $data->id,
    //             'description' => $data->description,
    //             'created_date' => $newDate,
    //             'qury_log' => $data->qury_log,
    //             'table_name' => $data->table_name,

    //         ];
    //         array_push($finalData, $outputData);
    //     }

    //     $output = array(
    //         "recordsTotal" => count($finalData),
    //         "recordsFiltered" => count($finalData),
    //         "data" => $finalData
    //     );
    //     echo json_encode($output);

    // }
}
