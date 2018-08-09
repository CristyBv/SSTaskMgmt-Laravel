$(document).ready(function() {

    $('.task-table').each(function() {
        $(this).DataTable({
            responsive: true,
        });
        $(this).addClass('table-responsive');
    });
    $('.dataTables_length').each(function() {
        $(this).addClass('bs-select');
    });

    $('.taskrow').find('td:lt(1)').css({
        'cursor': 'pointer',
        'overflow': 'hidden',
    });

    $('.taskrow').find('td:lt(1)').hover(function() {
        $(this).css('font-weight', 'bold');
    }, function() {
        $(this).css('font-weight', 'normal');
    });

    $('.task-table tbody tr').each(function() {
        if (!$(this).hasClass('taskrow'))
            $(this).closest('table').closest('tr').css('display', 'none');
    });
    $(".taskrow").find('td:lt(1)').on('click', function(e) {
        taskshow = taskshow.replace(':id', $(this).parent().data('id'));
        window.location.replace(taskshow);
    });

    $('[data-toggle="popover"]').popover({
        html: true,
        container: 'body',
        placement: 'right',
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

    $('[name=group], [name=group_mytask], [name=groupdesc], [name=groupdesc_mytask], [name=sorttask], [name=sorttask_mytask], [name=taskdesc], [name=taskdesc_mytask], [name=searchtask], [name=searchtask_mytask]')
        .change(function() {
            $('#filterform').submit();
        });

    $('.selectstatus').change(function() {
        $(this).parent().submit();
    });
});

$(".selectstatus").select2({
    placeholder: "Select a Status",
    allowClear: true,
    width: 'auto',
});