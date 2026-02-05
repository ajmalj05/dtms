<?php

namespace App\Http\Controllers\users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class DashboardController extends Controller
{
    public function index()
    {
      //  dd(config('global.icon.url'));

        $data=array();
        $user = auth()->user();
        Session::put('user_role',$user->role_id);

        if (Session::has('current_branch'))
            {
                    // for branch based  things
            }
            else{
                if($user->role_id==1)
                {
                    $qry="SELECT branch_id,branch_name ,branch_code FROM branch_master";
                    $qrry=DB::select($qry);
                    Session::put('current_branch',$qrry[0]->branch_id);
                    Session::put('current_branch_code',$qrry[0]->branch_code);
                    Session::put('selected_group',0);
                }
                else{
                    $qry="SELECT gm.id,gm.branch_id,BM.branch_code,gm.group_id,UG.group_name,BM.branch_name  FROM user_group_mapping gm
                    INNER JOIN user_group UG ON UG.group_id=gm.group_id
                    INNER JOIN branch_master BM on BM.branch_id=gm.branch_id
                    WHERE gm.is_deleted=0 AND gm.user_id=$user->id
                    ORDER BY gm.group_id ASC;
                    ";
                     $qrry=DB::select($qry);
                     Session::put('current_branch',$qrry[0]->branch_id);
                     Session::put('selected_group',$qrry[0]->group_id);
                     Session::put('current_branch_code',$qrry[0]->branch_code);
                }

                //   echo Session::get('current_branch');
                //   echo Session::get('user_role');
                //   echo Session::get('selected_group');
            }

            if($user->role_id==1)
            {
                $qry="SELECT branch_id,branch_name  FROM branch_master";
                $qrry=DB::select($qry);
                $data['branch_list']=$qrry;
            }
            else{
                $qry="SELECT branch_id,branch_name  FROM branch_master WHERE branch_id in (
                    SELECT DISTINCT branch_id FROM user_group_mapping WHERE user_id=$user->id
                    )";
                $qrry=DB::select($qry);
                $data['branch_list']=$qrry;
            }

            $data['selected_branch']=Session::get('current_branch');


        //menu permission get


        $data['PageName']="";
        return Parent::page_maker('webpanel.dashboard',$data);
    }
    public function public_user()
    {

        return view('public_user/user');
    }
}
