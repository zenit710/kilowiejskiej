<?php

class Application_Model_DbTable_Players extends Zend_Db_Table_Abstract
{

    protected $_name = 'players';
    
    const TEAM = 'Kilo Wiejskiej';
    private $columnListFull = array(
        'id',
        'name',
        'surname',
        'position',
        'city',
        'date_of_birth',
        'photo',
        'team_id',
        'professionl_team',
        'is_junior',
        'is_active'
    );
    
    /**
     * Zwraca listę wszystkich zawodników Kilo Wiejskiej
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getAllKiloPlayers(){
        $select = $this->select()
                ->from($this->_name)
                ->setIntegrityCheck(false)
                ->joinLeft('teams',$this->_name.'.team_id = teams.id','')
                ->joinLeft('performances',$this->_name.'.id = performances.player_id','count(distinct performances.id) as performances')
                ->joinLeft('scorers',$this->_name.'.id = scorers.player_id','count(distinct scorers.id) as goals')
                ->joinLeft('cards',$this->_name.'.id = cards.player_id','count(distinct cards.id) as cards')
                ->group($this->_name.'.id')
                ->where('teams.name = ?',self::TEAM)
                ->order('position');
        
        return $this->fetchAll($select);
    }
 
    public function getAll()
    {
        $select = $this->select()
                ->from($this->_name)
                ->setIntegrityCheck(false)
                ->join('teams','team_id = teams.id','name as teamName')
                ->order(array('teamName ASC','surname ASC','name ASC'));
        
        return $this->fetchAll($select);
    }
    
    public function getById($id)
    {
        $select = $this->select()
                ->from($this->_name)
                ->where('id = ?',$id);
        
        return $this->fetchRow($select);
    }
    
}

