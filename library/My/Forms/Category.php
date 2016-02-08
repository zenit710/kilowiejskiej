<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_Category extends Zend_Form {
    
    private $img = null;
    
    public function __construct($img = null,$options = null) {
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
        $dt = new DateTime('now');
        $this->picture->addFilter(
            new Zend_Filter_File_Rename(array(
                'target' => $dt->getTimestamp() . '.' . pathinfo($this->picture->getFilename(),PATHINFO_EXTENSION),
                'overwrite' => false
            ))
        );
        $this->picture->getValidator('Extension')->setMessage('Nipoprawne rozszerzenie. Możesz dodawać tylko .jpg,.png,.gif');
        $this->addElement(
            'hidden',
            'preview_picture',
            array(
                'label' => 'Aktualny obraz:',
                'required' => false,
                'ignore' => true,
                'autoInsertNotEmptyValidator' => false,
                'decorators' => array(
                    array(
                        'HtmlTag', array(
                            'tag'  => 'img',
                            'id'   => 'preview_picture',
                            'src'  => $this->img ? '/img/kw/team_photo/' . $this->img : ''
                        )
                    )
                )
            )
        );
        $this->preview_picture->clearValidators();
        $this->addElement('submit','submit',array(
            'label' => 'Dodaj',
            'ignore' => true
        ));
    }
    
}
