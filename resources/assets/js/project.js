$(document).ready(function() {
    $('[name=sortproject], [name=projectdesc], [name=searchproject]')
        .change(function() {
            $('#projectform').submit();
        });

});