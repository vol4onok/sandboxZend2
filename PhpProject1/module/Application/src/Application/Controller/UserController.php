<?php

namespace Application\Controller;

use Application\Lib\AppController;
use Auth\Adapter\AuthAdapter;

class UserController extends AppController {
  
  /**
   * login action
   *   
   */
	public function loginAction() {
        if($this->user->getUser()->getId()) {
			return $this->redirect()->toRoute('home');
		}
		$err = isset($_GET['err']) ? htmlspecialchars($_GET['err']) : '';
		$form = new \Application\Form\LoginForm();
		$form->setInputFilter($form->getLoginInputFilter());
		
		$retUrl = $this->request->getQuery('r');
        if(!empty($data->retUrl)) {
			$retUrl = $data->retUrl;
		}
		if($this->request->isPost()){
            $data = $this->request->getPost();
			$form->setData($data);
            
            if ($form->isValid()) {
				$data = $form->getData();
                /**
                 * @var \Zend\Authentication\AuthenticationService
                 */
                $auth = $this->getServiceLocator()->get('Auth');
                
                $authAdapter = new AuthAdapter($data['password'], $data['email'], $this->getServiceLocator());

                $result = $auth->authenticate($authAdapter);

                if ($result->isValid()) {
                    if(!empty($retUrl)){
						$this->redirect()->toUrl($retUrl);
					}
					else {
						$this->redirect()->toRoute('home');
					}
                } else{
					$err = _('Wrong login/password or your account is not activated');
				}
			}
		}
		return array(
			'form' => $form,
			'err' => $err,
		);
	}
	
	/**
	 * logout action
	 * 
	 */
	public function logoutAction() {
//		session_destroy();
        
        /**
        * @var $auth Zend\Authentication\AuthenticationService
        */
        $auth = $this->getServiceLocator()->get('Auth');
        $auth->clearIdentity();
		return $this->redirect()->toRoute('home');
	}
	
	/**
	 * send request to reset password
	 * 
	 */
	public function forgotpasswordAction() {
		if ($this->user->getId()) {
			return $this->redirect()->toRoute('home');
		}
		$success = false;
		$form = new \Application\Form\ForgotpasswordForm();
		
		if($this->request->isPost()){
			$data = $this->request->getPost();
			$form->setInputFilter($form->getFormInputFilter());
			$form->setData($data);
			if ($form->isValid()) {
				$data = $form->getData();
				try {
					$userTable = new \Application\Lib\User();
					$res = $userTable->forgotPass($data['email'], $data['password']);
					$success = true;
				}
				catch(\Exception $e) {
					$this->error = $e->getMessage();
				}
			}
		}
		
		return array(
			'form' => $form,
			'success' => $success,
		);
	}
	
	/**
	 * activate new password
	 * 
	 */
	public function activeforgotAction () {
		$id = $this->getParam('id');
		$code = $this->getParam('code');
		$mess = '';
		$err = null;
		try {
			$this->user->activeForgotPass($id, $code);
			$this->user->login($id);
			return $this->redirect()->toRoute('home');
		}
		catch(\Exception $e) {
			$err = $e->getMessage();
		}

    return array(
    	'mess' => $mess,
      'err' => $err
    );

	}
    
    public function registrationAction () {
        die;
		$id = $this->getParam('id');
		$code = $this->getParam('code');
		$mess = '';
		$err = null;
		try {
			$this->user->activeForgotPass($id, $code);
			$this->user->login($id);
			return $this->redirect()->toRoute('home');
		}
		catch(\Exception $e) {
			$err = $e->getMessage();
		}

    return array(
    	'mess' => $mess,
      'err' => $err
    );

	}
}
