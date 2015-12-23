<?php

class Application_Model_DbTable_Performances extends Zend_Db_Table_Abstract
{

    /**
     * Liczba strzelców do pobrania
     * @var integer MAX_TOP_SCORERS
     */
    const MAX_TOP_SCORERS = 5;

    /**
     * Nazwa tabeli
     * @var string $_name
     */
    protected $_name = 'performances';

    /**
     * Pełna lista kolumn
     * @var array $columnListFull
     */
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

    /**
     * Zwraca listę zawodników, którzy wystąpili w meczu o danym ID
     * @param integer $id
     * @return array
     */
    public function getIdsByMatchId($id)
    {
        $select = $this->select()
                ->from($this->_name)
                ->where('match_id = ?', $id);
               
        $performances = $this->fetchAll($select);
        $performancesIds = $this->getIdsFromPerformances($performances);
        
        return $performancesIds;
    }

    /**
     * Zwraca ID zawodników zawodników, któzy zaliczyli występ
     * @param Zend_Db_Table_Rowset $performances
     * @return array
     */
    private function getIdsFromPerformances($performances){
        $ids = array();
        foreach($performances as $performance){
            $ids[] = $performance->id;
        }
        
        return $ids;
    }
    
}

