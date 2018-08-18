<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use DB;
use Config;

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
        // test if filter was ever used in this session

        if(!session()->has('filtredproject')) {
            session([
                'filtredproject' => 'default',
                'projectsort' => 'title',
                'projectdesc' => null,
                'projectsearch' => null,
            ]);
            $projects = Project::orderBy('title')->paginate(Config::get('projects')['perPage']);
        } else {
            $projectsort = session('projectsort');        
            if(session('projectdesc') != null) {
                if($projectsort == 'user_id')
                    $projects = Project::join('users', 'user_id', '=', 'users.id')->orderByDesc('users.name')->select('projects.*')->with('user');
                else $projects = Project::orderBy($projectsort, 'desc')->with('user');
            } else {
                if($projectsort == 'user_id')
                    $projects = Project::join('users', 'user_id', '=', 'users.id')->orderBy('users.name')->select('projects.*')->with('user');
                else $projects = Project::orderBy($projectsort)->with('user');
            }

            if(session('projectsearch'))
                $projects = $projects->where('title', 'like', '%'.$request->searchproject.'%');

            $projects = $projects->paginate(Config::get('projects')['perPage']);          
        } 
        
        return view('project.index')->with('projects', $projects);
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
        ]);

        Project::create(array_add($request->all(), 'user_id', auth()->user()->id));        
        return redirect()->route('projects.index')->with('success', 'Project Created');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $this->validate($request, [
            'title' => 'required|max:191',
            'body' => 'required',
        ]);

        $project->update($request->all());
        return redirect()->route('projects.index')->with('success', 'Task Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if(auth()->user()->id === $project->user_id)
            $project->delete();
        else return redirect()->route('projects.index')->with('error', 'Unauthorized Page');

        return redirect()->route('projects.index')->with('success', 'Project Removed');
    }

    public function filter(Request $request) {
       session([
            'filtredproject' => 'used',
            'projectsort' => $request->sortproject,
            'projectdesc' => $request->projectdesc,
            'projectsearch' => $request->searchproject,
        ]);
        return redirect()->route('projects.index');        
    }

    // function for ajax request to search

    public function search(Request $request) {
        $searched = $request->search;
        if($searched != '') {
            $projects = DB::table('projects')->select('id', 'title as text')->where('title', 'like', '%'.$searched.'%')->get();
            return $projects;
        }      
    }
}
