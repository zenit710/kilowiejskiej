<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

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
    
}

