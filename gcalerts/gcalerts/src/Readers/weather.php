<?php
	
namespace Drupal\gcalerts\Readers;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Serialization\Json;

class Weather {
	public function getWeatherWarnings() {
		$config = \Drupal::config('gcalerts.settings');

		$http = new \Drupal\gcalerts\Utilities\Http();
		$response = $http->get($config->get('weather.url'));
		
		$rssObj = simplexml_load_string($response); // I_hate_globalphpfunctions_2
		return $rssObj->entries;
	}
}
//TODO: Caching