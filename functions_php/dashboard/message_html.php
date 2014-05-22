<?php
	require_once(dirname(__FILE__).'/../user_utils/dashboard/getUtils_html.php');
	require_once(dirname(__FILE__).'/../user_utils/dashboard/getUtils.php');
	require_once(dirname(__FILE__).'/../user_utils/getUtils.php');
	require_once(dirname(__FILE__).'/../user_utils/getUtils_html.php');
	require_once(dirname(__FILE__).'/../../enum/enum_type_user.php');
	require_once(dirname(__FILE__).'/../recherche_biens/getInfos.php');



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

			// si j > 15 on affiche la date dd/mm/yy
			if($date_diff->m==0 || $date_diff->d<15){
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
					$date_return =' Il y a '.$date_diff->d.' j';
				}
			}
			else{
				$date_temp = new DateTime($date_to_compare_timestamp,new DateTimeZone('Europe/Paris'));
				$date_return = $date_temp->format('d/m/Y');
			}
		}
		return $date_return;
	}

	function getMessageHTML($id_bien_immobilier,$id_personne_destinataire,$id_personne_auteur,&$cpt_message){
		$cpt_message='';

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
			$cpt=0;
			foreach ($conversation as $value) {
				$cpt++;

				if($value['id_auteur'] != $id_personne_auteur)	//on met a droite l'autre interlocuteur, clase AGENCE
					//message provenant de l'agence
					$classe = "contact-direct-mess contact-direct-AGENCE margin15";
				else
					$classe = "contact-direct-mess contact-direct-USER margin15";

				$link_photo = getPathRoot().$value['photo_auteur'];
				$prenom_nom=$value['prenom_auteur'].' '.$value['nom_auteur'];
				$date_formated = getDateFormatedConversation($value['date_message']);
				$contenu_message=$value['contenu_message'];

				$ancre='';
				if($cpt == sizeof($conversation))
					$ancre = "id='last_message_ancre'";

				$html.=<<<HTML
					<div class="$classe">
						<div class="contact-direct-pic">
							<img src="$link_photo" alt='photo_personne'>
						</div>
						<div class="contact-direct-mess">
							<div class="contact-direct-author">
								<h4>$prenom_nom <span>$date_formated</span> </h4>
								
							</div>
							<div class="contact-direct-message bg-white">
								<div class="contact-direct-message-triangle" $ancre></div>
								<p>$contenu_message
							</p>
							</div>
						</div>
					</div>
					<div class="mess-separ"></div>
HTML;
			}
		}
		$cpt_message = '( '.$cpt.' )';

		$traitement_formulaire=getPathRoot().'user/dashboard/commun/sendMessage.php';
		$come_from = $_SERVER['PHP_SELF'].'?';
		$cpt=1;
		foreach ($_GET as $key => $value) {
			$come_from.=$key.'='.$value;
			if($cpt != sizeof($_GET))
				$come_from.='&';
			$cpt++;
		}
		$come_from.="#last_message_ancre";


		$html.=<<<HTML
				<div class="send-mess-user">
					<form name="form_send_message" action='$traitement_formulaire' method='POST'>
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

	function raccourciSendMessageJquery(){
		return <<<JAVASCRIPT
		function listenerRaccourciSend(){
			$(function(){
				var isCtrlFormMessage = false;
				$("textarea[name='contenu_message']").keydown(function(e){
					if(e.keyCode == 17)//ctrl
						isCtrlFormMessage=true;

					if(isCtrlFormMessage && e.keyCode == 13 && $("textarea[name=contenu_message]").val() != '')//enter
						$("form[name=form_send_message]").submit();
				});

				$("textarea[name='contenu_message']").keyup(function(e){
					if(e.keyCode == 17)
						isCtrlFormMessage = false;
				});
			});
		}

		listenerRaccourciSend();

JAVASCRIPT;
	}

	function getHandleJsMiniViewMessages(){
		return <<<JAVASCRIPT
		function handleMiniViewMessages(){
			$(function(){
				var bool =0;
				$( ".fa-message" ).click(function() {
					if (bool == 0) {
					  	$( "#new-messages" ).css("display","block");
					  	$( "#new-messages-modal" ).css("display","block");
					  	bool =1;
				  	}else{
				  		$( "#new-messages" ).css("display","none");
				  		$( "#new-messages-modal" ).css("display","none");
					  	bool =0;
				  	}
				});
				$( "#new-messages-modal" ).click(function() {
				  		$( "#new-messages" ).css("display","none");
				  		$( "#new-messages-modal" ).css("display","none");
					  	bool =0;
				});
			});
		}

		handleMiniViewMessages();
JAVASCRIPT;
	}


	function getInfosForListeMessages($id_personne,$type_personne){
		if($id_personne == NULL)
			return NULL;

		$condition='';
		if($type_personne == LOCATAIRE)
			$condition = "bien_immobilier.id_personne_locataire = $id_personne";
		else if($type_personne == PROPRIETAIRE)
			$condition = "bien_immobilier.id_personne_proprio = $id_personne";
		else if($type_personne == EMPLOYE)
			$condition = "bien_immobilier.id_personne_gest = $id_personne";

		$query = <<<SQL
			SELECT 	bien_immobilier.id_bien_immobilier,
				 	bien_immobilier.id_personne_gest,
					bien_immobilier.id_personne_locataire,
					bien_immobilier.id_personne_proprio
			FROM 	bien_immobilier
			WHERE 	$condition
SQL;

		$stmt=myPDO::getSingletonPDO()->query($query);
		$res=array();
		while($ligne=$stmt->fetch()){
			$res[] = $ligne;
		}
		$stmt->closeCursor();
		return $res;
	}

	function getListeMessagesForNotifs($session){
		$html='';
		$liste='';
		$cpt=0;
		$infos_biens = getInfosForListeMessages($session['id_personne'],$session['type_personne']);
		
		$liste='';
		foreach ($infos_biens as $key => $value) {
			$array_to_loop_multiple_destinataire=array();

			if($session['type_personne'] == EMPLOYE){
				if($value['id_personne_proprio'] != NULL)
					$array_to_loop_multiple_destinataire[] = array('id_personne_destinataire'=>$value['id_personne_proprio']);
				if($value['id_personne_locataire'] != NULL)
					$array_to_loop_multiple_destinataire[] = array('id_personne_destinataire'=>$value['id_personne_locataire']);
			}
			else
				$array_to_loop_multiple_destinataire[] = array('id_personne_destinataire'=>$value['id_personne_gest']);

			foreach ($array_to_loop_multiple_destinataire as $value_multiple_destinataire) {
				$conversation = getConversation($value['id_bien_immobilier'],$session['id_personne'],$value_multiple_destinataire['id_personne_destinataire'],true,false);
				if($conversation){
					$cpt++;
					$infos_personne = getIdentitePersonne($value_multiple_destinataire['id_personne_destinataire']);
					$photo_personne = getPathRoot().getPhotoPersonne($value_multiple_destinataire['id_personne_destinataire'])[0];
					$prenom_nom = $infos_personne['prenom_personne'].' '.$infos_personne['nom_personne'];
					$apercu_message = mb_substr($conversation[0]['contenu_message'], 0,25,"utf-8").'...';
					$link_message = getPathRoot().'user/dashboard/commun/messageGateway.php?id_bien_immobilier='.$value['id_bien_immobilier'];

					if($session['type_personne'] == EMPLOYE)
						$link_message.="&id_destinataire=".$value_multiple_destinataire['id_personne_destinataire'];

					$liste.=<<<HTML
					<a href="$link_message">
						<div class="new-message">
							<div class="new-message-pic">
								<img src="$photo_personne" alt='photo_personne'>
							</div>
							<div class="new-message-desc">
								<span>$prenom_nom</span>
								<p>$apercu_message</p>
							</div>
						</div>
					</a>
HTML;
				}
			}
		}

		$link_all = getPathRoot().'user/dashboard/'.strtolower($session['type_personne']).'/messages.php';
		if($cpt == 0 || $cpt == 1)
			$phrase_message = 'Nouveau message';
		else
			$phrase_message='Nouveaux messages';

		$html=<<<HTML
		<div id="new-messages" class="bg-white">
			<div class="top-new-messages">
				<p>$phrase_message (<span>$cpt</span>)</p>
				<div class="triangle-message"></div>
			</div>
HTML;
		$all=<<<HTML
			<div class="bottom-new-messages">
				<a href="$link_all">Afficher tous les messages</a>
			</div>
		</div>
HTML;
		$html.=$liste.$all;
		return $html;
	}

	function getListMessagesHTML($id_personne,$type_personne){

		$infos = getInfosForListeMessages($id_personne,$type_personne);	
		$html='';
		
		foreach ($infos as $value) {
			$array_to_loop_multiple_destinataire=array();

			if($type_personne == EMPLOYE){
				if($value['id_personne_locataire'] != NULL)
					$array_to_loop_multiple_destinataire[]=array('type_personne'=>'locataire','id_personne_destinataire'=>$value['id_personne_locataire']);
				if($value['id_personne_proprio'] != NULL)	
					$array_to_loop_multiple_destinataire[]=array('type_personne'=>'prorietaire','id_personne_destinataire'=>$value['id_personne_proprio']);
			}
			else{
				$array_to_loop_multiple_destinataire[] = array('type_personne'=>'','id_personne_destinataire'=>$value['id_personne_gest']);
			}

			foreach ($array_to_loop_multiple_destinataire as $value_multiple_destinataire) {
				$conversation = getConversation($value['id_bien_immobilier'],$id_personne,$value_multiple_destinataire['id_personne_destinataire'],true);
				$type_personne_info = ucfirst($value_multiple_destinataire['type_personne']); 
				$infos_adresse = getInfosAdresse($value['id_bien_immobilier']);

				$apercu_message=''; $nom=''; $prenom=''; $photo=''; $classe_new=''; $traite='';
				$infos_heure='Discussion vide';

				if($conversation){
					$infos_heure = getDateFormatedConversation($conversation[0]['date_message']);
					if($conversation[0]['id_auteur'] != $id_personne && !$conversation[0]['traite'])
						$traite='new';
					$apercu_message = mb_substr($conversation[0]['contenu_message'], 0,35,"utf-8").' ...';
				}


				if($infos_adresse){
					$adresse= $infos_adresse['numero_rue'].' '.mb_substr($infos_adresse['rue'],0,20,"utf-8").', '.$infos_adresse['code_postal'].' '.mb_substr($infos_adresse['ville'],0,30,"utf-8");
				}

				if( ($infos_personne=getIdentitePersonne($value_multiple_destinataire['id_personne_destinataire']))){
					$photo=getPathRoot().getPhotoPersonne($value_multiple_destinataire['id_personne_destinataire'])[0];
					$nom = $infos_personne['nom_personne'];
					$prenom=$infos_personne['prenom_personne'];
				}

				//lien du message
				$href=getPathRoot().'user/dashboard/commun/messageGateway.php?id_bien_immobilier='.$value['id_bien_immobilier'];
				if($type_personne == EMPLOYE)
					$href.="&id_destinataire=".$value_multiple_destinataire['id_personne_destinataire'];

				$html.=<<<HTML

				<div class="message-spe margin15  $traite">
					<a href="$href">
						<div class="message-spe-pic">
							<img src="$photo" alt='photo_personne'>
						</div>
						<div class="message-spe-title bg-white">
							<h2>$prenom <span>$nom</span> $type_personne_info</h2>
							<h3>$infos_heure</h3>
						</div>
						<div class="message-spe-desc bg-white">
							<p>$adresse <B class="message-spe-apercu">$apercu_message</B></p>
						</div>
					</a>
				</div>
HTML;
			}
		}
		
		return $html;
	}
