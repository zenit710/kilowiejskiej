<?php

class Admin_UserController extends Zend_Controller_Action
{

    private $usersMapper;
    
    public function init()
    {
        $this->_helper->layout->setLayout('admin-panel');
        $this->usersMapper = new Application_Model_DbTable_Users();
    }

    public function indexAction()
    {
        $this->view->users = $this->usersMapper->fetchAll();
    }
    
    public function permissionsAction()
    {
        $id = $this->getParam('id');
        $permissions = $this->usersMapper->getPermissions($id);
        if (!$permissions) {
            throw new My_Exception_NotFound('Nie ma takiego użytkownika!');
        }
        $form = new My_Forms_User();
        $form->populate($permissions->toArray());
        $this->view->form = $form;
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $this->usersMapper->update($values,'id = '.$id);
            $this->redirect('/admin/user');
        }
    }
    
    public function banAction()
    {
        $id = $this->getParam('id');
        $banned = $this->usersMapper->isBanned($id);
        $this->usersMapper->update(array('is_banned' => !$banned),'id = '.$id);
        $this->redirect('/admin/user');
    }

    public function deleteAction()
    {
        $id = $this->getParam('id');
        $this->usersMapper->delete('id = '.$id);
        $this->redirect('/admin/user');
    }

}

