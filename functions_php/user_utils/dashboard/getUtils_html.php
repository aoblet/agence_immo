<?php
	/**
	*	Partie commune aux dashs
	**/
	require_once(dirname(__FILE__).'/../getUtils_html.php');
	require_once(dirname(__FILE__).'/../../../enum/enum_type_user.php');

	function getBanniereDash(){
		$link_home = getPathRoot();
		return <<<HTML
			<h1>Dashboard</h1>

			<div class="container" >
				<div class="row">
					<div class="col-md-12">
						<div class="dash-nav">
							<ul>
								<li><a href="$link_home" class="button-home"><i class="fa fa-reply"></i>Retour au site</a></li>
							</ul>
						</div>
					</div>
				</div>

			</div>

HTML;
	}

	function getMenuAccueil($type_personne,$is_on_accueil){
		$link_employe_add_bien='';

		$infos_persos_link = getPathRoot().'user/dashboard/';
		if($type_personne == LOCATAIRE){
			$infos_persos_link.="locataire/infos.php";
		}
		else if($type_personne == PROPRIETAIRE){
			$infos_persos_link.="proprietaire/infos.php";
		}
		else if($type_personne == EMPLOYE){
			$link_messages_a=getPathRoot().'user/dashboard/employe/messages.php';
			$link_ajout_bien_a=getPathRoot().'user/dashboard/employe/ajoutBien.php';
			$link_employe_add_bien="<a href='$link_ajout_bien_a' class='button-home'><i class='fa fa-pencil'></i>Ajouter un bien</a>";
		}
		else
			$infos_persos_link='';

		$link_dash=''; $link_dash_a=''; $link_messages="";
		
		if(!$is_on_accueil){
			$link_dash_a = getPathRoot().'user/dashboardGateWay.php';
			$link_dash="<a href='$link_dash_a' class='button-home'><i class='fa fa-reply'></i>Retour au Dash</a>";
		}
		$link_messages_a=getPathRoot().'user/dashboard/'.strtolower($type_personne).'/messages.php';
		$link_messages =" <a href='$link_messages_a' class='button-home'><i class='fa fa-envelope'></i>Mes messages</a> ";

		return <<<HTML
		<div class="col-md-3" >
			<div class="dash-menu bg-white" >
				<h4>MENU</h4>
				<a href="$infos_persos_link" class="button-home"><i class="fa fa-user"></i>Mes données personnelles</a>
				$link_messages
				$link_employe_add_bien
				$link_dash
			</div>
		</div>

HTML;
	}

	function getMenuOnMessage($type_personne){
		$infos_persos_link = getPathRoot().'user/dashboard/';
		$mes_messages_link = getPathRoot().'user/dashboard/';
		$link_ajout_bien='';
		if($type_personne == LOCATAIRE){
			$infos_persos_link.="locataire/infos.php";
			$mes_messages_link.="locataire/messages.php";
		}
		else if($type_personne == PROPRIETAIRE){
			$infos_persos_link.="proprietaire/infos.php";
			$mes_messages_link.="proprietaire/messages.php";

		}
		else if($type_personne == EMPLOYE){
			$infos_persos_link.="employe/infos.php";
			$mes_messages_link.="employe/messages.php";
			$link_ajout_bien_a=getPathRoot().'user/dashboard/employe/ajoutBien.php';
			$link_ajout_bien="<a href='$link_ajout_bien_a' class='button-home'><i class='fa fa-pencil'></i>Ajouter un bien</a>";
		}
		else{
			$infos_persos_link='';
			$mes_messages_link="";
		}

		return <<<HTML
		<div class="col-md-3" >
			<div class="dash-menu bg-white" >
				<h4>MENU</h4>
				<a href="$infos_persos_link" class="button-home"><i class="fa fa-user"></i>Mes données personnelles</a>
				<a href="$mes_messages_link" class="button-home"><i class="fa fa-envelope"></i>Mes messages</a>
				$link_ajout_bien
				<a href="./" class="button-home"><i class="fa fa-reply"></i>Retour au Dash</a>
				<!--<a href="" class="button-home"><i class="fa fa-envelope"></i>Contacter l'agence</a>-->
			</div>
		</div>

HTML;
	}

	function getModalsInfos(){
		return <<<HTML
		<div class="user-modal"></div>

		<div class="user-modal-okay user-box-modal" id='find_ok'>
			<div id="adress-okay">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="user-modal-content bg-white">
								<span class='user-close-modal' title='Annuler' ><i class="fa fa-times"></i></span>
								<h1><i class="fa fa-check"></i></h1>
								<p>Nous avons trouvé votre adresse, veuillez cliquer pour confirmer le changement</p>
								<input type="submit" value="Mettre à jour" >
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="user-modal-clarify user-box-modal" id='find_approximate'>
			<div id="adress-clarify">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="user-modal-content bg-white">
								<span class='user-close-modal' title='Annuler'><i class="fa fa-times"></i></span>
								<h1><i class="fa fa-search"></i></h1>
								<p>L'adresse est trop aproximative, veuillez affiner.</p>
								<input type="submit" value="Affiner l'adresse">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="user-modal-error user-box-modal" id='find_fail'>
			<div id="adress-error">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="user-modal-content bg-white">
								<span class='user-close-modal' title='Annuler'><i class="fa fa-times"></i></span>
								<h1><i class="fa fa-times"></i></h1>
								<p>L'adresse n'a pas été trouvée, veuillez recommencer.</p>
								<input type="submit" value="Recommencer" >
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

HTML;
	}

	function getJsForModalInfosPersos($nom_formulaire){
		return <<<JAVASCRIPT


		function showScrollBar(){
			$('html, body').css({
			    'overflow': 'visible',
			    'height': '100%'
			});
		}

		function hideScrollBar(){
			$('html, body').css({
			    'overflow': 'hidden',
			    'height': '100%'
			});
		}
		function handleModals(){
			$(function(){
				//comportement par défaut
				$( ".user-modal" ).hide();
				$( ".user-modal-okay" ).hide();
				$( ".user-modal-clarify" ).hide();
				$( ".user-modal-error" ).hide();
				$(".user-close-modal").css('opacity',0.2);

				//comportement close-modal
				$( ".user-box-modal").hover(function(){
					$(".user-close-modal").fadeTo('slow',1);
				});

				$( ".user-box-modal").mouseleave(function(){
					$(".user-close-modal").fadeTo('slow',0.2);
				});

				$( ".user-close-modal" ).click(function(){
					$(".user-modal").fadeOut('slow',showScrollBar);
					$(".user-box-modal").fadeOut('slow');
					
				});
				
				$('.user-close-modal').mouseleave(function(){
					$(this).addClass('user-close-modal-color-down');
				});
				
				//comportement box-modal
				var find_form = $("input[name='find']")[0];

				if(find_form){
					var id_modal = 'find_'+find_form.value;

					$('#'+id_modal+" input[type='submit']").click(function(e){
						e.preventDefault();
					});

					if(find_form.value=='ok'){
						//traitement spécial: envoi formulaire
						$('#'+id_modal+" input[type='submit']").click(function(){
							$('#'+id_modal).fadeOut('slow', function (){
								$("form[name='$nom_formulaire']").submit();
							});
						});
					}
					else{
						$('#'+id_modal+" input[type='submit']").click(function(){
							$( ".user-modal" ).fadeOut('slow',showScrollBar);
							$('#'+id_modal).fadeOut('slow');
							
						});
					}
					//on affiche
					$( ".user-modal" ).fadeIn();
					$( '#'+id_modal).fadeIn();
					hideScrollBar();
				}

			});
		}
		
		handleModals();
JAVASCRIPT;
	}

	function getModalNewNotifsMessage(){
		return "<div id='new-messages-modal'></div>";
	}
