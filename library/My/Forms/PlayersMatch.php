<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_PlayersMatch extends Zend_Form {
    
    private $players = null;
    private $scorersCount = null;
    const PERFORMANCES_LIMIT = 15;
    
    public function __construct($players, $scorersCount, $options = null) {
        $this->players = $players;
        $this->scorersCount = $scorersCount;
        parent::__construct($options);
    }
    
    public function init()
    {
        $scorers = array();
        for($i = 0; $i< $this->scorersCount; $i++){
            $this->addElement('select','scorer_'.$i,array(
                'required' => true,
                'multiOptions' => $this->players
            ));
            $scorers[] = 'scorer_'.$i;
        }
        $this->addDisplayGroup($scorers, 'scorers', array(
            'legend' => 'Strzelcy:'
        ));
        $performances = array();
        for($i = 0; $i< self::PERFORMANCES_LIMIT; $i++){
            $this->addElement('select','player_'.$i,array(
                'required' => true,
                'multiOptions' => $this->players
            ));
            $performances[] = 'player_'.$i;
        }
        $this->addDisplayGroup($performances, 'performances', array(
            'legend' => 'Skład:'
        ));
        $this->addElement('submit','submit',array(
            'label' => 'Aktualizuj',
            'ignore' => true
        ));
    }
}
