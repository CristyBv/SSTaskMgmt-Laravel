@extends('layouts.app')



@section('content')
    <br>
    <a href=" {{ route('home') }}" class="btn btn-secondary">Go back</a>
    <br>
    <h1>Create a Task</h1>
    {!! Form::open(['route' => 'tasks.store', 'method' => 'POST', 'id' => 'createform']) !!}
        <div class="row row-eq-height   ">
                <div class="col-sm-7">
                        <div class="form-group">
                                {{ Form::label('title','Title') }}
                                {{ Form::text('title','',['class' => 'form-control', 'placeholder' => 'Title']) }}
                        </div>        
                        <div class="form-group">
                                {{ Form::label('chooseuser','Choose a User') }}
                                <div class="row funkyradio">
                                        @foreach($data['users'] as $id => $name)
                                                <div class="funkyradio-primary col-sm-2">
                                                        @if(key(session('task_user')) == $id)
                                                                <input type="radio" name="radiouser" value={{$id}} id="{{$id.$name}}" checked onchange="radiochange('radiouser', 'user');"/>
                                                        @else
                                                                <input type="radio" name="radiouser" value={{$id}} id="{{$id.$name}}" onchange="radiochange('radiouser', 'user');"/>
                                                        @endif                                                        
                                                        <label for="{{$id.$name}}" class="">{{$name}}</label>
                                                </div>
                                        @endforeach
                                </div>
                                {{ Form::select('user_id', session('task_user'), null, ['class' => 'form-control', 'id' => 'user']) }}
                        </div>
                        <div class="form-group">
                                {{ Form::label('chooseproject','Choose a Project') }}
                                <div class="row funkyradio">
                                        @foreach($data['projects'] as $id => $title)
                                                <div class="funkyradio-primary col-sm-2">
                                                        @if(key(session('task_project')) == $id)
                                                                <input type="radio" name="radioproject" value={{$id}} id="{{$id.$title}}" checked onchange="radiochange('radioproject', 'project');"/>
                                                        @else
                                                                <input type="radio" name="radioproject" value={{$id}} id="{{$id.$title}}" onchange="radiochange('radioproject', 'project');"/>
                                                        @endif                                                        
                                                        <label for="{{$id.$title}}">{{$title}}</label>
                                                </div>
                                        @endforeach
                                </div>
                                {{ Form::select('project_id', session('task_project'), null, ['class' => 'form-control', 'id' => 'project']) }}
                        </div>
                        <div class="form-group">
                                {{ Form::label('choosepriority','Choose a Priority') }}
                                <div class="row funkyradio">
                                        @foreach($data['priorities'] as $id => $title)
                                                <div class="funkyradio-primary col-sm-2">
                                                        @if(key(session('task_priority')) == $id)
                                                                <input type="radio" name="radiopriority" value={{$id}} id="{{$id.$title}}" checked onchange="radiochange('radiopriority', 'priority');"/>
                                                        @else
                                                                <input type="radio" name="radiopriority" value={{$id}} id="{{$id.$title}}" onchange="radiochange('radiopriority', 'priority');"/>
                                                        @endif                                                        
                                                        <label for="{{$id.$title}}">{{$title}}</label>
                                                </div>
                                        @endforeach
                                </div>
                                {{ Form::select('priority', session('task_priority'), null, ['class' => 'form-control', 'id' => 'priority']) }}
                        </div>
                        <div class="form-group">
                                {{ Form::label('choosestatus','Choose Status') }}
                                <div class="row funkyradio">
                                        @foreach($data['status'] as $id => $title)                                        
                                                <div class="funkyradio-primary col-sm-2">
                                                        @if(key(session('task_status')) == $id)
                                                                <input type="radio" name="radiostatus" value={{$id}} id="{{$id.$title}}" checked onchange="radiochange('radiostatus', 'status');"/>
                                                        @else
                                                                <input type="radio" name="radiostatus" value={{$id}} id="{{$id.$title}}" onchange="radiochange('radiostatus', 'status');"/>
                                                        @endif                                                        
                                                        <label for="{{$id.$title}}">{{$title}}</label>
                                                </div>
                                        @endforeach
                                </div>
                                {{ Form::select('status', session('task_status'), null, ['class' => 'form-control', 'id' => 'status']) }}
                        </div>
                </div>
                <div class="col-sm-5">                        
                        <div class="form-group">
                                {{ Form::label('choosedeadline','Choose Deadline') }}
                                <div id='datepicker'></div>
                                {{ Form::hidden('deadline', session('task_date'), ['id' => 'deadline_date', 'data-last-deadline-date' => session('task_date')]) }}                                                           
                        </div>
                        <div class="form-group">
                                {{ Form::label('body','Description') }}
                                {{ Form::textarea('body','',['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body Text', 'rows' => '5']) }}
                        </div>                      
                </div>                             
        </div>              
        {{ Form::submit('Submit',['class' => 'btn btn-primary float-right', 'style' => 'width:100%;']) }}
    {!! Form::close() !!}
    <br>
    
@endsection

@section('scripts')
        <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
        <script> CKEDITOR.replace( 'article-ckeditor' );</script>
        <script type="text/javascript">
                var userroute = "{{ route('users.search') }}";
                var projectroute = "{{ route('projects.search') }}";
</script>        
        <script type="text/javascript" src="{{ asset('js/create_task.js') }}"></script>
@endsection