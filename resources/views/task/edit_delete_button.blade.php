<div class="btn-group">
    <a href="{{ route('tasks.edit', ['task' => $task]) }}" class='btn btn-secondary float-left editform'>Edit</a>
    @if(!Auth::guest())
        @if(Auth::user()->id == $task->creator_id)
            <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="sr-only">Edit Dropdown</span>
            </button>                    
            <div class="dropdown-menu">
                {!! Form::open(['route' => ['tasks.destroy', $task], 'method' => 'POST', 'onsubmit' => 'return ConfirmDelete()']) !!}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete', ['class' => 'btn btn-danger deleteform dropdown-item']) }}
                {!! Form::close() !!}          
            </div>
        @endif
    @endif 
</div>