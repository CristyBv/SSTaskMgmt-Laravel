$(document).ready(function() {


    // Set DataTable for all task tables

    $('.task-table').each(function() {
        $(this).DataTable({
            responsive: true,
        });
        $(this).addClass('table-responsive');
    });
    $('.dataTables_length').each(function() {
        $(this).addClass('bs-select');
    });

    // set css and hover for first td of each row of task table

    $('.taskrow').find('td:lt(1)').css({
        'cursor': 'pointer',
        'overflow': 'hidden',
    });

    $('.taskrow').find('td:lt(1)').hover(function() {
        $(this).css('font-weight', 'bold');
    }, function() {
        $(this).css('font-weight', 'normal');
    });

    // set display none for groups without rows

    $('.task-table tbody tr').each(function() {
        if (!$(this).hasClass('taskrow'))
            $(this).closest('table').closest('tr').css('display', 'none');
    });

    // redirect to task show if click on first td of each row

    $(".taskrow").find('td:lt(1)').on('click', function(e) {
        taskShow = taskShow.replace(':id', $(this).parent().data('id'));
        window.location.replace(taskShow);
    });

    // set property of popover with manual trigger

    $('[data-toggle="popover"]').popover({
        html: true,
        container: 'body',
        placement: 'right',
        trigger: 'manual',
        content: function() {
            return $('.popover_content').html();
        }
    });

    // dinamic set of popover content with select2 ajax request

    $(document).on('click', ".popover_button", function() {

        if ($('.popover_content_form_div').children().length != 0) {
            $('.popover_content_form_div').empty();
            $('.popover_button').popover('hide');
        } else {
            $(this).popover('show');
            var select = $('<select class=\"popover_users_select\" name=\"forwarduser\"></select>');
            var id = $(this).data('id');
            select.on('change', function() {
                if ($(this).val() != null) {
                    var submit = $('<input type=\"submit\" class=\"btn btn-success\" value=\"Forward\">');
                    var idField = $('<input>', { 'type': 'hidden', 'name': 'id', 'value': id });
                    $('.popover_content_form_div input').remove();
                    $('.popover_content_form_div').append(idField);
                    $('.popover_content_form_div').append(submit);
                } else {
                    $('.popover_content_form_div input').remove();
                }
            });
            $('.popover_content_form_div').append(select);
            $(".popover_users_select").select2({
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

    // auto submit on filter change

    $('[name=group], [name=group_mytask], [name=groupdesc], [name=groupdesc_mytask], [name=sorttask], [name=sorttask_mytask], [name=taskdesc], [name=taskdesc_mytask], [name=searchtask], [name=searchtask_mytask]')
        .change(function() {
            $('#filterform').submit();
        });

    // submit on change for status select in task row

    $('.selectstatus').change(function() {
        $(this).parent().submit();
    });
});

// select2 for status select in task row

$(".selectstatus").select2({
    placeholder: "Select a Status",
    allowClear: true,
    width: 'auto',
});