<?php

class MatchController extends Zend_Controller_Action
{

    private $matchesMapper;
    private $cardsMapper;
    private $performancesMapper;
    private $scorersMapper;

    public function init()
    {
        $this->matchesMapper = new Application_Model_DbTable_Matches();
        $this->cardsMapper = new Application_Model_DbTable_Cards();
        $this->performancesMapper = new Application_Model_DbTable_Performances();
        $this->scorersMapper = new Application_Model_DbTable_Scorers();
    }

    public function itemAction()
    {
        $id = $this->_getParam('id');

        $this->view->match = $this->matchesMapper->getMatchInfoById($id);
        if (!$this->view->match) {
            throw new My_Exception_NotFound('Mecz, którego szukasz, nie istnieje!');
        }
        $this->view->performances = $this->performancesMapper->getPerformancesByMatchId($id);
        $this->view->scorers = $this->scorersMapper->getScorersByMatchId($id);
        $this->view->cards = $this->cardsMapper->getCardsByMatchId($id);
    }

}