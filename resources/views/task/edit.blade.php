@extends('layouts.app')

@section('content')
    <br>
    <a href=" {{ route('home') }}" class="btn btn-secondary">Go back</a>
    <br>
    <h1>Edit a Task</h1>
    {!! Form::open(['action' => ['TasksController@update', $task->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{ Form::label('title','Title') }}
            {{ Form::text('title', $task->title, ['class' => 'form-control', 'placeholder' => 'Title', 'readonly' => $readonly]) }}
        </div>        
        <div class="form-group">
            {{ Form::label('chooseuser','Choose a User') }}
            {{ Form::select('user', $data['users'], $task->user_id, ['class' => 'form-control', 'id' => 'user']) }}
        </div>
        <div class="form-group">
            {{ Form::label('chooseproject','Choose a Project') }}
            {{ Form::select('project', $data['projects'], $task->project_id, ['class' => 'form-control', 'id' => 'project', 'disabled' => $readonly]) }}
        </div>
        <div class="form-group">
            {{ Form::label('choosepriority','Choose a Priority') }}
            {{ Form::select('priority', $data['priorities'], $task->priority, ['class' => 'form-control', 'id' => 'priority', 'disabled' => $readonly]) }}
        </div>
        <div class="form-group">
            {{ Form::label('choosestatus','Choose Status') }}
            {{ Form::select('status', $data['status'], $task->status, ['class' => 'form-control', 'id' => 'status']) }}
        </div>
        <div class="form-group">
            {{ Form::label('choosedeadline','Choose Deadline') }}
            {{ Form::date('date', $task->deadline, ['class' => 'form-control', 'readonly' => $readonly]) }}
        </div>
        <div class="form-group">
            {{ Form::label('body','Description') }}
            {{ Form::textarea('body', $task->body, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body Text']) }}
        </div>
        {{ Form::hidden('_method','PUT') }}
        {{ Form::submit('Submit',['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}
    <br>
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script> CKEDITOR.replace( 'article-ckeditor' );</script>
@endsection