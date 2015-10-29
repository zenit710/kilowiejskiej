<?php

class Application_Model_DbTable_Teams extends Zend_Db_Table_Abstract
{

    protected $_name = 'teams';

    private $columnListFull = array(
        'id',
        'name',
        'photo'
    );
    
    public function getAllTeamNameIdPairs()
    {
        $select = $this->select()
                ->from($this->_name,array('id','name'))
                ->order('name ASC');
        
        return $this->fetchAll($select);
    }
    
}

