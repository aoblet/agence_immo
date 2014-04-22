<?php
	session_start();
	$_SESSION = array();
	session_destroy();

	if(isset($_POST['come_from']) && $_POST['come_from']=="result.php"){
		header('Location: ../result.php');
		die();
	}

	header('Location: ../index.php');
	die();