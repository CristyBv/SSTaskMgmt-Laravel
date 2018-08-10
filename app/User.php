<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tasks(){
        return $this->hasMany('App\Task');
    }

    // for sorting to do tasks by creator name and project title, make a left join

    public function myTasksSort($how, $which)
    {
        switch($which) {
            case 'users':
            return $this->tasks()->leftJoin('users', 'tasks.creator_id', '=', 'users.id')->orderBy('users.name', $how)->select('tasks.*')->get();
                break;
            case 'projects':
            return $this->tasks()->leftJoin('projects', 'tasks.project_id', '=', 'projects.id')->orderBy('projects.title', $how)->select('tasks.*')->get();
        }
        
    }

    public function creations(){
        return $this->hasMany('App\Task', 'creator_id');
    }

    // for sorting creation tasks by receiver name and project title, make a left join

    public function creationsSort($how, $which){
        switch($which) {
            case 'users':
                return $this->creations()->leftJoin('users', 'tasks.user_id', '=', 'users.id')->orderBy('users.name', $how)->select('tasks.*')->get();
                break;
            case 'projects':
                return $this->creations()->leftJoin('projects', 'project_id', '=', 'projects.id')->orderBy('projects.title', $how)->select('tasks.*')->get();
                break;
        }
    }

    public function projects(){
        return $this->hasMany('App\Project');
    }

    public function history(){
        return $this->hasMany('App\History_task');
    }

    public function forward(){
        return $this->hasMany('App\History_task', 'forward_by');
    }

    public function comments(){
        return $this->hasMany('App\Task_Comment');
    }
}
