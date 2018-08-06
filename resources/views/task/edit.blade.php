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
                        {{ Form::select('user', [$task->user_id => $task->user->name], $task->user_id, ['class' => 'form-control', 'id' => 'user']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('chooseproject','Choose a Project') }}
                        {{ Form::select('project', [$task->project_id => $task->project->title], $task->project_id, ['class' => 'form-control', 'id' => 'project', 'disabled' => $readonly]) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('choosepriority','Choose a Priority') }}
                        {{ Form::select('priority', $data['priorities'], $task->priority, ['class' => 'form-control', 'id' => 'priority', 'disabled' => $readonly]) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('choosestatus','Choose Status') }}
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