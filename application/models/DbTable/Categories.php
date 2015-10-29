<?php

class Application_Model_DbTable_Categories extends Zend_Db_Table_Abstract
{

    protected $_name = 'categories';

    private $columnListFull = array(
        'id',
        'name',
        'picture',
        'slug'
    );
    
    public function getById($id)
    {
        $select = $this->select()
                ->from($this->_name)
                ->where('id = ?',$id);
        
        return $this->fetchRow($select);
    }
    
    public function getAllNamesWithId()
    {
        $select = $this->select()
                ->from($this->_name,array('name','id'))
                ->order('name ASC');
        
        return $this->fetchAll($select);
    }
}

