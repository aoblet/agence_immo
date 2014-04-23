<?php

	$hash = sha1('password');
	echo $hash;

	$date = new DateTime("2014-04-08 00:00:00");
	$date_current = new DateTime();
	$date_diff = $date_current->diff($date);
	var_dump($date_diff);

	var_dump($date);
	var_dump($date_current);

	echo $date_current->format("d/m/Y");