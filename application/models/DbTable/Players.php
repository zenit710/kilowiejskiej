<?php

class Application_Model_DbTable_Players extends Zend_Db_Table_Abstract
{

    /**
     * Poprawna nazwa drużyny Kilo Wiejskiej
     * @var string TEAM
     */
    const TEAM = 'Kilo Wiejskiej';

    /**
     * Nazwa tabeli
     * @var string $_name
     */
    protected $_name = 'players';

    /**
     * Pełna lista kolumn
     * @var array $columnListFull
     */
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
     * Lista kokumn potrzebna do wyświetlenia aktualnych zawdoników Kilo
     * @var array $columnListFull
     */
    private $coulmnListForSquad = array(
        'id',
        'name',
        'surname',
        'position',
        'city',
        '(YEAR(NOW()) - YEAR(date_of_birth)) as age',
        'photo'
    );
    
    /**
     * Zwraca listę wszystkich zawodników Kilo Wiejskiej
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getAllKiloPlayers(){
        $select = $this->select()
                ->from($this->_name, $this->coulmnListForSquad)
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

    /**
     * Zwraca wszystkich zawodników
     * @return Zend_Db_Table_Rowset
     */
    public function getAll()
    {
        $select = $this->select()
                ->from($this->_name)
                ->setIntegrityCheck(false)
                ->join('teams','team_id = teams.id','name as teamName')
                ->order(array('teamName ASC','surname ASC','name ASC'));
        
        return $this->fetchAll($select);
    }

    /**
     * Zwraca zawodnika o danym ID
     * @param integer $id
     * @return Zend_Db_Table_Row
     */
    public function getById($id)
    {
        $select = $this->select()
                ->from($this->_name)
                ->where('id = ?',$id);
        
        return $this->fetchRow($select);
    }
    
}

