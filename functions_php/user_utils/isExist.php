<?php
	require_once(dirname(__FILE__).'/../settings/connexion.php');
	
	function userIsExistFromMail($mail){
		try{
			$stmt = myPDO::getSingletonPDO()->prepare("SELECT * FROM personne WHERE mail =:mail");
			$mail = myPDO::my_escape_string(htmlentities($mail));
			$stmt->execute(array("mail"=>$mail));
			$res = $stmt->fetch();
			$stmt->closeCursor();
			if($res)
				return $res['id_personne'];
			return false;
		}
		catch(PDOException $e){
			die("Probleme PDO userisexist".$e->getMessage());
		}
	}

	function adresseIsExistInDataBase($numero_rue, $rue, $ville, $code_postal){
		if(!is_numeric($numero_rue) && !is_string($rue) && !is_string($ville) && !is_string($code_postal)){
			$rue = strtolower($rue);
			$ville = strtolower($ville);
			$code_postal = trim(strtolower($code_postal));

			$query=<<<SQL
				SELECT 	id_adresse
				FROM 	adresse a
				WHERE  	a.numero_rue = :numero_rue AND 
						LOWER(a.rue) LIKE :rue AND
						LOWER(a.ville) LIKE :ville AND
						a.code_postal = :code_postal
SQL;
			$stmt = myPDO::getSingletonPDO()->prepare($query);
			$stmt->exexute(array(':numero_rue'=>$numero_rue, ':rue'=>"%$rue%", ':ville'=>"%$ville%", ':code_postal'=>$code_postal));

			$res = false;
			if($stmt->fetch)
				$res=true;
			$stmt->closeCursor();

			return $res;
		}
		return false;
	}

	function adresseIsExistInMaps($id_personne, $numero_rue, $rue, $ville, $code_postal){
		$format='xml';

		$adresse =$numero_rue.' '.$rue.' '.$code_postal.' '.$ville;
		$adresse = urlencode(utf8_encode($adresse));
		$url = "http://maps.googleapis.com/maps/api/geocode/$format?address=$adresse&sensor=false";
		$reponse = file_get_contents($url);

		$time_stamp_log = new DateTime(NULL,new DateTimeZone('Europe/Paris'));
		$xml_doc = new DOMDocument();
		$xml_doc->loadXML($reponse);
		
		//log recherche avec id_personne
		try{
			if( ($file_log = fopen(dirname(__FILE__).'/../../user/dashboard/commun/log_maps/'.$id_personne.'_'.$time_stamp_log->getTimestamp().'.xml',"w+")) ){
				fprintf($file_log, "%s", $xml_doc->saveXML());
				fclose($file_log);
			}
		}
		catch(Exception $e){
			return false;
		}

		$xml_response = new DOMDocument('1.0','UTF-8');
		$xml_response->formatOutput=true;
		$element_root = $xml_response->createElement("Adresse");
		$xml_response->appendChild($element_root);

		if(	$xml_doc->getElementsByTagName("status")->item(0)->nodeValue == "OK"){

			if($xml_doc->getElementsByTagName("geometry")->item(0)->getElementsByTagName("location_type")->item(0)->nodeValue != "APPROXIMATE"){

				$rue = ""; $numero_rue=""; $ville=""; $codePostal='';

				foreach ($xml_doc->getElementsByTagName('address_component') as $value) {
					if($value->getElementsByTagName("type")->item(0)->nodeValue == 'street_number'){
						$numero_rue = $value->getElementsByTagName("long_name")->item(0)->nodeValue;
					}

					if($value->getElementsByTagName("type")->item(0)->nodeValue == 'route'){
						$rue = $value->getElementsByTagName("long_name")->item(0)->nodeValue;
					}

					if($value->getElementsByTagName("type")->item(0)->nodeValue == 'locality'){
						$ville = $value->getElementsByTagName("long_name")->item(0)->nodeValue;
					}

					if($value->getElementsByTagName("type")->item(0)->nodeValue == 'postal_code'){
						$codePostal = $value->getElementsByTagName("long_name")->item(0)->nodeValue;
					}
				}	

				if(empty($rue) || empty($numero_rue) ||  empty($ville) ||  empty($codePostal))
					$element_root->appendChild($xml_response->createElement("status","APPROXIMATE"));
				else
					$element_root->appendChild($xml_response->createElement("status","OK"));
					
				$element = $xml_response->createElement("numeroRue",$numero_rue);
				$element_root->appendChild($element);

				$element = $xml_response->createElement("rue",$rue);
				$element_root->appendChild($element);

				$element = $xml_response->createElement("ville",$ville);
				$element_root->appendChild($element);

				$element = $xml_response->createElement("codePostal",$codePostal);
				$element_root->appendChild($element);
			}
			else
				$element_root->appendChild($xml_response->createElement("status","APPROXIMATE"));
		}
		else
			$element_root->appendChild($xml_response->createElement("status","FAIL"));

		return $xml_response;	
	}

	//echo(adresseIsExistInMaps(12, 98, "rue Joseph Gailgregerlard", "Vincgergeennes",94365616165100)->saveXML());

