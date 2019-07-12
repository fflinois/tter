<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class tter extends eqLogic {
    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */

    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
      public static function cron() {

      }
     */


    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {

      }
     */



    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
		// Création des différentes commandes de type 'info'
		// Création de la commande depart
        $depart = $this->getCmd(null, 'depart');
		if (!is_object($depart)) {
			$depart = new tterCmd();
			$depart->setLogicalId('depart');
			$depart->setIsVisible(1);
      		$depart->setOrder(1);
			$depart->setName(__('Gare de départ', __FILE__));
		}
		$depart->setType('info');
        $depart->setSubType('string');
		$depart->setEventOnly(1);
		$depart->setEqLogic_id($this->getId());
		$depart->save();

		// Création de la commande arrivee
		$arrivee = $this->getCmd(null, 'arrivee');
		if (!is_object($arrivee)) {
			$arrivee = new tterCmd();
			$arrivee->setLogicalId('arrivee');
			$arrivee->setIsVisible(1);
      		$arrivee->setOrder(2);
		    $arrivee->setName(__('Gare d\'arrivée', __FILE__));
		}
		$arrivee->setType('info');
		$arrivee->setSubType('string');
		$arrivee->setEventOnly(1);
		$arrivee->setEqLogic_id($this->getId());
		$arrivee->save();

		// Création des commandes en tableau d'objets
		$arrayTrajets = [];

		for ($indexTrajet = 0; $indexTrajet <= 3; $indexTrajet){

			$arrayTrajets[$indexTrajet] = array(				
			  	'heureDepart' => $this->getCmd(null, 'heureDepart'.$indexTrajet),
			  	'heureArrivee' => $this->getCmd(null, 'heureArrivee'.$indexTrajet),
			  	'dureeTrajet' => $this->getCmd(null, 'dureeTrajet'.$indexTrajet),
			  	'retard' => $this->getCmd(null, 'retard'.$indexTrajet)
			);
			// Création de la commande heureDepart
			if (!is_object($arrayTrajets[$indexTrajet]['heureDepart'])) {
				$arrayTrajets[$indexTrajet]['heureDepart'] = new tterCmd();
				$arrayTrajets[$indexTrajet]['heureDepart']->setLogicalId('heureDepart'.$indexTrajet);
				$arrayTrajets[$indexTrajet]['heureDepart']->setIsVisible(1);
				$arrayTrajets[$indexTrajet]['heureDepart']->setOrder(3+$indexTrajet*4);
				$arrayTrajets[$indexTrajet]['heureDepart']->setName(__('Heure départ train '.$indexTrajet, __FILE__));
			}
			$arrayTrajets[$indexTrajet]['heureDepart']->setType('info');
			$arrayTrajets[$indexTrajet]['heureDepart']->setSubType('string');
			$arrayTrajets[$indexTrajet]['heureDepart']->setEventOnly(1);
			$arrayTrajets[$indexTrajet]['heureDepart']->setEqLogic_id($this->getId());
			$arrayTrajets[$indexTrajet]['heureDepart']->save();

			// Création de la commande heureArrivee
			if (!is_object($arrayTrajets[$indexTrajet]['heureArrivee'])) {
				$arrayTrajets[$indexTrajet]['heureArrivee'] = new tterCmd();
				$arrayTrajets[$indexTrajet]['heureArrivee']->setLogicalId('heureArrivee'.$indexTrajet);
				$arrayTrajets[$indexTrajet]['heureArrivee']->setIsVisible(1);
				$arrayTrajets[$indexTrajet]['heureArrivee']->setOrder(4);
				$arrayTrajets[$indexTrajet]['heureArrivee']->setName(__('Heure arrivée train '.$indexTrajet, __FILE__));
			}
			$arrayTrajets[$indexTrajet]['heureArrivee']->setType('info');
			$arrayTrajets[$indexTrajet]['heureArrivee']->setSubType('string');
			$arrayTrajets[$indexTrajet]['heureArrivee']->setEventOnly(1);
			$arrayTrajets[$indexTrajet]['heureArrivee']->setEqLogic_id($this->getId());
			$arrayTrajets[$indexTrajet]['heureArrivee']->save();

			// Création de la commande dureeTrajet
			if (!is_object($arrayTrajets[$indexTrajet]['dureeTrajet'])) {
				$arrayTrajets[$indexTrajet]['dureeTrajet'] = new tterCmd();
				$arrayTrajets[$indexTrajet]['dureeTrajet']->setLogicalId('dureeTrajet'.$indexTrajet);
				$arrayTrajets[$indexTrajet]['dureeTrajet']->setIsVisible(1);
				$arrayTrajets[$indexTrajet]['dureeTrajet']->setOrder(5);
				$arrayTrajets[$indexTrajet]['dureeTrajet']->setName(__('Temps de trajet train '.$indexTrajet, __FILE__));
			}
			$arrayTrajets[$indexTrajet]['dureeTrajet']->setType('info');
			$arrayTrajets[$indexTrajet]['dureeTrajet']->setSubType('string');
			$arrayTrajets[$indexTrajet]['dureeTrajet']->setEventOnly(1);
			$arrayTrajets[$indexTrajet]['dureeTrajet']->setEqLogic_id($this->getId());
			$arrayTrajets[$indexTrajet]['dureeTrajet']->save();
		
			// Création de la commande retard
			if (!is_object($arrayTrajets[$indexTrajet]['retard'])) {
				$arrayTrajets[$indexTrajet]['retard'] = new tterCmd();
				$arrayTrajets[$indexTrajet]['retard']->setLogicalId('retard'.$indexTrajet);
				$arrayTrajets[$indexTrajet]['retard']->setIsVisible(1);
				$arrayTrajets[$indexTrajet]['retard']->setOrder(5);
				$arrayTrajets[$indexTrajet]['retard']->setName(__('Retard train '.$indexTrajet, __FILE__));
			}
			$arrayTrajets[$indexTrajet]['retard']->setType('info');
			$arrayTrajets[$indexTrajet]['retard']->setSubType('string');
			$arrayTrajets[$indexTrajet]['retard']->setEventOnly(1);
			$arrayTrajets[$indexTrajet]['retard']->setEqLogic_id($this->getId());
			$arrayTrajets[$indexTrajet]['retard']->save();
		}
		
		// Création des commandes de type action
		
		$refreshA = $this->getCmd(null, 'refresha');
		if (!is_object($refreshA)) {
            $refreshA = new tterCmd();
            $refreshA->setLogicalId('refresha');
            $refreshA->setOrder(13);
            $refreshA->setName(__('Màj Aller', __FILE__));
		}
		$refreshA->setType('action');
		$refreshA->setSubType('other');
		$refreshA->setEqLogic_id($this->getId());
		$refreshA->save();

    	$refreshR = $this->getCmd(null, 'refreshr');
		if (!is_object($refreshR)) {
            $refreshR = new tterCmd();
            $refreshR->setLogicalId('refreshr');
            $refreshR->setOrder(14);
            $refreshR->setName(__('Màj Retour', __FILE__));
		}
		$refreshR->setType('action');
		$refreshR->setSubType('other');
		$refreshR->setEqLogic_id($this->getId());
		$refreshR->save();

		$notify = $this->getCmd(null, 'notify');
		if (!is_object($notify)) {
            $notify = new tterCmd();
            $notify->setLogicalId('notify');
            $notify->setOrder(15);
            $notify->setName(__('Notifier', __FILE__));
		}
		$notify->setType('action');
		$notify->setSubType('other');
		$notify->setEqLogic_id($this->getId());
		$notify->save();
    }

    public function preUpdate() {
        
    }

    public function postUpdate() {
        
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */

    /*     * **********************Getteur Setteur*************************** */
}

class tterCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
        
    }

    /*     * **********************Getteur Setteur*************************** */
}


