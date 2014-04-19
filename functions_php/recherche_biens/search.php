<?php
	require_once('../settings/connexion.php');

	function getAllBiensFromType($type){
		try{
			$stmt = $bdd->prepare("SELECT * FROM {$type}");
			$stm->excute();
			$stmt->closeCursor();
			return $stmt;
		}
		catch(Exception $e){
			return false;
		}	
	}

