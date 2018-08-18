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
                    {{ Form::label('sortproject-3','creator') }}
                    {{ Form::checkbox('projectdesc', 'desc', (session('projectdesc') != null), ['class' => 'ml-3', 'id' => 'projectdesc']) }}
                    {{ Form::label('projectdesc','desc') }}
                </div>
                <div class="form-group">
                    {{ Form::text('searchproject', session('projectsearch'), ['id' => 'searchproject', 'placeholder' => 'Search Projects', 'class' => 'searchinput form-control']) }}
                </div>
                {{ Form::submit('Filter', ['class' => 'btn btn-secondary']) }}                                             
            {!! Form::close() !!}
        </div>
    </div>
    <div class="card dropdown">
        <div class="card-header dropdown-toggle border border-secondary" onclick="show('projectsbody')">Projects</div>
        <div class="card-body" id="projectsbody">
            <div class="dropdown">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    Create Project
                </button>
                <div class="dropdown-menu">
                    {!! Form::open(['action' => 'ProjectsController@store', 'method' => 'POST']) !!}
                        <div class="form-group">
                            {{ Form::text('title','',['class' => 'form-control', 'placeholder' => 'Title']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::textarea('body','',['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body Text']) }}
                        </div>
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary', 'style' => 'width:100%;']) }}
                    {!! Form::close() !!}
                </div>
            </div>
            <hr>
            @if(count($projects) > 0)          
                @foreach($projects as $project)                
                    <div class="card border-2 border-info">
                        <div class="card-header ">{{ $project->title }}</div>
                        <div class="card-body">{!! $project->body !!}</div>
                        <div class="card-footer">
                            Created by {{ $project->user->name }} on {{ $project->created_at }}
                            <hr>
                                @if(Auth::user()->id == $project->user_id)
                                @include('project.delete_button')
                                <div class="dropup">
                                    <button type="button" class="btn btn-secondary dropdown-toggle float-right mr-3" data-toggle="dropdown">
                                        Edit
                                    </button>
                                    <div class="dropdown-menu">
                                        {!! Form::open(['route' => ['projects.update', $project], 'method' => 'POST']) !!}
                                            <div class="form-group">
                                                {{ Form::text('title', $project->title, ['class' => 'form-control', 'placeholder' => 'Title']) }}
                                            </div>
                                            <div class="form-group">
                                                    {{ Form::textarea('body', $project->body, ['id' => 'article-ckeditor'.$project->id, 'class' => 'form-control', 'placeholder' => 'Body Text']) }}
                                            </div>
                                            {{ Form::hidden('_method','PUT') }}
                                            {{ Form::submit('Submit',['class' => 'btn btn-primary']) }}
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                                @endif
                        </div>
                    </div>
                    <br>
                @endforeach
                {{ $projects->links() }}
            @else
                <p>There are no projects!</p>
            @endif
        </div>
    </div>
@endsection

@section('scripts')

    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        
        // on event

        $('textarea').each(function() {
            CKEDITOR.replace( $(this).attr('id') );
        });

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
</script>        
    <script type="text/javascript" src="{{ asset('js/project.js') }}"></script>
@endsection

@section('css')
    <style>
        .dropdown-menu {
            padding: 1em;
        }    
    </style>
@endsection