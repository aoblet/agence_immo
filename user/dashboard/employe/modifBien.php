<?php
	require_once(dirname(__FILE__).'/../../../enum/enum_type_user.php'); 
	require_once(dirname(__FILE__).'/../../../enum/enum_type_biens.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/recherche_biens/search.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/getUtils_html.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/dashboard/getUtils_html.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/dashboard/getUtils.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/dashboard/employe/affichage_result.php'); 
	session_start();

	if(empty($_SESSION['id_personne']) || $_SESSION['type_personne'] != EMPLOYE || empty($_GET['id_bien_immobilier'])){
		header('Location: '.getPathRoot().'index.php',false,301);
		die();
	}

	if(!getLegitimite($_SESSION,$_GET['id_bien_immobilier'])){
		header('Location: '.getPathRoot().'user/dashboardGateway.php',false,301);
		die();
	}

	$statut_reponse=''; $message_reponse='';
	if(!empty($_GET['statut_reponse']) && ($_GET['statut_reponse'] == 'ok' || $_GET['statut_reponse'] =='fail')){
		$statut_reponse= $_GET['statut_reponse'];
		if(!empty($_GET['message_reponse']))
			$message_reponse=urldecode(utf8_decode($_GET['message_reponse']));
	}

	$infos_bien = searchForEmployeAvance($_GET['id_bien_immobilier'])[0];

	$id_chauffage_seleced		= !empty($infos_bien['infos_chauffage']['id_type_chauffage']) ? $infos_bien['infos_chauffage']['id_type_chauffage']  : '' ;
	$id_gaz_selected			= !empty($infos_bien['infos_gaz']['id_gaz']) 				? $infos_bien['infos_gaz']['id_gaz'] : '' ;
	$id_energetique_selected	= !empty($infos_bien['infos_conso_energetique']['id_consommation_energetique']) ? $infos_bien['infos_conso_energetique']['id_consommation_energetique'] : '' ;

	$id_locataire_selected		= !empty($infos_bien['id_personne_locataire'])			? $infos_bien['id_personne_locataire']  : '' ;
	$id_departement_selected	= !empty($infos_bien['infos_adresse']['id_departement'])? $infos_bien['infos_adresse']['id_departement'] : '' ;
	$id_type_bien_selected		= !empty($infos_bien['info_type_bien']) 				? strtolower($infos_bien['info_type_bien']) : '' ;		
	$numero_rue_form			= !empty($infos_bien['infos_adresse']['numero_rue']) 	? $infos_bien['infos_adresse']['numero_rue'] : '' ;
	$rue_form 					= !empty($infos_bien['infos_adresse']['rue']) 			? $infos_bien['infos_adresse']['rue']  : '' ;
	$code_postal_form			= !empty($infos_bien['infos_adresse']['code_postal']) 	? $infos_bien['infos_adresse']['code_postal'] : '' ;
	$ville_form					= !empty($infos_bien['infos_adresse']['ville']) 		? $infos_bien['infos_adresse']['ville']  : '' ;
	$prix_form					= !empty($infos_bien['prix']) 							? $infos_bien['prix'] : '0' ;
	$superficie_form			= !empty($infos_bien['superficie']) 					? $infos_bien['superficie']  : '0' ;
	$superficie_jardin_form		= !empty($infos_bien['superficie_jardin']) 				? $infos_bien['superficie_jardin']  : '0' ;
	$nb_pieces_form				= !empty($infos_bien['nb_pieces']) 						? $infos_bien['nb_pieces']  : '0' ;
	$nb_etage_form				= !empty($infos_bien['nb_etages']) 						? $infos_bien['nb_etages']  : '0' ;
	$etage_appart_form			= !empty($infos_bien['etage']) 							? $infos_bien['etage']  : '0' ;
	$numero_appart_form			= !empty($infos_bien['numero_appartement']) 			? $infos_bien['numero_appartement'] : '' ;
	$description_form			= !empty($infos_bien['descriptif']) 					? $infos_bien['descriptif'] : '' ;
	$id_proprietaire_selected	= !empty($infos_bien['id_personne_proprio']) || !empty($infos_bien['id_agence_loueur']) || !empty($infos_bien['id_agence_loueur']) ? 
								  $infos_bien['id_personne_proprio'] || $infos_bien['id_agence_loueur'] || $infos_bien['id_agence_loueur'] : '' ;

	$ascenseur_oui_form			=  empty($infos_bien['ascenseur']) 						?  '' : 'checked' ;
	$ascenseur_non_form			=  empty($infos_bien['ascenseur']) 						?  'checked' : '' ;
	$parking_oui_form			=  empty($infos_bien['parking']) 						?  '' : 'checked' ;
	$parking_non_form			=  empty($infos_bien['parking']) 						?  'checked' : '' ;
	$checked_location			= !empty($infos_bien['info_type_achat_location']) && strtolower($infos_bien['info_type_achat_location']) =='location' ? 'checked' : '' ;
	$checked_vente				= !empty($infos_bien['info_type_achat_location']) && strtolower($infos_bien['info_type_achat_location']) =='vente' ? 'checked' : '' ;
		
	$infos_adresse = $infos_bien['infos_adresse'];
	$adresse_rue = $infos_adresse['rue'];
	$adresse_numero_rue = $infos_adresse['numero_rue'];
	$adresse_ville = $infos_adresse['ville'];
	$adresse = $adresse_numero_rue.' '.$adresse_rue.', '.$adresse_ville.' ( '.$infos_adresse['code_postal'].' )';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="apple-touch-icon" href="icon.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

		<title>Agence Immo</title>

		<link rel='stylesheet' href='../../../css/pushy.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../../../css/bootstrap.min.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../../../css/flexslider.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../../../css/default.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../../../css/dash.css' type='text/css' media='all' />



		<link rel='stylesheet' href='../../../css/fonts/font-awesome.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../../../css/fonts/font-awesome-animation.css' type='text/css' media='all' />

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


		<script type="text/javascript" src="../../../js/modernizr.custom.js"></script>
		<script type="text/javascript" src='../../../js/main.js'></script>
		<script type="text/javascript" src='../../../js/Chart.js'></script>
	</head>

	<body>

		<?php echo getModalsInfos();?>
		<header>
			<?php echo getBanniereConnected($_SESSION) ?>
			<?php echo getBanniereDash() ?>
		</header>


	<section class="bg-grey first-section" style="padding-top:60px;">


		<div class="container ">
			<div class="row">

				<?php echo getMenuAccueil($_SESSION['type_personne'],false) ?>

				<div class="col-md-9">
					
					<div class="titlepage bg-blue">
						<h2>Modifier le bien <span class='indication-bien-dash'><?php echo $adresse?></span></h2>
					</div>
		


					<div class="form-add bg-white margin30" style='min-height:2100px'>
						<form action='traitement/modifBienTraitement.php' method='POST' name='form_modif_bien'>
							<h4>Les informations primaires sont obligatoires.</h4>
							<div class="form-champ" >
								<label>Type d'opération</label>

								<div class="form-add-radio">
									<input type="radio" id="location_id" <?php echo $checked_location ?> name="vente_location" value="location" /> 
									<span><label for='location_id' style='line-height:0px;font-weight:normal;margin-top:12px'>Location</label></span>

									<input type="radio" id="vente_id" <?php echo $checked_vente ?> name="vente_location" value="vente" /> 
									<span><label for="vente_id" style='line-height:0px;font-weight:normal;margin-top:12px'>Vente</label></span>
								</div>

							</div>
							
							<div class="form-champ">
								<label>Proprietaire</label>
								<?php echo getSelectProprietaireEmploye('proprietaire',$id_proprietaire_selected)?>
							</div>

							<div class="form-champ">
								<label>Locataires</label>
								<?php echo getSelectLocatairesEmploye('locataire',$id_locataire_selected)?>
							</div>

							<div class="form-champ">
								<label>Prix</label>
								<input style="max-width:500px;" type="text" name="prix" value="<?php echo $prix_form ?>" required="required" placeholder="€"/>
							</div>

							<div class="form-champ">
								<label>Supérficie (en m&sup2;)</label>
								<input style="max-width:500px;" type="text" name="superficie" value="<?php echo $superficie_form ?>" required="required" placeholder="0"/>
							</div>

							<div class="form-champ">
								<label>Nombre de pièces</label>
								<input style="max-width:500px;" type="text" name="nb_pieces" value="<?php echo $nb_pieces_form ?>" required="required" placeholder="0"/>
							</div>

							<div class="form-champ">
								<label>Type de bien</label>

								<div class="form-add-radio">
									<?php echo getSelectTypeBienEmploye('type_bien_radio',$ARRAY_TYPE_BIEN,$id_type_bien_selected); ?>
								</div>

							</div>


							<div class="form-champ">
								<label>Adresse du bien</label>
								<input style="max-width:500px;" type="text" name="numero_rue" value="<?php echo $numero_rue_form ?>" required="required" placeholder="20"/>
								<input style="max-width:500px;" type="text" name="rue" value="<?php echo $rue_form ?>" required="required" placeholder="rue du php"/>
							</div>

							<div class="form-champ">
								<label>Ville</label>
								<input style="max-width:500px;" type="text" name="ville" value="<?php echo $ville_form ?>" required="required" placeholder="Paris"/>
							</div>

							<div class="form-champ">
								<label>Code Postal</label>
								<input type="text" class="form-add-cp" name="code_postal" value="<?php echo $code_postal_form ?>" required="required" placeholder="75000"/>
								<?php echo getSelectDepartementsEmploye('departement',$id_departement_selected) ?>
							</div>

							

							<div class="form-champ">
								<label>Description du bien</label>
								<textarea name="description" style='min-height:200px' value="" required="required" placeholder="Description du bien"><?php echo $description_form ?></textarea>
							</div>

							
							

							<div class="form-add-tech">
							<h4>Toutes les informations techniques ne sont pas obligatoires.</h4>


							<div class="form-champ">
								<label>Parking ?</label>

								<div class="form-add-radio">
								<input type="radio" id="garage_oui" <?php echo $parking_oui_form ?> name="parking" value="1" /> 
								<span for="garage_oui">Oui</span>

								<input type="radio" id="garage_non" <?php echo $parking_non_form ?> name="parking" value="0" /> 
								<span for="garage_non">Non</span>
								</div>

							</div>

							<div class="form-champ">
								<label>Jardin si maison (surface en m&sup2;)</label>
								<input style="max-width:120px;" name='superficie_jardin' type="number" value="<?php echo $superficie_jardin_form ?>"/>
							</div>


							



							<h4 style="font-style:italic;margin-top:20px;">Energie :</h4>

							<div class="form-champ">
								<?php echo getSelectTypeChauffageEmploye('chauffage',$id_chauffage_seleced) ?>
							</div>

						
						


							<div class="form-champ">
								<label>Consommations:</label>
								<?php echo getSelectConsosEnergetiqueEmploye('energetique',$id_energetique_selected); ?>
								<?php echo getSelectConsosGazEmploye('gaz',$id_gaz_selected); ?>								
							</div>


							<div class="form-si-appt">
								<h4 style="font-style:italic;padding-bottom:0px;">Si appartement :</h4>
								
								<div class="form-champ" style="margin-top:0px;">
									<label>Nombre d'étages</label>
									<input type="number" name="nb_etage" style='max-width:400px' value="<?php echo $nb_etage_form ?>"/>
								</div>

								<div class="form-champ">
									<label>Étage et numéro d'appartement</label>
									<input type="number" class="form-add-nom" name="etage" value="<?php echo $etage_appart_form ?>" placeholder="étage"/>
									<input type="number" class="form-add-prenom" name="numero_appartement" value="<?php echo $numero_appart_form ?>" placeholder="numéro"/>
								</div>

								<div class="form-champ">
									<label>Ascenseur ?</label>

									<div class="form-add-radio">
									<input type="radio" id="ascenseur_oui_id" <?php echo $ascenseur_oui_form ?> name="ascenseur" value="1" /> 
									<span for="ascenseur">Oui</span>

									<input type="radio" id="ascenseur_non_id" <?php echo $ascenseur_non_form ?> name="ascenseur" value="0" /> 
									<span for="ascenseur">Non</span>
									</div>

								</div>
							</div>


							</div>

							<div class="form-champ-sub">
								<input type="submit" name="connexion" value="Modifier le bien" />
							</div>
							<input type="hidden" name='id_bien_immobilier' value='<?php echo $_GET['id_bien_immobilier'] ?>'/>
							<input type='hidden' name='statut_reponse' value='<?php echo $statut_reponse ?>'/>
							<input type='hidden' name='statut_message' value="<?php echo $message_reponse?>"/>
						</form>
					</div>
				</div>
			</div>
		</div>

	</section>







	<section class="bg-grey" style="min-height:100px;">

	</section>




	<footer class="bg-blue">
		<div class="container">
			<div class="row">


				<div class="col-md-6">
					<h1>FAKE AGENCY</h1>
				</div>


				<div class="col-md-3">
					<div class="contact-footer">
						<h4>Nous contacter</h4>
						<p><a><i class="fa fa-phone"></i>01 45 38 49 02</a></p>
						<p><a><i class="fa fa-envelope"></i>contact@agence.com</a></p>
						<p><a><i class="fa fa-keyboard-o"></i>Prendre contact avec un conseiller</a></p>
					</div>
				</div>



				<div class="col-md-3">
					<div class="direct-links-footer">
						<h4>Liens directs</h4>
						<a href="">Proposer un bien</a><br>
						<a href="">Rechercher un bien</a><br>
						<a href="">Gérer mes biens</a>
					</div>
				</div>


			</div>
		</div>
	</footer>



</div>



<script type="text/javascript">
<?php echo getJsForModalAjoutBien('form_modif_bien');?>

var open_menu = 0;
$( "#connect").click(function() {
	$('#connect-form').slideToggle('fast');
});

$( "#connect-mobile").click(function() {
	$('#connect-form').slideToggle('fast');
});


</script>


</body>
</html>