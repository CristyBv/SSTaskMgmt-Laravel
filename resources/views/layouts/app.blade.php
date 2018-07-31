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
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('includes.navbar')
        <div class="container">
            @include('includes.messages')
            @yield('content')
        </div>
    </div>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">  

    <script type="text/javascript">
        $("#project").select2({
            placeholder: "Select a Name",
            allowClear: true
        });

        $("#user").select2({
            placeholder: "Select a Name",
            allowClear: true
        });

        $( "#datepicker" ).datepicker({
            changeMonth: true,
            changeYear: true,
            autoclose: true,
            format: 'yyyy-mm-dd',
            orientation: 'bottom auto',
        });

    </script>

    <script type="text/javascript">
        function ConfirmDelete() {
            if (confirm("Are you sure you want to delete?")) return true;
            else return false;
        } 
        function show(id) {
            if($("#"+id).length) {
                if($("#"+id).css('display') == 'none')
                    $("#"+id).css('display', 'block');
                else $("#"+id).css('display', 'none');
            }
        }
        $(document).ready(function() {
            $('.taskrow').css('cursor', 'pointer');
            $(".taskrow").on('click', function(e) {
                if(!e.target.classList.contains('deleteform') && !e.target.classList.contains('editform')) {
                    var url = '{{ route("tasks.show", ":id") }}';
                    url = url.replace(':id', $(this).data('id'));
                    window.location.replace(url);
                }
            });
            $('#datepicker').datepicker("show"); 
        });
        
    </script>
</body>
</html>
