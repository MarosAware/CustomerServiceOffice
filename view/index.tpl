<!DOCTYPE html>
<html>
    <head>
        <title>BOK</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/main.css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"
                integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
                crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="navbar navbar-default">
                    <div class="navbar-header">
                        <a href="../controllers/LoginController.php" class="navbar-brand">Customer Service Office</a>
                    </div>
                    {{logout}}
                </div>
                <div class="row">
                    <div class="col-md-12 text-center alertMsg">
                        {{msg}}
                    </div>
                </div>
            </div>
            {{content}}
            </div>
        </div>
        {{script}}
    </body>
</html>
