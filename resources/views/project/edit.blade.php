@extends('layouts.app')

@section('content')
    <br>
    <a href=" {{ route('projects.index') }}" class="btn btn-secondary">Go back</a>
    <br>
    <h1>Edit a Project</h1>
    {!! Form::open(['action' => ['ProjectsController@update', $project->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{ Form::label('title','Title') }}
            {{ Form::text('title', $project->title, ['class' => 'form-control', 'placeholder' => 'Title']) }}
        </div>
        <div class="form-group">
                {{ Form::label('body','Description') }}
                {{ Form::textarea('body', $project->body, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body Text']) }}
        </div>
        {{ Form::hidden('_method','PUT') }}
        {{ Form::submit('Submit',['class' => 'btn btn-primary']) }}
    {!! Form::close() !!}
    <br>
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script> CKEDITOR.replace( 'article-ckeditor' );</script>
@endsection