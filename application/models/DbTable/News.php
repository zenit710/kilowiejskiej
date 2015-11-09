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
        'main_photo',
        'slug'
    );
    private $columnListForHomepage = array(
        'id',
        'title',
        'description',
        'date',
        'main_photo',
        'slug'
    );
    
    /**
     * Zwraca newsy, które będą wyświetlone na stronie głównej
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getNewsForHomepage(){
        $select = $this->select()
                ->from($this->_name, $this->columnListForHomepage)
                ->setIntegrityCheck(false)
                ->join('categories',$this->_name.'.category_id = categories.id','slug as category_slug')
                ->order('date DESC')
                ->limit(self::HOMEPAGE_NEWS_LIMIT);
        
        return $this->fetchAll($select);
    }
    
    /**
     * Zwraca newsa o danej kategorii i slug
     * 
     * @param string $category
     * @param string $slug
     * @return Zend_Db_Table_Row
     */
    public function getByCategoryAndSlug($category, $slug){
        $select = $this->select()
                ->from($this->_name, $this->columnListFull)
                ->setIntegrityCheck(false)
                ->join('categories',$this->_name.'.category_id = categories.id','')
                ->where($this->_name.'.slug = ?', $slug)
                ->where('categories.slug = ?', $category);
        
        return $this->fetchRow($select);
    }
    
    public function getAll()
    {
        $select = $this->select()
                ->from($this->_name)
                ->setIntegrityCheck(false)
                ->join('categories','category_id = categories.id',array('name as categoryName'))
                ->order('date DESC');
        
        return $this->fetchAll($select);
    }
    
    public function getById($id)
    {
        $select = $this->select()
                ->from($this->_name)
                ->where('id = ?',$id);
        
        return $this->fetchRow($select);
    }
    
}

