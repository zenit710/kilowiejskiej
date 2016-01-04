<?php

class Application_Model_DbTable_Matches extends Zend_Db_Table_Abstract
{

    const KILO_WIEJSKIEJ = 'Kilo Wiejskiej';

    /**
     * Nazwa tabeli
     * @var string $_name
     */
    protected $_name = 'matches';

    /**
     * Pełna lista kolumn
     * @var array $columnListFull
     */
    private $columnListFull = array(
        'id',
        'season_id',
        'stage',
        'date',
        'home_id',
        'away_id',
        'home_name',
        'away_name',
        'home_goals',
        'away_goals',
        'is_played'
    );

    /**
     * Zwraca mecz na podstawie ID
     * @param integer $id
     * @return Zend_Db_Table_Row
     */
    public function getById($id)
    {
        $select = $this->select()
                ->from($this->_name)
                ->where('id = ?', $id);
        
        return $this->fetchRow($select);
    }

    /**
     * Zwraca wszystkie mecze z konkretnego sezonu
     * @param integer $id
     * @return Zend_Db_Table_Rowset
     */
    public function getAllBySeasonId($id)
    {
        $select = $this->select()
                ->from($this->_name)
                ->where('season_id = ?', $id);
        
        return $this->fetchAll($select);
    }

    /**
     * Zwraca poprzedni mecz
     * @return Zend_Db_Table_Row
     */
    public function getPreviousMatch()
    {
        $team = self::KILO_WIEJSKIEJ;

        $select = $this->select()
            ->from($this->_name)
            ->setIntegrityCheck(false)
            ->join(array('h' => 'teams'), 'h.id ='.$this->_name.'.home_id', 'h.name as home_name')
            ->join(array('a' => 'teams'), 'a.id ='.$this->_name.'.away_id', 'a.name as away_name')
            ->where('is_played = ?', 1)
            ->where("h.name='$team' OR a.name='$team'")
            ->order($this->_name.'.date DESC')
            ->limit(1);

        return $this->fetchRow($select);
    }

    /**
     * Zwraca następny mecz
     * @return Zend_Db_Table_Row
     */
    public function getNextMatch()
    {
        $team = self::KILO_WIEJSKIEJ;

        $select = $this->select()
            ->from($this->_name)
            ->setIntegrityCheck(false)
            ->join(array('h' => 'teams'), 'h.id ='.$this->_name.'.home_id', 'h.name as home_name')
            ->join(array('a' => 'teams'), 'a.id ='.$this->_name.'.away_id', 'a.name as away_name')
            ->where('is_played = ?', 0)
            ->where("h.name='$team' OR a.name='$team'")
            ->order($this->_name.'.date ASC')
            ->limit(1);

        return $this->fetchRow($select);
    }
    
}

