<?php

namespace Drupal\gcalerts\Readers;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Serialization\Json;

class PowerOutages {
	protected $config = null;
	protected $suburbs = null;

	function __construct() {
		$this->config = \Drupal::config('gcalerts.settings');
	}

	public function getUnplannedOutages() {	
		// Need to wait for a legitimate outage to see results
		//$unplannedoutages = $this->getEnergexSearchResults($this->config->get('energex_unplanned_outage_source.url'));
		return array();
	}

	public function getPlannedOutages() {		
		$suburbs = (new \Drupal\gcalerts\Utilities\Suburbs())->getSuburbArrayCached(); 

		//This call should iterate/page and return whole result set. Could be improved by passing a comparison function to compare suburbs (for example) while paging
		$plannedoutages = $this->getEnergexSearchResults($this->config->get('energex_planned_outage_source.url'));

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

				// TODO: Also 'Status' which contains 'Completed' 'Cancelled'
				
				$outages['planned'][] = $outage;
			}
		}
		
		return $outages;
	}
	
	//Generic reader for Funnelback search results
	protected function getEnergexSearchResults($url) {
		//TODO: (important) Paging on this!
		
		$http = new \Drupal\gcalerts\Utilities\Http();

		$outages = array();
		$jsonResp = Json::decode($http->get($url));
		return $jsonResp['response']['resultPacket']['results'];
	}

	public function getPowerOutagesCached() {
	}
}