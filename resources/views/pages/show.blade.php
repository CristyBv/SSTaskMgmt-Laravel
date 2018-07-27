@extends('layouts.app')

@section('content')
    <br>
    <a href=" {{ route('home') }}" class="btn btn-secondary">Go back</a>
    <div class='card'>
        <div class='card-header'>
            <p class='h3 float-left'>{{ $task->title }}</p>
            <p class='h3 float-right'>History</p>
        </div>
        <div class='card-body'>
            <div class='row'>
                <div class='col-sm-8'>
                    {!! $task->body !!}
                    <hr>
                    <small>Written on {{ $task->created_at }} by {{ $task->creator->name }}</small>
                    <br>
                    <small>Project: {{ $task->project->title }} written on {{ $task->project->created_at }} by {{ $task->project->user->name }}</small>
                    <br>
                    <small>Priority: {{ Config::get('priorities')[$task->priority] }}</small>
                    <br>
                    <small>Status: {{ Config::get('status')[$task->status] }}</small>
                    <br>
                    <small>Deadline: {{ $task->deadline }}</small>
                    <br>
                    <small>Last update: {{ $task->updated_at }}</small>
                </div>
                <div class='col-sm-4'>
                    @foreach($history as $forwards)
                        <p> {{ $forwards->forward->name }} forward to {{ $forwards->user->name }} on {{ $forwards->created_at }} </p>
                    @endforeach
                </div>
            </div>
            
        </div>
    </div>
        @if(!Auth::guest())
            @if(Auth::user()->id == $task->user_id || Auth::user()->id == $task->creator_id)
                <a href="{{ route('tasks.edit', ['id'=> $task->id]) }}" class='btn btn-secondary float-left mr-3'>Edit</a>
                {!! Form::open(['action' => ['TasksController@destroy', $task->id], 'method' => 'POST', 'onsubmit' => 'return ConfirmDelete()']) !!}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                {!! Form::close() !!}
            @endif
        @endif
@endsection