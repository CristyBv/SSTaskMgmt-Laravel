<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class ProjectsController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::orderBy('title')->paginate(5); //->sortBy('title')->paginate(1);
        $data = [
            'filtersort' => 'title',
            'projectdesc' => null,
            'searched' => null,
        ];
        return view('project.index')->with('projects', $projects)->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('project.create');
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

        $project = new Project;
        $project->title = $request->title;
        $project->body = $request->body;
        $project->user_id = auth()->user()->id;
        $project->save();

        return redirect()->route('projects.index')->with('success', 'Project Created');
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
        $project = Project::find($id);
        if(auth()->user()->id === $project->user_id)
            return view('project.edit')->with('project', $project);
        else return redirect()->route('projects.index')->with('error', 'Unauthorized Page');
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

        $project = Project::find($id);
        $project->title = $request->title;
        $project->body = $request->body;
        $project->save();

        return redirect()->route('projects.index')->with('success', 'Task Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        if(auth()->user()->id === $project->user_id)
            $project->delete();
        else return redirect()->route('projects.index')->with('error', 'Unauthorized Page');

        return redirect()->route('projects.index')->with('success', 'Project Removed');
    }

    public function filter(Request $request) {
        
        if($request->projectdesc != null) {
            if($request->sortproject == 'user_id')
                $projects = Project::join('users', 'user_id', '=', 'users.id')->orderByDesc('users.name')->select('projects.*')->get();
            else $projects = Project::all()->sortByDesc($request->sortproject);
        } else {
            if($request->sortproject == 'user_id')
                $projects = Project::join('users', 'user_id', '=', 'users.id')->orderBy('users.name')->select('projects.*')->get();
            else $projects = Project::all()->sortBy($request->sortproject);
        }

        $page = $request->page;
        $perPage = 5;

        $paginator = new Paginator($projects->forPage($page, $perPage), count($projects), $perPage, $page, [
            'path'  => $request->url(),
            'query' => $request->query(),
        ]);

        $data = [
            'filtersort' => $request->sortproject,
            'projectdesc' => $request->projectdesc,
            'searched' => $request->searchproject,
        ];
        return view('project.index')->with('projects', $paginator)->with('data', $data);
        
    }
}
