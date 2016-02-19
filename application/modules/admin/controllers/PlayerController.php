<?php

class Admin_PlayerController extends Zend_Controller_Action
{
    
    private $playersMapper;
    private $teamsMapper;

    public function init()
    {
        $this->_helper->layout->setLayout('admin-panel');
        $this->playersMapper = new Application_Model_DbTable_Players();
        $this->teamsMapper = new Application_Model_DbTable_Teams();
    }

    public function indexAction()
    {
        $this->view->players = $this->playersMapper->getAll();
    }
    
    public function addAction()
    {
        $teams = $this->teamsMapper->getAllTeamNameIdPairs();
        $teamsArray = $this->prepareTeamsArray($teams);
        $form = new My_Forms_Player($teamsArray);
        $this->view->form = $form;
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            if(!$values['photo']){
                unset($values['photo']);
            }
            if ($values['photo_delete']) {
                $values['photo'] = NULL;
            }
            unset($values['photo_delete']);
            $this->playersMapper->insert($values);
            $this->redirect('/admin/player');
        }
    }
    
    public function editAction()
    {
        $id = $this->getParam('id');
        $player = $this->playersMapper->getById($id);
        if(!$player){
            throw new My_Exception_NotFound('Nie ma takiego zawodnika!');
        }
        $teams = $this->teamsMapper->getAllTeamNameIdPairs();
        $teamsArray = $this->prepareTeamsArray($teams);
        $form = new My_Forms_Player($teamsArray,$player['photo']);
        $form->populate($player->toArray());
        $this->view->form = $form;
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            if(!$values['photo']){
                unset($values['photo']);
            }
            if ($values['photo_delete']) {
                $values['photo'] = NULL;
            }
            unset($values['photo_delete']);
            $this->playersMapper->update($values,'id = '.$id);
            $this->redirect('/admin/player');
        }
    }

    public function deleteAction()
    {
        $id = $this->getParam('id');
        $this->playersMapper->delete('id = '.$id);
        $this->redirect('/admin/player');
    }

    /**
     * Przygotowuje tablicę dostępnych drużyn
     * @param array $teams
     * @return array
     */
    private function prepareTeamsArray($teams)
    {
        if($teams) {
            $teamsArray = array();
            foreach($teams as $team) {
                $teamsArray[$team->id] = $team->name;
            }
            return $teamsArray;
        }
        return null;
    }
    
}
