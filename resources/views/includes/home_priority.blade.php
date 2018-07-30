<?php
use \App\User;
use \App\Task;
use \App\Project;

if($data['desc'] != null)
    $group = $user->creations->sortByDesc('priority')->groupBy($data['filter']);
else $group = $user->creations->sortBy('priority')->groupBy($data['filter']);

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

                    $searched = $data['searched'];
                    if($searched != null || $searched != '')
                        $task_sorted = $task_sorted->filter(function ($value, $key) use ($searched) {
                            return false !== stristr($value->title, $searched);
                        });
                    
                    foreach($task_sorted as $tsk) {
                        echo "<tr class='taskrow' data-id='" . $tsk->id . "'>";
                        echo "<td>" . $tsk->title . "</td>";
                        echo "<td>" . $tsk->user->name . "</td>";
                        echo "<td>" . $tsk->project->title . "</td>";
                        echo "<td>" . Config::get('status')[$tsk->status] . "</td>";
                        echo "<td>" . $tsk->deadline . "</td>";                                                                        
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
                echo "</table>";
            echo "</td>";
        echo "</tr>";
    }
?>