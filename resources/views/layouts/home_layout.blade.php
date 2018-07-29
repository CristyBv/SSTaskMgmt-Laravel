<?php
use \App\User;
use \App\Task;
use \App\Project;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
?>

@yield('groupping')

<?php

if(isset($_GET['page']))
    $page=$_GET['page'];
else $page = 1;
$perPage = 1;

$group = new Paginator($group->forPage($page, $perPage), count($group), $perPage, $page, [
            'path'  => $_SERVER['REQUEST_URI'],
            'query' => $_GET,
        ]);
?>
<thead>
    <tr>
        <th>User</th>
    </tr>
</thead>
@foreach($group as $id => $task)
    <tr>
        <td>
            @yield('groupname')
        </td>
        <td>
            <table class='table table-striped'>
                <tr>
                    @yield('thead')
                    <th></th>
                    <th></th>
                </tr>
                <?php
                    if($data['taskdesc'] != null)
                        $task_sorted = $task->sortByDesc($data['filtersort']);
                    else $task_sorted = $task->sortBy($data['filtersort']);
                ?>

                @foreach($task_sorted as $tsk)
                    @if($data['searched'] == null || $data['searched'] == "" || strpos($tsk->title, $data['searched']) !== false)
                        <tr class='taskrow' data-id='{{ $tsk->id }}'>
                            @yield('tbody')
                            <td>
                                <a href="{{ route('tasks.edit', ['id'=> $tsk->id]) }}" class='btn btn-secondary'>Edit</a>
                            </td>
                            <td>
                                {!! Form::open(['action' => ['TasksController@destroy', $tsk->id], 'method' => 'POST', 'onsubmit' => 'return ConfirmDelete()']) !!}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    {{ Form::submit('Delete', ['class' => 'btn btn-danger deleteform']) }}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </td>
    </tr>    
@endforeach


