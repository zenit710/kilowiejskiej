<?php

class Application_Model_DbTable_Seasons extends Zend_Db_Table_Abstract
{

    /**
     * Nazwa tabeli
     * @var string $_name
     */
    protected $_name = 'seasons';

    /**
     * Pełna lista kolumn
     * @var array $columnListFull
     */
    private $columnListFull = array(
        'id',
        'name',
        'period',
        'is_cup',
        'is_active'
    );
    
    /**
     * Zwraca info o aktualnym sezonie
     * 
     * @return int
     */
    public function getActualSeasonInfo(){
        $select = $this->select()
                ->from($this->_name, $this->columnListFull)
                ->where('is_active = ?', 1)
                ->where('is_cup = ?', 0)
                ->limit(1);
        
        return $this->fetchRow($select);
    }

    /**
     * Zwraca dane sezonu o danym ID
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
     * Zwraca listę aktywnych sezonów
     * @return Zend_Db_Table_Rowset
     */
    public function getAllActive()
    {
        $select = $this->select()
                ->from($this->_name)
                ->where('is_active = ?', 1);
        
        return $this->fetchAll($select);
    }

    /**
     * Zwraca info, czy sezon jest pucharem, czy nie
     * @param integer $seasonId
     * @return bool
     */
    public function isCup($seasonId)
    {
        $select = $this->select()
            ->from($this->_name, array('is_cup'))
            ->where('id = ?', $seasonId)
            ->limit(1);

        return (bool)$this->fetchRow($select)->is_cup;
    }
}

