<?php
use \App\User;
use \App\Task;
use \App\Project;

if($data['desc_mytask'] != null)
    $group = $user->tasks->sortByDesc('status')->groupBy($data['filter_mytask']);
else $group = $user->tasks->sortBy('status')->groupBy($data['filter_mytask']);

echo "<thead>";
    echo "<tr><th> Status </th></tr>";
    echo "</thead>";
    foreach($group as $id => $task) {
        echo "<tr>";
            echo "<td>";
                echo Config::get('status')[$id];
            echo "</td>";
            echo "<td>";
                echo "<table class='table table-striped'>";
                    echo "<tr>";
                        echo "<th>". "Title". "</th>";
                        echo "<th>". "Creator". "</th>";
                        echo "<th>". "Project". "</th>";                                                                    
                        echo "<th>". "Deadline". "</th>";
                        echo "<th>". "Priority". "</th>";
                        echo "<th>". "Created Date". "</th>";
                        echo "<th>" . "</th>";
                        echo "<th>" . "</th>";
                    echo "</tr>";

                    if($data['taskdesc_mytask'] != null)
                        $task_sorted = $task->sortByDesc($data['filtersort_mytask']);
                    else $task_sorted = $task->sortBy($data['filtersort_mytask']);

                    $searched = $data['searched_mytask'];
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
                            @include('task.edit_button', ['item' => $tsk])
                            <?php
                            echo "</td>";
                            echo "<td>"
                            ?>
                            @include('task.delete_button', ['item' => $tsk])
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