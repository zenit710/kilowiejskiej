<?php

class Application_Model_DbTable_Cards extends Zend_Db_Table_Abstract
{

    protected $_name = 'cards';

    const MAX_TOP_SCORERS = 5;
    private $columnListFull = array(
        'id',
        'season_id',
        'match_id',
        'team_id',
        'player_id'
    );
    
    /**
     * Zwraca listę zawodników z największą ilością kartek w aktualnym sezonie
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getMostCards(){
        $select = $this->select()
                ->from($this->_name, 'count(*) as cards')
                ->setIntegrityCheck(false)
                ->join('seasons', $this->_name.'.season_id = seasons.id', '')
                ->join('players', $this->_name.'.player_id = players.id', array('name','surname'))
                ->join('teams', 'players.team_id = teams.id', '')
                ->where('seasons.is_active = ?', 1)
                ->where('teams.name = ?', 'Kilo Wiejskiej')
                ->group('player_id')
                ->order('cards DESC')
                ->limit(self::MAX_TOP_SCORERS);
                
        return $this->fetchAll($select);
    }
    
}

