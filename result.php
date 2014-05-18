<?php
	session_start();
	require_once('functions_php/recherche_biens/search.php');
	require_once('functions_php/recherche_biens/getUtils_html.php');
	require_once('functions_php/user_utils/getUtils_html.php');
	require_once('functions_php/user_utils/getUtils.php');
	require_once('functions_php/recherche_biens/affichage_result.php');

	// var_dump($_GET);
	$mail_message = 'Email';
	$password_message='Mot de passe';
	$session_to_getBanniere ='';

	if(isset($_SESSION['id_personne']) && !empty($_SESSION['id_personne'])){
		$session_to_getBanniere = $_SESSION;
	}
	else{
		if(isset($_GET['err_compte'])){
			if($_GET['err_compte'] == "wrong_mail_password"){
				$mail_message='Email ou mot de passe inconnu';
				$password_message='';
			}
			else{
				$mail_message = 'Mauvaise utilisation';
				$password_message=$mail_message;
			}
		}
	}

	//dernier parmam:come_from pour redirection
	$formulaire_connexion = getFormulaireConnexion($mail_message,$password_message,basename(__FILE__));
	$banniere_header = getBanniere($session_to_getBanniere,basename(__FILE__));


	// penser aux consos energetiques : fonction
	if(isset($_GET['submit_base'])){
		// on enleve l'affinage
		$indices_to_remove = array("jardin","parking","ascenseur","superficie_mini","superficie_maxi","ville","nb_etages");
		foreach ($indices_to_remove as $value) {
			if(isset($_GET[$value])){
				unset($_GET[$value]);
			}
		}
	}

	//completion formulaire base:
	$departement_selected_form  	= isset($_GET['departement']) && !empty($_GET['departement']) ? $_GET['departement'] :'';
	$budget_mini_form		  		= isset($_GET['budget_mini']) && is_numeric($_GET['budget_mini']) ? $_GET['budget_mini'] : '';
	$budget_maxi_form		  		= isset($_GET['budget_maxi']) && is_numeric($_GET['budget_maxi']) ? $_GET['budget_maxi']:'';
	$nb_pieces_form					= isset($_GET['nb_pieces']) && is_numeric($_GET['nb_pieces']) ? $_GET['nb_pieces']:'';
	$type_vente_checked_form		= isset($_GET['type_achat_location']) && !empty($_GET['type_achat_location']) && $_GET['type_achat_location'] == 'vente' ? "checked":'';
	$type_location_checked_form 	='';	//bouton radio

	if(!$type_vente_checked_form)
		$type_location_checked_form= isset($_GET['type_achat_location']) && !empty($_GET['type_achat_location']) && $_GET['type_achat_location'] == 'location' ? "checked":'';

	//completion type biens fonction à faire
	/*$types_bien_maison_form			= isset($_GET['types_bien']) && !empty($_GET['types_bien']) ? 'checked':'';
	$types_bien_appartement_form	=
	$types_bien_immeuble_form		=
	$types_bien_garage_form			=*/

	//completion affinage
	$jardin_form 			= isset($_GET['jardin']) && is_numeric($_GET['jardin']) && $_GET['jardin'] == 1 ? "checked" : '';
	$parking_form 			= isset($_GET['parking']) && is_numeric($_GET['parking']) && $_GET['parking'] == 1 ? "checked" : '';
	$ascenseur_form 		= isset($_GET['ascenseur']) && is_numeric($_GET['ascenseur']) && $_GET['ascenseur'] == 1 ? "checked" : '';
	$nb_etages_form			= isset($_GET['nb_etages']) && is_numeric($_GET['nb_etages']) ? $_GET['nb_etages'] : '';
	$superficie_mini_form	= isset($_GET['superficie_mini']) && is_numeric($_GET['superficie_mini']) ? $_GET['superficie_mini'] : '';
	$superficie_maxi_form	= isset($_GET['superficie_maxi']) && is_numeric($_GET['superficie_maxi']) ? $_GET['superficie_maxi'] : '';
	$ville_form				= isset($_GET['ville']) && !empty($_GET['ville']) ? $_GET['ville'] :'';


	//completion tri
	$order_by_prix_croissant_form 	= isset($_GET['order_by']) && !empty($_GET['order_by']) && $_GET['order_by'] =='prix_croissant' ? "selected" : '';
	$order_by_prix_decroissant_form = isset($_GET['order_by']) && !empty($_GET['order_by']) && $_GET['order_by'] =='prix_decroissant' ? "selected" : '';
	$order_by_nb_pieces_form		= isset($_GET['order_by']) && !empty($_GET['order_by']) && $_GET['order_by'] =='nb_pieces' ? "selected" : '';
	$order_by_date_parution_form	= isset($_GET['order_by']) && !empty($_GET['order_by']) && $_GET['order_by'] =='date_parution' ? "selected" : '';	
	$order_by_superficie_decroissant_form	= isset($_GET['order_by']) && !empty($_GET['order_by']) && $_GET['order_by'] =='superficie_decroissant' ? "selected" : '';
	$order_by_superficie_croissant_form		= isset($_GET['order_by']) && !empty($_GET['order_by']) && $_GET['order_by'] =='superficie_croissant' ? "selected" : '';

	$opts = formToArrayOpt($_GET);
	//var_dump($opts);
	$res = searchBase($opts);
	//var_dump($res);

	$phrase_associe_count = 'résultats associés';
	$nb_res_search = count($res);

	if($nb_res_search == 0 || $nb_res_search==1)
		$phrase_associe_count = 'résultat associé';

	$nb_res_search="<span>".$nb_res_search."</span> ";

	$resultat_search_html = affichage_base_liste_html($res);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="apple-touch-icon" href="icon.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

		<title>Agence Immo</title>

		<link rel='stylesheet' href='css/pushy.css' type='text/css' media='all' />
		<link rel='stylesheet' href='css/flexslider.css' type='text/css' media='all' />
		<link rel='stylesheet' href='css/bootstrap.min.css' type='text/css' media='all' />
		<link rel='stylesheet' href='css/default.css' type='text/css' media='all' />
		<link rel='stylesheet' href='css/styles.css' type='text/css' media='all' />



		<link rel='stylesheet' href='css/fonts/font-awesome.css' type='text/css' media='all' />
		<link rel='stylesheet' href='css/fonts/font-awesome-animation.css' type='text/css' media='all' />

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="js/jquery-ui-1.10.4.min.js"></script>


		<script type="text/javascript" src="js/modernizr.custom.js"></script>
		<script type="text/javascript" src='js/main.js'></script>
		<script type="text/javascript" src='js/multiple.js'></script>
	</head>

	<body>


		<header>
			<?php echo $banniere_header ?>
			<?php echo $formulaire_connexion ?>
		</header>

	<section class="bg-grey first-section" >
		<div class="container" >
			<div class="row">

					<div class="col-md-3  margin30">
						<div id="avanced-search-infos" class="bg-white">
							<h1>FAKE AGENCY</h1>
							<h4>Nous contacter</h4>
							<p><a href='tel:+33145384902'><i class="fa fa-phone"></i>01 45 38 49 02</a></p>
							<p><a href='mailto:contact@agence.com'><i class="fa fa-envelope"></i>contact@agence.com</a></p>
							<!-- <p><a><i class="fa fa-keyboard-o"></i>Joindre un conseiller</a></p> -->
						</div>

						<div id="avanced-search" class="bg-white margin30">
							

						<form action='' method='GET'>

							<h4>Affiner votre recherche</h4>

							<p>
								<label>Ville</label>
								<input type="text" name="ville" value="<?php echo $ville_form ?>" placeholder="Nom de ville"/>
							</p>

							<p>
								<label>Utilitaires</label>
								<input type="checkbox" name="jardin" <?php echo $jardin_form ?>  value="1"><span>Jardin</span><br>
								<input type="checkbox" name="parking" <?php echo $parking_form ?>  value="1"><span>Parking</span><br>
								<input type="checkbox" name="ascenseur" <?php echo $ascenseur_form ?> value="1"><span>Ascenseur</span><br>
							</p>

							<p>
								<label>Nombre d'étages</label>
								<input type="number" name="nb_etages" placeHolder='Indifférent' value="<?php echo $nb_etages_form ?>"/>
							</p>

							<p>
								<label>Superficie</label>
								<input type="number" name="superficie_mini" id="superficie_mini" placeHolder='Mini' value='<?php echo $superficie_mini_form ?>'>
								<input type="number" name="superficie_maxi" id="superficie_maxi" placeHolder='Maxi' value='<?php echo $superficie_maxi_form ?>'>
							</p>
							<p>

								<input class="affiner" type="submit" name="submit_affine" value="Affiner la recherche"/>
							</p>
						
						</div>	
					</div>

					<div class="col-md-9 bg-grey margin30">
						
						<div class="bg-white home-form-search">
							<h4>Votre recherche de base</h4>

							<div id="search-form">
								<div id="form-col2" class="col-md-4 form-col2">
									<p>
										<input type="radio" name="type_achat_location" value="vente" <?php echo $type_vente_checked_form ?> id="acheter" /> 
										<label class="choice" for="acheter">Acheter</label>
									</p>
									<p>
										<input type="radio" name="type_achat_location" value="location" <?php echo $type_location_checked_form ?> id="louer" />
										<label class="choice" for="louer">Louer</label>
									</p>

									<p>
										<select id="type" name="types_bien[]" multiple="multiple">
											<option value="maison">Maison</option>
											<option value="appartement">Appartement</option>
											<option value="immeuble">Immeuble</option>
											<option value="garage">Garage</option>
										</select>
									</p>
									
									<p>
											<?php 
												echo getSelectDepartementsHTML("departement",$departement_selected_form);
											?>

									</p>
								</div>


								<div id="form-col3" class="col-md-4 form-col3">
									<p>
										<label>Budget</label><br>
										<input class="bud" type="number" name="budget_mini" id="budget_mini" placeHolder="Mini" value="<?php echo $budget_mini_form ?>">
										<label> à </label>
										<input class="bud" type="number" name="budget_maxi" id="budget_maxi" placeHolder="Maxi" value="<?php echo $budget_maxi_form ?>">
										<label> € </label>
									</p>
									<p class="margin30">
										<label>Nombre de pièces</label><br>
										<input type="number" name="nb_pieces" id="room" placeHolder='Indifférent' value="<?php echo $nb_pieces_form ?>">
									</p>
								</div>
								
								<div id="form-col1" class="col-md-4 form-col1">
									<p>
										Pour une précision de recherche plus importante, l'agence vous conseille l'utilisation de la recherche avancée, située à gauche de la page.
									</p>
									
									<input type="submit" name="submit_base" id="search" value="Rechercher">
									<input type='reset'  value='Annuler' onclick="window.location.href='result.php'">

									
								</div>
							</div>
						</div>
					
						
						
						<div class="margin30 results-title">

								<div class="col-md-6 bg-blue">
									<h4><?php echo $nb_res_search.$phrase_associe_count ?></h4>
								</div>

								<div class="col-md-6 bg-white" style="border:none">
									
									<p>

										Trier par

										<select name='order_by'>
											<option value='date_parution' 			<?php echo $order_by_date_parution_form ?>				>Date</option>							
											<option value='prix_croissant' 			<?php echo $order_by_prix_croissant_form ?> 			>Prix croissant</option>
											<option value='prix_decroissant' 		<?php echo $order_by_prix_decroissant_form ?> 			>Prix décroissant</option>
											<option value='superficie_croissant' 	<?php echo $order_by_superficie_croissant_form ?> 		>Superficie croissante</option>
											<option value='superficie_decroissant' 	<?php echo $order_by_superficie_decroissant_form ?> 		>Superficie decroissante</option>
											<option value='nb_pieces' 				<?php echo $order_by_nb_pieces_form ?> 					>Nombre de pièces</option>
										</select>

										<input type="submit" name="submit_trier" id="search" value="Trier">

									</p>
								</div>
						</div>

					</form>

					<?php echo $resultat_search_html ?>	

					</div>
			</div>
		</div>
	</section>


	<section class="bg-grey" style="min-height:100px;">
			
	</section>


	<?php echo getFooter() ?>

		



<script type="text/javascript">

var open_menu = 0;
		$( "#connect").click(function() {
			$('#connect-form').slideToggle('fast');
		});

		$( "#connect-mobile").click(function() {
			$('#connect-form').slideToggle('fast');
		});




		$(document).ready(function(){
			$("#type").multiselect();
		});

		</script>


	</body>
	</html>