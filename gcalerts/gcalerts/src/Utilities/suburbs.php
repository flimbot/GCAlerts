<?php

namespace Drupal\gcalerts\Utilities;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Serialization\Json;

//https://www.drupal.org/docs/8/api/responses/responses-overview
class Suburbs {
	protected $config = null;

	function __construct() {
		$this->config = \Drupal::config('gcalerts.settings');
	}

	protected function getSuburbArray() {
		$http = new \Drupal\gcalerts\Utilities\Http();
		
		//TODO: This isn't paging at the moment. No immediate need with a small result set, but should be amended later. 92 results as of 19/12/2016
		$responseBody = $http->postJson(
			$this->config->get('suburbs.url'),
			[
				"resource_id" => $this->config->get('suburbs.resource_id'),
				"limit" => 150,
				"offset" => 0,
				"sort" => "SUBURB asc",
				"distinct" => true,
				"fields" => "SUBURB"
			] 
		);

		$suburbArray = array();
		$json = Json::decode($responseBody);
		foreach ($json['result']['records'] as $record) {
			$suburbArray[] = array_values($record)[0];
		}
		return $suburbArray;
	}

	public function getSuburbArrayCached() {
		$suburbCache = \Drupal::cache()->get('goldcoastsuburbs');
		
		if(!$suburbCache) {
			$suburbArray = $this->getSuburbArray();
			\Drupal::cache()->set('goldcoastsuburbs', $suburbArray, $this->config->get('suburbs.cachetime'));
			return $suburbArray;
		}
		else {
			return $suburbCache->data;
		}
	}
}