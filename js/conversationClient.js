$(function() {

    var alertMsg = $('.alertMsg');

    function makeConRow(data) {
        var newRow = `
<tr>
    <td>
        <a href="../controllers/ClientController.php?convId=${data.success[0].id}&supportId=${data.success[0].supportId}">${data.success[0].subject}</a><br>
    </td>
</tr>`;

        $('.convRow').children().children().eq(0).after(newRow);
    }

    var convForm = $('#addConv');

    convForm.on('click', 'button', function(event) {
        event.preventDefault();

        alertMsg.children().remove();
        alertMsg.slideUp();

        var senderId = $('#convSenderId').val();
        var subject = $('#conversationSubject').val();

        var obj = {
            senderId: senderId,
            subject: subject
        };

        $.ajax({
            url: 'http://localhost/WAR_PHP_S_12_Warszata_Wolny_Tydzien/controllers/ConversationController.php',
            method: 'POST',
            data: obj,
            contentType: 'application/json',
            dataType: 'json'
        }).done(function(data) {
            makeConRow(data);
            $('#conversationSubject').val('');
            alertMsg.append(`<p class="alert alert-success">New conversation created.</p>`);
            alertMsg.slideDown();

        }).fail(function(data) {
            alertMsg.append(`<p class="alert alert-danger">${data.responseJSON.inputErr}</p>`);
            $('#conversationSubject').val('');
            alertMsg.slideDown();
        })
    })

});