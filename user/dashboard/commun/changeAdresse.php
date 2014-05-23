<?php
	require_once(dirname(__FILE__).'/../../../functions_php/settings/connexion.php');
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/isExist.php');
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/getUtils_html.php');
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/ajoutAdresse.php');
	require_once(dirname(__FILE__).'/../../../enum/enum_type_user.php');
	session_start();

	if(!isset($_SESSION['id_personne']) || empty($_SESSION['id_personne'])){
		$link_home = getPathRoot().'index.php';
		header('Location: '.$link_home,false,301);
		die();
	}

	$url_redirect = getPathRoot().'user/dashboard/';

	if($_SESSION['type_personne'] == PROPRIETAIRE)
		$url_redirect.="proprietaire/";
	else if($_SESSION['type_personne'] == LOCATAIRE)
		$url_redirect.="locataire/";
	else if($_SESSION['type_personne'] == EMPLOYE)
		$url_redirect.="employe/";
	else{
		//par sécurité
		$link_home = getPathRoot().'index.php';
		header('Location: '.$link_home,false,301);
		die();
	}

	$url_redirect.="infos.php";


	if(!empty($_POST['rue']) && !empty($_POST['numero_rue']) && !empty($_POST['ville']) && !empty($_POST['code_postal'])){
		$id_adresse_existante = adresseIsExistInDataBase($_POST['numero_rue'],$_POST['rue'],$_POST['ville'],$_POST['code_postal']);
		if($id_adresse_existante){
			if(empty($_POST['confirm']) || $_POST['confirm'] == 0){
				urlEncodeArray($_POST);
				$url_redirect.="?rue={$_POST['rue']}&numero_rue={$_POST['numero_rue']}&ville={$_POST['ville']}&code_postal={$_POST['code_postal']}&confirm=1&find=ok";
			}
			else{
				$stmt=myPDO::getSingletonPDO()->query("UPDATE personne SET id_adresse ={$id_adresse_existante['id_adresse']} WHERE id_personne={$_SESSION['id_personne']}");
				$stmt->closeCursor();
			}
		}
		else{
			// si pas dans la BD, on va chercher avec maps
			$xml_response = adresseIsExistInMaps($_SESSION['id_personne'], $_POST['numero_rue'], $_POST['rue'], $_POST['ville'], $_POST['code_postal']);
			
			if($xml_response == false || $xml_response->getElementsByTagName("status")->item(0)->nodeValue == 'FAIL'){
				$url_redirect.="?find=fail";
			}
			else if ($xml_response->getElementsByTagName("status")->item(0)->nodeValue == 'APPROXIMATE'){
				$url_redirect.="?find=approximate";
			}
			else{
				// SUCCESS
				$rue 		= $xml_response->getElementsByTagName("rue")->item(0)->nodeValue;
				$numero_rue = $xml_response->getElementsByTagName("numeroRue")->item(0)->nodeValue ;
				$ville 		= $xml_response->getElementsByTagName("ville")->item(0)->nodeValue ;
				$code_postal= $xml_response->getElementsByTagName("codePostal")->item(0)->nodeValue ;

				if(empty($_POST['confirm']) || $_POST['confirm'] == 0){
					$array_to_encode=array(&$rue,&$numero_rue,&$ville,&$code_postal);
					urlEncodeArray($array_to_encode);
					$url_redirect.="?rue=$rue&numero_rue=$numero_rue&ville=$ville&code_postal=$code_postal&confirm=1&find=ok";
				}
				else{
					//insertion nouvelle adresse dans la bd puis update sur la personne								
					$id_insert = addAdresse($ville,$numero_rue,$rue,$code_postal);
					$stmt=myPDO::getSingletonPDO()->query("UPDATE personne SET id_adresse=$id_insert WHERE id_personne={$_SESSION['id_personne']}");
					$stmt->closeCursor();
				}
			}
		}
	}
	else
		$url_redirect.="?find=fail";
	
	header('Location: '.$url_redirect);
	die();