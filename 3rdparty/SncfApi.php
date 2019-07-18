<?php

class SncfApi {
  /**
   * Fonction qui permet de récuperer les trajets entre deux gares
   * 
   * @param String $apiKey clé de l'api SNCF
   * @param String $depart code de la gare de départ
   * @param String $arrivee code de la gare d'arrivée
   * 
   * @return array tableau de trajets
   */
	public function getTrajets($apiKey, $depart, $arrivee) {
		log::add('tter','debug','calling sncf api with :'.$apiKey.' / '.$depart.' / '.$arrivee);
		date_default_timezone_set("Europe/Paris");
		$currentDate = date("Ymd\TH:i");

			// construction de la requete vers l'API SNCF
			$baseQuery = 'https://'.$apiKey.'@api.sncf.com/v1/coverage/sncf/journeys?';
			$finalQuery = $baseQuery.'from='.$depart.'&to='.$arrivee.'&datetime='.$currentDate.'&datetime_represents=departure&min_nb_journeys=4';
			log::add('tter','debug',$finalQuery);

			// Execution de la requete
			$response = file_get_contents($finalQuery);
			log::add('tter','debug','API response :'.$response);

			// Decodage de la response en JSON
			$responseJSON = json_decode($response, true);
			log::add('tter','debug','API response json :'.$responseJSON);

			$trajets = [];
			$indexTrajet = 0;

			// Pour chaque 'journeys' du JSON représentant un trajet
			foreach($responseJSON['journeys'] as $trajet) {

			// récuperation des informations principales du trajet
			$dateTimeDepart = $trajet['departure_date_time'];
			$heureDepart = substr($dateTimeDepart,9,4);
			$dateTimeArrivee = $trajet['arrival_date_time'];
			$heureArrivee = substr($dateTimeArrivee,9,4);
			$dureeTrajet = gmdate("Hi", strtotime($dateTimeArrivee)-strtotime($dateTimeDepart));
			$numeroTrain = $trajet['sections'][1]['display_informations']['headsign'];
			$gareDepart = $trajet['sections'][1]['from']['stop_point']['name'];
			$gareArrivee = $trajet['sections'][1]['to']['stop_point']['name'];

			log::add('tter','debug','Found train '.$numeroTrain.' :'.$dateTimeDepart.' / '.$dateTimeArrivee.' - '.$gareDepart.' > '.$gareArrivee);

			// si le train est indisponible 
			if ($trajet['status'] == 'NO_SERVICE'){
				$retard = 'PAS DE SERVICE';
			}else{
				// sinon recherche des retards éventuels
				$retard = 'aucun';
				$updatedTime = $heureDepart;
				$numdisrup = $trajet['sections'][1]['display_informations']['links'][0]['id'];
				log::add('tter','debug','Disruption ID '.$numdisrup);

				$disruptions = $responseJSON['disruptions'];
				foreach($disruptions as $disruption) {
					if ( $disruption['disruption_id']== $numdisrup ) {
					log::add('tter','debug','Disruption ID '.$numdisrup. ' has been found!');
					log::add('tter','debug','Search for impacted departure '.$heureDepart);
					// go through each impacted stops
					foreach($disruption['impacted_objects'][0]['impacted_stops'] as $impactStop) {
						log::add('tter','debug','testing departure '.substr($impactStop['base_departure_time'],0,4));

						if ( substr($impactStop['base_departure_time'],0,4) == $heureDepart ) {
							$updatedTime = $impactStop['amended_departure_time'];
							// compute delay
							$retard = ( substr($updatedTime,0,2) * 60 + substr($updatedTime,2,2) ) - ( substr($heureDepart,0,2) * 60 + substr($heureDepart,2,2) ).' minutes';
							if ($retard == 0) {
								$retard = 'aucun';
							} elseif ($retard > 1) {
								$retard .= ' minutes';
							} else {
								$retard .= ' minute';
							}
							break;
						}
					}
					break;
					}
				}
			}		

					// store data for current train
			$trajets[$indexTrajet] = array(
				'numeroTrain' => $numeroTrain,
				'gareDepart' => $gareDepart,
				'gareArrivee' => $gareArrivee,
				'dateTimeDepart' => $dateTimeDepart,
				'heureDepart' => $heureDepart,
				'dateTimeArrivee' => $dateTimeArrivee,
				'heureArrivee' => $heureArrivee,
				'dureeTrajet' => $dureeTrajet,
				'retard' => $retard,
				'updatedheureDepart' => $updatedTime
				);
			log::add('tter','debug','trajet '.$indexTrajet.' : '.$trajets[$indexTrajet]);

			$indexTrajet++;
		}
}
}


 ?>
