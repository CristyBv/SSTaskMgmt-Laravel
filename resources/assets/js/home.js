$(document).ready(function() {

    $('.task-table').not(':has(.taskrow)').parent().parent().css('display', 'none');

    $('.taskrow').css('cursor', 'pointer');
    $(".taskrow").on('click', function(e) {
        if (!e.target.classList.contains('popoverbutton') && !e.target.classList.contains('editform')) {
            taskshow = taskshow.replace(':id', $(this).data('id'));
            window.location.replace(taskshow);
        }
    });
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
                if ($(this).val() != null) {
                    var submit = $('<input type=\"submit\" class=\"btn btn-success\" value=\"Forward\">');
                    var idfield = $('<input>', { 'type': 'hidden', 'name': 'id', 'value': id });
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
                    url: userroute,
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