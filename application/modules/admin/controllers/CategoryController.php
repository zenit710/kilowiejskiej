<?php

class Admin_CategoryController extends Zend_Controller_Action
{

    const MAX_FILE_SIZE = 1048576;
    private $categoriesMapper;
    
    public function init()
    {
        $this->categoriesMapper = new Application_Model_DbTable_Categories();
        $this->_helper->layout->setLayout('admin-panel');
    }

    public function indexAction()
    {
        $this->view->categories = $this->categoriesMapper->fetchAll();
    }

    public function addAction()
    {
        $form = new My_Forms_Category();
        $this->view->form = $form;
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $values['slug'] = $this->generateSlug($values['name']);
            if(!$values['picture']){
                unset($values['picture']);
            }
            $this->categoriesMapper->insert($values);
            $this->redirect('/admin/category');
        }
    }
    
    public function editAction()
    {
        $id = $this->getParam('id');
        $category = $this->categoriesMapper->getById($id);
        if(!$category){
            return;
        }
        $form = new My_Forms_Category($category['picture']);
        $form->populate($category->toArray());
        $this->view->form = $form;
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $values['slug'] = $this->generateSlug($values['name']);
            if(!$values['picture']){
                unset($values['picture']);
            }
            $this->categoriesMapper->update($values, 'id = '.$id);
            $this->redirect('/admin/category');
        }
    }
    
    public function deleteAction()
    {
        $id = $this->getParam('id');
        $this->categoriesMapper->delete('id = '.$id);
        $this->redirect('/admin/category');
    }

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

