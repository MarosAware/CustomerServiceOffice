<div class="row">
    <div class="col-md-12">
        {{messageForm}}
        {{assignButton}}
    </div>
</div>
<div class="row" id="content">
    <div class="col-md-3 colHeight">
        <div class="page-header">
            <h3>My conversations</h3>
        </div>
        <div>
            <table class="table myConv">
                <tr>
                    <th>Subject</th>
                </tr>
                {{myConversations}}
            </table>
        </div>
    </div>
    <div class="col-md-3 colHeight">
        <div class="page-header">
            <h3>Open conversations</h3>
        </div>
        <div>
            <table class="table openConv">
                <tr>
                    <th>Subject</th>
                    <th></th>
                </tr>
                {{openConversations}}
            </table>
        </div>
    </div>
    <div class="col-md-6 colHeight">
        <div class="page-header">
            <h3>Messages</h3>
        </div>
        <div>
            <table class="table">
                <tr>
                    <th>Sender</th>
                    <th>Message</th>
                    <th>Created</th>
                </tr>
                {{messages}}
            </table>
        </div>
    </div>
</div>