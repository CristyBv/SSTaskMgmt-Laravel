<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class UsersController extends Controller
{
    public function search(Request $request) {

        $what = $request->search;
        $users = DB::table('users')->where('name', 'like', '%'.$what.'%')->get();
        $output = "";
        foreach($users as $user) {
            $output = $output . "<option value=\"" . $user->id . "\">" . $user->name . "</option>";
        }
        echo json_encode(['result' => $output, 'test' => $users]);

    }
}
