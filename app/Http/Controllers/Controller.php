<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function page_maker($page,$data)
    {

        $user = auth()->user();

        if($page!="webpanel.dashboard")
        {
            if (Session::has('current_branch'))
            {
            }
            else{
                return redirect('/dashboard');
            }
        }

        /*******************************/
        $op_menu=array();
        if($user->role_id==1)
        {


            $menus="SELECT * FROM menu_items WHERE display_status=1 order by mid";
            $menu_items=DB::select($menus);

            foreach($menu_items as $item)
                {


                    $menu_single=$item;
                    $menu_single->submenu=array();


                    $sub_menus="SELECT * FROM submenus WHERE display_status=1 and mainmenuid=$item->mid order by sid";
                    $sub_menus_items=DB::select($sub_menus);
                    if($sub_menus_items)
                    {
                        $menu_single->submenu=$sub_menus_items;
                    }

                    array_push($op_menu, $menu_single);
                }

                $data['menus']=$op_menu;
        }
        else  if($user->role_id>1){
            // get menus of user

            $branch_id=Session::get('current_branch');

            $cond=['branch_id' => $branch_id,'user_id'=>$user->id,'is_deleted'=>0];
			$group_id=getAfeild("group_id","user_group_mapping",$cond);

           $menus="SELECT * FROM menu_items WHERE display_status=1 AND mid in (
                SELECT menu_id FROM user_group_menus WHERE user_group_id=$group_id AND menu_type=1
                ) order by mid";



            $menu_items=DB::select($menus);
              //  print_r($menu_items); exit;

            foreach($menu_items as $item)
            {


                $menu_single=$item;
                $menu_single->submenu=array();


                $sub_menus="SELECT * FROM submenus WHERE display_status=1 and mainmenuid=$item->mid and
                sid in ( SELECT menu_id FROM user_group_menus WHERE user_group_id=$group_id AND menu_type=2 )
                 order by sid";
                $sub_menus_items=DB::select($sub_menus);
                if($sub_menus_items)
                {
                    $menu_single->submenu=$sub_menus_items;
                }

                array_push($op_menu, $menu_single);
            }

            $data['menus']=$op_menu;
        }

        /**********************************8 */

        $data['selected_branch']=Session::get('current_branch');
        $template=   view('frames/header',$data);
        $template.= view('frames/sidebar',$data);
        $template.= view($page,$data);


        return $template;
    }

}
