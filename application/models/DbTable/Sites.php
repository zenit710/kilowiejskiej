<?php

class Application_Model_DbTable_Sites extends Zend_Db_Table_Abstract
{

    protected $_name = 'sites';

    private $columnListFull = array(
        'id',
        'title',
        'content',
        'keywords',
        'order',
        'url',
        'outside_url'
    );
    private $columnListForDetail = array(
        'title',
        'content',
        'keywords',
        'outside_url'
    );
    
    public function getSiteByUrl($url){
        $select = $this->select()
                ->from($this->_name, $this->columnListForDetail)
                ->where('url = ?', $url);
        
        return $this->fetchRow($select);
    }
    
    public function getById($id)
    {
        $select = $this->select()
                ->from($this->_name)
                ->where('id = ?',$id);
        
        return $this->fetchRow($select);
    }

}

