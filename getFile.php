<?php
	if(emtpy($_GET['file'])) return;
	if(file_exists($file)) return;
	if($_SERVER['REMOTE_ADDR'] !== $_SERVER['SERVER_ADDR'])
	echo file_get_contents($file);
?>