<?php

namespace Admin\Form;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class CategoryForm extends \Zend\Form\Form {

    /**
     * @var Zend\InputFilter\InputFilter;
     */
    protected $inputFilter;
    protected $sm;

    public function __construct($name, ServiceLocatorInterface $sm) {

        $this->sm = $sm;
        parent::__construct($name);

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'line-form');

        $this->add(array(
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
        ));

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => _('Title'),
            ),
            'attributes' => array(
                'class' => 'input-big',
                'autofocus' => true,
            ),
        ));
        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'label' => _('Status'),
                'options' => array(
                    '0' => 'disable',
                    '1' => 'enable',
                ),
            ),
            'attributes' => array(
                'class' => 'chosen-select level-select',
                'autofocus' => true,
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => _('Save'),
            ))
        );

        $this->add(array(
            'name' => 'cancel',
            'type' => 'button',
            'options' => array(
                'label' => _('Cancel'),
            ),
            'attributes' => array(
                'value' => _('Cancel'),
                'onclick' => 'close_fancybox();',
                'class' => 'clear-btn',
            ))
        );
        $this->setInputFilter($this->getFilter());
    }

    public function getFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                        'name' => 'title',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StringTrim'),
                        ),
            )));

           $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
