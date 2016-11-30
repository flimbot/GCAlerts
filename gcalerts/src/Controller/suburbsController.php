<?php

	namespace Drupal\gcalerts\Controller;

	use Drupal\Core\Controller\ControllerBase;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Drupal\Component\Serialization\Json;

	//https://www.drupal.org/docs/8/api/responses/responses-overview
	class SuburbsController extends ControllerBase {
		public function getSuburbs() {
			return new JsonResponse($this->getSuburbsArray(), 200);
		}
		
		public function getSuburbsArray() {
			$suburbCache = \Drupal::cache()->get('goldcoastsuburbs');
			
			if(!$suburbCache) {
				$config = \Drupal::config('gcalerts.settings');
				$client = \Drupal::httpClient(); //Returns \GuzzleHttp\Client
				
				$response = $client->post($config->get('suburbs_source.url'), [
					'json' => [
						"resource_id" => $config->get('suburbs_source.resource_id'),
						"limit" => 150,
						"offset" => 0,
						"sort" => "SUBURB asc",
						"distinct" => true,
						"fields" => "SUBURB"
					],
					'proxy' => $config->get('proxy')
				]);

				//$json = Json::decode($response->getBody());
			
				$suburbArray = array();
				$json = Json::decode($response->getBody());
				foreach ($json['result']['records'] as $record) {
					$suburbArray[] = array_values($record)[0];
				}
			
				\Drupal::cache()->set('goldcoastsuburbs', $suburbArray, $config->get('suburbs_source.cachetime')); // Cached 24 hours
				return $suburbArray;
			}
			else {
				return $suburbCache->data;
			}
		}
	}