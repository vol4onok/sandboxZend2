<?
namespace Application\View;

use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\Form\View\Helper\FormElementErrors;

class WrappedElement extends AbstractHelper {
	public function __invoke(ElementInterface $element, $class = 'element') {
		$view   = $this->getView();
		$type = $element->getAttribute('type');
		if(empty($type)) {
			$type = 'text';
		}

		$name = $element->getName();
		$id = $element->getAttribute('id');
		$name = (!empty($id)) ? $id : $name;

		$element->setAttribute('id', $name);

		if($element instanceof \Application\Lib\Form\HTML) {
			$helper = new \Application\View\Helper\FormHTML();
			$input = $helper($element);
		}
		elseif($element instanceof \Zend\Form\Element\Button && $type == 'submit') {
			$input = $view->formButton($element, $element->getAttribute('label'));
		}
		elseif($element instanceof \Application\Lib\Form\StylizedFile) {
			$helper = new \Application\View\Helper\FormStylizedFile();
			$input = $helper($element);
		}
		else {
			$input  = 'form'.ucfirst($type);
			$input = $view->$input($element);
		}
		$visible = !in_array($type, array('hidden'));

    $elementErrorsHelper = $this->getElementErrorsHelper();
    $errors = $elementErrorsHelper->render($element);
		if(!empty($errors)) {
			//$errors = "<div class='error'>$errors [{$element->getName()}]</div>";
			$errors = "<div class='error'>$errors</div>";
		}

		//$element->setAttribute('id', $element->getName());

		//translate labels
		$currLabel = $element->getLabel();
		if($currLabel)$element->setLabel($currLabel);

		$label = '';
		try {
			$label = $view->formLabel($element);
		}
		catch(\Exception $e) {}

		switch($type) {
			case 'checkbox':
			case 'radio':
				$elementHTML = "<div>$label $input</div>"; break;
			//case 'html': $elementHTML = "<div>$input $label</div>"; break;
			default: $elementHTML = (empty($label) ? '' : "<div class='label'>$label:</div>"). "<div class='el'>$input</div>";
		}

		return "<div class='$type $name ".$view->escapeHTML($class)." ".($errors?"highlited":"")."'>
				$elementHTML".($visible ? '<br>' : '')."
				$errors
			</div>";
	}

	protected function getElementErrorsHelper()	{
		if (isset($this->elementErrorsHelper) && $this->elementErrorsHelper) {
			return $this->elementErrorsHelper;
		}

		if (method_exists($this->view, 'plugin')) {
			$this->elementErrorsHelper = $this->view->plugin('form_element_errors');
		}

		if (!$this->elementErrorsHelper instanceof FormElementErrors) {
			$this->elementErrorsHelper = new FormElementErrors();
		}

		return $this->elementErrorsHelper;
	}
}
