<?php
	require_once(dirname(__FILE__).'/../user_utils/dashboard/getUtils_html.php');


	function getMessageHTML($id_bien_immobilier, $id_personne_destinataire){
		$conversation = getConversation($id_bien_immobilier,$id_personne_destinataire);
		if(empty($conversation)){
			//traité si pas de messages..
			return "<div class='contact-direct'>
						PAS DE MESSAGE !!
					</div>";
		}
		var_dump($conversation);
	}