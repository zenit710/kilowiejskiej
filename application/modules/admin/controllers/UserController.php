<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->setLayout('admin-panel');
        $this->matchesMapper = new Application_Model_DbTable_Matches();
    }

    public function indexAction()
    {
        
    }
    
    public function addAction()
    {
        
    }
    
    public function editAction()
    {
        
    }

    public function deleteAction()
    {
        
    }

}

