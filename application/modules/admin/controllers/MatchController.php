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
        $this->view->form = $form;
        
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
        $this->view->form = $form;
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $this->matchesMapper->update($values, 'id='.$id);
            $this->prepareTable($values['season_id']);
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
        $this->view->form = $form;
        
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
 
    private function prepareTable($season_id)
    {
        $matches = $this->matchesMapper->getAllBySeasonId($season_id);
        $teamsAlreadySaved = $this->seasonsDataMapper->getSavedTeamInSeason($season_id);
        
        $teamData = array();
                
        $valuesArray = array(
            'season_id' => $season_id,
            'team_id' => 0,
            'wins' => 0,
            'draws' => 0,
            'loses' => 0,
            'goals_scored' => 0,
            'goals_lost' => 0,
            'points' => 0,
            'rank' => 0
        );
        
        foreach($matches as $match)
        {
            $homeId = $match['home_id'];
            $homeGoals = $match['home_goals'];
            $awayId = $match['away_id'];
            $awayGoals = $match['away_goals'];

            if(!isset($teamData[$homeId])){
                $teamData[$homeId] = $valuesArray;
                $teamData[$homeId]['team_id'] = $homeId;
            }
            
            if(!isset($teamData[$awayId])){
                $teamData[$awayId] = $valuesArray;
                $teamData[$awayId]['team_id'] = $awayId;
            }
            
            if($match->is_played){
                if($homeGoals>$awayGoals){
                    $teamData[$homeId]['wins']++;
                    $teamData[$homeId]['points']+= 3;
                    $teamData[$awayId]['loses']++;
                } else if($homeGoals<$awayGoals){
                    $teamData[$homeId]['loses']++;
                    $teamData[$awayId]['wins']++;
                    $teamData[$awayId]['points']+= 3;
                } else {
                    $teamData[$homeId]['draws']++;
                    $teamData[$homeId]['points']++;
                    $teamData[$awayId]['draws']++;
                    $teamData[$awayId]['points']++;
                }

                $teamData[$homeId]['goals_scored'] += $homeGoals;
                $teamData[$homeId]['goals_lost'] += $awayGoals;
                $teamData[$awayId]['goals_scored'] += $awayGoals;
                $teamData[$awayId]['goals_lost'] += $homeGoals;
            }
        }
        
        $teamData = $this->makeRank($teamData, $matches);
        
        foreach($teamData as $key=>$team){
            if(in_array($team['team_id'], $teamsAlreadySaved)) {
                $this->seasonsDataMapper->update($team, 'team_id = '.$team['team_id']);
                unset($teamData[$key]);
            }
        }
        
        if(count($teamData)){
            $this->seasonsDataMapper->insert($teamData);
        }
    }
    
    private function makeRank($teamArray, $matches)
    {
        $teamArray = $this->sortByPoints($teamArray);
        $previous = null;
        $change = true;
        while($change){
            $change = false;
            foreach($teamArray as $key=>$value) {
                if($previous == null){
                    $previous = $key;
                } else {
                    $goalsBalancePrev = $teamArray[$previous]['goals_scored'] - $teamArray[$previous]['goals_lost'];
                    $goalsBalance = $value['goals_scored'] - $value['goals_lost'];
                    if(
                        $teamArray[$previous]['points'] == $value['points'] &&
                        $goalsBalance> $goalsBalancePrev
                    ){
                        $teamArray[$previous]['rank']++;
                        $value['rank']--;
                        $change= true;
                    }
                }
            }
        }
        $change = true;
        while($change){
            $change = false;
            foreach($teamArray as $key=>$value) {
                if($previous == null){
                    $previous = $key;
                } else {
                    if($teamArray[$previous]['points'] == $value['points']){
                        if($this->iAmBetter($previous, $key, $matches)){
                            $teamArray[$previous]['rank']++;
                            $value['rank']--;
                            $change= true;
                        }
                    }
                }
            }
        }
        
        return $teamArray;
    }
    
    private function sortByPoints($teamArray)
    {
        $ranks = array();
        $points = array();
        foreach($teamArray as $key=>$value) {
            $index = $this->findPlaceToInsert($value['points'],$points);
            if(!count($ranks) || $index== count($ranks)){
                $ranks[] = $key;
            } else {
                $ranks = array_splice($ranks, $index, 0, $key);
            }
            if(!count($points) || $index== count($points)){
                $points[] = $key;
            } else {
                $points = array_splice($ranks, $index, 0, $key);
            }
        }
        foreach($teamArray as $key=>$value) {
            $teamArray[$key]['rank'] = array_search($key, $ranks)+1;
        }
        
        return $teamArray;
    }
    
    private function findPlaceToInsert($points,$array)
    {
        if(!count($array)){
            return 0;
        }
        for($i = 0; $i < count($array); $i++){
            if($points>$array[$i]) {
                echo $i;
                return $i;
            }
        }
        return count($array);
    }
    
    private function iAmBetter($prev, $me, $matches){
        foreach($matches as $match){
            if($match['home_id'] == $prev && $match['away_id'] == $me){
                if($match['away_goals'] > $match['home_goals']){
                    return true;
                }
            }
            if($match['away_id'] == $prev && $match['home_id'] == $me){
                if($match['home_goals'] > $match['away_goals']){
                    return true;
                }
            }
        }
        return false;
    }
    
}

