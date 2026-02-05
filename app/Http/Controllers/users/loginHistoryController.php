<?php
namespace App\Http\Controllers;




use App\Models\login_log;

class loginHistoryController extends Controller
{

    public function saveHistory(array $data)
    {
        $users = login_log::insertGetId([
            'id' => $data['id'],
            'user_id' => $data['user_id'],
            'login_time' => $data['login_time'],
            'login_ip' => $data['login_ip'],
            
        ]);

        return $users;
    }

}
?>


