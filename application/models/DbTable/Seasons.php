<?php

class Application_Model_DbTable_Seasons extends Zend_Db_Table_Abstract
{

    protected $_name = 'seasons';

    private $columnListFull = array(
        'id',
        'name',
        'period',
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
                ->limit(1);
        
        return $this->fetchRow($select);
    }

}

