{!! Form::open(['route' => ['tasks.destroy', $task], 'method' => 'POST', 'onsubmit' => 'return ConfirmDelete()']) !!}
    {{ Form::hidden('_method', 'DELETE') }}
    {{ Form::submit('Delete', ['class' => 'btn btn-danger deleteform float-right']) }}
{!! Form::close() !!}