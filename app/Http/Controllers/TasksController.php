<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\User;
use App\Project;
use App\History_task;
use Config;
use DB;

class TasksController extends Controller
{ 

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth')->except(['index', 'show']);
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->create_data();

        return view('pages.create_task')->with('data',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        $task = new Task;
        $task->title = $request->title;
        $task->body = $request->body;
        $task->creator_id = $request->user()->id;
        $task->user_id = $request->user;
        $task->project_id = $request->project;
        $task->status = $request->status;
        $task->priority = $request->priority;
        $task->deadline = $request->date;
        $task->save();

        $history = New History_task;
        $history->task_id = $task->id;
        $history->user_id = $task->user_id;
        $history->forward_by = $task->creator_id;
        $history->save();

        return redirect()->route('home')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        $data = $this->create_data();
        if(auth()->user()->id === $task->user_id || auth()->user()->id === $task->creator_id)
            return view('pages.edit_task')->with('task', $task)->with('data', $data);
        else return redirect()->route('home')->with('error', 'Unauthorized Page');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        $ok = 0;
        $task = Task::find($id);
        if($task->user_id !== $request->user) $ok = 1;
        $task->title = $request->title;
        $task->body = $request->body;
        $task->creator_id = $request->user()->id;
        $task->user_id = $request->user;
        $task->project_id = $request->project;
        $task->status = $request->status;
        $task->priority = $request->priority;
        $task->deadline = $request->date;
        $task->save();

        if($ok == 1) {
            $history = New History_task;
            $history->task_id = $task->id;
            $history->user_id = $task->user_id;
            $history->forward_by = $request->user()->id;
            $history->save();
        }
        
        return redirect()->route('home')->with('success', 'Task Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        if(auth()->user()->id === $task->user_id || auth()->user()->id === $task->creator_id) {
            $task->delete();
        }
        else return redirect()->route('home')->with('error', 'Unauthorized Page');

        return redirect()->route('home')->with('success', 'Task Removed');
    }

    public function create_data(){
        $users = array();
        foreach(User::all() as $user){
            $var = [$user->id => $user->name];
            $users = $users + $var;
        }

        $projects = array();
        foreach(Project::all() as $proj){
            $var = [$proj->id => $proj->title . ' --- created by ' . User::where('id', $proj->user_id)->first()->name];
            $projects = $projects + $var;
        }
            
        $priorities = array();
        foreach(Config::get('priorities') as $pri){
            $var = [$pri => $pri];
            $priorities = array_merge($priorities, $var);
        }

        $status = array();
        foreach(Config::get('status') as $stat){
            $var = [$stat => $stat];
            $status = array_merge($status, $var);
        }

        $data = [
            'users' => $users,
            'projects' => $projects,
            'priorities' => $priorities,
            'status' => $status,
        ];

        return $data;
    }

    public function filter(Request $request) {

        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $group = $request->group;
        $data = [
            'filter' => $group,
            'desc' => $request->groupdesc,
            'filtersort' => $request->sorttask,
            'taskdesc' => $request->taskdesc,
            'searched' => $request->searchtask,
        ];   
        return view('home')->with('user', $user)->with('data', $data);
        
    }
}
