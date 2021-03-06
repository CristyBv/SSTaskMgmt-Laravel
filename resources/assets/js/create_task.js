$(document).ready(function() {

    // constructor for datepicker, on select put the value in the date input

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

    // set for datepicker the last data input

    var lastDeadlineDate = $(this).find('#deadline_date').data('last-deadline-date');
    $("#datepicker").datepicker("setDate", lastDeadlineDate);
});

// function for radio inputs, it taks the id and value and make an option for select

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

// constructor with ajax requests for user input

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

// constructor with ajax requests for project input

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