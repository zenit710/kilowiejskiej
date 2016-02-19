<?php

class My_Exception_NotFound
{

    public function __construct($message)
    {
        $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
        $view->layout()->disableLayout();
        throw new Zend_Controller_Action_Exception($message, 404);
    }

}