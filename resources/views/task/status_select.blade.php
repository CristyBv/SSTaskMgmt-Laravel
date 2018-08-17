{!! Form::open(['route' => ['tasks.changestatus', $item], 'method' => 'POST']) !!}
    {{ Form::select('selectstatus', Config::get('status'), $item->status, ['class' => 'form-control', 'class' => 'selectstatus']) }}
    {{ Form::hidden('id', $item->id) }}
{!! Form::close() !!}
