<?php
namespace Auth\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthAdapter implements AdapterInterface
{
    
    protected $username;
    protected $password;
    /**
     *
     * @var ServiceLocatorInterface
     */
    protected $_sm;
    /**
     * Sets username and password for authentication
     *
     * @return void
     */
    public function __construct($username, $password, ServiceLocatorInterface $sm)
    {
        $this->username = $username;
        $this->password = $password;
        $this->_sm = $sm;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface
     *               If authentication cannot be performed
     */
    public function authenticate()
    {
        /**
         * @var Auth\Plugin\UserPlugin
         */
        $user = $this->_sm->get('Authentication')->getUserAuthenticationPlugin();
        $currentUser = $user->checkLogin($this->password, $this->username);
        if($currentUser) {
            return new Result(Result::SUCCESS, $currentUser);
        } else {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, $user);
        }
    }
}
