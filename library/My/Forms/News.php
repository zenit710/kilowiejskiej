<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_News extends Zend_Form {
    
    private $categories = null;
    private $img = null;
    
    public function __construct($categories, $img = null, $options = null) {
        $this->categories = $categories;
        $this->img = $img;
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
            'destination' => realpath(APPLICATION_PATH . '/../public/img/kw/news'),
            'required' => false,
            'validators' => array(
                array('extension',true,'jpg,png,gif')
            ),
            'accept' => 'image/*'
        ));
        $dt = new DateTime('now');
        $this->main_photo->addFilter(
            new Zend_Filter_File_Rename(array(
                'target' => $dt->getTimestamp() . '.' . pathinfo($this->main_photo->getFilename(),PATHINFO_EXTENSION),
                'overwrite' => false
            ))
        );
        $this->main_photo->getValidator('Extension')->setMessage('Nipoprawne rozszerzenie. Możesz dodawać tylko .jpg,.png,.gif');
        $this->addElement(
            'hidden',
            'preview_main_photo',
            array(
                'label' => 'Aktualny obraz:',
                'required' => false,
                'ignore' => true,
                'autoInsertNotEmptyValidator' => false,
                'decorators' => array(
                    array(
                        'HtmlTag', array(
                            'tag'  => 'img',
                            'id'   => 'preview_main_photo',
                            'src'  => $this->img ? '/img/kw/news/' . $this->img : ''
                        )
                    )
                )
            )
        );
        $this->preview_main_photo->clearValidators();
        $this->addElement('submit','submit',array(
            'label' => 'Dodaj artykuł',
            'ignore' => true
        ));
    }
}
