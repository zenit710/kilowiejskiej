<?php

class NewsController extends Zend_Controller_Action
{

    private $monthNames = array('Sty','Lut','Mar','Kwi','Maj','Cze','Lip','Sie','Wrz','Paź','Lis','Gru');
    
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
        $news = $newsMapper->getByCategoryAndSlug($category, $slug);
        $this->view->news = $this->prepareNewsDate($news);
        $this->view->currentLink = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        
        $filter = new Zend_Filter_StripTags();
        
        $imgUrl = "http://$_SERVER[HTTP_HOST]";
        $imgUrl .= $news['main_photo'] ? "/img/kw/news/".$news['main_photo'] : "/img/kwft_150.png";
        
        $this->getInvokeArg('bootstrap')->getResource('view')->headTitle($news['title']); 
        $this->view->doctype('XHTML1_RDFA');
        $this->getInvokeArg('bootstrap')->getResource('view')->headMeta()
                ->setProperty('og:type', 'article') 
                ->setProperty('og:title', $news['title']) 
                ->setProperty('og:description', $filter->filter($news['description'])) 
                ->setProperty('og:image', $imgUrl)
                ->appendName('keywords', $news['tags']); 
        
    }

    /**
     * Przygotowuje datę dodania newsa do wyświetlenia na stronie
     * @param Zend_Db_Table_Row $news
     * @return Zend_Db_Table_Row
     */
    public function prepareNewsDate($news){
        $timestamp = strtotime($news->date);
        $day = date('j',$timestamp);
        $month = $this->monthNames[date('n',$timestamp) -1];
        $news->date = array('day' => $day, 'month' => $month);
        return $news;
    }

}

