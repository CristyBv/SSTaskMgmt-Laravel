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

        // test if filter was ever used in this session

        if(!session()->has('filtred')) {
            session([
                'filtred' => 'default',
                'groupby' => 'user_id',
                'groupdesc' => null,
                'tasksort' => 'title',
                'taskdesc' => null,
                'searched' => null,
                'groupby_mytask' => 'creator_id',
                'groupdesc_mytask' => null,
                'tasksort_mytask' => 'title',
                'taskdesc_mytask' => null,
                'searched_mytask' => null,
            ]);
        }        
        return view('home')->with('user', $user);
    }
}
