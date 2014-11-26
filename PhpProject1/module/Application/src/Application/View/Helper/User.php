<?php
namespace Application\View\Helper;

use Application\View\Helper\CoreAbstractHelper;

class User extends CoreAbstractHelper { 
    
	public function __invoke() {
		return $this->getServiceLocator()->get('User');
	}
	public function getId() {
		return $id;
	}
}
