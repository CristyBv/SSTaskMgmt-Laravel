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
            $users = DB::table('users')->select('id', 'name as text')->where('name', 'like', '%'.$what.'%')->get();
            return $users;
        }      
    }
}
