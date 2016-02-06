<?php
/**
 * Kilo Wiejskiej stats widget
 *
 * @author Kamil
 */

class My_View_Helper_Stats extends Zend_View_Helper_Abstract {

    public function stats()
    {
        $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');

        // GET TOP SCORERS
        $scorersMapper = new Application_Model_DbTable_Scorers();
        $this->view->scorers = $scorersMapper->getTopScorers();

        // GET MOST PERFORMANCES
        $performancesMapper = new Application_Model_DbTable_Performances();
        $this->view->performances = $performancesMapper->getMostPerformances();

        // GET MOST CARDS
        $cardsMapper = new Application_Model_DbTable_Cards();
        $this->view->cards = $cardsMapper->getMostCards();

        return $view->render('stats.phtml');
    }

}
