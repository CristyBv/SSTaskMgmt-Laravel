@extends('layouts.app')

@section('content')
    <div class="card dropdown">
        <div class="card-header dropdown-toggle border border-secondary" onclick="show('filterbody')">Filter</div>
        <div class="card-body" id="filterbody">
            {!! Form::open(['action' => ['ProjectsController@filter'], 'method' => 'GET', 'id' => 'projectform']) !!}
                {{ Form::label('sortproject','Sorteaza proiectele dupa: ') }}
                <div class="form-group">
                    {{ Form::radio('sortproject', 'title', (session('projectsort') == 'title'), ['id' => 'sortproject-0']) }}
                    {{ Form::label('sortproject-0','title') }}
                    {{ Form::radio('sortproject', 'created_at', (session('projectsort') == 'created_at'), ['id' => 'sortproject-2']) }}
                    {{ Form::label('sortproject-2','created_date') }}
                    {{ Form::radio('sortproject', 'user_id', (session('projectsort') == 'user_id'), ['id' => 'sortproject-3']) }}
                    {{ Form::label('sortproject-3','Creator') }}
                    {{ Form::checkbox('projectdesc', 'desc', (session('projectdesc') != null), ['class' => 'ml-3', 'id' => 'projectdesc']) }}
                    {{ Form::label('projectdesc','desc') }}
                </div>
                <div class="form-group">
                    {{ Form::text('searchproject', session('projectsearch'), ['id' => 'searchproject', 'placeholder' => 'Search Projects', 'class' => 'searchinput']) }}
                </div>
                {{ Form::submit('Filter', ['class' => 'btn btn-secondary']) }}                                             
            {!! Form::close() !!}
        </div>
    </div>
    <div class="card dropdown">
        <div class="card-header dropdown-toggle border border-secondary" onclick="show('projectsbody')">Projects</div>
        <div class="card-body" id="projectsbody">
            <a href="{{ route('projects.create') }}" class="btn btn-primary">Create Project</a>
            <hr>
            @if(count($projects) > 0)
                @foreach($projects as $project)
                    @if(session('projectsearch') == null || session('projectsearch') == '' || strpos(strtolower($project->title), strtolower(session('projectsearch'))) !== false) 
                    <div class="card border-2 border-info">
                        <div class="card-header ">{{ $project->title }}</div>
                        <div class="card-body">{!! $project->body !!}</div>
                        <div class="card-footer">
                            Created by {{ $project->user->name }} on {{ $project->created_at }}
                            <hr>
                            @if(!Auth::guest())
                                @if(Auth::user()->id == $project->user_id)
                                @include('project.edit_button', ['item' => $project])
                                @include('project.delete_button', ['item' => $project])
                                @endif
                            @endif
                        </div>
                    </div>
                    <br>
                    @endif
                @endforeach
                {{ $projects->links() }}
            @else
                <p>There are no projects!</p>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">

        function ConfirmDelete() {
                    if (confirm("Are you sure you want to delete?")) return true;
                    else return false;
                }

        function show(id) {
            if ($("#" + id).length) {
                if ($("#" + id).css('display') == 'none')
                    $("#" + id).css('display', 'block');
                else $("#" + id).css('display', 'none');
            }
        }
        var userroute = "{{ route('users.search') }}";
        var taskshow = "{{ route('tasks.show',':id') }}";
    </script>        
    <script type="text/javascript" src="{{ asset('js/project.js') }}"></script>
@endsection
