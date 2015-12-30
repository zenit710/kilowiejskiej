<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_Match extends Zend_Form {
    
    private $teams = null;
    private $seasons = null;
    
    public function __construct($teams, $seasons, $options = null) {
        $this->teams = $teams;
        $this->seasons = $seasons;
        parent::__construct($options);
    }
    
    public function init()
    {
        $this->addElement('select','season_id',array(
            'label' => 'Sezon:',
            'required' => true,
            'multiOptions' => $this->seasons
        ));
        $this->addElement(new Glitch_Form_Element_Text_Number('stage',array(
            'type' => 'number',
            'label' => 'Kolejka:',
            'required' => true,
            'min' => 0
        )));
        $this->addElement(new Glitch_Form_Element_Text_DateTime('date',array(
            'type' => 'datetime',
            'label' => 'Data i godzina:',
            'placeholder' => 'yyyy-mm-dd hh:mm:ss',
            'validators' => array(
                array('regex',false,array(
                    'pattern' => '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',
                    'messages' => 'Nieprawidłowy format daty! YYYY-MM-DD HH:MM:SS'
                ))
            ),
            'required' => true
        )));
        $this->addElement('select','home_name',array(
            'label' => 'Gospodarz:',
            'required' => true,
            'multiOptions' => $this->teams
        ));
        $this->addElement('select','away_name',array(
            'label' => 'Gość:',
            'required' => true,
            'multiOptions' => $this->teams
        ));
        $this->addElement(new Glitch_Form_Element_Text_Number('home_goals',array(
            'type' => 'number',
            'label' => 'Bramki gospodarza:',
            'required' => false,
            'min' => 0
        )));
        $this->addElement(new Glitch_Form_Element_Text_Number('away_goals',array(
            'type' => 'number',
            'label' => 'Bramki gościa:',
            'required' => false,
            'min' => 0
        )));
        $this->addElement('checkbox','is_played',array(
            'label' => 'Rozegrany:',
            'required' => true
        ));
        $this->addElement('submit','submit',array(
            'label' => 'Dodaj mecz',
            'ignore' => true
        ));
    }
}
