<?php

namespace Auth\Plugin;
use Zend\ServiceManager\ServiceLocatorInterface;
class UserPlugin
{
    
    protected $_user;
    
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;
    protected $_sm;
    
    
    public function __construct(ServiceLocatorInterface $sm)
    {
        $this->_sm = $sm;
    }
    
    public function getUser()
    {
        return $this->_user;
    }

        /**
     * check login data for user
     * return user object if exists or false
     * 
     * @param string $email
     * @param string $pass
     * @return \ArrayObject|bool
     */
    public function checkLogin($email, $pass) {
        if (!$email || !$pass)
            return false;

        $user = $this->getEntityManager()->getRepository('Model\Entity\User')->findOneBy(array(
                'email' => $email,
                'password'  => $this->saltPass($pass),
                'active'    => 1
        ));
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }
    
    /**
     * user authorization
     * 
     * @return bool
     */
    public function auth() {
        $auth = $this->_sm->get('Auth');
        if ($auth->hasIdentity()) {
            $this->_user = $auth->getIdentity();
            $this->isActive();
            return true;
        } else {
            $this->_user = new \Model\Entity\User();
        }

//        if ($forceAuth) {
//            throw new \Exception(_('Server requires you should be logged in to access this area'));
//        }

        return false;
    }
    
    /**
     * Send message to current user
     * 
     * @param string $subject
     * @param string|\Zend\Mime\Message|object $text
     */
    public function sendMail($subject, $text, $emailTo = null, $nameTo = '') {
        if (!isset($emailTo) || empty($emailTo)) {
            if ($this->_user->getEmail()) {
                throw new \Exception(_('E-Mail is not set or is empty'));
            } else {
                $emailTo = $this->_user->getEmail();
                $nameTo = $this->_user->getName();
            }
        }

        $m = new \Zend\Mail\Message();
        $m->addFrom(SUPPORT_EMAIL, SITE_NAME)
                ->addTo($emailTo, $nameTo)
                ->setSubject($subject);

        $bodyPart = new \Zend\Mime\Message();

        $bodyMessage = new \Zend\Mime\Part($text);
        $bodyMessage->type = 'text/html; charset=utf-8';

        $bodyPart->setParts(array($bodyMessage));

        $m->setBody($bodyPart);
        $m->setEncoding('UTF-8');

        $transport = new \Zend\Mail\Transport\Sendmail();
        $transport->send($m);
    }
    
    /**
     * save temporary password and send email to activate it
     * 
     * @param string $email
     * @param string $newpass
     */
    public function forgotPass($email, $newpass) {

        parent::forgotPass($email, $newpass);

        $template = new Template();
        $tmplName = 'Forgot password';
        $tmplParams = array(
            'newpass' => $newpass,
            'id' => $this->_user->getId(),
            'code' => $this->_user->getCode(),
            'name' => $this->_user->getName(),
        );

        $message = $template->prepareMessage($tmplName, $tmplParams);
        $this->sendMail($message['subject'], $message['text']);
    }
    
    /**
     * returns current user role
     * 
     * @return string
     */
    public function getRole() {
        $role = \Auth\Acl::DEFAULT_ROLE;

        //\Zend\Debug\Debug::dump($user);
        if ($this->_user->getId()) {

            $role = $this->_user->getLevel();
        }

        return $role;
    }
    
    /**
     * verify user account activity
     * if user is blocked - throw exception and don't log in user
     * 
     */
    public function isActive() {
        //verify user activity
        if (!$this->_user->getActive()) {
            throw new \Exception(_('Cannot log you in: your account is blocked. Please contact Support'));
        }

        return true;
    }
    
    /**
     * method returns if some resorce is allowed to current user, using Acl
     * 
     * @param string $resource
     * @param string $action
     * @param string $role
     * 
     * @return bool
     */
    public function isAllowed($resource = null, $action = null, $role = null) {
        $acl = $this->_sm->get('Authentication')->getAclClass();
        if (is_null($role)) {
            $role = $this->_user->getLevel();
        }
        if ($acl->hasResource($resource)) {
            return $acl->isAllowed($role, $resource, $action);
        } else {
            return false;
        }
    }
    
    protected function saltPass($pass) {
        return hash('sha256', DATABASE_SALT . $pass);
    }
    
    /**
     * 
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->_sm;
    }

    /**
     * 
     * @return ServiceLocatorInterface
     */
    public function getEntityManager() {
        if (null === $this->_em) {
            $this->_em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->_em;
    }
}
