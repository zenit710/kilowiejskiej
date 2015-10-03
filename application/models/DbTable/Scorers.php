<?php

class Application_Model_DbTable_Scorers extends Zend_Db_Table_Abstract
{

    protected $_name = 'scorers';
    
    const MAX_TOP_SCORERS = 5;
    private $columnListFull = array(
        'id',
        'season_id',
        'match_id',
        'player_id',
        'goals'
    );
    
    /**
     * Zwraca listę najlepszych strzelców aktualnego sezonu
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getTopScorers(){
        $select = $this->select()
                ->from($this->_name, 'sum(goals) as goals')
                ->setIntegrityCheck(false)
                ->join('seasons', 'scorers.season_id = seasons.id', '')
                ->join('players', 'scorers.player_id = players.id', array('name','surname'))
                ->join('teams', 'players.team_id = teams.id', '')
                ->where('seasons.is_active = ?', 1)
                ->where('teams.name = ?', 'Kilo Wiejskiej')
                ->group('player_id')
                ->order('goals DESC')
                ->limit(self::MAX_TOP_SCORERS);
                
        return $this->fetchAll($select);
    }

}

