<?php
	require_once(dirname(__FILE__).'/isExist.php');
	require_once(dirname(__FILE__).'/../settings/connexion.php');

	function addAdresse($ville,$numero_rue,$rue,$code_postal,$code_postal){
		$query_insert = "INSERT INTO adresse (ville,numero_rue,rue,code_postal,id_departement)
						VALUES ( :ville, :numero_rue, :rue, :code_postal, (SELECT id_departement FROM departement WHERE code_departement = :code_departement))";
		$stmt = myPDO::getSingletonPDO()->prepare($query_insert);
		$stmt->execute(array(':ville'=>$ville,':numero_rue'=>$numero_rue,':rue'=>$rue, ':code_postal'=>$code_postal,':code_departement'=>substr($code_postal, 0,2)));
		$stmt->closeCursor();
		
		$id_inserted = (int) myPDO::getSingletonPDO()->lastInsertId();
		return $id_inserted;
	}