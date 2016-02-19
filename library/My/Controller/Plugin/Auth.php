<?php

class My_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    
    private $_module = null;
    private $_action = null;
    private $_controller = null;
    
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if(!($this->_action || $this->_controller || $this->_module))
        {
            $this->_module = $request->getModuleName();
            $this->_action = $request->getActionName();
            $this->_controller = $request->getControllerName();
        }
        
        $auth = Zend_Registry::getInstance()->get('auth');
        $acl = new Zend_Acl();
        // for default module
        if ($this->_module == 'default') {
            // access resources (controllers)
            // usually there will be more access resources
            $acl->add(new Zend_Acl_Resource('index'));
            $acl->add(new Zend_Acl_Resource('news'));
            $acl->add(new Zend_Acl_Resource('site'));
            $acl->add(new Zend_Acl_Resource('error'));
            $acl->add(new Zend_Acl_Resource('match'));
 
            // access roles
            $acl->addRole(new Zend_Acl_Role('USER'));
            $acl->addRole(new Zend_Acl_Role('MODERATOR'));
            $acl->addRole(new Zend_Acl_Role('ADMIN'));
 
            // access rules
            $acl->allow('USER'); // allow guests everywhere
            $acl->allow('MODERATOR'); // allow users everywhere
            $acl->allow('ADMIN'); // allow administrators everywhere
 
            $role = $auth->getIdentity()
            ? $auth->getIdentity()->permissions : 'USER';
            
            if (!$acl->has($this->_controller)) {
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
                $redirector->gotoUrlAndExit('/404');
            }

            if (!$acl->isAllowed($role, $this->_controller, $this->_action)) {
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
                $redirector->gotoUrlAndExit('/404');
            }
 
        }
        // for users and seasons
        else if ($this->_module == 'admin' && in_array($this->_controller, array('user','season'))) {
 
            // access resources (controllers)
            // usually there will be more access resources
            $acl->add(new Zend_Acl_Resource('index'));
            $acl->add(new Zend_Acl_Resource('category'));
            $acl->add(new Zend_Acl_Resource('match'));
            $acl->add(new Zend_Acl_Resource('news'));
            $acl->add(new Zend_Acl_Resource('player'));
            $acl->add(new Zend_Acl_Resource('season'));
            $acl->add(new Zend_Acl_Resource('seasonData'));
            $acl->add(new Zend_Acl_Resource('site'));
            $acl->add(new Zend_Acl_Resource('team'));
            $acl->add(new Zend_Acl_Resource('user'));
            $acl->add(new Zend_Acl_Resource('error'));
 
            // access roles
            $acl->addRole(new Zend_Acl_Role('USER'));
            $acl->addRole(new Zend_Acl_Role('MODERATOR'));
            $acl->addRole(new Zend_Acl_Role('ADMIN'));
 
            // access rules
            $acl->allow('ADMIN'); // allow administrators everywhere
 
            $role = $auth->getIdentity()
            ? $auth->getIdentity()->permissions : 'USER';

            if (!$acl->has($this->_controller)) {
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
                $redirector->gotoUrlAndExit('/404');
            }
 
            if (!$acl->isAllowed($role, $this->_controller, $this->_action)) {
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
                $redirector->gotoUrlAndExit('/admin/index/login');
            }
 
        }
        // for admin module
        else if ($this->_module == 'admin' && $this->_action != 'login'  && $this->_action != 'register') {
 
            // access resources (controllers)
            // usually there will be more access resources
            $acl->add(new Zend_Acl_Resource('index'));
            $acl->add(new Zend_Acl_Resource('category'));
            $acl->add(new Zend_Acl_Resource('match'));
            $acl->add(new Zend_Acl_Resource('news'));
            $acl->add(new Zend_Acl_Resource('player'));
            $acl->add(new Zend_Acl_Resource('season'));
            $acl->add(new Zend_Acl_Resource('seasonData'));
            $acl->add(new Zend_Acl_Resource('site'));
            $acl->add(new Zend_Acl_Resource('team'));
            $acl->add(new Zend_Acl_Resource('user'));
            $acl->add(new Zend_Acl_Resource('error'));
 
            // access roles
            $acl->addRole(new Zend_Acl_Role('USER'));
            $acl->addRole(new Zend_Acl_Role('MODERATOR'));
            $acl->addRole(new Zend_Acl_Role('ADMIN'));
 
            // access rules
            $acl->allow('MODERATOR'); // allow moderator everywhere
            $acl->allow('ADMIN'); // allow administrators everywhere
 
            $role = $auth->getIdentity()
            ? $auth->getIdentity()->permissions : 'USER';

            if (!$acl->has($this->_controller)) {
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
                $redirector->gotoUrlAndExit('/404');
            }
 
            if (!$acl->isAllowed($role, $this->_controller, $this->_action)) {
                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
                $redirector->gotoUrlAndExit('/admin/index/login');
            }
 
        } 
    }
}