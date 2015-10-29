<?php

class Admin_MatchController extends Zend_Controller_Action
{

    private $matchesMapper;
    private $teamsMapper;
    
    public function init()
    {
        $this->_helper->layout->setLayout('admin-panel');
        $this->matchesMapper = new Application_Model_DbTable_Matches();
        $this->teamsMapper = new Application_Model_DbTable_Teams();
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
        $form = new My_Forms_Match($teamsArray);
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
        $form = new My_Forms_Match($teamsArray);
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

