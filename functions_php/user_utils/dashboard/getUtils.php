<?php
	/**
	*	Partie commune aux dashs
	**/
	require_once(dirname(__FILE__).'/../../settings/connexion.php');
	require_once(dirname(__FILE__).'/../../../enum/enum_type_user.php');


	function getLegitimite($session, $id_bien_immobilier){

		if(!isset($session['id_personne']) || !is_numeric($session['id_personne']))
			return false;
		$id_personne = $session['id_personne'];

		$query="SELECT id_bien_immobilier FROM bien_immobilier WHERE id_bien_immobilier = $id_bien_immobilier ";

		if($session['type_personne'] == PROPRIETAIRE){
			$query.=" AND id_personne_proprio = $id_personne";
		}
		elseif ($session['type_personne'] == EMPLOYE) {
			$query.=" AND id_personne_gest = $id_personne";
		}
		elseif ($session['type_personne'] == LOCATAIRE) {
			$query.=" AND id_personne_locataire = $id_personne";
		}
		else{
			return false;
		}

		$stmt = myPDO::getSingletonPDO()->query($query);
		$res = $stmt->fetch();
		$stmt->closeCursor();

		return ($res ? true : false);
	}

	// on s'assure que l'employe ne fasse pas n'importe quoi pour les messages
	function getLegitimiteEmployeDestinataire($id_personne_gest,$id_destinataire,$id_bien_immobilier){
		$query = "	SELECT 	id_bien_immobilier 
					FROM 	bien_immobilier 
					WHERE 	id_bien_immobilier = $id_bien_immobilier AND 
							id_personne_gest = $id_personne_gest AND
							(id_personne_locataire=$id_destinataire OR id_personne_proprio=$id_destinataire)";
		$stmt = myPDO::getSingletonPDO()->query($query);
		$res = $stmt->fetch();
		$stmt->closeCursor();
		return $res;
	}

	function getIdGestionnaire($id_bien_immobilier){
		$id_gest='';
		$stmt = myPDO::getSingletonPDO()->query("SELECT id_personne_gest FROM bien_immobilier WHERE id_bien_immobilier={$id_bien_immobilier}");
		if($res=$stmt->fetch())
			$id_gest = $res['id_personne_gest'];
		$stmt->closeCursor();

		return $id_gest;
	}


	function getConversation($id_bien_immobilier,$id_personne_destinataire,$id_personne_auteur,$last=false,$traite=true){
		$res = array();

		if($id_personne_destinataire == NULL || $id_bien_immobilier == NULL)
			return $res;

		$condition_last='';$condition_traite='';
		if($last)
			$condition_last='DESC LIMIT 1';
		if(!$traite)
			$condition_traite=" AND (traite=0 OR traite IS NULL) AND id_auteur!=$id_personne_destinataire";

		$query = "	SELECT DISTINCT message.id_message,
						 	message.date_message,
							message.contenu_message,
							message.traite,
							message.id_auteur,
							message.id_destinataire,
							message.id_bien_immobilier,
							(SELECT personne.prenom_personne FROM personne WHERE personne.id_personne = message.id_auteur) AS prenom_auteur, 
							(SELECT personne.nom_personne FROM personne WHERE personne.id_personne = message.id_auteur) AS nom_auteur,
							(SELECT photo.chemin_photo FROM photo,personne WHERE photo.id_photo = personne.id_photo AND personne.id_personne = message.id_auteur) AS photo_auteur,
							(SELECT personne.nom_personne FROM personne WHERE personne.id_personne = message.id_destinataire) AS prenom_destinataire, 
							(SELECT personne.nom_personne FROM personne WHERE personne.id_personne = message.id_destinataire) AS nom_destinataire,
							(SELECT photo.chemin_photo FROM photo,personne WHERE photo.id_photo = personne.id_photo AND personne.id_personne = message.id_destinataire) AS photo_destinataire 
					FROM 	message LEFT JOIN personne ON message.id_destinataire =personne.id_personne
					WHERE 	(	(id_destinataire = $id_personne_destinataire AND id_auteur = $id_personne_auteur) OR
								(id_destinataire = $id_personne_auteur AND id_auteur = $id_personne_destinataire) ) AND 
							id_bien_immobilier = $id_bien_immobilier
							$condition_traite
					ORDER BY date_message $condition_last";

		$stmt = myPDO::getSingletonPDO()->query($query);
		while($ligne = $stmt->fetch())
			$res[] = $ligne;
		$stmt->closeCursor();

		return $res;
	}

	function getMessagesSent($id_bien_immobilier,$id_personne_auteur){
		$res = array();

		if($id_personne_auteur == NULL || $id_bien_immobilier == NULL)
			return $res;
		
		$stmt = myPDO::getSingletonPDO()->query("SELECT * FROM message WHERE id_auteur = $id_personne_auteur AND id_bien_immobilier = $id_bien_immobilier");
		while($ligne = $stmt->fetch())
			$res[] = $ligne;
		$stmt->closeCursor();

		return $res;
	}

	function getNbMessagesNonLus($id_personne){
		if($id_personne == NULL)
			return -1;
		$stmt = myPDO::getSingletonPDO()->query("SELECT COUNT(DISTINCT id_auteur) AS count FROM message WHERE id_destinataire = $id_personne AND (traite=0 OR traite IS NULL)");
		$res=0;
		if($ligne=$stmt->fetch())
			$res = $ligne["count"];

		$stmt->closeCursor();

		return $res;
	}

	function getCountTotalMessagesReceived($id_personne){
		if($id_personne == NULL)
			return -1;

		$stmt = myPDO::getSingletonPDO()->query("SELECT COUNT(*) AS count FROM message WHERE id_destinataire = $id_personne");
		$res=0;
		if($ligne=$stmt->fetch())
			$res = $ligne["count"];

		$stmt->closeCursor();

		return $res;
	}

	function getIdentitePersonne($id_personne){
		$res = array();
		if($id_personne == NULL)
			return $res;

		$query = <<<SQL
		SELECT  personne.nom_personne,
				personne.prenom_personne,
				adresse.code_postal,
				adresse.ville,
				adresse.rue,
				adresse.numero_rue
		FROM 	personne LEFT OUTER JOIN adresse ON personne.id_adresse = adresse.id_adresse
		WHERE 	personne.id_personne = $id_personne
SQL;
		$stmt = myPDO::getSingletonPDO()->query($query);
		if($ligne = $stmt->fetch())
			$res = $ligne;
		$stmt->closeCursor();
		return $res;
	}

	function getLastMessageConversation($id_bien_immobilier,$id_personne_destinataire,$id_personne_auteur){
		return getConversation($id_bien_immobilier,$id_personne_destinataire,$id_personne_auteur,true);
	}

	function getIdAgence(){
		$stmt = myPDO::getSingletonPDO()->query("SELECT id_agence_immobiliere FROM agence_immobiliere");
		$res=-1;
		if($ligne=$stmt->fetch())
			$res = $ligne['id_agence_immobiliere'];
		$stmt->closeCursor();
		return $res;
	}
