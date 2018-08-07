<td>
    {!! Form::open(['action' => 'TasksController@changestatus', 'method' => 'GET']) !!}
        {{ Form::select('selectstatus', Config::get('status'), $item->status, ['class' => 'form-control', 'class' => 'selectstatus']) }}
        {{ Form::hidden('id', $item->id) }}
    {!! Form::close() !!}
</td>