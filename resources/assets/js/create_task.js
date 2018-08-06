$(document).ready(function() {
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

    var lastDeadlineDate = $(this).find('#deadline_date').data('last-deadline-date');
    $("#datepicker").datepicker("setDate", lastDeadlineDate);
});

window.radiochange = function radiochange(name, id) {
    var radio = $('input[type=radio][name=\"' + name + '\"]:checked');
    var data = {
        id: radio.val(),
        text: radio.attr('id').substr(radio.val().length)
    };

    var newOption = new Option(data.text, data.id, true, true);
    $('#' + id).empty();
    $('#' + id).append(newOption).trigger('change');
}

$("#user").select2({
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

$("#project").select2({
    placeholder: "Select a Title",
    allowClear: true,
    ajax: {
        url: projectroute,
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