<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task_comment; // TaskComment
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
            'title' => 'required|max:191', //modify
            'body' => 'required',
        ]);
        //$task->comments()->create(array_add($request->all(), 'user_id', $request->user()->id));
        $comment = new Task_comment;
        $comment->title = $request->title;
        $comment->body = $request->body;
        $comment->task_id = $request->task_id;
        $comment->user_id = auth()->user()->id;
        $comment->save();

        return redirect()->route('tasks.show', $request->task_id)->with('success', 'Comment Created');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);
            // fill
        $comment = Task_comment::find($id);
        $comment->title = $request->title;
        $comment->body = $request->body;
        $comment->save();
    
        return redirect()->route('tasks.show', $request->task_id)->with('success', 'Comment Edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) // object
    {
        // gate / policy
        $comment = Task_comment::find($id);
        if(auth()->user()->id == $comment->user_id) {
            $comment->delete();
            return redirect()->route('tasks.show', $request->task_id)->with('success', 'Comment Deleted');
        } else return redirect()->route('tasks.show', $request->task_id)->with('error', 'Unauthorized Page');
    }
}
