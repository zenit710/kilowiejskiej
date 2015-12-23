<?php

class Application_Model_DbTable_Teams extends Zend_Db_Table_Abstract
{

    /**
     * Nazwa tabeli
     * @var string $_name
     */
    protected $_name = 'teams';

    /**
     * Pełna lista kolumn
     * @var array $columnListFull
     */
    private $columnListFull = array(
        'id',
        'name',
        'photo'
    );

    /**
     * Zwraca wszystkie drużyny wraz z ich ID
     * @return Zend_Db_Table_Rowset
     */
    public function getAllTeamNameIdPairs()
    {
        $select = $this->select()
                ->from($this->_name,array('id','name'))
                ->order('name ASC');
        
        return $this->fetchAll($select);
    }

    /**
     * Zwraca drużybę o danym ID
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

