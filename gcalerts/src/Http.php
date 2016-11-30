<?php
	
	namespace Drupal\gcalerts;
	
	use Drupal\Component\Serialization\Json;
	
	class Http {
		protected $config = null;
		protected $client = null;
	
		function __construct() {
			$this->config = \Drupal::config('gcalerts.settings');
			$this->client = \Drupal::httpClient(['defaults' => [
				'verify' => false
			]]);
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
			if($config->get('proxy')) {
				$response = $client->post($url, [
					'json' => $jsonArray,
					'proxy' => $config->get('proxy')
				]);
			}
			else {
				$response = $client->post($url, [
					'json' => $jsonArray
				]);
			}
		}
	}