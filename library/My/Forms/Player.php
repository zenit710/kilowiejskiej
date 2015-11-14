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
    
    public function __construct($teams, $options = null) {
        $this->teams = $teams;
        parent::__construct($options);
    }
    
    public function init()
    {
        $this->addElement('text','name',array(
            'label' => 'Imię:',
            'required' => true
        ));
        $this->addElement('text','surname',array(
            'label' => 'Nazwisko:',
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
        $this->addElement('text','date_of_birth',array(
            'label' => 'Data utodzenia:',
            'placeholder' => 'yyyy-mm-dd',
            'required' => true
        ));
//        $this->addElement('file','photo',array(
//            'label' => 'Zdjęcie',
//            'required' => false
//        ));
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
