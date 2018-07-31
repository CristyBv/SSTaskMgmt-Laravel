@extends('layouts.app')

@section('content')
    <br>
    <a href=" {{ route('home') }}" class="btn btn-secondary">Go back</a>
    <br>
    <h1>Create a Task</h1>
    {!! Form::open(['action' => 'TasksController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{ Form::label('title','Title') }}
            {{ Form::text('title','',['class' => 'form-control', 'placeholder' => 'Title']) }}
        </div>
        
        <div class="form-group">
                {{ Form::label('chooseuser','Choose a User') }}
                {{ Form::select('user', $data['users'], null, ['class' => 'form-control', 'id' => 'user']) }}
        </div>
        <div class="form-group">
            {{ Form::label('chooseproject','Choose a Project') }}
            {{ Form::select('project', $data['projects'], null, ['class' => 'form-control', 'id' => 'project']) }}
        </div>
        <div class="form-group">
                {{ Form::label('choosepriority','Choose a Priority') }}
                {{ Form::select('priority', $data['priorities'], null, ['class' => 'form-control', 'id' => 'priority']) }}
        </div>
        <div class="form-group">
                {{ Form::label('choosestatus','Choose Status') }}
                {{ Form::select('status', $data['status'], null, ['class' => 'form-control', 'id' => 'status']) }}
        </div>
        <div class="form-group">
                {{ Form::label('choosedeadline','Choose Deadline') }}
                {{ Form::text('date', \Carbon\Carbon::now()->toDateString(), ['class' => 'form-control', 'id' => 'datepicker']) }}
        </div>
        <div class="form-group">
                {{ Form::label('body','Description') }}
                {{ Form::textarea('body','',['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body Text']) }}
        </div>
        {{ Form::submit('Submit',['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}
    <br>
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script> CKEDITOR.replace( 'article-ckeditor' );</script>
@endsection