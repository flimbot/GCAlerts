<?php
	
namespace Drupal\gcalerts\Utilities;

use Drupal\Component\Serialization\Json;

class Http {
	protected $config = null;
	protected $client = null;

	function __construct() {
		$this->config = \Drupal::config('gcalerts.settings');
		$this->client = \Drupal::httpClient(['defaults' => [
			'verify' => false
		]]);  //Returns \GuzzleHttp\Client
	}
	
	public function get($url) {
		if($this->config->get('proxy')) {
			$response = $this->client->get($url, ['proxy' => $this->config->get('proxy')]);
		}
		else {
			$response = $client->get($url);
		}
		return $response->getBody();
	}
	
	public function postJson($url, $jsonArray) {
		if($this->config->get('proxy')) {
			$response = $this->client->post($url, [
				'json' => $jsonArray,
				'proxy' => $this->config->get('proxy')
			]);
		}
		else {
			$response = $this->client->post($url, [
				'json' => $jsonArray
			]);
		}
		return $response->getBody();
	}
}