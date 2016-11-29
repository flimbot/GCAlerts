<?php
	
	namespace Drupal\gcalerts\Controller;

	use Drupal\Core\Controller\ControllerBase;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Drupal\Component\Serialization\Json;
	
	class WeatherWarningController extends ControllerBase {
		public function getWeatherWarnings() {
			$config = \Drupal::config('gcalerts.settings');
			//$url = "http://www.bom.gov.au/fwo/IDZ00056.warnings_qld.xml";
			$client = \Drupal::httpClient();
			$response = $client->get($config->get('weather_source.url'), ['proxy' => $config->get('proxy')]);
			
			$rssObj = simplexml_load_string($response->getBody()); // I_hate_globalphpfunctions_2
			if($rssObj->entries) {
				return new JsonResponse($rssObj->entries, 200);
			}
			return new Response('[]', 200);
		}
	}
	
	//TODO: Caching