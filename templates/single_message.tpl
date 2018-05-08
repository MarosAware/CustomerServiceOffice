<form class="form-inline" method="post" action="../controllers/MessageController.php">
    <label for="conversationSubject" id="newConvSubject">Subject: <input id="conversationSubject" name="subject" value="{{subject}}" type="text" readonly></label><br>
    <label for="conversationMessage" id="newConvMessage">Your message:</label><br>

    <textarea name="message" id="conversationMessage" cols="30" rows="10"></textarea><br>
    <input type="hidden" value="{{convId}}" name="convId">
    <input type="hidden" value="{{senderId}}" name="senderId">
    <button class="btn">Send...</button>
</form>
