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
     * Zwraca newsy, które będą wyświetlone na stronie głównej wraz z paginacją
     * 
     * @return Zend_Paginator
     */
    public function getNewsForHomepage($page){
        $select = $this->select()
                ->from($this->_name, $this->columnListForHomepage)
                ->setIntegrityCheck(false)
                ->join('categories',$this->_name.'.category_id = categories.id','slug as category_slug')
                ->order('date DESC');
        
        $rowCount = $this->select()
                ->from($this->_name, array(
                    Zend_Paginator_Adapter_DbSelect::ROW_COUNT_COLUMN => "count($this->_name.id)"
                ))
                ->setIntegrityCheck(false)
                ->join('categories',$this->_name.'.category_id = categories.id','slug as category_slug');
        
        $adapter = new Zend_Paginator_Adapter_DbSelect($select);
        $adapter->setRowCount($rowCount);
        
        $paginator = new Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(self::HOMEPAGE_NEWS_LIMIT);
        
        return $paginator;
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
                ->join('categories','category_id = categories.id',array('name as categoryName','slug as category_slug'))
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

