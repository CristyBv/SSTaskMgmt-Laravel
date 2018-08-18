@extends('layouts.app')

@section('content')
    <br>
    <a href=" {{ route('home') }}" class="btn btn-secondary">Go back</a>
    <div class='card'>
        <div class='card-header'>
            <p class='h3 float-left'>{{ $task->title }}</p>
            <p class='h3 float-right'>History</p>
        </div>
        <div class='card-body'>
            <div class='row'>
                <div class='col-sm-8'>
                    {!! $task->body !!}
                    <hr>
                    <small>Written on {{ $task->created_at }} by {{ $task->creator->name }}</small>
                    <br>
                    <small>Project: {{ $task->project->title }} written on {{ $task->project->created_at }} by {{ $task->project->user->name }}</small>
                    <br>
                    <small>Priority: {{ Config::get('priorities')[$task->priority] }}</small>
                    <br>
                    <small>Status: {{ Config::get('status')[$task->status] }}</small>
                    <br>
                    <small>Deadline: {{ $task->deadline }}</small>
                    <br>
                    <small>Last update: {{ $task->updated_at }}</small>
                </div>
                <div class='col-sm-4'>
                    @foreach($history as $forwards)
                        <p> {{ $forwards->forward->name }} forward to {{ $forwards->user->name }} on {{ $forwards->created_at }} </p>
                    @endforeach
                </div>
            </div>
            
        </div>
    </div>
    @include('task.edit_button')
    @if(Auth::user()->id == $task->creator_id)
        @include('task.delete_button')                    
    @endif
    <br>
    <hr>
    <div class="dropdown">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            Create Comment
        </button>
        <div class="dropdown-menu">
            {!! Form::open(['route' => 'comments.store', 'method' => 'POST']) !!}
            <div class="form-group">
                {{ Form::text('title','',['class' => 'form-control', 'placeholder' => 'Title']) }}
            </div>
            <div class="form-group">
                {{ Form::textarea('body','',['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body Text']) }}
            </div>
                {{ Form::hidden('task_id', $task->id) }}
                {{ Form::submit('Submit', ['class' => 'btn btn-primary float-right', 'style' => 'width:100%;']) }}
            {!! Form::close() !!}
        </div>
    </div>
    <br><br>
    @if(count($comments) > 0)
        @foreach($comments as $comment)
            <div class="card">
                <div class="card-header">
                    <p class="float-left">{{ $comment->title }}</p>
                    @if(Auth::user()->id == $comment->user_id)
                        {!! Form::open(['route' => ['comments.destroy', $comment], 'method' => 'POST', 'onsubmit' => 'return ConfirmDelete()']) !!}
                            {{ Form::hidden('_method', 'DELETE') }}
                            {{ Form::hidden('task_id', $task->id) }}
                            {{ Form::submit('Delete', ['class' => 'btn btn-danger deleteform float-right']) }}
                        {!! Form::close() !!}               
                        <div class="dropdown">
                            <button type="button" class="btn btn-secondary dropdown-toggle float-right mr-3" data-toggle="dropdown">
                                Edit
                            </button>
                            <div class="dropdown-menu">
                                {!! Form::open(['route' => ['comments.update', $comment], 'method' => 'POST']) !!}
                                    <div class="form-group">
                                        {{ Form::text('title', $comment->title,['class' => 'form-control', 'placeholder' => 'Title']) }}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::textarea('body', $comment->body, ['id' => 'article-ckeditor'.$comment->id, 'class' => 'form-control', 'placeholder' => 'Body Text']) }}
                                    </div>
                                    {{ Form::hidden('_method','PUT') }}
                                    {{ Form::hidden('task_id', $task->id) }}
                                    {{ Form::submit('Submit', ['class' => 'btn btn-primary float-right', 'style' => 'width:100%;']) }}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    {!! $comment->body !!}
                </div>
                <div class="card-footer">
                    Written on {{ $comment->created_at }} by {{ $comment->user->name }}                          
                </div>
            </div>
            <br><br>
        @endforeach
        {{ $comments->links() }}
    @else
        This task has no comments!
    @endif
    

@endsection

@section('scripts')
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script> 
        $('textarea').each(function() {
            CKEDITOR.replace( $(this).attr('id') );
        });
</script>
    
    <script type="text/javascript">
        function ConfirmDelete() {
            if (confirm("Are you sure you want to delete?")) return true;
            else return false;
        }
</script>
@endsection

@section('css')
    <style>
        .dropdown-menu {
            padding: 1em;
        }    
    </style>
@endsection