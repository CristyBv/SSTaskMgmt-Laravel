@extends('layouts.app')

@section('content')
    <br>
    <a href=" {{ route('home') }}" class="btn btn-secondary">Go back</a>
    <br>
    <h1>Edit a Task</h1>
    {!! Form::open(['action' => ['TasksController@update', $task->id], 'method' => 'POST']) !!}
        <div class="row row-eq-height">
            <div class="col-sm-7">
                    <div class="form-group">
                        {{ Form::label('title','Title') }}
                        {{ Form::text('title', $task->title, ['class' => 'form-control', 'placeholder' => 'Title', 'readonly' => $readonly]) }}
                    </div>        
                    <div class="form-group">
                        {{ Form::label('chooseuser','Choose a User') }}
                        <div class="row funkyradio">
                            @foreach($data['users'] as $id => $name)
                                    <div class="funkyradio-primary col-sm-2">
                                            @if($task->user_id == $id)
                                                    <input type="radio" name="radiouser" value={{$id}} id="{{$id.$name}}" checked onchange="radiochange('radiouser', 'user');"/>
                                            @else
                                                    <input type="radio" name="radiouser" value={{$id}} id="{{$id.$name}}" onchange="radiochange('radiouser', 'user');"/>
                                            @endif                                                        
                                            <label for="{{$id.$name}}" class="">{{$name}}</label>
                                    </div>
                            @endforeach
                        </div>
                        {{ Form::select('user', [$task->user_id => $task->user->name], $task->user_id, ['class' => 'form-control', 'id' => 'user']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('chooseproject','Choose a Project') }}
                        <div class="row funkyradio">
                            @foreach($data['projects'] as $id => $title)
                                <div class="funkyradio-primary col-sm-2">
                                    @if($task->project_id == $id)
                                            <input type="radio" name="radioproject" value={{$id}} id="{{$id.$title}}" checked onchange="radiochange('radioproject', 'project');"/>
                                    @else
                                            <input type="radio" name="radioproject" value={{$id}} id="{{$id.$title}}" onchange="radiochange('radioproject', 'project');"/>
                                    @endif                                                        
                                    <label for="{{$id.$title}}">{{$title}}</label>
                                </div>
                            @endforeach
                        </div>
                        {{ Form::select('project', [$task->project_id => $task->project->title], $task->project_id, ['class' => 'form-control', 'id' => 'project', 'disabled' => $readonly]) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('choosepriority','Choose a Priority') }}
                        <div class="row funkyradio">
                            @foreach($data['priorities'] as $id => $title)
                                <div class="funkyradio-primary col-sm-2">
                                    @if($task->priority == $id)
                                        <input type="radio" name="radiopriority" value={{$id}} id="{{$id.$title}}" checked onchange="radiochange('radiopriority', 'priority');"/>
                                    @else
                                        <input type="radio" name="radiopriority" value={{$id}} id="{{$id.$title}}" onchange="radiochange('radiopriority', 'priority');"/>
                                    @endif                                                        
                                    <label for="{{$id.$title}}">{{$title}}</label>
                                </div>
                            @endforeach
                        </div>
                        {{ Form::select('priority', $data['priorities'], $task->priority, ['class' => 'form-control', 'id' => 'priority', 'disabled' => $readonly]) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('choosestatus','Choose Status') }}
                        <div class="row funkyradio">
                            @foreach($data['status'] as $id => $title)                                        
                                <div class="funkyradio-primary col-sm-2">
                                    @if($task->status == $id)
                                        <input type="radio" name="radiostatus" value={{$id}} id="{{$id.$title}}" checked onchange="radiochange('radiostatus', 'status');"/>
                                    @else
                                        <input type="radio" name="radiostatus" value={{$id}} id="{{$id.$title}}" onchange="radiochange('radiostatus', 'status');"/>
                                    @endif                                                        
                                    <label for="{{$id.$title}}">{{$title}}</label>
                                </div>
                            @endforeach
                        </div>
                        {{ Form::select('status', $data['status'], $task->status, ['class' => 'form-control', 'id' => 'status']) }}
                    </div>
            </div>
            <div class="col-sm-5">
                    <div class="form-group">
                        {{ Form::label('choosedeadline','Choose Deadline') }}
                        <div id='datepicker'></div>
                        {{ Form::hidden('date', $task->deadline, ['id' => 'deadline_date', 'data-last-deadline-date' => $task->deadline, 'readonly' => $readonly]) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('body','Description') }}
                        {{ Form::textarea('body', $task->body, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body Text']) }}
                    </div>
            </div>
        </div>
        {{ Form::hidden('_method','PUT') }}
        {{ Form::submit('Submit',['class' => 'btn btn-primary float-right', 'style' => 'width:100%;']) }}
    {!! Form::close() !!}
    <br>
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script> CKEDITOR.replace( 'article-ckeditor' );</script>
@endsection

@section('scripts')
        <script type="text/javascript">
                var userroute = "{{ route('users.search') }}";
                var projectroute = "{{ route('projects.search') }}";
</script>        
        <script type="text/javascript" src="{{ asset('js/create_task.js') }}"></script>
@endsection