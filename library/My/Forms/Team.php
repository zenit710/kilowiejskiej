<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_Team extends Zend_Form {
    
    public function init()
    {
        $this->addElement('text','name',array(
            'label' => 'Nazwa drużyny:',
            'required' => true
        ));
        $this->addElement('file','photo',array(
            'label' => 'Zdjęcie drużyny:',
            'required' => false
        ));
        $this->addElement('submit','submit',array(
            'label' => 'Dodaj',
            'ignore' => true
        ));
    }
}
