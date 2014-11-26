<?php

namespace Application\Controller;
use Application\Lib\AppController;

class PaymentController extends AppController {

    protected $paymentTable;
    
    public function ready() {
        parent::ready();

    }

    public function indexAction() {

        
        return array(
            
        );
    }
}
