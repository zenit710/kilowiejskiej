<?php

class Application_Model_DbTable_Sites extends Zend_Db_Table_Abstract
{

    /**
     * Nazwa tabeli
     * @var string $_name
     */
    protected $_name = 'sites';

    /**
     * Pełna lista kolumn
     * @var array $columnListFull
     */
    private $columnListFull = array(
        'id',
        'title',
        'content',
        'keywords',
        'order',
        'url',
        'outside_url'
    );

    /**
     * Lista kolumn dla detalu strony
     * @var array $columnListFull
     */
    private $columnListForDetail = array(
        'title',
        'content',
        'keywords',
        'outside_url'
    );

    /**
     * Zwraca stronę po jej adresie
     * @param string $url
     * @return Zend_Db_Table_Row
     */
    public function getSiteByUrl($url){
        $select = $this->select()
                ->from($this->_name, $this->columnListForDetail)
                ->where('url = ?', $url);
        
        return $this->fetchRow($select);
    }

    /**
     * Zwraca stronę po ID
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
     * Zwraca strony, które mają zostać wyświetlone w menu
     * @return Zend_Db_Table_Rowset
     */
    public function getMenuElements()
    {
        $select = $this->select()
                ->from($this->_name)
                ->where($this->_name.'.order > ?', 0)
                ->order('order ASC');
        
        return $this->fetchAll($select);
    }

}

