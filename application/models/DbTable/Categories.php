<?php

class Application_Model_DbTable_Categories extends Zend_Db_Table_Abstract
{
    
    /**
     * Nazwa tabeli
     * @var string $_name
     */
    protected $_name = 'categories';

    /**
     * Pełna lista kolumn tabeli
     * @var array $columnListFull
     */
    private $columnListFull = array(
        'id',
        'name',
        'picture',
        'slug'
    );

    /**
     * Pobiera kategorię na podstawie ID
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

    /**
     * Pobiera pełną listę kategorii wraz z ID
     * @return Zend_Db_Table_Rowset
     */
    public function getAllNamesWithId()
    {
        $select = $this->select()
                ->from($this->_name,array('name','id'))
                ->order('name ASC');

        return $this->fetchAll($select);
    }
}

