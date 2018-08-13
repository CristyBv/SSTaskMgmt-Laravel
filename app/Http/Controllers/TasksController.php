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
    const modelsType = [
        'db' => 'DB',
        'config' => 'Config',
    ];
    const modelsName = [
        'user' => 'User',
        'project' => 'Project',
        'task' => 'Task',
        'history' => 'History_task',
        'comment' => 'Task_comment',
    ];
    const configsName = [
        'status' => 'status',
        'priority' => 'priorities',
    ];
    const groupsName = [
        'user' => 'User',
        'creator' => 'Creator',
        'project' => 'Project',
        'status' => 'Status',
        'priority' => 'Priority',
    ];
    const groupsTable = [
        'user' => 'users',
        'project' => 'projects',
    ];
    const thsName = [
        'title' => 'Title',
        'project' => 'Project',
        'status' => "Status",
        'deadline' => 'Deadline',
        'priority' => "Priority",
        'created_at' => "Created Date",
        'user' => "User",
        'creator' => "Creator",
    ];

    const tdsName = [
        'title' => 'title',
        'project' => 'project',
        'status' => "status",
        'deadline' => 'deadline',
        'priority' => "priorities",
        'created_at' => "created_at",
        'user' => "user",
        'creator' => "creator",
        'name' => 'name',
    ];

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

        $contentCreator = $this->createContentBySession($user, 'creator');        
        $contentReceiver = $this->createContentBySession($user, 'receiver');

        return view('home')->with('user', $user)->with('contentCreator', $contentCreator)->with('contentReceiver', $contentReceiver);
    }

    private function createContentBySession($user, $type) {
        $data = [];
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
                            'groupTable' => self::groupsTable['user'],
                            'groupName' => self::groupsName['user'],
                            'groupField' => self::tdsName['name'],
                            'modelName' => self::modelsName['user'],
                            'modelType' => self::modelsType['db'],
                            'tableThOrder' => [self::thsName['title'], self::thsName['project'], self::thsName['status'], self::thsName['deadline'], self::thsName['priority'], self::thsName['created_at']],
                            'tableTdOrder' => [self::tdsName['title'], self::tdsName['project'], self::tdsName['status'], self::tdsName['deadline'], self::tdsName['priority'], self::tdsName['created_at']],
                        ];
                        break;
                    case 'project_id':
                        $data = $data + [
                            'groupFunction' => 'creationsSort',
                            'groupTable' => self::groupsTable['project'],
                            'groupName' => self::groupsName['project'],
                            'groupField' => self::tdsName['title'],
                            'modelName' => self::modelsName['project'],
                            'modelType' => self::modelsType['db'],
                            'tableThOrder' => [self::thsName['title'], self::thsName['user'], self::thsName['status'], self::thsName['deadline'], self::thsName['priority'], self::thsName['created_at']],
                            'tableTdOrder' => [self::tdsName['title'], self::tdsName['user'], self::tdsName['status'], self::tdsName['deadline'], self::tdsName['priority'], self::tdsName['created_at']],
                        ];
                        break;
                    case 'priority':
                        $data = $data + [
                            'groupFunction' => 'creations',
                            'groupTable' => 'none',
                            'groupName' => self::groupsName['priority'],
                            'groupField' => 'none',
                            'modelName' => self::configsName['priority'],
                            'modelType' => self::modelsType['config'],
                            'tableThOrder' => [self::thsName['title'], self::thsName['user'], self::thsName['project'], self::thsName['status'], self::thsName['deadline'], self::thsName['created_at']],
                            'tableTdOrder' => [self::tdsName['title'], self::tdsName['user'], self::tdsName['project'], self::tdsName['status'], self::tdsName['deadline'], self::tdsName['created_at']],
                        ];
                        break;
                    case 'status':
                        $data = $data + [
                            'groupFunction' => 'creations',
                            'groupTable' => 'none',
                            'groupName' => self::groupsName['status'],
                            'groupField' => 'none',
                            'modelName' => self::configsName['status'],
                            'modelType' => self::modelsType['config'],
                            'tableThOrder' => [self::thsName['title'], self::thsName['user'], self::thsName['project'], self::thsName['deadline'], self::thsName['priority'], self::thsName['created_at']],
                            'tableTdOrder' => [self::tdsName['title'], self::tdsName['user'], self::tdsName['project'], self::tdsName['deadline'], self::tdsName['priority'], self::tdsName['created_at']],
                        ];
                        break;
                }
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
                            'groupTable' => self::groupsTable['user'],
                            'groupName' => self::groupsName['creator'],
                            'groupField' => self::tdsName['name'],
                            'modelName' => self::modelsName['user'],
                            'modelType' => self::modelsType['db'],
                            'tableThOrder' => [self::thsName['title'], self::thsName['project'], self::thsName['status'], self::thsName['deadline'], self::thsName['priority'], self::thsName['created_at']],
                            'tableTdOrder' => [self::tdsName['title'], self::tdsName['project'], self::tdsName['status'], self::tdsName['deadline'], self::tdsName['priority'], self::tdsName['created_at']],
                        ];
                        break;
                    case 'project_id':
                        $data = $data + [
                            'groupFunction' => 'myTasksSort',
                            'groupTable' => self::groupsTable['project'],
                            'groupName' => self::groupsName['project'],
                            'groupField' => self::tdsName['title'],
                            'modelName' => self::modelsName['project'],
                            'modelType' => self::modelsType['db'],
                            'tableThOrder' => [self::thsName['title'], self::thsName['creator'], self::thsName['status'], self::thsName['deadline'], self::thsName['priority'], self::thsName['created_at']],
                            'tableTdOrder' => [self::tdsName['title'], self::tdsName['creator'], self::tdsName['status'], self::tdsName['deadline'], self::tdsName['priority'], self::tdsName['created_at']],
                        ];
                        break;
                    case 'priority':
                        $data = $data + [
                            'groupFunction' => 'tasks',
                            'groupTable' => 'none',
                            'groupName' => self::groupsName['priority'],
                            'groupField' => 'none',
                            'modelName' => self::configsName['priority'],
                            'modelType' => self::modelsType['config'],
                            'tableThOrder' => [self::thsName['title'], self::thsName['creator'], self::thsName['project'], self::thsName['status'], self::thsName['deadline'], self::thsName['created_at']],
                            'tableTdOrder' => [self::tdsName['title'], self::tdsName['creator'], self::tdsName['project'], self::tdsName['status'], self::tdsName['deadline'], self::tdsName['created_at']],
                        ];
                        break;
                    case 'status':
                        $data = $data + [
                            'groupFunction' => 'tasks',
                            'groupTable' => 'none',
                            'groupName' => self::groupsName['status'],
                            'groupField' => 'none',
                            'modelName' => self::configsName['status'],
                            'modelType' => self::modelsType['config'],
                            'tableThOrder' => [self::thsName['title'], self::thsName['creator'], self::thsName['project'], self::thsName['deadline'], self::thsName['priority'], self::thsName['created_at']],
                            'tableTdOrder' => [self::tdsName['title'], self::tdsName['creator'], self::tdsName['project'], self::tdsName['deadline'], self::tdsName['priority'], self::tdsName['created_at']],
                        ];
                        break;
                }
                break;
        }
        return $this->groupHtmlTemplate($data, $user);

    }

    private function groupHtmlTemplate($data, $user) {
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
        
        $content.= "<thead> <tr> <th> ". $data['groupName'] . "</th> </tr> </thead>";
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

            $content.= "<tr> <td> ". $name ." </td> <td> <table class='table table-striped task-table'> <thead> <tr>";
            for($i = 0; $i<count($data['tableThOrder']); $i++) {
                $content.= "<th>". $data['tableThOrder'][$i] ."</th>";
            }

            $content.= "<th></th> <th></th> </tr> </thead> <tbody>";

            // add td

            foreach($task_sorted as $tsk) {
                $content.= "<tr class='taskrow' data-id='" . $tsk->id . "'>";
                for($i = 0; $i<count($data['tableTdOrder']); $i++) {
                    $content.= "<td>";
                    
                    $field = $data['tableTdOrder'][$i];
                    if($field == 'user' || $field == 'creator') 
                        $content.= $tsk->$field->name;
                    else if($field == 'project')
                        $content.= $tsk->$field->title;
                    else if($field == 'status')
                        $content.= Config::get($field)[$tsk->status];
                    else if($field == 'priorities')
                        $content.= Config::get($field)[$tsk->priority];
                    else $content.= $tsk->$field;

                    $content.= "</td>";
                }
                $content.= "<td>" . view('task.edit_delete_button', ['item' => $tsk])->render() . "</td>";
                $content.= "<td>" . view('task.popover_content', ['item' => $tsk])->render() . "</td> </tr>";            
            }
            $content.= "</tbody> </table> </td> </tr>";         
        }
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
