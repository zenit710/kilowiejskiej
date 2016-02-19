<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_Team extends Zend_Form {
    
    private $photoImg = null;
    private $logoImg = null;
    
    public function __construct($photo = null, $logo = null, $options = null) {
        $this->photoImg = $photo;
        $this->logoImg = $logo;
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
            'destination' => realpath(APPLICATION_PATH . '/../public/img/kw/team_photo'),
            'required' => false,
            'validators' => array(
                array('extension',true,'jpg,png,gif')
            ),
            'accept' => 'image/*'
        ));
        $dt = new DateTime('now');
        $this->photo->addFilter(
            new Zend_Filter_File_Rename(array(
                'target' => $dt->getTimestamp() . '.' . pathinfo($this->photo->getFilename(),PATHINFO_EXTENSION),
                'overwrite' => false
            ))
        );
        $this->photo->getValidator('Extension')->setMessage('Nipoprawne rozszerzenie. Możesz dodawać tylko .jpg,.png,.gif');
        $this->addElement(
            'hidden',
            'preview_photo',
            array(
                'label' => 'Aktualny obraz:',
                'required' => false,
                'ignore' => true,
                'autoInsertNotEmptyValidator' => false,
                'decorators' => array(
                    array(
                        'HtmlTag', array(
                            'tag'  => 'img',
                            'id'   => 'preview_photo',
                            'style'=> 'max-width: 50%',
                            'src'  => $this->photoImg ? '/img/kw/team_photo/' . $this->photoImg : ''
                        )
                    )
                )
            )
        );
        $this->preview_photo->clearValidators();
        $this->addElement('checkbox','photo_delete',array(
            'label' => 'Usuń zdjęcie drużyny:'
        ));
        $this->addElement('file','logo',array(
            'label' => 'Logo drużyny:',
            'destination' => realpath(APPLICATION_PATH . '/../public/img/kw/team'),
            'required' => false,
            'validators' => array(
                array('extension',true,'jpg,png,gif')
            ),
            'accept' => 'image/*'
        ));
        $this->logo->addFilter(
            new Zend_Filter_File_Rename(array(
                'target' => $dt->getTimestamp() . '.' . pathinfo($this->logo->getFilename(),PATHINFO_EXTENSION),
                'overwrite' => false
            ))
        );
        $this->logo->getValidator('Extension')->setMessage('Nipoprawne rozszerzenie. Możesz dodawać tylko .jpg,.png,.gif');
        $this->addElement(
            'hidden',
            'preview_logo',
            array(
                'label' => 'Aktualny obraz:',
                'required' => false,
                'ignore' => true,
                'autoInsertNotEmptyValidator' => false,
                'decorators' => array(
                    array(
                        'HtmlTag', array(
                            'tag'  => 'img',
                            'id'   => 'preview_logo',
                            'style'=> 'max-width: 50%',
                            'src'  => $this->logoImg ? '/img/kw/team/' . $this->logoImg : ''
                        )
                    )
                )
            )
        );
        $this->preview_logo->clearValidators();
        $this->addElement('checkbox','logo_delete',array(
            'label' => 'Usuń logo drużyny:'
        ));
        $this->addElement('submit','submit',array(
            'label' => 'Zapisz',
            'ignore' => true
        ));
    }
}
