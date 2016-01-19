<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_Player extends Zend_Form {
    
    private $teams = array();
    private $positions = array(
        '' => null,
        'Bramkarz' => 'Bramkarz',
        'Obrońca' => 'Obrońca',
        'Pomocnik' => 'Pomocnik',
        'Napastnik' => 'Napastnik'
    );
    private $img = null;
    
    public function __construct($teams, $img = null, $options = null) {
        $this->teams = $teams;
        $this->img = $img;
        parent::__construct($options);
    }
    
    public function init()
    {
        $this->addElement('text','name',array(
            'label' => 'Imię:',
            'validators' => array(
                array('alpha', false, array(
                    'messages' => 'Imię może składać się tylko z liter'
                ))
            ),
            'required' => true
        ));
        $this->addElement('text','surname',array(
            'label' => 'Nazwisko:',
            'validators' => array(
                array('alpha', false, array(
                    'messages' => 'Nazwisko może składać się tylko z liter'
                ))
            ),
            'required' => true
        ));
        $this->addElement('select','position',array(
            'label' => 'Pozycja:',
            'required' => false,
            'multiOptions' => $this->positions
        ));
        $this->addElement('text','city',array(
            'label' => 'Miejscowość:',
            'required' => false
        ));
        $this->addElement(new Glitch_Form_Element_Text_Date('date_of_birth',array(
            'type' => 'date',
            'label' => 'Data urodzenia:',
            'placeholder' => 'yyyy-mm-dd',
            'validators' => array(
                array('regex',false,array(
                    'pattern' => '/\d{4}-\d{2}-\d{2}/',
                    'messages' => 'Nieprawidłowy format daty! YYYY-MM-DD'
                ))
            ),
            'required' => true
        )));
        $this->addElement(new Glitch_Form_Element_Text_Number('height', array(
            'type' => 'number',
            'label' => 'Wzrost w cm:',
            'required' => false
        )));
        $this->addElement(new Glitch_Form_Element_Text_Number('weight', array(
            'type' => 'number',
            'label' => 'Waga w kg:',
            'required' => false
        )));
        $this->addElement('file','photo',array(
            'label' => 'Zdjęcie',
            'destination' => realpath(APPLICATION_PATH . '/../public/img/kw/player'),
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
                            $this->img ? 'src' : '' => '/img/kw/player/' . $this->img
                        )
                    )
                )
            )
        );
        $this->preview->clearValidators();
        $this->addElement('select','team_id',array(
            'label' => 'Drużyna:',
            'required' => true,
            'multiOptions' => $this->teams
        ));
        $this->addElement('text','professional_team',array(
            'label' => 'Zespół zawodowy:',
            'required' => false
        ));
        $this->addElement('checkbox','is_junior',array(
            'label' => 'Junior:',
            'required' => false
        ));
        $this->addElement('checkbox','is_active',array(
            'label' => 'Aktywny:',
            'required' => true,
            'checked' => 'checked'
        ));
        $this->addElement('submit','submit',array(
            'label' => 'Dodaj zawodnika',
            'ignore' => true
        ));
    }
}
