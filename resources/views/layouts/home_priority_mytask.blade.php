<?php
use \App\User;
use \App\Task;
use \App\Project;

if($data['desc_mytask'] != null)
    $group = $user->tasks->sortByDesc('priority')->groupBy($data['filter_mytask']);
else $group = $user->tasks->sortBy('priority')->groupBy($data['filter_mytask']);

echo "<thead>";
    echo "<tr><th> Priority </th></tr>";
    echo "</thead>";
    foreach($group as $id => $task) {
        echo "<tr>";
            echo "<td>";
                echo Config::get('priorities')[$id];
            echo "</td>";
            echo "<td>";
                echo "<table class='table table-striped'>";
                    echo "<tr>";
                        echo "<th>". "Title". "</th>";
                        echo "<th>". "Creator". "</th>";
                        echo "<th>". "Project". "</th>";
                        echo "<th>". "Status". "</th>";
                        echo "<th>". "Deadline". "</th>";
                        echo "<th>". "Created Date". "</th>";
                        echo "<th>" . "</th>";
                        echo "<th>" . "</th>";
                    echo "</tr>";

                    if($data['taskdesc_mytask'] != null)
                        $task_sorted = $task->sortByDesc($data['filtersort_mytask']);
                    else $task_sorted = $task->sortBy($data['filtersort_mytask']);
                    
                    foreach($task_sorted as $tsk) {
                        if($data['searched_mytask'] == null || $data['searched_mytask'] == "" || strpos($tsk->title, $data['searched_mytask']) !== false)
                            {
                                echo "<tr>";
                                echo "<td>" . $tsk->title . "</td>";
                                echo "<td>" . User::find($tsk->creator_id)->name . "</td>";
                                echo "<td>" . Project::find($tsk->project_id)->title . "</td>";
                                echo "<td>" . Config::get('status')[$tsk->status] . "</td>";
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
?>