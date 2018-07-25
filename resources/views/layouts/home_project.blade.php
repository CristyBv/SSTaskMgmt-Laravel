<?php
use \App\User;
use \App\Task;
use \App\Project;

if($data['desc'] != null)
    $group = $user->creationsSort('desc', 'projects')->groupBy($data['filter']);
else $group = $user->creationsSort('asc', 'projects')->groupBy($data['filter']);

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
                                echo "<td>" . Config::get('status')[$tsk->status] . "</td>";
                                echo "<td>" . $tsk->deadline . "</td>";
                                echo "<td>" . Config::get('priorities')[$tsk->priority] . "</td>";
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
?>
