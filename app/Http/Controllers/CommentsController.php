<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TaskComment;
use App\Task;

class CommentsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Task $task)
    {
        $this->validate($request, [
            'title' => 'required|max:191',
            'body' => 'required',
        ]);
        
        TaskComment::create(array_add($request->all(), 'user_id', $request->user()->id));
        return redirect()->route('tasks.show', $request->task_id)->with('success', 'Comment Created');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskComment $comment)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);
        
        $comment->update($request->all());
    
        return redirect()->route('tasks.show', $request->task_id)->with('success', 'Comment Edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TaskComment $comment) // object
    {
        // gate / policy
        if(auth()->user()->id == $comment->user_id) {
            $comment->delete();
            return redirect()->route('tasks.show', $request->task_id)->with('success', 'Comment Deleted');
        } else return redirect()->route('tasks.show', $request->task_id)->with('error', 'Unauthorized Page');
    }
}
