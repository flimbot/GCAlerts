<?php

namespace Drupal\gcalerts\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Serialization\Json;

//https://www.drupal.org/docs/8/api/responses/responses-overview
class SuburbsController extends ControllerBase {
	public function getSuburbs() {
		$suburbs = new \Drupal\gcalerts\Utilities\Suburbs;
		return new JsonResponse($suburbs->getSuburbArrayCached(), 200);
	}
}
