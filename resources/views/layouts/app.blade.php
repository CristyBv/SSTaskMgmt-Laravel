<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

    <!-- Scripts -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet"> 

</head>
<body>
    <div id="app">
        @include('includes.navbar')
        <div class="container">
            @include('includes.messages')
            @yield('content')
        </div>
    </div>

    <script type="text/javascript">
        function ConfirmDelete() {
            if (confirm("Are you sure you want to delete?")) return true;
            else return false;
        }

        function show(id) {
            if ($("#" + id).length) {
                if ($("#" + id).css('display') == 'none')
                    $("#" + id).css('display', 'block');
                else $("#" + id).css('display', 'none');
            }
        }
        $(document).ready(function() {

            $('.task-table').not(':has(.taskrow)').parent().parent().css('display', 'none');

            $('.taskrow').css('cursor', 'pointer');
            $(".taskrow").on('click', function(e) {
                if (!e.target.classList.contains('popoverbutton') && !e.target.classList.contains('editform')) {
                    var url = '{{ route("tasks.show", ":id") }}';
                    url = url.replace(':id', $(this).data('id'));
                    window.location.replace(url);
                }
            });
            //$('#datepicker').datepicker("show");
            $('[data-toggle="popover"]').popover({
                html: true,
                container: 'body',
                placement: 'bottom',
                trigger: 'manual',
                content: function() {
                    return $('.popover_content').html();
                }
            });

            $(document).on('click', ".popoverbutton", function() {
                if ($('.formforward').children().length != 0) {
                    $('.formforward').empty();
                    $('.popoverbutton').popover('hide');
                } else {
                    $(this).popover('show');
                    var select = $('<select class=\"usersselect\" name=\"forwarduser\"></select>');
                    var id = $(this).data('id');
                    select.on('change', function() {
                        if($(this).val() != null) {
                            var submit = $('<input type=\"submit\" class=\"btn btn-success\" value=\"Forward\">');
                            var idfield = $('<input>', {'type': 'hidden', 'name': 'id', 'value': id});
                            $('.formforward input').remove();
                            $('.formforward').append(idfield);
                            $('.formforward').append(submit);
                        } else {
                            $('.formforward input').remove();
                        }
                    });
                    $('.formforward').append(select);
                    $(".usersselect").select2({
                        width: '100%',
                        placeholder: "Select a Name",
                        allowClear: true,
                        ajax: {
                            url: "{{ route('users.search') }}",
                            dataType: 'json',
                            data: function(params) {
                                var query = {
                                    search: params.term,
                                    type: 'public'
                                }
                                return query;
                            },
                            processResults: function(data) {
                                return {
                                    results: data
                                };
                            }
                        }
                    });
                }
            });
        });

        $("#user").select2({
            placeholder: "Select a Name",
            allowClear: true,
            ajax: {
                url: "{{ route('users.search') }}",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term,
                        type: 'public'
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            }
        });

        $("#project").select2({
            placeholder: "Select a Title",
            allowClear: true,
            ajax: {
                url: "{{ route('projects.search') }}",
                dataType: 'json',
                data: function(params) {
                    var query = {
                        search: params.term,
                        type: 'public'
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            }
        });

        $("#datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            autoclose: true,
            dateFormat: 'yy-mm-dd',
            orientation: 'bottom auto',
            onSelect: function(dateText, inst) {
                $("input[name='date']").val(dateText);
            }
        });

        function radiochange(name, id) {
            var radio = $('input[type=radio][name=\"'+name+'\"]:checked');      
            var data = {
            id: radio.val(),
            text: radio.attr('id').substr(radio.val().length)
            };

            var newOption = new Option(data.text, data.id, true, true);
            $('#'+id).empty();
            $('#'+id).append(newOption).trigger('change');
        }

        
</script>

</body>
</html>