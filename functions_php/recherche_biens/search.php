<?php
	require_once('../settings/connexion.php');

	function getAllBiensFromType($type){
		try{
			$stmt = myPDO::getSingletonPDO()->prepare("SELECT * FROM {$type}");
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


