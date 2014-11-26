<?php
/**
* @namespace
*/
namespace Auth;

/**
* @uses \Auth\Plugin\UserPlugin
* @uses \Zend\Cache\Pattern\ObjectCache
*/
use Auth\Plugin\UserPlugin as AuthPlugin;
use Application\Lib\AppController;
use Zend\Cache\Pattern\ObjectCache;
/**
* Authentication Event Handler Class
*
* This Event Handles Authentication
*/
class Authentication {
	/**
	* @var \Zend\Cache\Pattern\ObjectCache
	*/
    private $_cachedAcl = null;
    private $_userAuth;

    /**
	* preDispatch Event Handler
	*
	* @param AppController $controller
	* @throws \Exception
	*/
	public function preDispatch(AppController $controller) {
		//@todo - Should we really use here and Controller Plugin?
		/**
		* @var User
		*/
		$user = $this->getUserAuthenticationPlugin();
		$acl = $this->getAclClass();
		$role = $user->getRole();
        $params = $controller->getEvent()->getRouteMatch()->getParams();
        
		if (!$acl->hasResource($params['controller'])) {
			throw new \Exception('Acl resource ' . $params['controller'] . ' not defined');
		}
				
		if (!$acl->isAllowed($role, $params['controller'], $params['action'])) {
			if($role == 'guest') {
					$url = URL.'user/login?r='.urlencode($_SERVER['REQUEST_URI']);
					header('HTTP/1.1 302 Found');
					header('Location: '.$url);
					exit;
			}
			else {
				$controller->forbiddenAction();
			}
		}
		
	}

	/**
	* Sets Authentication Plugin
	*
	* @param \Auth\Plugin\UserPlugin $userAuthenticationPlugin
	* @return Authentication
	*/
	public function setUserAuthenticationPlugin(AuthPlugin $userAuthenticationPlugin) {
		
        $this->_userAuth = $userAuthenticationPlugin;
		return $this;
	}

	/**
	* Gets Authentication Plugin
	*
	* @return \Auth\Plugin\UserPlugin
	*/
	public function getUserAuthenticationPlugin()
	{
		if ($this->_userAuth === null) {
			$this->_userAuth = new \Auth\Plugin\UserPlugin();
		}
		return $this->_userAuth;
	}

	/**
	* Sets ACL Cache Class
	*
	* @param ObjectCache $aclClass
	* @return Authentication
	*/
	public function setAclClass(ObjectCache $aclClass)
	{
		$this->_cachedAcl = $aclClass;
		return $this;
	}

	/**
	* Gets ACL Cache Class
	*
	* @return \Auth\Alc
	*/
	public function getAclClass() {
        
		return $this->_cachedAcl;
	}
}
