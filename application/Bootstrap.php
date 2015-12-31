<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initTimezone()
    {
        date_default_timezone_set('Europe/Warsaw');
    }

    protected function _initAuth()
    {
        Zend_Registry::set('auth', Zend_Auth::getInstance());
        $this->bootstrap(array(
            'frontController'
        ));
        $this->getResource('frontController')->registerPlugin(new My_Controller_Plugin_Auth());
    }
    
    protected function _initPage(){
        $this->bootstrap(array(
            'view'
        ));  
        $view = $this->getResource('view');
        $defaultsArray = array(
            'page' => array(
                'title' => array(
                    'separator' => '',
                    'content' => ''
                ),
                'keywords' => '',
                'description' => ''
            )
        );
        $defaults = new Zend_Config($defaultsArray, true);
        $cfg = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini','production');
        $cfg = $defaults->merge($cfg);
        $view->headTitle()
                ->setDefaultAttachOrder($cfg->page->title->defaultAttachOrder)
                ->setSeparator($cfg->page->title->separator)
                ->headTitle($cfg->page->title->content);
        $view->headMeta()
                ->appendName('keywords', $cfg->page->keywords)
                ->appendName('description', $cfg->page->description);
    }
    
    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $config);
        return $config;
    }

}

