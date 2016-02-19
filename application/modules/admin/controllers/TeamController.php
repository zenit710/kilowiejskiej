<?php

class Admin_TeamController extends Zend_Controller_Action
{

    private $teamsMapper;
    
    public function init()
    {
        $this->_helper->layout->setLayout('admin-panel');
        $this->teamsMapper = new Application_Model_DbTable_Teams();
    }

    public function indexAction()
    {
        $this->view->teams = $this->teamsMapper->fetchAll();
    }
    
    public function addAction()
    {
        $form = new My_Forms_Team();
        $this->view->form = $form;
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            if(!$values['photo']){
                unset($values['photo']);
            }
            if ($values['photo_delete']) {
                $values['photo'] = NULL;
            }
            unset($values['photo_delete']);
            if (!$values['logo']) {
                unset($values['logo']);
            }
            if ($values['logo_delete']) {
                $values['logo'] = NULL;
            }
            unset($values['logo_delete']);
            $this->teamsMapper->insert($values);
            $this->redirect('/admin/team');
        }
    }
    
    public function editAction()
    {
        $id = $this->getParam('id');
        $team = $this->teamsMapper->getById($id);
        $form = new My_Forms_Team($team['photo'], $team['logo']);
        $form->populate($team->toArray());
        $this->view->form = $form;
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            if(!$values['photo']){
                unset($values['photo']);
            }
            if ($values['photo_delete']) {
                $values['photo'] = NULL;
            }
            unset($values['photo_delete']);
            if (!$values['logo']) {
                unset($values['logo']);
            }
            if ($values['logo_delete']) {
                $values['logo'] = NULL;
            }
            unset($values['logo_delete']);
            $this->teamsMapper->update($values,'id = '.$id);
            $this->redirect('/admin/team');
        }
    }

    public function deleteAction()
    {
        $id = $this->getParam('id');
        $this->teamsMapper->delete('id = '.$id);
        $this->redirect('/admin/team');
    }

}

