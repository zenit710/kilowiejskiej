<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_User extends Zend_Form {
    
    private $permissions = array(
        'ADMIN' => 'ADMIN',
        'MODERATOR' => 'MODERATOR',
        'USER' => 'USER'
    );
    
    public function init()
    {
        $this->addElement('select','permissions',array(
            'label' => 'Uprawnienia:',
            'required' => true,
            'multiOptions' => $this->permissions
        ));
        $this->addElement('submit','submit',array(
            'label' => 'Zmień',
            'ignore' => true
        ));
    }
}
