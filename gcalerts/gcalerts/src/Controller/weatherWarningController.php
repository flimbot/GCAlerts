<?php
	
namespace Drupal\gcalerts\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Serialization\Json;

class WeatherWarningController extends ControllerBase {
	public function getWeatherWarnings() {
		$weather = new \Drupal\gcalerts\Readers\Weather;
		return new JsonResponse($weather->getWeatherWarnings(), 200);
	}
}