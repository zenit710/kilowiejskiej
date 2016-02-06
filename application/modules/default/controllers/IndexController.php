<?php

class IndexController extends Zend_Controller_Action
{

    const PAGE_RANGE = 5;
    
    private $monthNames = array('Sty','Lut','Mar','Kwi','Maj','Cze','Lip','Sie','Wrz','Paź','Lis','Gru');    
    
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $page = $this->_getParam('page',1);
        
        // GET NEWS FOR HOMEPAGE
        $newsMapper = new Application_Model_DbTable_News();
        $paginator = $newsMapper->getNewsForHomepage($page);
        $this->view->news = $this->prepareNewsForHomepage($paginator->getItemsByPage($page));
        $paginator->setPageRange(self::PAGE_RANGE);
        $this->view->paginator = $paginator;
    }

    public function introAction(){
        $this->view->layout()->setLayout('intro');
        $this->view->viewRenderer()->setNoRender(true);
    }
    
    /**
     * Wyciąga z daty newsa dzień miesiącia i polski skrót nazwy miesiąca
     * 
     * @param Zend_Db_Table_Rowset $newsList
     * @return Zend_Db_Table_Rowset
     */
    public function prepareNewsForHomepage($newsList){
        foreach($newsList as &$news){
            $timestamp = strtotime($news['date']);
            $day = date('j',$timestamp);
            $month = $this->monthNames[date('n',$timestamp) -1];
            $news['date'] = array('day' => $day, 'month' => $month);
        }
        
        return $newsList;
    }

}

