<?php

class Application_Model_DbTable_Scorers extends Zend_Db_Table_Abstract
{
    /**
     * Ilość zwracanych strzelców
     * @var integer MAX_TOP_SCORERS
     */
    const MAX_TOP_SCORERS = 5;

    /**
     * Nazwa tabeli
     * @var string $_name
     */
    protected $_name = 'scorers';

    /**
     * Pełna lista kolumn
     * @var array $columnListFull
     */
    private $columnListFull = array(
        'id',
        'season_id',
        'match_id',
        'player_id'
    );
    
    /**
     * Zwraca listę najlepszych strzelców aktualnego sezonu
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getTopScorers(){
        $select = $this->select()
                ->from($this->_name, 'count(player_id) as goals')
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

    /**
     * Zwraca listę strzelców meczu o danym ID
     * @param integer $id
     * @return array
     */
    public function getIdsByMatchId($id)
    {
        $select = $this->select()
                ->from($this->_name)
                ->where('match_id = ?', $id);
               
        $scorers = $this->fetchAll($select);
        $scorersIds = $this->getIdsFromScorers($scorers);
        
        return $scorersIds;
    }

    /**
     * Wyciąga ID zawodników z listy zawodników
     * @param Zend_Db_Table_Rowset $scorers
     * @return array
     */
    private function getIdsFromScorers($scorers){
        $ids = array();
        foreach($scorers as $scorer){
            $ids[] = $scorer->id;
        }
        
        return $ids;
    }
    
}

