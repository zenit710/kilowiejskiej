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
            $values['slug'] = $this->generateSlug($values['title']);
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
            $values['slug'] = $this->generateSlug($values['title']);
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

    /**
     * Przygotowuje slug na podstawie tytułu artykuł€
     * @param string $string
     * @return string
     */
    private function generateSlug($string)
    {
        $string = strtr($string, 'ĘęÓóĄąŚśŁłŹźŻżĆćŃń', 'EeOoAaSsLlZzZzCcNn');
        $string = strtr($string, 'ˇ¦¬±¶Ľ','ASZasz');
        $string = preg_replace("'[[:punct:][:space:]]'",'-',$string);
        $string = strtolower($string);
        $znaki = '-'; 
        $powtorzen = 1;
        $string = preg_replace_callback('#(['.$znaki.'])\1{'.$powtorzen.',}#', create_function('$a', 'return substr($a[0], 0,'.$powtorzen.');'), $string);
        return $string;
    }

}

