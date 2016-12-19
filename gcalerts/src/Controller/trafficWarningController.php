<?php
	namespace Drupal\gcalerts\Controller;

	use Drupal\Core\Controller\ControllerBase;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Drupal\Component\Serialization\Json;
	
	class TrafficWarningController extends ControllerBase {
		protected $http = null;
		protected $config = null;
		protected $suburbsController = null;
		
		protected function init() {
			if(!$this->config) {
				$this->config = \Drupal::config('gcalerts.settings');
			}
			
			if(!$this->http) {
				$this->http = new \Drupal\gcalerts\Http();
			}
			
			if(!$this->suburbsController) {
				$this->suburbsController = new \Drupal\gcalerts\Controller\SuburbsController();
			}
		}

		public function getTrafficWarnings() {
			
		}
	}