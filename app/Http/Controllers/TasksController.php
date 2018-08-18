<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Http\Request;
use App\Task;
use App\User;
use App\Project;
use App\HistoryTask;
use App\TaskComment;
use Config;
use DB;

class TasksController extends Controller
{ 
    protected $constants;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth')->except(['index', 'show']);
        $this->middleware('auth');
        $this->constants = Config::get('tasks_controller_const');
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
                'switch_dataTable' => 'on',
            ]);
        }
        $contentCreator = $this->createContentBySession($user, 'creator');        
        $contentReceiver = $this->createContentBySession($user, 'receiver');

        return view('home')->with('user', $user)->with('contentCreator', $contentCreator)->with('contentReceiver', $contentReceiver);
    }

    private function createContentBySession($user, $type) {
        $data = [];
        $consts = $this->constants;
        switch($type) {
            case 'creator':
                $data = [
                    'groupBy' => session('groupby'),
                    'groupDesc' => session('groupdesc'),
                    'taskSort' => session('tasksort'),
                    'taskDesc' => session('taskdesc'),
                    'searched' => session('searched'),
                ];
                switch(session('groupby')) {
                    case 'user_id':
                        $data = $data + [
                            'groupFunction' => 'creationsSort',
                            'groupTable' => $consts['groupsTable']['user'],
                            'groupName' => $consts['groupsName']['user'],
                            'groupField' => $consts['tdsName']['name'],
                            'modelName' => $consts['modelsName']['user'],
                            'modelType' => $consts['modelsType']['db'],
                            'tableThOrder' => [$consts['thsName']['title'], $consts['thsName']['project'], $consts['thsName']['status'], $consts['thsName']['deadline'], $consts['thsName']['priority'], $consts['thsName']['created_at']],
                            'tableTdOrder' => [$consts['tdsName']['title'], $consts['tdsName']['project'], $consts['tdsName']['status'], $consts['tdsName']['deadline'], $consts['tdsName']['priority'], $consts['tdsName']['created_at']],
                        ];
                        break;
                    case 'project_id':
                        $data = $data + [
                            'groupFunction' => 'creationsSort',
                            'groupTable' => $consts['groupsTable']['project'],
                            'groupName' => $consts['groupsName']['project'],
                            'groupField' => $consts['tdsName']['title'],
                            'modelName' => $consts['modelsName']['project'],
                            'modelType' => $consts['modelsType']['db'],
                            'tableThOrder' => [$consts['thsName']['title'], $consts['thsName']['user'], $consts['thsName']['status'], $consts['thsName']['deadline'], $consts['thsName']['priority'], $consts['thsName']['created_at']],
                            'tableTdOrder' => [$consts['tdsName']['title'], $consts['tdsName']['user'], $consts['tdsName']['status'], $consts['tdsName']['deadline'], $consts['tdsName']['priority'], $consts['tdsName']['created_at']],
                        ];
                        break;
                    case 'priority':
                        $data = $data + [
                            'groupFunction' => 'creations',
                            'groupTable' => 'none',
                            'groupName' => $consts['groupsName']['priority'],
                            'groupField' => 'none',
                            'modelName' => $consts['configsName']['priority'],
                            'modelType' => $consts['modelsType']['config'],
                            'tableThOrder' => [$consts['thsName']['title'], $consts['thsName']['user'], $consts['thsName']['project'], $consts['thsName']['status'], $consts['thsName']['deadline'], $consts['thsName']['created_at']],
                            'tableTdOrder' => [$consts['tdsName']['title'], $consts['tdsName']['user'], $consts['tdsName']['project'], $consts['tdsName']['status'], $consts['tdsName']['deadline'], $consts['tdsName']['created_at']],
                        ];
                        break;
                    case 'status':
                        $data = $data + [
                            'groupFunction' => 'creations',
                            'groupTable' => 'none',
                            'groupName' => $consts['groupsName']['status'],
                            'groupField' => 'none',
                            'modelName' => $consts['configsName']['status'],
                            'modelType' => $consts['modelsType']['config'],
                            'tableThOrder' => [$consts['thsName']['title'], $consts['thsName']['user'], $consts['thsName']['project'], $consts['thsName']['deadline'], $consts['thsName']['priority'], $consts['thsName']['created_at']],
                            'tableTdOrder' => [$consts['tdsName']['title'], $consts['tdsName']['user'], $consts['tdsName']['project'], $consts['tdsName']['deadline'], $consts['tdsName']['priority'], $consts['tdsName']['created_at']],
                        ];
                        break;
                }
                return $this->groupHtmlTemplate($data, $user);
                break;                
            case 'receiver':
                $data = [
                    'groupBy' => session('groupby_mytask'),
                    'groupDesc' => session('groupdesc_mytask'),
                    'taskSort' => session('tasksort_mytask'),
                    'taskDesc' => session('taskdesc_mytask'),
                    'searched' => session('searched_mytask'),
                    ];
                switch(session('groupby_mytask')) {
                    case 'creator_id':
                        $data = $data + [
                            'groupFunction' => 'myTasksSort',
                            'groupTable' => $consts['groupsTable']['user'],
                            'groupName' => $consts['groupsName']['creator'],
                            'groupField' => $consts['tdsName']['name'],
                            'modelName' => $consts['modelsName']['user'],
                            'modelType' => $consts['modelsType']['db'],
                            'tableThOrder' => [$consts['thsName']['title'], $consts['thsName']['project'], $consts['thsName']['status'], $consts['thsName']['deadline'], $consts['thsName']['priority'], $consts['thsName']['created_at']],
                            'tableTdOrder' => [$consts['tdsName']['title'], $consts['tdsName']['project'], $consts['tdsName']['status'], $consts['tdsName']['deadline'], $consts['tdsName']['priority'], $consts['tdsName']['created_at']],
                        ];
                        break;
                    case 'project_id':
                        $data = $data + [
                            'groupFunction' => 'myTasksSort',
                            'groupTable' => $consts['groupsTable']['project'],
                            'groupName' => $consts['groupsName']['project'],
                            'groupField' => $consts['tdsName']['title'],
                            'modelName' => $consts['modelsName']['project'],
                            'modelType' => $consts['modelsType']['db'],
                            'tableThOrder' => [$consts['thsName']['title'], $consts['thsName']['creator'], $consts['thsName']['status'], $consts['thsName']['deadline'], $consts['thsName']['priority'], $consts['thsName']['created_at']],
                            'tableTdOrder' => [$consts['tdsName']['title'], $consts['tdsName']['creator'], $consts['tdsName']['status'], $consts['tdsName']['deadline'], $consts['tdsName']['priority'], $consts['tdsName']['created_at']],
                        ];
                        break;
                    case 'priority':
                        $data = $data + [
                            'groupFunction' => 'tasks',
                            'groupTable' => 'none',
                            'groupName' => $consts['groupsName']['priority'],
                            'groupField' => 'none',
                            'modelName' => $consts['configsName']['priority'],
                            'modelType' => $consts['modelsType']['config'],
                            'tableThOrder' => [$consts['thsName']['title'], $consts['thsName']['creator'], $consts['thsName']['project'], $consts['thsName']['status'], $consts['thsName']['deadline'], $consts['thsName']['created_at']],
                            'tableTdOrder' => [$consts['tdsName']['title'], $consts['tdsName']['creator'], $consts['tdsName']['project'], $consts['tdsName']['status'], $consts['tdsName']['deadline'], $consts['tdsName']['created_at']],
                        ];
                        break;
                    case 'status':
                        $data = $data + [
                            'groupFunction' => 'tasks',
                            'groupTable' => 'none',
                            'groupName' => $consts['groupsName']['status'],
                            'groupField' => 'none',
                            'modelName' => $consts['configsName']['status'],
                            'modelType' => $consts['modelsType']['config'],
                            'tableThOrder' => [$consts['thsName']['title'], $consts['thsName']['creator'], $consts['thsName']['project'], $consts['thsName']['deadline'], $consts['thsName']['priority'], $consts['thsName']['created_at']],
                            'tableTdOrder' => [$consts['tdsName']['title'], $consts['tdsName']['creator'], $consts['tdsName']['project'], $consts['tdsName']['deadline'], $consts['tdsName']['priority'], $consts['tdsName']['created_at']],
                        ];
                        break;
                }
                return $this->groupHtmlTemplate($data, $user, true);
                break;
        }
    }

    private function groupHtmlTemplate($data, $user, $myTask = false) {
        $content = "";

        // grouping

        $function = $data['groupFunction'];
        if($data['groupDesc'] != null)
            if($data['groupTable'] != 'none')
                $group = $user->$function('desc', $data['groupTable'])->groupBy($data['groupBy']);
            else $group = $user->$function->sortByDesc($data['groupBy'])->groupBy($data['groupBy']);
        else if($data['groupTable'] != 'none')
                $group = $user->$function('asc', $data['groupTable'])->groupBy($data['groupBy']);
            else $group = $user->$function->sortBy($data['groupBy'])->groupBy($data['groupBy']);
        
        $content.= "<table class='table table-responsive'> <thead> <tr> <th> ". $data['groupName'] . "</th> </tr> </thead>";
        foreach($group as $id => $task) {
   
            // set Group name

            $field = $data['groupField'];
            if($data['modelType'] == 'DB') {
                $model = 'App\\'.$data['modelName'];
                $name = $model::where('id', $id)->first()->$field;
            } else if($data['modelType'] == 'Config') {
                $name = Config::get($data['modelName'])[$id];
            }

            // sort asc/desc
            if($data['taskDesc'] != null) 
                $task_sorted = $task->sortByDesc($data['taskSort']);                       
            else $task_sorted = $task->sortBy($data['taskSort']);       

            // filter by search

            $searched = $data['searched'];
            if($searched != null || $searched != '')
                $task_sorted = $task_sorted->filter(function ($value, $key) use ($searched) {
                    return false !== stristr($value->title, $searched);
            });

            // add th

            $content.= "<tr class='groupRow'> <td> ". $name ." </td> <td> <table class='table table-striped task-table'> <thead> <tr>";
            for($i = 0; $i<count($data['tableThOrder']); $i++) {
                $content.= "<th>". $data['tableThOrder'][$i] ."</th>";
            }
            if($myTask == false) $content.= "<th></th>";
            $content.= "<th></th> </tr> </thead> <tbody>";

            // add td

            foreach($task_sorted as $tsk) {
                if($myTask == false || ($myTask == true && $tsk->status != count(Config::get('status')))) {
                    $content.= "<tr class='taskrow' data-id='" . $tsk->id . "'>";
                    for($i = 0; $i<count($data['tableTdOrder']); $i++) {
                        $content.= "<td>";
                        
                        $field = $data['tableTdOrder'][$i];
                        if($field == 'user' || $field == 'creator')
                            $content.= $tsk->$field->name;
                        else if($field == 'project')
                            $content.= $tsk->$field->title;
                        else if($field == 'status')
                            if($myTask == false) $content.= Config::get($field)[$tsk->status];
                            else $content.= view('task.status_select', ['task' => $tsk])->render();                            
                        else if($field == 'priorities')
                            $content.= Config::get($field)[$tsk->priority];
                        else $content.= $tsk->$field;

                        $content.= "</td>";
                    }
                    if($myTask == false) $content.= "<td>" . view('task.edit_delete_button', ['task' => $tsk])->render() . "</td>";
                    $content.= "<td>" . view('task.popover_content', ['task' => $tsk])->render() . "</td> </tr>";  
                }                          
            }
            $content.= "</tbody> </table> </td> </tr>";
        }
        $content.= "</table>";
        return $content;
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
            'title' => 'required|max:191',
            'body' => 'required',
            'user_id' => 'required',
            'project_id' => 'required',
            'status' => 'required',
            'priority' => 'required',
            'deadline' => 'required',
        ]);

        $user = User::find($request->user_id);
        $project = Project::find($request->project_id);

        session([
            'lasttask' => 'open',
            'task_user' => [$request->user_id => $user->name],
            'task_project' => [$request->project_id => $project->title],
            'task_status' => [$request->status => Config::get('status')[$request->status]],
            'task_priority' => [$request->priority => Config::get('priorities')[$request->priority]],
            'task_date' => $request->deadline,
        ]);

        if($request->status != count(Config::get('status'))) {
            $task = Task::create(array_add($request->all(), 'creator_id', auth()->user()->id));
            HistoryTask::create(['task_id' => $task->id, 'user_id' => $task->user_id, 'forward_by' => $task->creator_id]);
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
    public function show(Request $request, Task $task)
    {
        $forwards = $task->history_tasks()->with('user')->with('forward')->get();
        $comments = $task->comments()->orderBy('created_at', 'desc')->with('user')->paginate(Config::get('comments')['perPage']);
        if(auth()->user()->id == $task->user_id || auth()->user()->id == $task->creator_id)
            return view('task.show')->with('task', $task)->with('history', $forwards)->with('comments', $comments);
        else return redirect()->route('home')->with('error', 'You have no rights to view that task');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
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
    public function update(Request $request, Task $task)
    {
        $this->validate($request, [
            'title' => 'required|max:191',
            'body' => 'required',
        ]);

        $ok = 0;
        if($task->user_id != $request->user_id) $ok = 1;

        $task->update(['user_id' => $request->user_id, 'status' => $request->status]);
        
        if($task->creator_id == auth()->user()->id)
            $task->update($request->all());       

        if($ok == 1)
            HistoryTask::create(['task_id' => $task->id, 'user_id' => $task->user_id, 'forward_by' => auth()->user()->id]);
    
        return redirect()->route('home')->with('success', 'Task Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        if(auth()->user()->id == $task->user_id || auth()->user()->id == $task->creator_id) {
            $task->delete();
        }
        else return redirect()->route('home')->with('error', 'Unauthorized Page');

        return redirect()->route('home')->with('success', 'Task Removed');
    }

    // creates arrays for select input

    public function create_data(){

        // users and projects are sorted by count field (number of use)

        $users = User::withCount('tasks')->orderByDesc('tasks_count')->take(5)->get()->pluck('name','id')->toArray();
        $projects = Project::withCount('tasks')->orderByDesc('tasks_count')->take(5)->get()->pluck('title', 'id')->toArray(); 
        $priorities = Config::get('priorities');        
        $status = Config::get('status');

        $data = [
            'users' => $users,
            'projects' => $projects,
            'priorities' => $priorities,
            'status' => $status,
        ];

        return $data;
    }

    // function for setting the inputs from filter in session

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
            'switch_dataTable' => $request->switchDataTable,
        ]);
        return redirect()->route('home');
        
    }

    // change the user_id and create a history task row for that

    public function forward(Request $request) {

        $task = Task::find($request->id);
        if($task->user_id != $request->forwarduser) {    
            $task->user_id = $request->forwarduser;
            $task->save();
            HistoryTask::create(['task_id' => $task->id, 'user_id' => $task->user_id, 'forward_by' => auth()->user()->id]);
            return redirect()->route('home')->with('success', "Task Forwarded");
        } else {
            return redirect()->route('home')->with('error', "You can't forward to the same user");
        }        
    }

    // live search for tasks

    public function search(Request $request) {

        $searched = $request->search;
        if($searched != '') {
            $authId = auth()->user()->id;
            $tasks = DB::table('tasks')->select('id', 'title as text')->where('title', 'like', '%'.$searched.'%')->where(function($query) use ($authId) {
                $query->where('creator_id', auth()->user()->id)->orWhere('user_id', auth()->user()->id);
            })->get();
            return $tasks;
        }      
    }

    // change the status of a task

    public function changestatus(Request $request, Task $task) {
        $task->status = $request->selectstatus;
        $task->save();
        return redirect()->route('home')->with('success', 'Status Updated');
    }
}
