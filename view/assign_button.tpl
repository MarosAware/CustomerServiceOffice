<form class="form-inline" method="post" action="../controllers/ConversationController.php?">
    <h3>Chosen Subject:</h3>
    <h4>{{subject}}</h4>
    <input type="hidden" name="supportId" value="{{supportId}}">
    <input type="hidden" name="convId" value="{{convId}}">
    <button class="btn btn-primary">Assign this subject to me</button>
</form>