<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class ApiController extends AbstractActionController {

	protected $table;
	
	public function isValidAction() {

		$request = $this->getRequest();

		if ($request->isPost()) {

			$package_name = $request->getPost('package_name');
			
			if(empty($package_name)){
				message('0', 'No package name is provided');
			}

			$result = $this->getTable()->findByAttr(['package_name' => $package_name])->current();

			if ($result) {
				if ($result->trial_end < Date('Y-m-d h:i:s') && $result->enabled == '1') {
					message('0', 'Trial has ended');
				} else {
					$this->getTable()->launched($package_name);
					$days_left = ceil((strtotime($result->trial_end) - time()) / (86400));
					message('1', 'Trial has not ended', ['left' => $days_left]);
				}
			} else {
				message('0', 'No such package name');
			}
		} else {
			message('0', 'Wrong method');
		}
	}

	public function getTable() {
		if (!$this->table) {
			$sm = $this->getServiceLocator();
			$this->table = $sm->get('TrializeTable');
		}
		return $this->table;
	}

}
