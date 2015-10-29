<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_News extends Zend_Form {
    
    private $categories = null;
    
    public function __construct($categories, $options = null) {
        $this->categories = $categories;
        parent::__construct($options);
    }
    
    public function init()
    {
        $this->addElement('text','title',array(
            'label' => 'Tytuł:',
            'required' => true
        ));
        $this->addElement('textarea','description',array(
            'label' => 'Treść wstępna:',
            'required' => true
        ));
        $this->addElement('textarea','content',array(
            'label' => 'Treść:',
            'required' => true
        ));
        $this->addElement('text','tags',array(
            'label' => 'Słowa kluczowe:',
            'required' => true
        ));
        $this->addElement('select','category_id',array(
            'label' => 'Kategoria:',
            'required' => true,
            'multiOptions' => $this->categories
        ));
        $this->addElement('file','main_photo',array(
            'label' => 'Zdjęcie główne:',
            'required' => false
        ));
        $this->addElement('submit','submit',array(
            'label' => 'Dodaj artykuł',
            'ignore' => true
        ));
    }
}
