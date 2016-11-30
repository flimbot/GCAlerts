<?php
	namespace Drupal\gcalerts\Controller;

	use Drupal\Core\Controller\ControllerBase;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Drupal\Component\Serialization\Json;
	
	class PowerOutageController extends ControllerBase {
		protected $http = null;
		protected $config = null;
		protected $suburbsController = null;
		
		protected function init() {
			if(!$this->config) {
				$this->config = \Drupal::config('gcalerts.settings');
			}
			
			if(!$this->http) {
				$this->http = new \Drupal\gcalerts\Http();
			}
			
			if(!$this->suburbsController) {
				$this->suburbsController = new \Drupal\gcalerts\Controller\SuburbsController();
			}
		}
		
		public function getPowerOutages() {
			$this->init();
			
			$suburbs = $this->suburbsController->getSuburbsArray();
			
			$outages = array();
			$outages['planned'] = array();
			$outages['unplanned'] = array();
			
			$plannedoutages = $this->getEnergexSearchResults($this->config->get('power_source.plannedoutage_url'));
			foreach($plannedoutages as $result) {
				if(in_array($result['metaData']['suburb'], $suburbs)) {
					$outage = array();
					
					$outage['start'] = $result['metaData']['d'];
					$outage['end'] = $result['metaData']['E'];
					$outage['street'] = $result['metaData']['t'];
					$outage['suburb'] = $result['metaData']['suburb'];
					$outage['updated'] = $result['metaData']['updated'];
					preg_match("/(\d{4})/", $result['metaData']['keys'], $matches);
					$outage['postcode'] = $matches[0];
					
					$outages['planned'][] = $outage;
				}
			}
			
			// Need to wait for a legitimate outage to see results
			//$unplannedoutages = $this->getEnergexSearchResults($this->config->get('power_source.unplannedoutage_url'));
			
			return new Response(Json::encode($outages));
		}
		
		protected function getEnergexSearchResults($url) {
			$outages = array();
			$jsonResp = Json::decode($this->http->get($url));
			return $jsonResp['response']['resultPacket']['results'];
		}
		
		/*protected function getPlannedOutages() {
			
		}
		
		protected function getUnplannedOutages() {
			return $this->http->get($this->config->get('power_source.unplannedoutage_url'));
		}*/
	}