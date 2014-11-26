<?php
namespace Application\View;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormElementErrors;
use Application\View\Helper\CoreAbstractHelper;

class WrappedUploadElement extends CoreAbstractHelper {
    
    protected $view;

    /**
    * Set script paths
    */
    protected function getViewHelper()
    {
        $this->getView()->headScript()->appendFile($this->getView()->basePath() . '/js/simpleAjaxUploder/SimpleAjaxUploader.js', 'text/javascript');
        $this->getView()->headScript()->appendFile($this->getView()->basePath() . '/js/simpleAjaxUploder/SimpleAjaxUploader.min.js', 'text/javascript');
	}
    
	public function __invoke(ElementInterface $element, $class = 'element') 
    {
        $this->getViewHelper();
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
			$input = $this->getView()->formButton($element, $element->getAttribute('label'));
		}
		elseif($element instanceof \Application\Lib\Form\StylizedFile) {
			$helper = new \Application\View\Helper\FormStylizedFile();
			$input = $helper($element);
		}
		else {
			$input  = 'form'.ucfirst($type);
			$input = $this->getView()->$input($element);
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
			$label = $this->getView()->formLabel($element);
		}
		catch(\Exception $e) {}
        $imageArray = json_decode($element->getValue());
        $inputUpload = '<input type="button" id="upload-btn" class="add form-button clearfix" value="Choose file">
        <span style="padding-left:5px;vertical-align:middle;"><i>PNG, JPG, or GIF (5Mb max file size)</i></span>
        <div id="errormsg" class="clearfix redtext"></div>	              
        <div id="pic-progress-wrap" class="progress-wrap" style="margin-top:10px;margin-bottom:10px;"></div>	
        <div id="picbox" class="image-uploder" style="padding-top:0px;padding-bottom:10px;">';
        foreach ($imageArray as $imageId => $resource) {
        $inputUpload .=    '<div class="product-image">
                <img src="' . $this->getView()->basePath($resource) . '"/>
                <a data-image="' . $imageId . '" class="delete-image" href="javascript:void(0)" title="Close"></a>
            </div>';
        }
        $inputUpload .= '</div>';
		$elementHTML = "<div class='el'>$inputUpload$input</div>";
        $script = <<<HTML
<script>
        window.onload = function() {
            var btn = document.getElementById('upload-btn'),
                    wrap = document.getElementById('pic-progress-wrap'),
                    picBox = document.getElementById('picbox'),
                    errBox = document.getElementById('errormsg');
            
        $("#picbox").on("click", "a.delete-image", function() {
                var link = $(this);
                var imageData = link.attr('data-image');
                $.ajax({
                   url: '{$this->getView()->url('admin', array('controller' => 'uploader', 'action' => 'delete'))}',
                   type: 'POST',
                   dataType: 'json',
                   data: { id: imageData},
                   success: function(response) {
                       if (response.success === true) {
                           link.parent().remove();
                           var imageJsonIds = $('#{$id}').val();
                           var data = JSON.parse( imageJsonIds );
                           delete data[response.id];
                           $('#{$id}').val(JSON.stringify(data));
                       } else {
                           if (response.msg) {
                            errBox.innerHTML = response.msg;
                            } else {
                                errBox.innerHTML = 'The file has not been deleted';
                            }
                       }
                           resize_fancybox();
                   },
                   error : function() {
                       alert('Error Delete!!!');
                   }
                });
            });

            var uploader = new ss.SimpleUpload({
                button: btn,
                url: '{$this->getView()->url('admin', array('controller' => 'uploader', 'action' => 'index'))}',
                progressUrl: '{$this->getView()->url('admin', array('controller' => 'uploader', 'action' => 'upload-progress'))}',
                name: 'imageType',
                multiple: true,
                maxUploads: 4,
                maxSize: 5000,
                allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
                accept: 'image/*',
                hoverClass: 'btn-hover',
                focusClass: 'active',
                disabledClass: 'disabled',
                responseType: 'json',
                onExtError: function(filename, extension) {
                    alert(filename + ' is not a permitted file type.' + 'Only PNG, JPG, and GIF files are allowed in the demo.');
                },
                onSizeError: function(filename, fileSize) {
                    alert(filename + ' is too big. (500K max file size)');
                },
                onSubmit: function(filename, ext) {
                    var prog = document.createElement('div'),
                            outer = document.createElement('div'),
                            bar = document.createElement('div'),
                            size = document.createElement('div');

                    prog.className = 'prog';
                    size.className = 'size';
                    outer.className = 'progress progress-striped active';
                    bar.className = 'progress-bar progress-bar-success';

                    outer.appendChild(bar);
                    prog.innerHTML = '<span style="vertical-align:middle;">' + filename + ' - </span>';
                    prog.appendChild(size);
                    prog.appendChild(outer);
                    wrap.appendChild(prog); // 'wrap' is an element on the page

                    this.setProgressBar(bar);
                    this.setProgressContainer(prog);
                    this.setFileSizeBox(size);

                    errBox.innerHTML = '';
                    btn.value = 'Choose another file';
                },
                startXHR: function() {
                    var abort = document.createElement('button');

                    wrap.appendChild(abort);
                    abort.className = 'btn btn-sm btn-info';
                    abort.innerHTML = 'Cancel';
                    this.setAbortBtn(abort, true);
                },
                onComplete: function(filename, response) {
                    if (!response) {
                        errBox.innerHTML = 'Unable to upload file';
                        return;
                    }
                    if (response.success === true) {
                        picBox.innerHTML += '<div class="product-image">' + 
                                    '<img src="{$this->getView()->basePath('uploads/products/')}' + 
                                        encodeURIComponent(response.file) + 
                                    '"/>' +
                                    '<a data-image="' + encodeURIComponent(response.id) + '" class="delete-image" href="javascript:void(0)" title="Close"></a>' +
                                '</div>';
                        var imageJsonIds = $('#{$id}').val();
                        var data = JSON.parse( imageJsonIds );
                        data[response.id] = '/uploads/products/' + response.file;
                        $('#{$id}').val(JSON.stringify(data));
                        resize_fancybox();
                    } else {
                        if (response.msg) {
                            errBox.innerHTML = response.msg;
                        } else {
                            errBox.innerHTML = 'Unable to upload file';
                        }
                    }
                }
            });
        };
    </script>                
HTML;
		return "<div class='$type $name ".$this->getView()->escapeHTML($class)." ".($errors?"highlited":"")."'>
				$elementHTML".($visible ? '<br>' : '')."
				$errors
                $script
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
