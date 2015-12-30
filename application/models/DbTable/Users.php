<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    /**
     * Nazwa tabeli
     * @var type
     */
    protected $_name = 'users';

    /**
     * Pełna lista kolumn
     * @var array $columnListFull
     */
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

    /**
     * Zwraca informację czy użytkownik o danym ID ma bana
     * @param integer $id
     * @return boolean
     */
    public function isBanned($id)
    {
        $select = $this->select()
                ->from($this->_name,'is_banned')
                ->where('id = ?', $id);
        
        $res = $this->fetchRow($select);
        
        return (bool)$res['is_banned'];
    }

    /**
     * Zwraca poziom uprawnień użytkownika
     * @param integer $id
     * @return Zend_Db_Table_Row
     */
    public function getPermissions($id)
    {
        $select = $this->select()
                ->from($this->_name,'permissions')
                ->where('id = ?', $id);
        
        return $this->fetchRow($select);
    }


    /**
     * Dodaje nowego użytkownika
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $data['password'] = $data['salt'] . sha1($data['salt'] . $data['password']);
        unset($data['passwordAgain']);

        return $this->insert($data);
    }

    /**
     * Zwraca użytkownika o podanym nicku
     * @param type $username
     * @return Zend_Db_Table_Row
     */
    public function getSingleWithUsername($username)
    {
        $select = $this->select();
        $where = $this->getAdapter()->quoteInto('nickname = ?', $username);
        $select->where($where);
        
        return $this->fetchRow($select);
    }

    /**
     * Zwraca dane użytkownika na podstawie adresu e-mail
     * @param string $email
     * @return Zend_Db_Table_Row
     */
    public function getSingleWithEmail($email)
    {
        $select = $this->select();
        $where = $this->getAdapter()->quoteInto('email = ?', $email);
        $select->where($where);
        
        return $this->fetchRow($select);
    }

    /**
     * Zwraca dane użytkownika na podstawie hashu e-mail
     * @param string $hash
     * @return Zebd_Db_Table_Row
     */
    public function getSingleWithEmailHash($hash)
    {
        $select = $this->select();
        $where = $this->getAdapter()->quoteInto('SHA1(email) = ?', $hash);
        $select->where($where);
        
        return $this->fetchRow($select);
    }
    
}

