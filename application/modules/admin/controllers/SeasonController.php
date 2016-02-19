<?php

class Admin_SeasonController extends Zend_Controller_Action
{

    private $seasonsMapper;
    
    public function init()
    {
        $this->seasonsMapper = new Application_Model_DbTable_Seasons();
        $this->_helper->layout->setLayout('admin-panel');
    }

    public function indexAction()
    {
        $this->view->seasons = $this->seasonsMapper->fetchAll();
    }

    public function addAction()
    {
        $form = new My_Forms_Season();
        $this->view->form = $form;
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $this->seasonsMapper->insert($values);
            $this->redirect('/admin/season');
        }
    }
    
    public function editAction()
    {
        $id = $this->getParam('id');
        $season = $this->seasonsMapper->getById($id);
        if(!$season){
            throw new My_Exception_NotFound('Nie ma takiego sezonu!');
        }
        $form = new My_Forms_Season();
        $form->populate($season->toArray());
        $this->view->form = $form;
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $this->seasonsMapper->update($values, 'id = '.$id);
            $this->redirect('/admin/season');
        }
    }
    
    public function deleteAction()
    {
        $id = $this->getParam('id');
        $this->seasonsMapper->delete('id = '.$id);
        $this->redirect('/admin/season');
    }
    
}

