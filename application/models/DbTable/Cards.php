<?php

class Application_Model_DbTable_Cards extends Zend_Db_Table_Abstract
{

    /**
     * Liczba zawodników do pobrania
     * @var integer MAX_TOP_SCORERS
     */
    const MAX_TOP_SCORERS = 5;
    const KILO_ID = 15;

    /**
     * Nazwa tabeli
     * @var string $_name
     */
    protected $_name = 'cards';

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

    /**
     * Zwraca info o kartkowiczach z danego meczu
     * @param integer $id
     * @return Zend_Db_Table_Rowset
     */
    public function getCardsByMatchId($id)
    {
        $select = $this->select()
            ->from($this->_name,array('card'))
            ->setIntegrityCheck(false)
            ->join('players','players.id = '.$this->_name.'.player_id', array('name','surname'))
            ->where($this->_name.'.match_id = ?', $id)
            ->where($this->_name.'.team_id = ?', self::KILO_ID);

        return $this->fetchAll($select);
    }
    
}

