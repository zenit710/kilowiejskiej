<?php

class Application_Model_DbTable_News extends Zend_Db_Table_Abstract
{

    protected $_name = 'news';
    
    const HOMEPAGE_NEWS_LIMIT = 5;
    private $columnListFull = array(
        'id',
        'title',
        'description',
        'content',
        'tags',
        'category_id',
        'author_id',
        'date',
        'main_photo'
    );
    private $columnListForHomepage = array(
        'id',
        'title',
        'description',
        'date',
        'main_photo'
    );
    
    /**
     * Zwraca newsy, które będą wyświetlone na stronie głównej
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getNewsForHomepage(){
        $select = $this->select()
                ->from($this->_name, $this->columnListForHomepage)
                ->order('date DESC')
                ->limit(self::HOMEPAGE_NEWS_LIMIT);
        
        return $this->fetchAll($select);
    }
    
}

