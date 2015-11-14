<?php

class NewsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function itemAction()
    {
        $category = $this->getRequest()->getParam('category');
        $slug = $this->getRequest()->getParam('slug');
        $newsMapper = new Application_Model_DbTable_News();
        $this->view->news = $news = $newsMapper->getByCategoryAndSlug($category, $slug);
        $this->view->currentLink = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        
        $this->getInvokeArg('bootstrap')->getResource('view')->headTitle($news['title']); 
        $this->view->doctype('XHTML1_RDFA');
        $this->getInvokeArg('bootstrap')->getResource('view')->headMeta()
                ->setProperty('og:type', 'article') 
                ->setProperty('og:title', $news['title']) 
                ->setProperty('og:description', $news['description']) 
                ->setProperty('og:image', "http://$_SERVER[HTTP_HOST]/img/kwft_150.png"); 
        
    }

}

