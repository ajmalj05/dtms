<?php

namespace App\Http\Controllers\users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UserManagement\UserGroup;
use App\Models\UserManagement\UserGroupMenus;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HistoryController;

class UserManagementController extends Controller
{

    // public function __construct()
    // {

    // }
    protected $HistoryController;

    public function __construct(HistoryController $HistoryController)
    {
        $this->HistoryController = $HistoryController;
    }

    public function branch_profile()
    {
        $data = array();
        $data['PageName'] = "Branch Settings";
        $branch_id = Session::get('current_branch');
        $data['branch_Details'] = GetBranchDetails($branch_id);

        return Parent::page_maker('webpanel.user_management.branch_profile', $data);
    }
    public function saveBranchData(Request $request)
    {
        $branch_id = Session::get('current_branch');
        $ins = array(
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'phone' => $request->phone,
            'email' => $request->email,
            'tag_line' => $request->tag_line,
        );

        $update = DB::table('branch_master')->where('branch_id', $branch_id)->update($ins);
        if ($update) {
            return ['status' => 1, 'message' => "Data updated Successfully"];
        } else {
            return ['status' => 2, 'message' => "Failed to update"];
        }
    }
    public function savePrintSettings(Request $request)
    {
        $print_item = $request->print_item;
        $paper_size = $request->paper_size;
        $paper_mode = $request->paper_mode;
        $branch_id = Session::get('current_branch');

        $cond = ['branch_id' => $branch_id];
        $getId = getAfeild("id", "print_settings", $cond);

        $ins = array(
            'print_item' => $print_item,
            'paper_size' => $paper_size,
            'paper_mode' => $paper_mode,
            'branch_id' => $branch_id
        );
        if ($getId > 0) {
            $insId = DB::table('print_settings')->where('id', $getId)->update($ins);
        } else {
            $insId = DB::table('print_settings')->insert($ins);
        }

        if ($insId) {
            return ['status' => 1, 'message' => "Data updated Successfully"];
        } else {
            return ['status' => 2, 'message' => "Failed to update"];
        }
    }

    public function getPrintSettings(Request $request)
    {
        $branch_id = Session::get('current_branch');
        $print_item = $request->print_item;
        $sel = DB::table('print_settings')->where('print_item', $print_item)->where('branch_id', $branch_id)->get();
        return ['status' => 1, 'message' => "Data updated Successfully", 'data' => $sel[0]];
    }

    public function createUser(Request $request)
    {
        $data = array();
        $data['PageName'] = "Create User";
        $user_data = null;
        // if(isset($request->id)){
        //     $user_data=User::where('id',$request->id)->first();
        // }

        $data['user_data'] = $user_data;
        return Parent::page_maker('webpanel.user_management.createUser', $data);
    }



    public function userGroup(Request $request)
    {

        $data = array();
        $data['PageName'] = "User Group";
        $op_menu = array();
        $group_data = null;
        $groupID = base64_decode($request->id);
        if (isset($request->id)) {

            $group_data = UserGroup::where('group_id', $groupID)->orderBy('group_id', 'DESC')->leftjoin('user_group_menus', 'user_group_menus.user_group_id', '=', 'user_group.group_id')->first();

            $data['group_id'] = $groupID;
        } else {
            $data['group_id'] = 0;
            $menus = "SELECT * FROM menu_items WHERE display_status=1 order by mid";
            $menu_items = DB::select($menus);

            foreach ($menu_items as $item) {
                $menu_single = $item;
                $menu_single->submenu = array();
                $sub_menus = "SELECT * FROM submenus WHERE display_status=1 and mainmenuid=$item->mid order by sid";
                $sub_menus_items = DB::select($sub_menus);
                if ($sub_menus_items) {
                    $menu_single->submenu = $sub_menus_items;
                }

                array_push($op_menu, $menu_single);
            }
            $data['menus'] = $op_menu;
        }

        $data['group_data'] = $group_data;

        return Parent::page_maker('webpanel.user_management.userGroup', $data);
    }

