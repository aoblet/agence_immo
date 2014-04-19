<?php
	require_once('../settings/connexion.php');

	function getAllBiensFromType($type){
		try{
			$stmt = $bdd->prepare("SELECT * FROM {$type}");
			$stm->excute();
			$res = array();

			while($ligne = $stmt->fetch()){
				$res[]=$ligne;
			}
			
			$stmt->closeCursor();
			return $res;
		}
		catch(Exception $e){
			return false;
		}	
	}



