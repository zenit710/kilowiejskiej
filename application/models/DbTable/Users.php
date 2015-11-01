<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';

    private $columListFull = array(
        'id',
        'name',
        'surname',
        'nickname',
        'password',
        'email',
        'city',
        'date_of_birth',
        'registration_date',
        'last_login',
        'permissions',
        'is_banned'
    );
    
    public function isBanned($id)
    {
        $select = $this->select()
                ->from($this->_name,'is_banned')
                ->where('id = ?', $id);
        
        $res = $this->fetchRow($select);
        
        return (bool)$res['is_banned'];
    }
    
    public function getPermissions($id)
    {
        $select = $this->select()
                ->from($this->_name,'permissions')
                ->where('id = ?', $id);
        
        return $this->fetchRow($select);
    }
    
}

