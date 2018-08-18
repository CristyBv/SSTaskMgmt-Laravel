{!! Form::open(['route' => ['tasks.changestatus', $task], 'method' => 'POST']) !!}
    {{ Form::select('selectstatus', Config::get('status'), $task->status, ['class' => 'form-control', 'class' => 'selectstatus']) }}
    {{ Form::hidden('id', $task->id) }}
{!! Form::close() !!}
