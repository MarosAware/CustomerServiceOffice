<div class="row" id="content">
    <div class="col-md-12">
        <div class="page-header">
            <h2>Zaloguj się</h2>
        </div>
        <form class="form-inline" method="post" action="">
            <div class="form-group">
                <label for="login">Login:</label>
                <input type="text" class="form-control" id="login" name="login">
            </div>
            <div class="form-group">
                <label for="pwd">Hasło:</label>
                <input type="password" class="form-control" id="pwd" name="password">
            </div>
            <button type="submit" class="btn btn-default">Zaloguj</button>
        </form>
        <form action="../controllers/RegisterController.php" method="post">
            <button type="submit" class="btn btn-primary">Załóż konto</button>
        </form>
    </div>
</div>