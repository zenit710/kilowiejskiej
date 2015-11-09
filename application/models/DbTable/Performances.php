<?php

class Application_Model_DbTable_Performances extends Zend_Db_Table_Abstract
{

    protected $_name = 'performances';

    const MAX_TOP_SCORERS = 5;
    private $columnListFull = array(
        'id',
        'season_id',
        'match_id',
        'team_id',
        'player_id'
    );
    
    /**
     * Zwraca listę zawodników z największą ilością występów w aktualnym sezonie
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getMostPerformances(){
        $select = $this->select()
                ->from($this->_name, 'count(*) as performances')
                ->setIntegrityCheck(false)
                ->join('seasons', $this->_name.'.season_id = seasons.id', '')
                ->join('players', $this->_name.'.player_id = players.id', array('name','surname'))
                ->join('teams', $this->_name.'.team_id = teams.id', '')
                ->where('seasons.is_active = ?', 1)
                ->where('teams.name = ?', 'Kilo Wiejskiej')
                ->group('player_id')
                ->order('performances DESC')
                ->limit(self::MAX_TOP_SCORERS);
                
        return $this->fetchAll($select);
    }
    
    public function getIdsByMatchId($id)
    {
        $select = $this->select()
                ->from($this->_name)
                ->where('match_id = ?', $id);
               
        $performances = $this->fetchAll($select);
        $performancesIds = $this->getIdsFromPerformances($performances);
        
        return $performancesIds;
    }

    private function getIdsFromPerformances($performances){
        $ids = array();
        foreach($performances as $performance){
            $ids[] = $performance->id;
        }
        
        return $ids;
    }
    
}

