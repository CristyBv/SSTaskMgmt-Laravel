<?php

return [

    'modelsType' => [
        'db' => 'DB',
        'config' => 'Config',
    ],

    'modelsName' => [
        'user' => 'User',
        'project' => 'Project',
        'task' => 'Task',
        'history' => 'History_task',
        'comment' => 'Task_comment',
    ],

    'configsName' => [
        'status' => 'status',
        'priority' => 'priorities',
    ],

    'groupsName' => [
        'user' => 'User',
        'creator' => 'Creator',
        'project' => 'Project',
        'status' => 'Status',
        'priority' => 'Priority',
    ],

    'groupsTable' => [
        'user' => 'users',
        'project' => 'projects',
    ],

    'thsName' => [
        'title' => 'Title',
        'project' => 'Project',
        'status' => "Status",
        'deadline' => 'Deadline',
        'priority' => "Priority",
        'created_at' => "Created Date",
        'user' => "User",
        'creator' => "Creator",
    ],

    'tdsName' => [
        'title' => 'title',
        'project' => 'project',
        'status' => "status",
        'deadline' => 'deadline',
        'priority' => "priorities",
        'created_at' => "created_at",
        'user' => "user",
        'creator' => "creator",
        'name' => 'name',
    ],
];

?>