<?php
use \App\User;
use \App\Task;
use \App\Project;

if(session('groupdesc_mytask') != null)
    $group = $user->tasks->sortByDesc('status')->groupBy(session('groupby_mytask'));
else $group = $user->tasks->sortBy('status')->groupBy(session('groupby_mytask'));

echo "<thead>";
    echo "<tr><th> Status </th></tr>";
    echo "</thead>";
    foreach($group as $id => $task) {
        echo "<tr>";
            echo "<td>";
                echo Config::get('status')[$id];
            echo "</td>";
            echo "<td>";
                echo "<table class='table table-striped task-table'> <thead>";
                    echo "<tr>";
                        echo "<th>". "Title". "</th>";
                        echo "<th>". "Creator". "</th>";
                        echo "<th>". "Project". "</th>";                                                                    
                        echo "<th>". "Deadline". "</th>";
                        echo "<th>". "Priority". "</th>";
                        echo "<th>". "Created Date". "</th>";
                        echo "<th>" . "</th>";
                    echo "</tr> </thead> <tbody>";

                    if(session('taskdesc_mytask') != null)
                        $task_sorted = $task->sortByDesc(session('tasksort_mytask'));
                    else $task_sorted = $task->sortBy(session('tasksort_mytask'));

                    $searched = session('searched_mytask');
                    if($searched != null || $searched != '')
                        $task_sorted = $task_sorted->filter(function ($value, $key) use ($searched) {
                            return false !== stristr($value->title, $searched);
                        });
                    
                    foreach($task_sorted as $tsk) {
                        if($tsk->status != count(Config::get('status'))) {
                            echo "<tr class='taskrow' data-id='" . $tsk->id . "'>";
                            echo "<td>" . $tsk->title . "</td>";
                            echo "<td>" . $tsk->creator->name . "</td>";
                            echo "<td>" . $tsk->project->title . "</td>";
                            echo "<td>" . $tsk->deadline . "</td>";
                            echo "<td>" . Config::get('priorities')[$tsk->priority] . "</td>";                                                                
                            echo "<td>" . $tsk->created_at . "</td>";
                            echo "<td>"
                            ?>
                            <div class="popover_content" style="display:none">
                                {!! Form::open(['action' => ['TasksController@forward', $tsk->id], 'method' => 'GET']) !!}
                                    <div class="form-group formforward">
                                    </div>
                                {!! Form::close() !!}
                            </div>
                            <button type="button" class="btn btn-info popoverbutton" data-toggle="popover" title="Forward To" data-id="{{ $tsk->id }}">Fwd</button>
                            <?php
                            echo "</td>";                                                                        
                            echo "</tr>";
                        }
                    }
                echo "</tbody> </table>";
            echo "</td>";
        echo "</tr>";
    }
?>