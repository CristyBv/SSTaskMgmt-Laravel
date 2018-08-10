$(document).ready(function() {

    // auto submit on filter change

    $('[name=sortproject], [name=projectdesc], [name=searchproject]')
        .change(function() {
            $('#projectform').submit();
        });

});