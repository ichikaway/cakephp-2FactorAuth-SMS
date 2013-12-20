<?php

class UsersController extends AppController {


	public function beforeFilter() {
		parent::beforeFilter();
		// Allow users to register and logout.
		$this->Auth->allow('add', 'logout');
	}

	public function login() {
		if ($this->request->is('post')) {

			//-------- 2 Factor Auth --------------
			$user = $this->Auth->identify($this->request, $this->response);
			if(empty($user['id'])) {
				$this->Session->setFlash(__('Invalid username or password, try again'));
				return;
			}

			if(empty($this->request->data('User.sms_token'))) {

				$account_sid = Configure::read('account_sid');
				$auth_token  = Configure::read('auth_token');
				$from_number = Configure::read('from_number');

				$rand = mt_rand();
				$token = substr($rand, 1, 4);

				$this->Session->write('sms_token', $token);

				//sending sms
				App::import('Vendor', '/Twilio/Services/Twilio');
				$client = new Services_Twilio($account_sid, $auth_token);
				$message = $client->account->messages->sendMessage(
						$from_number,
						$user['tel'],
						"sms_token: " . $token
						);
				return $this->render('login_sms');
			}


			$sms_token = $this->Session->read('sms_token');
			$this->Session->delete('sms_token');
			if(empty($sms_token) || $sms_token != $this->request->data('User.sms_token')) {
				unset($this->request->data);
				$this->Session->setFlash(__('Invalid SMS token, try again'));
				return ;
			}
			//-------- 2 Factor Auth --------------

			if ($this->Auth->login()) {
				$this->Session->setFlash(__('Passed 2 Factor Auth. Logged in.'));
				return $this->redirect($this->Auth->redirect());
			}
			$this->Session->setFlash(__('Invalid username or password, try again'));
		}
	}

	public function logout() {
		return $this->redirect($this->Auth->logout());
	}

	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(
					__('The user could not be saved. Please, try again.')
					);
		}
	}

	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(
					__('The user could not be saved. Please, try again.')
					);
		} else {
			$this->request->data = $this->User->read(null, $id);
			unset($this->request->data['User']['password']);
		}
	}

	public function delete($id = null) {
		$this->request->onlyAllow('post');

		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

}