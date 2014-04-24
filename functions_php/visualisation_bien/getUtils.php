<?php
	
	function getDateFormatedVisuBase($date_to_print){
		$date = new DateTime($date_to_print,new DateTimeZone('Europe/Paris'));
		return $date->format('d/m/Y');
	}

	function getDateFormatedVisuDetails($date_to_compare){
		$date_return='';
		if(!empty($date_to_compare)){
			$time_zone 			= new DateTimeZone('Europe/Paris');
			$date_to_compare 	= new DateTime($date_to_compare, $time_zone);
			$date_current 		= new DateTime(NULL,$time_zone);
			$date_diff 			= $date_current->diff($date_to_compare);
			$time_zone 			= NULL; //opti

			//var_dump($date_diff);
			
			// si jours > 10 on affiche pas de détails
			if($date_diff->days== 0){
				if($date_diff->h == 0){
					if($date_diff->i == 0){
						$date_return = "Il y a ".$date_diff->s." seconde";

						if($date_diff->s > 1)
							$date_return.='s';
					}
					else{
						$date_return = "Il y a ".$date_diff->i." minute";

						if($date_diff->i > 1)
							$date_return.='s';
					}
				}
				else{
					$date_return = "Il y a ".$date_diff->h." heure";

					if($date_diff->h > 1)
						$date_return .='s';
				}
			}
			elseif($date_diff->days < 10){
				$date_return = "Il y a ".$date_diff->d." jour";
				if($date_diff->days > 1)
					$date_return.='s';
			}
			
		}

		return $date_return;
	}