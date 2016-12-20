<?php

namespace Drupal\gcalerts\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Serialization\Json;

class PowerOutageController extends ControllerBase {
	public function getPowerOutages() {
		$outages = new \Drupal\gcalerts\Readers\PowerOutages;

		$outageResults = array();
		$outageResults['planned'] = $outages->getPlannedOutages();
		$outageResults['unplanned'] = $outages->getUnplannedOutages();

		return new JsonResponse($outageResults, 200);
	}
}