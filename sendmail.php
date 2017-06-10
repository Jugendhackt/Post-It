<?php
if(isset($_POST['name']) && isset($_POST['mail']) && isset($_POST['text'])){
	$message = "The User \"".$_POST['name']."\" wrote:\n\n\"".$_POST['text']."\"";
	$subject = "PostIt user mail";
	$header = 'From: '.$_POST['mail'].' \r\n Reply-To: webmaster@example.com \r\n X-Mailer: PHP/'.phpversion();
	mail("jasper.michalke@jasmich.de", $subject, $message, $header);
}
else{
	header("Location: mailer.php");
}
?>