<?php
	// a modifier en fonction de la bd
	// par sécu try catch
	
	try{
		$bdd = new PDO('mysql:host=localhost; dbname=agence_immo','root','');
	}
	catch(Exception $e){
		die("Erreur connexion base de donnees: ".$e->getMessage());
	}
