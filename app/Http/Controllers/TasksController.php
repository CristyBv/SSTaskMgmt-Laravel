<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Http\Request;
use App\Task;
use App\User;
use App\Project;
use App\History_task;
use App\Task_comment;
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
        //???
        //dd($user[["creationsSort",['desc','users']]]);
        //$contentCreator = $this->createContentBySession($user, 'creator');
        //$contentReceiver = $this->createContentBySession($user, 'receiver');
        
        return view('home')->with('user', $user);
    }

    private function createContentBySession($user, $type) {

        $data = [];

        switch($type) {
            case 'creator':
                array_push($data, [
                    'groupby' => session('groupby'),
                    'groupdesc' => session('groupdesc'),
                    'tasksort' => session('tasksort'),
                    'taskdesc' => session('taskdesc'),
                    ]);
                switch(session('groupby')) {
                    case 'user_id':
                        
                        break;
                    case 'project_id':
        
                        break;
                    case 'priority_id':
        
                        break;
                    case 'status_id':
                    
                        break;
                }
                break;
            case 'receiver':
                array_push($data, [
                    'groupby' => session('groupby_mytask'),
                    'groupdesc' => session('groupdesc_mytask'),
                    'tasksort' => session('tasksort_mytask'),
                    'taskdesc' => session('taskdesc_mytask'),
                    ]);
                switch(session('groupby_mytask')) {
                    case 'creator_id':
        
                        break;
                    case 'project_id':
        
                        break;
                    case 'priority_id':
        
                        break;
                    case 'status_id':
        
                        break;
                }
                break;
        }


    }

    private function grouHtmlTemplate($data) {


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->create_data();

        // if it was never created a task in this session, put in select the first most used

        if(!session()->has('lasttask')) {
            session([
                'task_user' => [key($data['users']) => current($data['users'])],
                'task_project' => [key($data['projects']) => current($data['projects'])],
                'task_status' => [key($data['status']) => current($data['status'])],
                'task_priority' => [key($data['priorities']) => current($data['priorities'])],
                'task_date' => \Carbon\Carbon::now()->toDateString(),
            ]);
        }
        return view('task.create')->with('data', $data);
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
            'user' => 'required',
            'project' => 'required',
            'status' => 'required',
            'priority' => 'required',
            'date' => 'required',
        ]);

        $user = User::find($request->user);
        $project = Project::find($request->project);

        session([
            'lasttask' => 'open',
            'task_user' => [$request->user => $user->name],
            'task_project' => [$request->project => $project->title],
            'task_status' => [$request->status => Config::get('status')[$request->status]],
            'task_priority' => [$request->priority => Config::get('priorities')[$request->priority]],
            'task_date' => $request->date,
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

            $user->count++;
            $user->save();
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
    public function show(Request $request, $id)
    {
        $task = Task::find($id);
        $forwards = $task->history_tasks;
        $comments = $task->comments->sortByDesc('created_at');

        $page = $request->page;
        $perPage = 5;

        $paginator = new Paginator($comments->forPage($page, $perPage), count($comments), $perPage, $page, [
            'path'  => $request->url(),
            'query' => $request->query(),
        ]);

        if(auth()->user()->id == $task->user_id || auth()->user()->id == $task->creator_id)
            return view('task.show')->with('task', $task)->with('history', $forwards)->with('comments', $paginator);
        else return redirect()->route('home')->with('error', 'You have no rights to view that task');
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

        // if the user is not the creator, he will not have total acces

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

        if(auth()->user()->id == $task->user_id || auth()->user()->id == $task->creator_id) {
            $task->delete();
        }
        else return redirect()->route('home')->with('error', 'Unauthorized Page');

        return redirect()->route('home')->with('success', 'Task Removed');
    }

    // creates arrays for select input

    public function create_data(){

        // users and projects are sorted by count field (number of use)

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

        // in DB will be only the IDs of priorities and status
            
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

    // function for setting the inputs from filter in session, the actual filter is in blade

    public function filter(Request $request) {
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
        return redirect()->route('home');
        
    }

    // change the user_id and create a history task row for that

    public function forward(Request $request) {

        $task = Task::find($request->id);
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

    // change the status of a task

    public function changestatus(Request $request) {
        $task = Task::find($request->id);
        $task->status = $request->selectstatus;
        $task->save();
        return redirect()->route('home')->with('success', 'Status Updated');
    }

}
