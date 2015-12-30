<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_Team extends Zend_Form {
    
    private $img = null;
    
    public function __construct($img = null, $options = null) {
        $this->img = $img;
        parent::__construct($options);
    }
    
    public function init()
    {
        $this->addElement('text','name',array(
            'label' => 'Nazwa drużyny:',
            'required' => true
        ));
        $this->addElement('file','photo',array(
            'label' => 'Zdjęcie drużyny:',
            'destination' => realpath(APPLICATION_PATH . '/../public/img/kw/team'),
            'required' => false,
            'validators' => array(
                array('extension',true,'jpg,png,gif')
            ),
            'accept' => 'image/*'
        ));
        $this->photo->getValidator('Extension')->setMessage('Nipoprawne rozszerzenie. Możesz dodawać tylko .jpg,.png,.gif');
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
                            $this->img ? 'src' : '' => '/img/kw/team/' . $this->img
                        )
                    )
                )
            )
        );
        $this->preview->clearValidators();
        $this->addElement('submit','submit',array(
            'label' => 'Dodaj',
            'ignore' => true
        ));
    }
}
