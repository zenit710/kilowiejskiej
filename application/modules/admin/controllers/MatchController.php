<?php

class Admin_MatchController extends Zend_Controller_Action
{

    private $matchesMapper;
    private $performancesMapper;
    private $playersMapper;
    private $scorersMapper;
    private $seasonsMapper;
    private $seasonsDataMapper;
    private $teamsMapper;
    
    public function init()
    {
        $this->_helper->layout->setLayout('admin-panel');
        $this->matchesMapper = new Application_Model_DbTable_Matches();
        $this->performancesMapper = new Application_Model_DbTable_Performances();
        $this->playersMapper = new Application_Model_DbTable_Players();
        $this->scorersMapper = new Application_Model_DbTable_Scorers();
        $this->seasonsMapper = new Application_Model_DbTable_Seasons();
        $this->seasonsDataMapper = new Application_Model_DbTable_SeasonData();
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
        $seasons = $this->prepareSeasonsArray($this->seasonsMapper->getAllActive());
        $form = new My_Forms_Match($teamsArray, $seasons);
        echo $form->render();
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $this->matchesMapper->insert($values);
            if($values['is_played']){
                $this->prepareTable($values['season_id']);
            }
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
            if($values['is_played']){
                $this->prepareTable($values['season_id']);
            }
            $this->redirect('/admin/match');
        }
    }
    
    public function fillAction()
    {
        $id = $this->getParam('id');
        $players = $this->playersMapper->getAllKiloPlayers();
        $playersArray = $this->preparePlayersArray($players);
        $match = $this->matchesMapper->getById($id);
        $home = $match->home_name == 'Kilo Wiejskiej';
        $goals = $home ? $match->home_goals : $match->away_goals ;
        $scorersIds = $this->scorersMapper->getIdsByMatchId($id);
        $performancesIds = $this->performancesMapper->getIdsByMatchId($id);
        
        $form = new My_Forms_PlayersMatch($playersArray, $goals);
        echo $form->render();
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $this->saveScorers($values, $scorersIds, $match->season_id, $match->id);
            $teamId = $home ? $match->home_id : $match->away_id;
            $this->savePerformances($values, $performancesIds, $match->season_id, $match->id, $teamId);
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
    
    private function preparePlayersArray($players)
    {
        if($players) {
            $playersArray = array(0 => '--- ---');
            foreach($players as $player) {
                $playersArray[$player->id] = $player->surname.' '.$player->name;
            }
            return $playersArray;
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
    
    private function saveScorers($values, $scorersIds, $seasonId, $matchId)
    {
        $i = 0;
        foreach($values as $key=>$value) {
            if(strpos('scorer', $key) === 0 && $value) {
                if(isset($scorersIds[$i])){
                    $this->scorersMapper->update(array('player_id' => $value), 'id='.$scorersIds[$i]);
                    $i++;
                } else {
                    $data = array(
                        'season_id' => $seasonId,
                        'match_id' => $matchId,
                        'player_id' => $value
                    );
                    $this->scorersMapper->insert($data);
                }
            }
        }
    }
    
    private function savePerformances($values, $performancesIds, $seasonId, $matchId, $teamId)
    {
        $i = 0;
        foreach($values as $key=>$value) {
            if(strpos('player', $key) === 0 && $value) {
                if(isset($performancesIds[$i])){
                    $this->performancesMapper->update(array('player_id' => $value), 'id='.$performancesIds[$i]);
                    $i++;
                } else {
                    $data = array(
                        'season_id' => $seasonId,
                        'match_id' => $matchId,
                        'team_id' => $teamId,
                        'player_id' => $value
                    );
                    $this->performancesMapper->insert($data);
                }
            }
        }
    }
 
    private function prepareTable($id)
    {
        
    }
    
}

