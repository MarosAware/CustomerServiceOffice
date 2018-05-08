<form class="form-inline" method="post" action="../controllers/ConversationController.php">
    <label for="conversationSubject" id="newConvSubject">New Subject: <input id="conversationSubject" name="subject" type="text" placeholder="Subject..."></label>
    <input type="hidden" name="senderId" value="{{senderId}}">
    <button class="btn">Create...</button>
</form>
