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

        return view('task.create')->with('data',$data);
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

        if($request->status != count(Config::get('status'))) {
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

            $user = User::find($request->user);
            $user->count++;
            $user->save();
            $project = Project::find($request->project);
            $project->count++;
            $project->save();

            return redirect()->route('tasks.create')->with('success', 'Task Created');
        } else {
            return redirect()->route('tasks.create')->with('error', 'You can not create a task with an end status');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        $forwards = $task->history_tasks;
        return view('task.show')->with('task', $task)->with('history', $forwards);
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
        if(auth()->user()->id == $task->user_id && auth()->user()->id != $task->creator_id) $readonly = true;
        else $readonly = false;
        if(auth()->user()->id === $task->user_id || auth()->user()->id === $task->creator_id)
            return view('task.edit')->with('task', $task)->with('data', $data)->with('readonly', $readonly);
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
        if($task->user_id != $request->user) $ok = 1;
        $task->user_id = $request->user;
        $task->status = $request->status;
        $task->body = $request->body;
        
        if($task->creator_id == auth()->user()->id) {
            $task->title = $request->title;
            $task->creator_id = $request->user()->id;
            $task->project_id = $request->project;
            $task->priority = $request->priority;
            $task->deadline = $request->date;
        }
        $task->save();

        if($ok == 1) {
            $history = New History_task;
            $history->task_id = $task->id;
            $history->user_id = $task->user_id;
            $history->forward_by = $request->user()->id;
            $history->save();
        }
        
        // $nrstatus = count(Config::get('status'));
        // if($request->status == $nrstatus)
        //     $task->delete();   

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
        $users_count = User::orderByDesc('count')->take(5)->get();
        foreach($users_count as $user) {
            $var = [$user->id => $user->name];
            $users = $users + $var;
        }

        $projects = array();
        $projects_count = Project::orderByDesc('count')->take(5)->get();
        foreach($projects_count as $proj) {
            $var = [$proj->id => $proj->title];
            $projects = $projects + $var;
        }
            
        $priorities = array();
        foreach(Config::get('priorities') as $id => $pri) {
            $var = [$id => $pri];
            $priorities = $priorities + $var;
        }

        $status = array();
        foreach(Config::get('status') as $id => $stat) {
            $var = [$id => $stat];
            $status = $status + $var;
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
        session([
            'filtred' => 'used',
            'groupby' => $request->group,
            'groupdesc' => $request->groupdesc,
            'tasksort' => $request->sorttask,
            'taskdesc' => $request->taskdesc,
            'searched' => $request->searchtask,
            'groupby_mytask' => $request->group_mytask,
            'groupdesc_mytask' => $request->groupdesc_mytask,
            'tasksort_mytask' => $request->sorttask_mytask,
            'taskdesc_mytask' => $request->taskdesc_mytask,
            'searched_mytask' => $request->searchtask_mytask,
        ]);
        return view('home')->with('user', $user);
        
    }

    public function forward(Request $request) {
        $task = Task::find($request->id);
        //dd($task->user_id . " " . $request->forwarduser);
        if($task->user_id != $request->forwarduser) {    
            $task->user_id = $request->forwarduser;
            $task->save();
            $history = New History_task;
            $history->task_id = $task->id;
            $history->user_id = $task->user_id;
            $history->forward_by = $request->user()->id;
            $history->save();
            return redirect()->route('home')->with('success', "Task Forwarded");
        } else {
            return redirect()->route('home')->with('error', "You can't forward to the same user");
        }
        
    }

}
