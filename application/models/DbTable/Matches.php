<?php

class Application_Model_DbTable_Matches extends Zend_Db_Table_Abstract
{

    protected $_name = 'matches';

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

    public function getById($id)
    {
        $select = $this->select()
                ->from($this->_name)
                ->where('id = ?', $id);
        
        return $this->fetchRow($select);
    }
    
    public function getAllBySeasonId($id)
    {
        $select = $this->select()
                ->from($this->_name)
                ->where('season_id = ?', $id);
        
        return $this->fetchAll($select);
    }
    
}

