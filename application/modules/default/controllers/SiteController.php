<?php

class SiteController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function itemAction()
    {
        $url = $this->getRequest()->getParam('url');
        $sitesMapper = new Application_Model_DbTable_Sites();
        $this->view->site = $site = $sitesMapper->getSiteByUrl($url);
    }


}

