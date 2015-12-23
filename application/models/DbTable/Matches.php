<?php

class Application_Model_DbTable_Matches extends Zend_Db_Table_Abstract
{

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
    
}

