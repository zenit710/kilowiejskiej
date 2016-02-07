<?php

class Admin_NewsController extends Zend_Controller_Action
{

    private $newsMapper;
    private $categoriesMapper;
    
    public function init()
    {
        $this->_helper->layout->setLayout('admin-panel');
        $this->newsMapper = new Application_Model_DbTable_News();
        $this->categoriesMapper = new Application_Model_DbTable_Categories();
    }

    public function indexAction()
    {
        $this->view->news = $this->newsMapper->getAll();
    }
    
    public function addAction()
    {
        $categories = $this->categoriesMapper->getAllNamesWithId();
        $categoriesArray = $this->prepareCategoriesArray($categories);
        $form = new My_Forms_News($categoriesArray);
        $this->view->form = $form;
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $values['author_id'] = 1;
            $values['date'] = date("Y-m-d H:i:s");
            $slug = My_Slugs::string2slug($values['title']);
            if (!$this->newsMapper->isSlugUnique($slug)) {
                $dt = new DateTime('now');
                $slug = $dt->getTimestamp() . '-' . $slug;
            }
            $values['slug'] = $slug;
            if(!$values['main_photo']){
                unset($values['main_photo']);
            }
            $this->newsMapper->insert($values);
            $this->redirect('/admin/news');
        }
    }
    
    public function editAction()
    {
        $id = $this->getParam('id');
        $news = $this->newsMapper->getById($id);
         if(!$news){
            return;
        }
        $categories = $this->categoriesMapper->getAllNamesWithId();
        $categoriesArray = $this->prepareCategoriesArray($categories);
        $form = new My_Forms_News($categoriesArray,$news['main_photo']);
        $form->populate($news->toArray());
        $this->view->form = $form;
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $values['slug'] = My_Slugs::string2slug($values['title']);
            if(!$values['main_photo']){
                unset($values['main_photo']);
            }
            $this->newsMapper->update($values,'id = '.$id);
            $this->redirect('/admin/news');
        }
    }

    public function deleteAction()
    {
        $id = $this->getParam('id');
        $this->newsMapper->delete('id = '.$id);
        $this->redirect('/admin/news');
    }

    /**
     * Przygotowuje tablicę dostępnych kategorii
     * @param array $categories
     * @return array
     */
    private function prepareCategoriesArray($categories)
    {
        if($categories) {
            $categoriesArray = array();
            foreach ($categories as $category){
                $categoriesArray[$category->id] = $category->name;
            }
            return $categoriesArray;
        }
        return null;
    }

}

