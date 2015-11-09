<?php

class Admin_MatchController extends Zend_Controller_Action
{

    private $matchesMapper;
    private $teamsMapper;
    private $seasonsMapper;
    private $seasonsDataMapper;
    
    public function init()
    {
        $this->_helper->layout->setLayout('admin-panel');
        $this->matchesMapper = new Application_Model_DbTable_Matches();
        $this->teamsMapper = new Application_Model_DbTable_Teams();
        $this->seasonsMapper = new Application_Model_DbTable_Seasons();
        $this->seasonsDataMapper = new Application_Model_DbTable_SeasonData();
    }

    public function indexAction()
    {
        $this->view->matches = $this->matchesMapper->fetchAll();
    }

    public function addAction()
    {
        $teams = $this->teamsMapper->getAllTeamNameIdPairs();
        $teamsArray = $this->prepareTeamsArray($teams);
        $this->view->teamIds = $this->getTeamIds($teams);
        $seasons = $this->prepareSeasonsArray($this->seasonsMapper->getAllActive());
        $form = new My_Forms_Match($teamsArray, $seasons);
        echo $form->render();
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $this->matchesMapper->insert($values);
            $this->redirect('/admin/match');
        }
    }

    public function editAction()
    {
        $id = $this->getParam('id');
        $match = $this->matchesMapper->getById($id);
        if(!$match){
            return;
        }
        $teams = $this->teamsMapper->getAllTeamNameIdPairs();
        $teamsArray = $this->prepareTeamsArray($teams);
        $this->view->teamIds = $this->getTeamIds($teams);
        $seasons = $this->prepareSeasonsArray($this->seasonsMapper->getAllActive());
        $form = new My_Forms_Match($teamsArray, $seasons);
        $form->populate($match->toArray());
        echo $form->render();
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $this->matchesMapper->update($values, 'id='.$id);
            $this->redirect('/admin/match');
        }
    }
    
    public function deleteAction()
    {
        $id = $this->getParam('id');
        $this->matchesMapper->delete('id='.$id);
        $this->redirect('/admin/match');
    }
    
    private function prepareSeasonsArray($seasons)
    {
        if($seasons) {
            $seasonsArray = array();
            foreach($seasons as $season) {
                $seasonsArray[$season->id] = $season->name.' '.$season->period;
            }
            return $seasonsArray;
        }
        return null;
    }
    
    private function prepareTeamsArray($teams)
    {
        if($teams) {
            $teamsArray = array();
            foreach($teams as $team) {
                $teamsArray[$team->name] = $team->name;
            }
            return $teamsArray;
        }
        return null;
    }
    
    private function getTeamIds($teams)
    {
        if($teams) {
            $teamsArray = array();
            foreach($teams as $team) {
                $teamsArray[$team->name] = $team->id;
            }
            return $teamsArray;
        }
        return null;
    }
    
}

