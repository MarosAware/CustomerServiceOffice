<div class="row" id="content">
    <div class="col-md-4 colHeight">
        <div class="page-header">
            <h3>Wątki</h3>
        </div>
        <div>
            <table class="table">
                <tr>
                    <th>Temat</th>
                </tr>
                {{conversations}}
            </table>
        </div>
    </div>
    <div class="col-md-8 colHeight">
        <div class="page-header">
            <h3>Chat</h3>
        </div>
        <div>
            <table class="table">
                <tr>
                    <th>Nadawca</th>
                    <th>Wiadomość</th>
                </tr>
                {{messages}}
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <form class="form-inline" method="post" action="../controllers/ConversationController.php">
            <label for="conversationSubject" id="newConvSubject">Temat: <input id="conversationSubject" type="text" placeholder="Temat..."></label>
            <button class="btn">Dodaj...</button>
        </form>
    </div>
</div>