    public function saveNewUser(Request $request)
    {
        if ($request->crude == 1) {
            $validated = $request->validate([
                'role_id' => 'required',
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation' => 'required',
            ]);
        } else if ($request->crude == 2) {
            if ($request->password) {
                $validated = $request->validate([
                    'role_id' => 'required',
                    'name' => 'required',
                    'email' => 'required|email',
                    'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
                    'password_confirmation' => 'required',
                ]);
            } else {
                $validated = $request->validate([
                    'role_id' => 'required',
                    'name' => 'required',
                    'email' => 'required|email',
                ]);
            }
        }


        if ($validated) {
            if ($request->crude == 1) {
                $cond = ['email' => $request->email, 'is_deleted' => 0];
                $getId = getAfeild("id", "users", $cond);
                if ($getId) {
                    return ['status' => 4, 'message' => "Email already exist"];
                } else {
                    $insert_id = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'role_id' => $request->role_id,
                        'active_status' => ($request->display_status == 'on') ? "1" : "0",
                        'created_by' => Auth::id()
                    ]);

                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Saved Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {

                $cond = array(
                    array('email', $request->email),
                    array('is_deleted', 0),
                    array('id', '!=', $request->hidid),
                );

                $getId = getAfeild("id", "users", $cond);
                if ($getId) {
                    return ['status' => 4, 'message' => "Email already exist"];
                } else {

                    if ($request->password) {
                        $insert_id = User::where('id', $request->hidid)->update([
                            'name' => $request->name,
                            'email' => $request->email,
                            'password' => Hash::make($request->password),
                            'role_id' => $request->role_id,
                            'active_status' => ($request->display_status == 'on') ? "1" : "0",
                            'created_by' => Auth::id()
                        ]);
                    } else {
                        $insert_id = User::where('id', $request->hidid)->update([
                            'name' => $request->name,
                            'email' => $request->email,
                            'role_id' => $request->role_id,
                            'active_status' => ($request->display_status == 'on') ? "1" : "0",
                            'created_by' => Auth::id()
                        ]);
                    }


                    if ($insert_id) {
                        return ['status' => 1, 'message' => "Updated Successfully"];
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            }
        } else {
            echo 2;
        }
    }

    public function mapuserGroup(Request $request)
    {
        $branchId = $request->branchId;
        $groupId = $request->groupId;
        $userId = $request->userId;
        $created_by = Auth::id();


        $cond = ['branch_id' => $branchId, 'is_deleted' => 0, 'user_id' => $userId];
        $getId = getAfeild("id", "user_group_mapping", $cond);

        if ($getId > 0) {
            echo 3;
        } else {
            $qry = "INSERT INTO user_group_mapping (branch_id,group_id,created_by,user_id) VALUES ($branchId,$groupId,$created_by,$userId)";
            $insert = DB::insert($qry);

            if ($insert) {
                echo 1;
            } else {
                echo 2;
            }
        }
    }

    public function getMappedGroup(Request $request)
    {
        $userId = $request->userId;


        $qry = "SELECT gm.id,gm.branch_id,gm.group_id,UG.group_name,BM.branch_name  FROM user_group_mapping gm
            INNER JOIN user_group UG ON UG.group_id=gm.group_id
            INNER JOIN branch_master BM on BM.branch_id=gm.branch_id
            WHERE gm.is_deleted=0 AND gm.user_id=$userId;
        ";
        $qrry = DB::select($qry);

        $html = "";
        foreach ($qrry as $item) {
            $html .= '<tr>
            <td>' . $item->branch_name . '</td>
            <td>' . $item->group_name . '</td>
            <td><div class="d-flex">
            <a href="#"  onclick="deleteGroup(' . $item->id . ')" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a>
            </div></td>
        </tr>';
        }
        echo $html;
    }

    public function getGroupByBranch(Request $request)
    {
        $branchId = $request->branchId;

        $filldata = UserGroup::where(array('is_deleted' => 0, 'branch_id' => $branchId))->orderByDesc('group_id')->get();
        echo json_encode($filldata);
    }

    public function getUsers()
    {
        $filldata = DB::table('users')
            ->select('name', 'email', 'role_id', 'id', 'active_status')
            ->where('is_deleted', 0)
            ->where('id', '>', 1)
            ->orderByDesc('id')
            ->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }
    public function deletemappedGroup(Request $request)
    {
        $delId = $request->id;

        $qry = "UPDATE  user_group_mapping SET is_deleted=1 WHERE id=$delId";
        $insert = DB::update($qry);

        if ($insert) {
            echo 1;
        } else {
            echo 2;
        }
    }

    public function saveUserGroup(Request $request)
    {
        $validated = $request->validate([
            'group_name' => 'required',
        ]);

        if ($validated) {
            $insert_id = "";
            $ins = array(
                'group_name' => $request->group_name,
                'branch_id' => Session::get('current_branch'),
                'created_by' => Auth::id(),
                'is_deleted' => 0

            );

            if ($request->crude == 1) {
                $cond = ['group_name' => $request->group_name, 'is_deleted' => 0];
                $getId = getAfeild("group_id", "user_group", $cond);
                if ($getId) {
                    return ['status' => 4, 'message' => "Group name already exist"];
                } else {
                    DB::connection()->enableQueryLog(); // enable qry log


                    $insert_id = UserGroup::create($ins);

                    // TO GET LAST EXECUTED QRY
                    $query = DB::getQueryLog();
                    $lastQuery = end($query);
                    $sql = $lastQuery['query'];
                    $bindings = $lastQuery['bindings'];
                    $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                    //end of qury

                    // LOG  // add parameters based on your need
                    $log = array(
                        'primarykeyvalue_Id' => $insert_id->group_id,
                        'user_id' => Auth::id(), // userId
                        'log_type' => 1, // Save
                        'table_name' => 'UserGroup', // Save UserGroup
                        'qury_log' => $sql,
                        'description' => 'UserManagement, Save  Group Name ',
                        'created_date' => date('Y-m-d H:i:s'),
                    );

                    $save_history = $this->HistoryController->saveHistory($log);
                    ////////////////////////////////////////////////////////////////////////////////////
                    // END OF LOG

                    $insert_id = $insert_id->group_id;
                    if ($insert_id) {

                        // menu insertion

                        if ($request->chkbp) {
                            foreach ($request->chkbp as $item => $val) {
                                $ins_menu = array(
                                    'user_group_id' => $insert_id,
                                    'menu_type' => 1,
                                    'menu_id' => $val,
                                    'created_user' => Auth::id()
                                );

                                DB::connection()->enableQueryLog(); // enable qry log

                                $insert = UserGroupMenus::insert($ins_menu);

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
                                    'table_name' => 'UserGroupMenus', // Save UserGroup
                                    'qury_log' => $sql,
                                    'description' => 'UserManagement, Save  Menus ',
                                    'created_date' => date('Y-m-d H:i:s'),
                                );

                                $save_history = $this->HistoryController->saveHistory($log);
                                ////////////////////////////////////////////////////////////////////////////////////
                                // END OF LOG
                            }
                        }

                        if ($request->chksubmenu) {
                            foreach ($request->chksubmenu as $item => $val) {
                                $ins_menu = array(
                                    'user_group_id' => $insert_id,
                                    'menu_type' => 2,
                                    'menu_id' => $val,
                                    'created_user' => Auth::id()
                                );
                                DB::connection()->enableQueryLog(); // enable qry log

                                $insert = UserGroupMenus::insert($ins_menu);

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
                                    'table_name' => 'UserGroupMenus', // Save UserGroup
                                    'qury_log' => $sql,
                                    'description' => 'UserManagement, Save Sub Menus ',
                                    'created_date' => date('Y-m-d H:i:s'),
                                );

                                $save_history = $this->HistoryController->saveHistory($log);
                                ////////////////////////////////////////////////////////////////////////////////////
                                // END OF LOG
                            }
                        }
                        return ['status' => 1, 'message' => "Saved Successfully"];
                        // echo 1; //save success
                    } else {
                        return ['status' => 3, 'message' => "Failed to save"];
                    }
                }
            } else if ($request->crude == 2) {

                DB::connection()->enableQueryLog(); // enable qry log

                $insert_id = UserGroup::where('group_id', $request->user_group_id)->update($ins);


                // TO GET LAST EXECUTED QRY
                $query = DB::getQueryLog();
                $lastQuery = end($query);
                $sql = $lastQuery['query'];
                $bindings = $lastQuery['bindings'];
                $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                //end of qury

                // LOG  // add parameters based on your need
                $log = array(
                    'primarykeyvalue_Id' => $request->user_group_id,
                    'user_id' => Auth::id(), // userId
                    'log_type' => 2, // Update
                    'table_name' => 'UserGroup', // update UserGroup
                    'qury_log' => $sql,
                    'description' => 'UserManagement, Update Group Name ',
                    'created_date' => date('Y-m-d H:i:s'),
                );

                $save_history = $this->HistoryController->saveHistory($log);
                ////////////////////////////////////////////////////////////////////////////////////
                // END OF LOG
                if ($insert_id) {
                    // delete and re insert menus

                    $insert_id = UserGroupMenus::where('user_group_id', $request->user_group_id)->delete();

                    if ($request->chkbp) {
                        foreach ($request->chkbp as $item => $val) {
                            $ins_menu = array(
                                'user_group_id' => $request->user_group_id,
                                'menu_type' => 1,
                                'menu_id' => $val,
                                'created_user' => Auth::id()
                            );
                            DB::connection()->enableQueryLog(); // enable qry log

                            $insert = UserGroupMenus::insert($ins_menu);

                            // TO GET LAST EXECUTED QRY
                            $query = DB::getQueryLog();
                            $lastQuery = end($query);
                            $sql = $lastQuery['query'];
                            $bindings = $lastQuery['bindings'];
                            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                            //end of qury

                            // LOG  // add parameters based on your need
                            $log = array(
                                'primarykeyvalue_Id' => $request->user_group_id,
                                'user_id' => Auth::id(), // userId
                                'log_type' => 2, // Update
                                'table_name' => 'UserGroupMenus', // update UserGroupMenus
                                'qury_log' => $sql,
                                'description' => 'UserManagement, Update User Group Menus ',
                                'created_date' => date('Y-m-d H:i:s'),
                            );

                            $save_history = $this->HistoryController->saveHistory($log);
                            ////////////////////////////////////////////////////////////////////////////////////
                            // END OF LOG
                        }
                    }

                    if ($request->chksubmenu) {
                        foreach ($request->chksubmenu as $item => $val) {
                            $ins_menu = array(
                                'user_group_id' => $request->user_group_id,
                                'menu_type' => 2,
                                'menu_id' => $val,
                                'created_user' => Auth::id()
                            );
                            DB::connection()->enableQueryLog(); // enable qry log

                            $insert = UserGroupMenus::insert($ins_menu);

                            // TO GET LAST EXECUTED QRY
                            $query = DB::getQueryLog();
                            $lastQuery = end($query);
                            $sql = $lastQuery['query'];
                            $bindings = $lastQuery['bindings'];
                            $sql = vsprintf(str_replace('?', '%s', $sql), $bindings);
                            //end of qury

                            // LOG  // add parameters based on your need
                            $log = array(
                                'primarykeyvalue_Id' => $request->user_group_id,
                                'user_id' => Auth::id(), // userId
                                'log_type' => 2, // Update
                                'table_name' => 'UserGroupMenus', // update UserGroupMenus
                                'qury_log' => $sql,
                                'description' => 'UserManagement, Update User Group Sub Menus ',
                                'created_date' => date('Y-m-d H:i:s'),
                            );

                            $save_history = $this->HistoryController->saveHistory($log);
                            ////////////////////////////////////////////////////////////////////////////////////
                            // END OF LOG
                        }
                    }

                    return ['status' => 1, 'message' => "Data updated Successfully"];
                } else {
                    return ['status' => 3, 'message' => "Failed to update data"];
                }
            }
        } else {
            echo 2;
        }
    }
    public function deleteUserGroup(Request $request)
    {

        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            DB::connection()->enableQueryLog(); // enable qry log

            $insert_id = UserGroup::where('group_id', $id)->update($ins);


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
                'log_type' => 3, // delete
                'table_name' => 'UserGroup', // update UserGroup
                'qury_log' => $sql,
                'description' => 'UserManagement, Delete User Group ',
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

    public function GetUserGroupMenus()
    {
        $filldata = UserGroup::where('is_deleted', 0)->where('branch_id', Session::get('current_branch'))->orderByDesc('group_id')->get();

        $output = array(
            "recordsTotal" => count($filldata),
            "recordsFiltered" => count($filldata),
            "data" => $filldata
        );
        echo json_encode($output);
    }
    public function deleteUser(Request $request)
    {

        $id = $request->id;
        if ($id) {
            $ins = array(
                'is_deleted' => 1,
            );
            $insert_id = User::where('id', $id)->update($ins);
            if ($insert_id) {
                return ['status' => 1, 'message' => "Data deleted successfully"];
            } else {
                return ['status' => 3, 'message' => "Failed to delete data"];
            }
        }
    }
    public function changeBranch(Request $request)
    {

        $cond = array(
            array('branch_id', $request->branch),
        );

        $branch_code = getAfeild("branch_code", "branch_master", $cond);

        Session::put('current_branch', $request->branch);
        Session::put('current_branch_code', $branch_code);
        return ['status' => 1, 'message' => "Branch Session Updated"];
    }
}
