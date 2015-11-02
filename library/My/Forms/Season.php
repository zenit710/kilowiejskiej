<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_Season extends Zend_Form {
    
    public function init()
    {
        $this->addElement('text','name',array(
            'label' => 'Nazwa sezonu:',
            'required' => true
        ));
        $this->addElement('text','period',array(
            'label' => 'Okres:',
            'required' => true
        ));
        $this->addElement('checkbox','is_active',array(
            'label' => 'Aktywny:',
            'required' => true
        ));
        $this->addElement('submit','submit',array(
            'label' => 'Dodaj',
            'ignore' => true
        ));
    }
}
