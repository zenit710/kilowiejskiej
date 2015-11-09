<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_Site extends Zend_Form {
    
    public function init()
    {
        $this->addElement('text','title',array(
            'label' => 'Tytuł strony:',
            'required' => true
        ));
        $this->addElement('textarea','content',array(
            'label' => 'Treść:',
            'required' => false
        ));
        $this->addElement('text','keywords',array(
            'label' => 'Słowa kluczowe:',
            'required' => false
        ));
        $this->addElement('text','order',array(
            'label' => 'Kolejność w menu:',
            'required' => true
        ));
        $this->addElement('text','outside_url',array(
            'label' => 'Link zewnętrzny:',
            'required' => false
        ));
        $this->addElement('submit','submit',array(
            'label' => 'Dodaj stronę',
            'ignore' => true
        ));
    }
}
