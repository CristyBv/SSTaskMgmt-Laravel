<div class="popover_content" style="display:none">
    {!! Form::open(['action' => ['TasksController@forward'], 'method' => 'GET']) !!}
        <div class="form-group popover_content_form_div"> </div>
    {!! Form::close() !!}
</div>
<button type="button" class="btn btn-info popover_button" data-toggle="popover" title="Forward To" data-id="{{ $item->id }}">Fwd</button>