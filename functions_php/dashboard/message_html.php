<?php
	require_once(dirname(__FILE__).'/../user_utils/dashboard/getUtils_html.php');
	require_once(dirname(__FILE__).'/../user_utils/dashboard/getUtils.php');
	require_once(dirname(__FILE__).'/../user_utils/getUtils_html.php');

	function getDateFormatedConversation($date_to_compare_timestamp){
		$date_return		='';
		if(!empty($date_to_compare_timestamp)){
			try{
				$time_zone 			= new DateTimeZone('Europe/Paris');
				$date_to_compare 	= new DateTime($date_to_compare_timestamp, $time_zone);
				$date_current 		= new DateTime(NULL,$time_zone);
				$date_diff 			= $date_current->diff($date_to_compare);
				$time_zone 			= NULL; //opti
			}
			catch(Exception $e){
				return '';
			}

			// si heure > 24 on affiche la date dd/mm/yy
			if($date_diff->d==0){
				if($date_diff->h==0){
					if($date_diff->i==0){
						$date_return = 'Il y a '.$date_diff->s.' s';
					}
					else{
						$date_return = 'Il y a '.$date_diff->i.' m';
					}
				}
				else{
					$date_return = 'Il y a '.$date_diff->h.' h';
				}
			}
			else{
				$date_temp = new DateTime($date_to_compare_timestamp,new DateTimeZone('Europe/Paris'));
				$date_return = $date_temp->format('d/m/Y');
			}
		}
		return $date_return;
	}

	function getIdGestionnaire($id_bien_immobilier){
		$id_gest='';
		$stmt = myPDO::getSingletonPDO()->query("SELECT id_personne_gest FROM bien_immobilier WHERE id_bien_immobilier={$id_bien_immobilier}");
		if($res=$stmt->fetch())
			$id_gest = $res['id_personne_gest'];
		$stmt->closeCursor();

		return $id_gest;
	}

	function getMessageHTML($id_bien_immobilier,$id_personne_destinataire,$id_personne_auteur){

		$conversation = getConversation($id_bien_immobilier,$id_personne_destinataire,$id_personne_auteur);

		$html=<<<HTML
			<div class="contact-direct">
				<div class="margin30"></div>
HTML;

		if(empty($conversation)){
			//traité si pas de messages..
			$html.= "<div class='contact-direct-message bg-white' style='padding:30px'>
							<div class='contact-direct-message-triangle'></div>
							<p>La conversation avec votre gestionnaire n'est pas encore commencée.</p>
					</div>";
		}
		else{
			foreach ($conversation as $value) {
				if($value['id_auteur'] != $id_personne_auteur)	//on met a droite l'autre interlocuteur, clase AGENCE
					//message provenant de l'agence
					$classe = "contact-direct-mess contact-direct-AGENCE margin15";
				else
					$classe = "contact-direct-mess contact-direct-USER margin15";

				$link_photo = getPathRoot().$value['photo_auteur'];
				$prenom_nom=$value['prenom_auteur'].' '.$value['nom_auteur'];
				$date_formated = getDateFormatedConversation($value['date_message']);
				$contenu_message=$value['contenu_message'];

				$html.=<<<HTML
					<div class="$classe">
						<div class="contact-direct-pic">
							<img src="$link_photo">
						</div>
						<div class="contact-direct-mess">
							<div class="contact-direct-author">
								<h4>$prenom_nom <span>$date_formated</span> </h4>
								
							</div>
							<div class="contact-direct-message bg-white">
								<div class="contact-direct-message-triangle"></div>
								<p>$contenu_message
							</p>
							</div>
						</div>
					</div>
					<div class="mess-separ"></div>
HTML;
			}
		}
		
		$traitement_formulaire=getPathRoot().'user/dashboard/commun/send_message.php';
		$come_from = $_SERVER['PHP_SELF'].'?';
		foreach ($_GET as $key => $value) {
			$come_from.=$key.'='.$value.'&#send_message_ancre';
		}

		$html.=<<<HTML
				<div class="send-mess-user" id='send_message_ancre'>
					<form action='$traitement_formulaire' method='POST'>
						<div class="send-mess-user-triangle"></div>
						<textarea name='contenu_message' required placeholder="Envoyer un message..."></textarea>
						<input type='hidden' name='come_from' value='$come_from'>
						<input type="submit" value="Envoyer"/>
					</form>
				</div>
			</div>
HTML;

		return $html;
	}
