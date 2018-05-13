$(function() {

    var alertMsg = $('.alertMsg');

    function makeConRow(data, count) {
        var newRow = `
<tr>
    <td>
        <a href="../controllers/SupportController.php?convId=${data.success[0].id}&supportId=${data.success[0].supportId}">${data.success[0].subject}</a><br>
        <span class='notRead'> (${count} new)</span>
    </td>
</tr>`;

        $('.myConv').children().children().eq(0).after(newRow);
    }

    var convForm = $('.assignForm');

    convForm.on('click', 'button', function(event) {
        event.preventDefault();

        alertMsg.children().remove();
        alertMsg.slideUp();

        var supportId = $('#assignConvSupportId').val();
        var convId = $('#assignConvId').val();

        var obj = {
            supportId: supportId,
            convId: convId
        };


        var count = $('.openConv').find(`span[data-id = '${convId}']`).data('count');

        $.ajax({
            url: 'http://localhost/WAR_PHP_S_12_Warszata_Wolny_Tydzien/controllers/ConversationController.php',
            method: 'POST',
            data: obj,
            contentType: 'application/json',
            dataType: 'json'
        }).done(function(data) {
            makeConRow(data, count);

            var lastInsertedElement = $('.myConv').find('tr td a').attr('href');
            lastInsertedElement = lastInsertedElement.slice(0,-1);

            var openConvLinks = $('.openConv').find('tr td a');


            for (var i = 0; i < openConvLinks.length; i++) {
                var element = openConvLinks[i].getAttribute('href');

                if (element === lastInsertedElement) {
                    $(openConvLinks[i]).parent().parent().slideUp();
                    convForm.slideUp();
                }
            }


            alertMsg.append(`<p class="alert alert-success">Conversation assigned successfully.</p>`);
            alertMsg.slideDown();

        }).fail(function(data) {
            alertMsg.append(`<p class="alert alert-danger">${data.responseJSON.inputErr}</p>`);

            alertMsg.slideDown();
        })
    })

});