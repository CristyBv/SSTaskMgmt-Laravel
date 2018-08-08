<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task_comment;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);
        $comment = new Task_comment;
        $comment->title = $request->title;
        $comment->body = $request->body;
        $comment->task_id = $request->task_id;
        $comment->user_id = auth()->user()->id;
        $comment->save();

        return redirect()->route('tasks.show', $request->task_id)->with('success', 'Comment Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy(Request $request, $id)
    {
        $comment = Task_comment::find($id);
        if(auth()->user()->id == $comment->user_id) {
            $comment->delete();
            return redirect()->route('tasks.show', $request->task_id)->with('success', 'Comment Deleted');
        } else return redirect()->route('tasks.show', $request->task_id)->with('error', 'Unauthorized Page');
    }
}
