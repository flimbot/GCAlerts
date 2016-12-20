<?php

namespace Drupal\gcalerts\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Serialization\Json;

class TrafficWarningController extends ControllerBase {

	public function getTrafficWarnings() {
		$traffic = new \Drupal\gcalerts\Readers\Traffic;
		return new JsonResponse($traffic->getTrafficWarnings(), 200);
	}
}