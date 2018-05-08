<div class="row">
    <div class="col-md-12">
        {{messageForm}}
        {{convForm}}
    </div>
</div>
<div class="row" id="content">
    <div class="col-md-4 colHeight">
        <div class="page-header">
            <h3>All your question</h3>
        </div>
        <div>
            <table class="table">
                <tr>
                    <th>Subject</th>
                </tr>
                {{conversations}}
            </table>
        </div>
    </div>
    <div class="col-md-8 colHeight">
        <div class="page-header">
            <h3>All Messages</h3>
        </div>
        <div>
            <table class="table">
                <tr>
                    <th>Sender</th>
                    <th>Message</th>
                    <th>Creation Date</th>
                </tr>
                {{messages}}
            </table>
        </div>
    </div>
</div>
