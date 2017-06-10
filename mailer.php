<!DOCTYPE html>
<html lang="en" onselectstart="return false;" style="-ms-user-select: none;">

<head>
  <title>PostIt</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width = device-width, initial-scale = 1">
  <link rel="icon" href="assets/images/favicon.png">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
</head>


<body>
<!--navigation-->
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand"><i onclick="location.href='main.html'" style="cursor: pointer;padding-top: 0;" class="fa fa-arrow-left faicon arrowback" aria-hidden="true"></i></a><p class="navbar-brand"><i class="fa fa-calendar-check-o faicon" aria-hidden="true"></i><span>Mailer</span></p>
      </div>
    </div>
  </nav>
<!--content-->
  <div class="content">
  <div class="container-fluid">
    <input type="email" name="mail" placeholder="Your mail address"><br />
    <input type="name" name="name"><br />
    <textarea name="text">Your message here.</textarea>
  </div>
</div>
<!--footer-->
<footer>
  <div class="container-fluid">
    <div class="row">
      <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <span class="footerbrand brand1">Post</span><span class="footerbrand brand2">It</span>
      </div>
      <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <a href="mailer.php" target="_blank">Email<i class="fa fa-envelope-o faicon" aria-hidden="true"></i></a>
        <span class="footerdivider">|</span>
        <a href="legalnotice.html">Legal notice<i class="fa fa-gavel faicon" aria-hidden="true"></i></a>
      </div>
    </div>
  </div>
</div>
<div class="form-group">
</div>
</form>
<!--scripts-->
<script src="https://use.fontawesome.com/2beeaf0fcf.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="login.js"></script>
</body>
</html>
