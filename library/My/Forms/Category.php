<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_Category extends Zend_Form {
    
    public function init()
    {
        $this->addElement('text','name',array(
            'label' => 'Nazwa kategorii:',
            'required' => true
        ));
        $this->addElement('file','picture',array(
            'label' => 'Domyślna grafika:',
            'destination' => realpath(APPLICATION_PATH . '/../public/img/kw/cat'),
            'required' => false
        ));
        $this->addElement('submit','submit',array(
            'label' => 'Dodaj',
            'ignore' => true
        ));
    }
}
