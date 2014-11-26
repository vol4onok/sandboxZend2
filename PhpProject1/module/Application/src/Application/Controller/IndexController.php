<?php

namespace Application\Controller;

use Application\Lib\AppController;

class IndexController extends AppController {

    protected $productTable;

    const COUNT_PER_PAGE = 12;

    public function ready() {
        parent::ready();
        $this->productTable = $this->getServiceLocator()->get('ProductTable');
    }

    public function indexAction() {
        
        $page = (int) $this->params()->fromRoute('page', 1);
        $categoryId = (int) $this->params()->fromRoute('category', null);
        $queryParams = array();
        if ($categoryId) {
            $category = $this->getServiceLocator()->get('TypeTable')->getCategoryById($categoryId);
            if ($category->count() == 0 || $category->current()->status == 0) {
                $this->getResponse()->setStatusCode(404);
                return;
            }
            $queryParams['type_id'] = $categoryId;
        }

        return array(
            'category' => (isset($category)) ? $category->current() : null,
            'paginator' => $this->productTable->getPaginator($queryParams, self::COUNT_PER_PAGE, $page),
            'categoryId' => $categoryId,
        );
    }
}
