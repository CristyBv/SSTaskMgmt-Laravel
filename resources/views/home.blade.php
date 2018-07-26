<?php
    use \App\User;
    use \App\Task;
    use \App\Project;
?>
@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card">
            <div class="card-header">Filter</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm">
                        {!! Form::open(['action' => ['TasksController@filter'], 'method' => 'POST']) !!}
                        {{ Form::label('group','Grupeaza dupa: ') }}
                        <div class="form-group">
                            {{ Form::radio('group', 'user_id', ($data['filter'] == 'user_id'), ['id' => 'group-0']) }}
                            {{ Form::label('group-0','User') }}
                            {{ Form::radio('group', 'project_id', ($data['filter'] == 'project_id'), ['id' => 'group-1']) }}
                            {{ Form::label('group-1','Project') }}
                            {{ Form::radio('group', 'priority', ($data['filter'] == 'priority'), ['id' => 'group-2']) }}
                            {{ Form::label('group-2','Priority') }}
                            {{ Form::radio('group', 'status', ($data['filter'] == 'status'), ['id' => 'group-3']) }}
                            {{ Form::label('group-3','Status') }}
                            {{ Form::checkbox('groupdesc', 'desc', ($data['desc'] != null), ['class' => 'ml-3', 'id' => 'groupdesc']) }}
                            {{ Form::label('groupdesc','desc') }}
                        </div>
                        {{ Form::label('sorttask','Sorteaza task-urile dupa: ') }}
                        <div class="form-group">
                            {{ Form::radio('sorttask', 'title', ($data['filtersort'] == 'title'), ['id' => 'sorttask-0']) }}
                            {{ Form::label('sorttask-0','title') }}
                            {{ Form::radio('sorttask', 'deadline', ($data['filtersort'] == 'deadline'), ['id' => 'sorttask-1']) }}
                            {{ Form::label('sorttask-1','deadline') }}
                            {{ Form::radio('sorttask', 'created_at', ($data['filtersort'] == 'created_at'), ['id' => 'sorttask-2']) }}
                            {{ Form::label('sorttask-2','created_date') }}
                            {{ Form::checkbox('taskdesc', 'desc', ($data['taskdesc'] != null), ['class' => 'ml-3', 'id' => 'taskdesc']) }}
                            {{ Form::label('taskdesc','desc') }}
                        </div>
                        <div class="form-group">
                            {{ Form::text('searchtask', $data['searched'], ['id' => 'searchtask', 'placeholder' => 'Search Tasks']) }}
                        </div>
                    </div>
                    <div class="col-sm">
                        {{ Form::label('group','Grupeaza dupa: ') }}
                        <div class="form-group">
                            {{ Form::radio('group_mytask', 'creator_id', ($data['filter_mytask'] == 'creator_id'), ['id' => 'group-0_mytask']) }}
                            {{ Form::label('group-0_mytask','Creator') }}
                            {{ Form::radio('group_mytask', 'project_id', ($data['filter_mytask'] == 'project_id'), ['id' => 'group-1_mytask']) }}
                            {{ Form::label('group-1_mytask','Project') }}
                            {{ Form::radio('group_mytask', 'priority', ($data['filter_mytask'] == 'priority'), ['id' => 'group-2_mytask']) }}
                            {{ Form::label('group-2_mytask','Priority') }}
                            {{ Form::radio('group_mytask', 'status', ($data['filter_mytask'] == 'status'), ['id' => 'group-3_mytask']) }}
                            {{ Form::label('group-3_mytask','Status') }}
                            {{ Form::checkbox('groupdesc_mytask', 'desc', ($data['desc_mytask'] != null), ['class' => 'ml-3', 'id' => 'groupdesc_mytask']) }}
                            {{ Form::label('groupdesc_mytask','desc') }}
                        </div>
                        {{ Form::label('sorttask','Sorteaza task-urile dupa: ') }}
                        <div class="form-group">
                            {{ Form::radio('sorttask_mytask', 'title', ($data['filtersort_mytask'] == 'title'), ['id' => 'sorttask-0_mytask']) }}
                            {{ Form::label('sorttask-0_mytask','title') }}
                            {{ Form::radio('sorttask_mytask', 'deadline', ($data['filtersort_mytask'] == 'deadline'), ['id' => 'sorttask-1_mytask']) }}
                            {{ Form::label('sorttask-1_mytask','deadline') }}
                            {{ Form::radio('sorttask_mytask', 'created_at', ($data['filtersort_mytask'] == 'created_at'), ['id' => 'sorttask-2_mytask']) }}
                            {{ Form::label('sorttask-2_mytask','created_date') }}
                            {{ Form::checkbox('taskdesc_mytask', 'desc', ($data['taskdesc_mytask'] != null), ['class' => 'ml-3', 'id' => 'taskdesc_mytask']) }}
                            {{ Form::label('taskdesc_mytask','desc') }}
                        </div>
                        <div class="form-group">
                            {{ Form::text('searchtask_mytask', $data['searched_mytask'], ['id' => 'searchtask_mytask', 'placeholder' => 'Search Tasks']) }}
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
                <div class="col-sm">
                    <div class="card">
                        <div class="card-header">Tasks for others</div>
                        <div class="card-body">
                            <a href="/tasks/create" class="btn btn-primary">Create Task</a>
                            <hr>
                            @if(count($user->creations) > 0)
                                <table class="table">
                                        @switch($data['filter'])
                                            @case('user_id')
                                                @include('layouts.home_user')
                                                @break;
                                            @case('project_id')
                                                @include('layouts.home_project')
                                                @break;
                                            @case('priority')
                                                @include('layouts.home_priority')
                                                @break;
                                            @case('status')
                                                @include('layouts.home_status')
                                                @break;
                                        @endswitch
                                </table>
                            @else
                                    <p>You have no tasks!</p>
                            @endif
                        </div>        
                    </div>
                </div>
                <div class="col-sm">
                    <div class="card">
                        <div class="card-header">My Tasks</div>
                        <div class="card-body">
                            <hr>
                            @if(count($user->tasks) > 0)
                                <table class="table">
                                @switch($data['filter_mytask'])
                                    @case('creator_id')
                                        @include('layouts.home_creator')
                                        @break;
                                    @case('project_id')
                                        @include('layouts.home_project')
                                        @break;
                                    @case('priority')
                                        @include('layouts.home_priority')
                                        @break;
                                    @case('status')
                                        @include('layouts.home_status')
                                        @break;
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
</div>

{{-- <script>
   $(document).ready(function() {
       fetch_task();

       function fetch_task(query = '') {
           $.ajax({
               url:"{{ route('tasks.search') }}",
               method:'GET',
               data:{query:query},
               dataType:'json',
               succes:function(data) {
                   $('#test').html(data.tasks);
               }

           })
       }
   }); 
</script> --}}
@endsection
