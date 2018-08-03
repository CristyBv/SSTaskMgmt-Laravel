@extends('layouts.app')

@section('content')
    <br>
    <a href=" {{ route('home') }}" class="btn btn-secondary">Go back</a>
    <br>
    <h1>Create a Task</h1>
    {!! Form::open(['action' => 'TasksController@store', 'method' => 'POST']) !!}
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
                                                        <input type="radio" name="radiouser" value={{$id}} id="{{$id.$name}}" onchange="radiochange('radiouser', 'user');"/>
                                                        <label for="{{$id.$name}}" class="">{{$name}}</label>
                                                </div>
                                        @endforeach
                                </div>
                                {{ Form::select('user', [], null, ['class' => 'form-control', 'id' => 'user']) }}
                        </div>
                        <div class="form-group">
                                {{ Form::label('chooseproject','Choose a Project') }}
                                <div class="row funkyradio">
                                        @foreach($data['projects'] as $id => $title)
                                                <div class="funkyradio-primary col-sm-2">
                                                        <input type="radio" name="radioproject" value={{$id}} id="{{$id.$title}}" onchange="radiochange('radioproject', 'project');"/>
                                                        <label for="{{$id.$title}}">{{$title}}</label>
                                                </div>
                                        @endforeach
                                </div>
                                {{ Form::select('project', [], null, ['class' => 'form-control', 'id' => 'project']) }}
                        </div>
                        <div class="form-group">
                                {{ Form::label('choosepriority','Choose a Priority') }}
                                <div class="row funkyradio">
                                        @foreach($data['priorities'] as $id => $title)
                                                <div class="funkyradio-primary col-sm-2">
                                                        <input type="radio" name="radiopriority" value={{$id}} id="{{$id.$title}}" onchange="radiochange('radiopriority', 'priority');"/>
                                                        <label for="{{$id.$title}}">{{$title}}</label>
                                                </div>
                                        @endforeach
                                </div>
                                {{ Form::select('priority', $data['priorities'], null, ['class' => 'form-control', 'id' => 'priority']) }}
                        </div>
                        <div class="form-group">
                                {{ Form::label('choosestatus','Choose Status') }}
                                <div class="row funkyradio">
                                        @foreach($data['status'] as $id => $title)
                                                <div class="funkyradio-primary col-sm-2">
                                                        <input type="radio" name="radiostatus" value={{$id}} id="{{$id.$title}}" onchange="radiochange('radiostatus', 'status');"/>
                                                        <label for="{{$id.$title}}">{{$title}}</label>
                                                </div>
                                        @endforeach
                                </div>
                                {{ Form::select('status', $data['status'], null, ['class' => 'form-control', 'id' => 'status']) }}
                        </div>
                </div>
                <div class="col-sm-5">                        
                        <div class="form-group">
                                {{ Form::label('choosedeadline','Choose Deadline') }}
                                <div id='datepicker'></div>
                                {{ Form::hidden('date', \Carbon\Carbon::now()->toDateString()) }}                                                           
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
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script> CKEDITOR.replace( 'article-ckeditor' );</script>
@endsection