<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $data = [
            'filter' => 'user_id',
            'desc' => null,
            'filtersort' => 'title',
            'taskdesc' => null,
            'searched' => null,
            'filter_mytask' => 'creator_id',
            'desc_mytask' => null,
            'filtersort_mytask' => 'title',
            'taskdesc_mytask' => null,
            'searched_mytask' => null,
        ]; 
        return view('home')->with('user', $user)->with('data', $data);
    }
}
