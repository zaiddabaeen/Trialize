<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

	public $action, $action2, $short;
	protected $table;
	protected $username = "root";
	protected $password = "root";

	public function createAction() {

		$this->getPublicThings();

		$request = $this->getRequest();

		if ($request->isPost()) {

			$app_name = $request->getPost('app_name');
			$package_name = $request->getPost('package_name');
			$trial_end = $request->getPost('trial_end');

			if (strlen($package_name) == 0) {
				message('0', 'Please provide a link');
			}

			$data['app_name'] = $app_name;
			$data['package_name'] = $package_name;
			$data['trial_end'] = $trial_end;
			$data['created_at'] = Date('Y-m-d h:i:s');
			$this->getTable()->save($data);

			message('1', 'Successfully created');
		} else {
			return new ViewModel();
		}
	}

	public function deleteAction() {

		$this->getPublicThings();

		$request = $this->getRequest();

		if ($request->isPost()) {

			$id = $request->getPost('id');
			$this->getTable()->del($id);

			message('1', 'Successfully deleted');
		} else {
			message('0', 'Error');
		}
	}

	public function editAction() {

		$this->getPublicThings();

		$request = $this->getRequest();

		if ($request->isPost()) {

			$id = $request->getPost('id');
			$app_name = $request->getPost('app_name');
			$package_name = $request->getPost('package_name');
			$trial_end = $request->getPost('trial_end');

			$data['id'] = $id;
			$data['app_name'] = $app_name;
			$data['package_name'] = $package_name;
			$data['trial_end'] = $trial_end;
			$this->getTable()->save($data);

			message('1', 'Successfully saved');
		} else {

			$search = $request->getQuery()->search;

			if (isset($search)) {

				$view = new ViewModel([
					'entries' => $this->getTable()->search($search),
					'search' => $search
				]);
			} else {

				$view = new ViewModel([
					'entries' => $this->getTable()->fetchAll()
				]);
			}

			$view->setTemplate('application/index/index.phtml');
			return $view;
		}
	}

	public function getPublicThings() {
		$this->action = $this->getEvent()->getRouteMatch()->getParam('action');
		$this->action2 = $this->getEvent()->getRouteMatch()->getParam('action2');
		$this->isLoggedIn();
	}

	public function loginAction() {

		$this->layout()->action = $this->getEvent()->getRouteMatch()->getParam('action');
		$request = $this->getRequest();

		if ($request->isPost()) {

			$username = $request->getPost('username');
			$password = $request->getPost('password');
			$remember_me = $request->getPost('rememberme');

			if ($username == $this->username && $password == $this->password) {

				$_SESSION['admin'] = true;

				if ($remember_me == 'on') {
					$expire = time() + (60 * 60 * 24 * 30);
					$path = '/';
					$domain = ($_SERVER['HTTP_HOST'] !== 'localhost') ? $_SERVER['HTTP_HOST'] : null;
					$secure = false;
					$httponly = true;

//					$this->setDomain($_SERVER['server_name']);
//					$this->setExpires(time() + 60 * 60 * 24 * 30);
//					$this->getResponse()->getHeaders()->get('Set-Cookie')->admin = md5('t+_' . $password);
					setcookie('admin', md5('t+_' . $password), $expire);
				}

				message('1', 'Get In!');
			} else {

				$_SESSION['admin'] = false;

				message('0', 'Wrong credentials');
			}
		} else {
			return new ViewModel();
		}
	}

	function logoutAction() {
		$this->layout()->loggedIn = false;
		unset($_SESSION['admin']);
		unset($_COOKIE['admin']);
		setcookie('admin', null, -1);
		return $this->redirect()->toRoute('index', ['action' => 'login']);
	}

	function isLoggedIn() {
		$cookies = $this->getRequest()->getCookie();
		if ((!empty($cookies->admin) && $cookies->admin == md5('t+_' . $this->password)) || isset($_SESSION['admin'])) {
			$this->layout()->loggedIn = true;
		} else {
			return $this->redirect()->toRoute('index', ['action' => 'login']);
			$this->layout()->loggedIn = false;
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
