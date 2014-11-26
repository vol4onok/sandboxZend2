<?php

namespace Admin\Form;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class ProductForm extends \Zend\Form\Form {

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
        $this->add(
            array(
                'type' => 'DoctrineORMModule\Form\Element\DoctrineEntity',
                'name' => 'type',
                'options' => array(
                    'label'   => 'sss',
                    'object_manager' => $this->sm->get('doctrine.entitymanager.orm_default'),
                    'target_class'   => 'Model\Entity\ProductType',
                    'property'       => 'title',
                    'is_method'      => true,
                    'find_method'    => array(
                        'name'   => 'findBy',
                        'params' => array(
                            'criteria' => array('status' => 1),
                            'sort'  => array('title' => 'ASC')
                        ),
                    ),
                ),
            )
        );
        $this->add(array(
            'name' => 'slug',
            'options' => array(
                'label' => _('Slug'),
            ),
            'attributes' => array(
                'class' => 'input-big',
                'autofocus' => true,
            ),
        ));
        $this->add(array(
            'name' => 'price',
            'options' => array(
                'label' => _('Price'),
            ),
            'attributes' => array(
                'class' => 'input-big',
                'autofocus' => true,
            ),
        ));
        $this->add(array(
            'type' => 'select',
            'name' => 'currency',
            'options' => array(
                'label' => _('Currency'),
                'options' => array(
                    'usd' => 'Dollars',
                ),
            ),
            'attributes' => array(
                'class' => 'input-big',
                'autofocus' => true,
            ),
        ));


        $this->add(array(
            'name' => 'description',
            'options' => array(
                'label' => _('Description'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'input-big',
            ),
        ));
        $this->add(
            array(
                'type' => 'Model\ObjectJsonText',
                'name' => 'attachments',
                'options' => array(
                    'label'   => 'Image',
                    'object_manager' => $this->sm->get('doctrine.entitymanager.orm_default'),
                    'target_class'   => 'Model\Entity\Attachment',
                    'property'       => 'resource',
                ),
                'attributes' => array(
                    'class' => 'btn btn-small',
                    'id'    => 'image-json',
                ),
            )
        );

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
    }
    public function getInpFilter() {
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

            $inputFilter->add($factory->createInput(array(
                        'name' => 'slug',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            new \Application\Lib\Validator\ExistValidator($this->sm->get('ProductTable'), 'slug', $this->get('id')->getValue(), 'id', "Product slug already exists"),
                            new \Zend\Validator\Regex("/[a-zA-Zа-яА-Я0-9_-]+/"),
                        ),
            )));

            $inputFilter->add($factory->createInput(array(
                        'name' => 'price',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'Float',
                            )
                        )
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
