<form id="addConv" class="form-inline" method="" action="../controllers/ConversationController.php">
    <label for="conversationSubject" id="newConvSubject">New Subject: <input id="conversationSubject" name="subject" type="text" placeholder="Subject..."></label>
    <input id="convSenderId" type="hidden" name="senderId" value="{{senderId}}">
    <button class="btn">Create...</button>
</form>
