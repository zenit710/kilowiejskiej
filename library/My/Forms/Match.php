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
        $this->addElement('text','stage',array(
            'label' => 'Kolejka:',
            'required' => true,
            'validators' => array(
                array('int',true,array(
                    'messages' => 'Numer kolejki musi być liczbą!'
                )),
                array('greaterThan',false,array(
                    'min' => 0,
                    'messages' => 'Numer kolejki musi być dodatni!'
                ))
            ),
            'min' => 0
        ));
        $this->addElement('text','date',array(
            'label' => 'Data i godzina:',
            'placeholder' => 'yyyy-mm-dd hh:mm:ss',
            'required' => true
        ));
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
        $this->addElement('text','home_goals',array(
            'label' => 'Bramki gospodarza:',
            'required' => false,
            'min' => 0
        ));
        $this->addElement('text','away_goals',array(
            'label' => 'Bramki gościa:',
            'required' => false,
            'min' => 0
        ));
        $this->addElement('checkbox','is_played',array(
            'label' => 'Rozegrany:',
            'required' => true
        ));
        $this->addElement('hidden','home_id',array());
        $this->addElement('hidden','away_id',array());
        $this->addElement('submit','submit',array(
            'label' => 'Dodaj mecz',
            'ignore' => true
        ));
    }
}
