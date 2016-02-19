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
            $values['slug'] = My_Slugs::string2slug($values['name']);
            if(!$values['picture']){
                unset($values['picture']);
            }
            if ($values['picture_delete']) {
                $values['picture'] = NULL;
            }
            unset($values['picture_delete']);
            $this->categoriesMapper->insert($values);
            $this->redirect('/admin/category');
        }
    }
    
    public function editAction()
    {
        $id = $this->getParam('id');
        $category = $this->categoriesMapper->getById($id);
        if(!$category){
            throw new My_Exception_NotFound('Nie ma kategorii o podanym ID!');
        }
        $form = new My_Forms_Category($category['picture']);
        $form->populate($category->toArray());
        $this->view->form = $form;
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $values['slug'] = My_Slugs::string2slug($values['name']);
            if(!$values['picture']){
                unset($values['picture']);
            }
            if ($values['picture_delete']) {
                $values['picture'] = NULL;
            }
            unset($values['picture_delete']);
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

}

