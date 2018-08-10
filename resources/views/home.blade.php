<?php
    use \App\User;
    use \App\Task;
    use \App\Project;
?>
@extends('layouts.app')

@section('content')
    <div class="card dropdown">
            <div class="card-header dropdown-toggle border border-secondary" onclick="show('filterbody')">Filter</div>
            <div class="card-body" id="filterbody">
                <div class="row">
                    <div class="col-sm">
                        {!! Form::open(['action' => ['TasksController@filter'], 'method' => 'GET', 'id' => 'filterform']) !!}
                        {{ Form::label('group','Grupeaza dupa: ') }}
                        <div class="form-group">
                            {{ Form::radio('group', 'user_id', (session('groupby') == 'user_id'), ['id' => 'group-0']) }}
                            {{ Form::label('group-0','User') }}
                            {{ Form::radio('group', 'project_id', (session('groupby') == 'project_id'), ['id' => 'group-1']) }}
                            {{ Form::label('group-1','Project') }}
                            {{ Form::radio('group', 'priority', (session('groupby') == 'priority'), ['id' => 'group-2']) }}
                            {{ Form::label('group-2','Priority') }}
                            {{ Form::radio('group', 'status', (session('groupby') == 'status'), ['id' => 'group-3']) }}
                            {{ Form::label('group-3','Status') }}
                            {{ Form::checkbox('groupdesc', 'desc', (session('groupdesc') != null), ['class' => 'ml-3', 'id' => 'groupdesc']) }}
                            {{ Form::label('groupdesc','desc') }}
                        </div>
                        {{ Form::label('sorttask','Sorteaza task-urile dupa: ') }}
                        <div class="form-group">
                            {{ Form::radio('sorttask', 'title', (session('tasksort') == 'title'), ['id' => 'sorttask-0']) }}
                            {{ Form::label('sorttask-0','title') }}
                            {{ Form::radio('sorttask', 'deadline', (session('tasksort') == 'deadline'), ['id' => 'sorttask-1']) }}
                            {{ Form::label('sorttask-1','deadline') }}
                            {{ Form::radio('sorttask', 'created_at', (session('tasksort') == 'created_at'), ['id' => 'sorttask-2']) }}
                            {{ Form::label('sorttask-2','created_date') }}
                            {{ Form::radio('sorttask', 'status', (session('tasksort') == 'status'), ['id' => 'sorttask-3']) }}
                            {{ Form::label('sorttask-3','status') }}
                            {{ Form::radio('sorttask', 'priority', (session('tasksort') == 'priority'), ['id' => 'sorttask-4']) }}
                            {{ Form::label('sorttask-4','priority') }}
                            {{ Form::checkbox('taskdesc', 'desc', (session('taskdesc') != null), ['class' => 'ml-3', 'id' => 'taskdesc']) }}
                            {{ Form::label('taskdesc','desc') }}
                        </div>
                        <div class="form-group">
                            {{ Form::text('searchtask', session('searched'), ['id' => 'searchtask', 'placeholder' => 'Search Tasks', 'class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="col-sm">
                        {{ Form::label('group','Grupeaza dupa: ') }}
                        <div class="form-group">
                            {{ Form::radio('group_mytask', 'creator_id', (session('groupby_mytask') == 'creator_id'), ['id' => 'group-0_mytask']) }}
                            {{ Form::label('group-0_mytask','Creator') }}
                            {{ Form::radio('group_mytask', 'project_id', (session('groupby_mytask') == 'project_id'), ['id' => 'group-1_mytask']) }}
                            {{ Form::label('group-1_mytask','Project') }}
                            {{ Form::radio('group_mytask', 'priority', (session('groupby_mytask') == 'priority'), ['id' => 'group-2_mytask']) }}
                            {{ Form::label('group-2_mytask','Priority') }}
                            {{ Form::radio('group_mytask', 'status', (session('groupby_mytask')== 'status'), ['id' => 'group-3_mytask']) }}
                            {{ Form::label('group-3_mytask','Status') }}
                            {{ Form::checkbox('groupdesc_mytask', 'desc', (session('groupdesc_mytask') != null), ['class' => 'ml-3', 'id' => 'groupdesc_mytask']) }}
                            {{ Form::label('groupdesc_mytask','desc') }}
                        </div>
                        {{ Form::label('sorttask','Sorteaza task-urile dupa: ') }}
                        <div class="form-group">
                            {{ Form::radio('sorttask_mytask', 'title', (session('tasksort_mytask') == 'title'), ['id' => 'sorttask-0_mytask']) }}
                            {{ Form::label('sorttask-0_mytask','title') }}
                            {{ Form::radio('sorttask_mytask', 'deadline', (session('tasksort_mytask') == 'deadline'), ['id' => 'sorttask-1_mytask']) }}
                            {{ Form::label('sorttask-1_mytask','deadline') }}
                            {{ Form::radio('sorttask_mytask', 'created_at', (session('tasksort_mytask') == 'created_at'), ['id' => 'sorttask-2_mytask']) }}
                            {{ Form::label('sorttask-2_mytask','created_date') }}
                            {{ Form::radio('sorttask_mytask', 'status', (session('tasksort_mytask') == 'status'), ['id' => 'sorttask-3_mytask']) }}
                            {{ Form::label('sorttask-3_mytask','status') }}
                            {{ Form::radio('sorttask_mytask', 'priority', (session('tasksort_mytask') == 'priority'), ['id' => 'sorttask-4_mytask']) }}
                            {{ Form::label('sorttask-4_mytask','priority') }}
                            {{ Form::checkbox('taskdesc_mytask', 'desc', (session('taskdesc_mytask') != null), ['class' => 'ml-3', 'id' => 'taskdesc_mytask']) }}
                            {{ Form::label('taskdesc_mytask','desc') }}
                        </div>
                        <div class="form-group">
                            {{ Form::text('searchtask_mytask', session('searched_mytask'), ['id' => 'searchtask_mytask', 'placeholder' => 'Search Tasks', 'class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                {{ Form::submit('Filter', ['class' => 'btn btn-secondary float-right']) }}                                             
                {!! Form::close() !!}
            </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-sm">            
            <div class="row justify-content-center">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header border border-secondary dropdown-toggle" onclick="show('createdtasks')">Tasks for others</div>
                        <div class="card-body" id="createdtasks">
                            <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create Task</a>
                            <hr>
                            @if(count($user->creations) > 0)
                                <table class="table table-responsive">
                                    @switch(session('groupby'))
                                        @case('user_id')
                                            @include('includes.home_user')
                                            @break
                                        @case('project_id')
                                            @include('includes.home_project')
                                            @break
                                        @case('priority')
                                            @include('includes.home_priority')
                                            @break
                                        @case('status')
                                            @include('includes.home_status')
                                            @break
                                    @endswitch
                                </table>
                            @else
                                <p>You have no tasks!</p>
                            @endif
                        </div>     
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header border border-secondary dropdown-toggle" onclick="show('mytasks')">My Tasks</div>
                        <div class="card-body" id="mytasks">
                            <hr>
                            @if(count($user->tasks) > 0)
                                <table class="table table-responsive">
                                    @switch(session('groupby_mytask'))
                                        @case('creator_id')
                                            @include('includes.home_creator_mytask')
                                            @break
                                        @case('project_id')
                                            @include('includes.home_project_mytask')
                                            @break
                                        @case('priority')
                                            @include('includes.home_priority_mytask')
                                            @break
                                        @case('status')
                                            @include('includes.home_status_mytask')
                                            @break
                                    @endswitch
                                </table>
                            @else
                                <p>You have no tasks!</p>
                            @endif
                        </div>
                    </div>
                </div>                
            </div>
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
        var taskShow = "{{ route('tasks.show',':id') }}";
    </script>        
        <script type="text/javascript" src="{{ asset('js/home.js') }}"></script>
@endsection

@section('css')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
@endsection