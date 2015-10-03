<?php

class IndexController extends Zend_Controller_Action
{

    private $monthNames = array('Sty','Lut','Mar','Kwi','Maj','Cze','Lip','Sie','Wrz','Paź','Lis','Gru');
    
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // GET NEWS FOR HOMEPAGE
        $newsMapper = new Application_Model_DbTable_News();
        $news = $newsMapper->getNewsForHomepage();
        $this->view->news = $this->prepareNewsForHomepage($news);
        
        // GET INFO ABOUT ACTUAL SEASON
        $seasonMapper = new Application_Model_DbTable_Seasons();
        $this->view->seasonInfo = $seasonMapper->getActualSeasonInfo();
        
        // GET TABLE FOR ACTUAL SEASON
        $seasonDataMapper = new Application_Model_DbTable_SeasonData();
        $this->view->table = $seasonDataMapper->getHomepageTable();
        
        // GET TOP SCORERS
        $scorersMapper = new Application_Model_DbTable_Scorers();
        $this->view->scorers = $scorersMapper->getTopScorers();
        
        // GET MOST PERFORMANCES
        $performancesMapper = new Application_Model_DbTable_Performances();
        $this->view->performances = $performancesMapper->getMostPerformances();
        
        // GET MOST CARDS
        $cardsMapper = new Application_Model_DbTable_Cards();
        $this->view->cards = $cardsMapper->getMostCards();
    }

    /**
     * Wyciąga z daty newsa dzień miesiącia i polski skrót nazwy miesiąca
     * 
     * @param Zend_Db_Table_Rowset $newsList
     * @return Zend_Db_Table_Rowset
     */
    public function prepareNewsForHomepage($newsList){
        foreach($newsList as $news){
            $timestamp = strtotime($news->date);
            $day = date('j',$timestamp);
            $month = $this->monthNames[date('n',$timestamp) -1];
            $news->date = array('day' => $day, 'month' => $month);
        }
        return $newsList;
    }

}

