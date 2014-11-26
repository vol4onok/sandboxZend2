<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

class EditOrderForm extends Form {

	protected $inputFilter;
	
  public function __construct(){
    parent::__construct();

    $this->setName('product_by')
			->setAttribute('method', 'post')
			->setAttribute('class', 'form-horizontal product-form')
		    
			->add(array(
				'name' => 'csrf',
				'type' => 'Zend\Form\Element\Csrf',
				'options' => array(
					'csrf_options' => array(
			  		'messages' => array(
			  			\Zend\Validator\Csrf::NOT_SAME => _('The form submitted did not originate from the expected site'),
			  		),
			  		'timeout' => null,
					),
				),
			))
			
			->add(array(
			      'name' => 'id',
			      'attributes' => array(
                        'type'  => 'hidden',
                        'class' => 'form-control',
                        'required' => 'required',
                  ),
			  ))
			  
     		->add(array(
			      'name' => 'submit',
			      'attributes' => array(
			          'type'  => 'submit',
			          'value' => _('Buy Now'),
			          'class' => 'btn btn-link',
			      ),
			  ));

    }
    
    public function getProductFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name' => 'id',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    new Zend\Validator\Digits(),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}