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
		$unplannedoutages = $this->getEnergexSearchResults($this->config->get('energex_unplanned_outage_source.url'));
		$region = $this->config->get('energex_unplanned_outage_source.region');
		foreach($unplannedoutages as $result) {
			if($result['metaData'] && $result['metaData']['L'] && $result['metaData']['L'] = $region) {
				$outage = array();
				
				$outage['start'] = $result['metaData']['d'];
				#$outage['end'] = $result['metaData']['E'];
				#$outage['street'] = $result['metaData']['t'];
				$outage['suburb'] = $result['metaData']['suburb'];
				$outage['updated'] = $result['metaData']['updated'];
				$outage['status'] = $result['metaData']['t'];

				preg_match("/(\d{4})/", $result['metaData']['keys'], $matches);
				$outage['postcode'] = $matches[0];

				// TODO: Also 'Status' which contains 'Completed' 'Cancelled'
				
				$outages[] = $outage;
			}
		}

		return $outages;
	}

	public function getPlannedOutages() {		
		$suburbs = (new \Drupal\gcalerts\Utilities\Suburbs())->getSuburbArrayCached(); 
		$plannedoutages = $this->getEnergexSearchResults($this->config->get('energex_planned_outage_source.url'));

		foreach($plannedoutages as $result) {
			if($result['metaData'] && $result['metaData']['suburb'] && in_array($result['metaData']['suburb'], $suburbs)) {
				$outage = array();
				
				$outage['start'] = $result['metaData']['d'];
				$outage['end'] = $result['metaData']['E'];
				$outage['street'] = $result['metaData']['t'];
				$outage['suburb'] = $result['metaData']['suburb'];
				$outage['updated'] = $result['metaData']['updated'];
				$outage['status'] = $result['metaData']['X'];

				preg_match("/(\d{4})/", $result['metaData']['keys'], $matches);
				$outage['postcode'] = $matches[0];

				// TODO: Also 'Status' which contains 'Completed' 'Cancelled'
				
				$outages[] = $outage;
			}
		}
		
		return $outages;
	}
	
	//Generic reader for Funnelback search results
	//This call will iterate/page and return whole result set. Could be improved by passing a comparison function to compare suburbs (for example) while paging
	protected function getEnergexSearchResults($url) {		
		$http = new \Drupal\gcalerts\Utilities\Http();
		$results = array();
		$startRank = 1;
		do {
			if($jsonResp) {
				$startRank = $jsonResp['response']['resultPacket']['resultsSummary']['nextStart'];
			}

			$jsonResp = Json::decode($http->get($url . $startRank));
			$results += $jsonResp['response']['resultPacket']['results'];
		} while($jsonResp['response']['resultPacket']['resultsSummary']['nextStart']);
		return $results;
	}

	public function getPowerOutagesCached() {
	}
}