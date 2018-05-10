<form class="form-inline" method="post" action="../controllers/MessageController.php">
    <h3>Chosen Subject:</h3>
    <h4>{{subject}}</h4>
    <label for="conversationMessage" id="newConvMessage">Your message:</label><br>
    <textarea class="form-control" name="message" id="conversationMessage" maxlength="254" cols="50" rows="10" placeholder="Your message here..."></textarea><br>
    <input type="hidden" value="{{convId}}" name="convId">
    <input type="hidden" value="{{senderId}}" name="senderId">
    <button class="btn btn-primary">Send...</button>
</form>
