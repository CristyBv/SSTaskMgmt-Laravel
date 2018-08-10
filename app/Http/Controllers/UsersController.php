<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class UsersController extends Controller
{

    // search a user using ajax requests and return options for select2

    public function search(Request $request) {

        $what = $request->get('search');
        if($what != '') {
            $users = DB::table('users')->where('name', 'like', '%'.$what.'%')->get();
            $data = [];
            foreach($users as $user) {
                array_push($data, [
                    'id' => $user->id,
                    'text' => $user->name,
                ]);
            }
            echo json_encode($data);
        }      
    }
}
