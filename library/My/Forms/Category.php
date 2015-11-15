<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_Category extends Zend_Form {
    
    private $img = null;
    
    public function __construct($img,$options = null) {
        $this->img = $img;
        parent::__construct($options);
    }
    
    public function init()
    {
        $this->addElement('text','name',array(
            'label' => 'Nazwa kategorii:',
            'required' => true
        ));
        $this->addElement('file','picture',array(
            'label' => 'Domyślna grafika:',
            'destination' => realpath(APPLICATION_PATH . '/../public/img/kw/cat'),
            'required' => false,
            'validators' => array(
                array('extension',true,'jpg,png,gif')
            ),
            'accept' => 'image/*'
        ));
        $this->addElement(
            'hidden',
            'preview',
            array(
                'label' => 'Aktualny obraz:',
                'required' => false,
                'ignore' => true,
                'autoInsertNotEmptyValidator' => false,
                'decorators' => array(
                    array(
                        'HtmlTag', array(
                            'tag'  => 'img',
                            'id'   => 'preview',
                            $this->img ? 'src' : '' => '/img/kw/cat/' . $this->img
                        )
                    )
                )
            )
        );
        $this->preview->clearValidators();
        $this->picture->getValidator('Extension')->setMessage('Nipoprawne rozszerzenie. Możesz dodawać tylko .jpg,.png,.gif');
        $this->addElement('submit','submit',array(
            'label' => 'Dodaj',
            'ignore' => true
        ));
    }
    
}
