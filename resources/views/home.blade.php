<?php
    use \App\User;
    use \App\Task;
    use \App\Project;
?>
@extends('layouts.app')

@section('content')
<div class="container">
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
                                        {{ Form::label('sorttask-2','created_at') }}
                                        {{ Form::checkbox('taskdesc', 'desc', ($data['taskdesc'] != null), ['class' => 'ml-3', 'id' => 'taskdesc']) }}
                                        {{ Form::label('taskdesc','desc') }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('searchtask','Search Tasks') }}
                                        {{ Form::text('searchtask', $data['searched']) }}
                                    </div>
                                    {{ Form::submit('Filter', ['class' => 'btn btn-secondary float-right']) }}                                             
                                {!! Form::close() !!}
                                <table class="table">
                                    <?php
                                        if($data['desc'] == null)
                                            $group = $user->creations->sortByDesc($data['filter'])->groupBy($data['filter']);
                                        else $group = $user->creations->sortBy($data['filter'])->groupBy($data['filter']);
                                        switch($data['filter']) {
                                            case 'user_id':
                                                echo "<thead>";
                                                echo "<tr><th> User </th></tr>";
                                                echo "</thead>";
                                                foreach($group as $id => $task) {
                                                    echo "<tr>";
                                                        echo "<td>";
                                                            echo User::where('id', $id)->first()->name;
                                                        echo "</td>";
                                                        echo "<td>";
                                                            echo "<table class='table table-striped'>";
                                                                echo "<tr>";
                                                                    echo "<th>". "Title". "</th>";
                                                                    echo "<th>". "Project". "</th>";
                                                                    echo "<th>". "Status". "</th>";
                                                                    echo "<th>". "Deadline". "</th>";
                                                                    echo "<th>". "Priority". "</th>";
                                                                    echo "<th>". "Created Date". "</th>";
                                                                    echo "<th>" . "</th>";
                                                                    echo "<th>" . "</th>";
                                                                echo "</tr>";
                                                                if($data['taskdesc'] != null)
                                                                    $task_sorted = $task->sortByDesc($data['filtersort']);
                                                                else $task_sorted = $task->sortBy($data['filtersort']);
                                                                foreach($task_sorted as $tsk) {
                                                                    if($data['searched'] == null || $data['searched'] == "" || strpos($tsk->title, $data['searched']) !== false)
                                                                        {
                                                                            echo "<tr>";
                                                                            echo "<td>" . $tsk->title . "</td>";
                                                                            echo "<td>" . Project::find($tsk->project_id)->title . "</td>";
                                                                            echo "<td>" . $tsk->status . "</td>";
                                                                            echo "<td>" . $tsk->deadline . "</td>";
                                                                            echo "<td>" . $tsk->priority . "</td>";
                                                                            echo "<td>" . $tsk->created_at . "</td>";
                                                                            echo "<td>"
                                                                        ?>
                                                                        <a href="{{ route('tasks.edit', ['id'=> $tsk->id]) }}" class='btn btn-secondary'>Edit</a>
                                                                        <?php
                                                                        echo "</td>";
                                                                        echo "<td>"
                                                                        ?>
                                                                        {!! Form::open(['action' => ['TasksController@destroy', $tsk->id], 'method' => 'POST', 'onsubmit' => 'return ConfirmDelete()']) !!}
                                                                            {{ Form::hidden('_method', 'DELETE') }}
                                                                            {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                                                                        {!! Form::close() !!}
                                                                        <?php
                                                                        echo "</td>";                                                                        
                                                                    echo "</tr>";
                                                                        }
                                                                        
                                                                }
                                                            echo "</table>";
                                                        echo "</td>";
                                                    echo "</tr>";
                                                }
                                                break;
                                            case 'project_id':
                                                echo "<thead>";
                                                echo "<tr><th> Project </th></tr>";
                                                echo "</thead>";
                                                foreach($group as $id => $task) {
                                                    echo "<tr>";
                                                        echo "<td>";
                                                            echo Project::where('id', $id)->first()->title;
                                                        echo "</td>";
                                                        echo "<td>";
                                                            echo "<table class='table table-striped'>";
                                                                echo "<tr>";
                                                                    echo "<th>". "Title". "</th>";
                                                                    echo "<th>". "User". "</th>";
                                                                    echo "<th>". "Status". "</th>";
                                                                    echo "<th>". "Deadline". "</th>";
                                                                    echo "<th>". "Priority". "</th>";
                                                                    echo "<th>". "Created Date". "</th>";
                                                                    echo "<th>" . "</th>";
                                                                    echo "<th>" . "</th>";
                                                                echo "</tr>";
                                                                if($data['taskdesc'] != null)
                                                                    $task_sorted = $task->sortByDesc($data['filtersort']);
                                                                else $task_sorted = $task->sortBy($data['filtersort']);
                                                                foreach($task_sorted as $tsk) {
                                                                    if($data['searched'] == null || $data['searched'] == "" || strpos($tsk->title, $data['searched']) !== false)
                                                                        {
                                                                            echo "<tr>";
                                                                            echo "<td>" . $tsk->title . "</td>";
                                                                            echo "<td>" . User::find($tsk->user_id)->name . "</td>";
                                                                            echo "<td>" . $tsk->status . "</td>";
                                                                            echo "<td>" . $tsk->deadline . "</td>";
                                                                            echo "<td>" . $tsk->priority . "</td>";
                                                                            echo "<td>" . $tsk->created_at . "</td>";
                                                                            echo "<td>"
                                                                            ?>
                                                                            <a href="{{ route('tasks.edit', ['id'=> $tsk->id]) }}" class='btn btn-secondary'>Edit</a>
                                                                            <?php
                                                                            echo "</td>";
                                                                            echo "<td>"
                                                                            ?>
                                                                            {!! Form::open(['action' => ['TasksController@destroy', $tsk->id], 'method' => 'POST', 'onsubmit' => 'return ConfirmDelete()']) !!}
                                                                                {{ Form::hidden('_method', 'DELETE') }}
                                                                                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                                                                            {!! Form::close() !!}
                                                                            <?php
                                                                            echo "</td>";                                                                        
                                                                            echo "</tr>";
                                                                        }
                                                                    }
                                                            echo "</table>";
                                                        echo "</td>";
                                                    echo "</tr>";
                                                }
                                                break;
                                            case 'priority':
                                                echo "<thead>";
                                                echo "<tr><th> Priority </th></tr>";
                                                echo "</thead>";
                                                foreach($group as $id => $task) {
                                                    echo "<tr>";
                                                        echo "<td>";
                                                            echo $id;
                                                        echo "</td>";
                                                        echo "<td>";
                                                            echo "<table class='table table-striped'>";
                                                                echo "<tr>";
                                                                    echo "<th>". "Title". "</th>";
                                                                    echo "<th>". "User". "</th>";
                                                                    echo "<th>". "Project". "</th>";
                                                                    echo "<th>". "Status". "</th>";
                                                                    echo "<th>". "Deadline". "</th>";
                                                                    echo "<th>". "Created Date". "</th>";
                                                                    echo "<th>" . "</th>";
                                                                    echo "<th>" . "</th>";
                                                                echo "</tr>";
                                                                if($data['taskdesc'] != null)
                                                                    $task_sorted = $task->sortByDesc($data['filtersort']);
                                                                else $task_sorted = $task->sortBy($data['filtersort']);
                                                                foreach($task_sorted as $tsk) {
                                                                    if($data['searched'] == null || $data['searched'] == "" || strpos($tsk->title, $data['searched']) !== false)
                                                                        {
                                                                            echo "<tr>";
                                                                            echo "<td>" . $tsk->title . "</td>";
                                                                            echo "<td>" . User::find($tsk->user_id)->name . "</td>";
                                                                            echo "<td>" . Project::find($tsk->project_id)->title . "</td>";
                                                                            echo "<td>" . $tsk->status . "</td>";
                                                                            echo "<td>" . $tsk->deadline . "</td>";                                                                        
                                                                            echo "<td>" . $tsk->created_at . "</td>";
                                                                            echo "<td>"
                                                                            ?>
                                                                            <a href="{{ route('tasks.edit', ['id'=> $tsk->id]) }}" class='btn btn-secondary'>Edit</a>
                                                                            <?php
                                                                            echo "</td>";
                                                                            echo "<td>"
                                                                            ?>
                                                                            {!! Form::open(['action' => ['TasksController@destroy', $tsk->id], 'method' => 'POST', 'onsubmit' => 'return ConfirmDelete()']) !!}
                                                                                {{ Form::hidden('_method', 'DELETE') }}
                                                                                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                                                                            {!! Form::close() !!}
                                                                            <?php
                                                                            echo "</td>";                                                                        
                                                                            echo "</tr>";
                                                                        }
                                                                }
                                                            echo "</table>";
                                                        echo "</td>";
                                                    echo "</tr>";
                                                }
                                                break;
                                            case 'status':
                                                echo "<thead>";
                                                echo "<tr><th> Status </th></tr>";
                                                echo "</thead>";
                                                foreach($group as $id => $task) {
                                                    echo "<tr>";
                                                        echo "<td>";
                                                            echo $id;
                                                        echo "</td>";
                                                        echo "<td>";
                                                            echo "<table class='table table-striped'>";
                                                                echo "<tr>";
                                                                    echo "<th>". "Title". "</th>";
                                                                    echo "<th>". "User". "</th>";
                                                                    echo "<th>". "Project". "</th>";                                                                    
                                                                    echo "<th>". "Deadline". "</th>";
                                                                    echo "<th>". "Priority". "</th>";
                                                                    echo "<th>". "Created Date". "</th>";
                                                                    echo "<th>" . "</th>";
                                                                    echo "<th>" . "</th>";
                                                                echo "</tr>";
                                                                if($data['taskdesc'] != null)
                                                                    $task_sorted = $task->sortByDesc($data['filtersort']);
                                                                else $task_sorted = $task->sortBy($data['filtersort']);
                                                                foreach($task_sorted as $tsk) {
                                                                    if($data['searched'] == null || $data['searched'] == "" || strpos($tsk->title, $data['searched']) !== false)
                                                                        {
                                                                            echo "<tr>";
                                                                            echo "<td>" . $tsk->title . "</td>";
                                                                            echo "<td>" . User::find($tsk->user_id)->name . "</td>";
                                                                            echo "<td>" . Project::find($tsk->project_id)->title . "</td>";
                                                                            echo "<td>" . $tsk->deadline . "</td>";
                                                                            echo "<td>" . $tsk->priority . "</td>";                                                                
                                                                            echo "<td>" . $tsk->created_at . "</td>";
                                                                            echo "<td>"
                                                                            ?>
                                                                            <a href="{{ route('tasks.edit', ['id'=> $tsk->id]) }}" class='btn btn-secondary'>Edit</a>
                                                                            <?php
                                                                            echo "</td>";
                                                                            echo "<td>"
                                                                            ?>
                                                                            {!! Form::open(['action' => ['TasksController@destroy', $tsk->id], 'method' => 'POST', 'onsubmit' => 'return ConfirmDelete()']) !!}
                                                                                {{ Form::hidden('_method', 'DELETE') }}
                                                                                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                                                                            {!! Form::close() !!}
                                                                            <?php
                                                                            echo "</td>";                                                                        
                                                                            echo "</tr>";
                                                                        }
                                                                }
                                                            echo "</table>";
                                                        echo "</td>";
                                                    echo "</tr>";
                                                }                                            
                                                break;
                                        }
                                    ?>
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
                                <table class="table table-striped">
                                    <tr>
                                        <th>Title</th>
                                        <th>Created by</th>
                                        <th></th>
                                    </tr>
                                    @foreach($user->tasks as $task)
                                        <?php
                                            $creator=User::where('id',$task->creator_id)->first();
                                        ?>
                                        <tr>
                                            <td>{{ $task->title }}</td>
                                            <td>{{ $creator->name }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
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
@endsection
