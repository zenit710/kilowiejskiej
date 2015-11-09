<?php
/**
 * Description of Category
 *
 * @author Kamil
 */
class My_Forms_Match extends Zend_Form {
    
    private $teams = null;
    
    public function __construct($teams, $options = null) {
        $this->teams = $teams;
        parent::__construct($options);
    }
    
    public function init()
    {
        $this->addElement('text','season_id',array(
            'label' => 'Sezon:',
            'required' => true,
            'min' => 0
        ));
        $this->addElement('text','stage',array(
            'label' => 'Kolejka:',
            'required' => true,
            'min' => 0
        ));
        $this->addElement('text','date',array(
            'label' => 'Data i godzina:',
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
        $this->addElement(
            'hidden',
            'scorers',
            array(
                'required' => false,
                'autoInsertNotEmptyValidator' => false,
                'decorators' => array(
                    array(
                        'HtmlTag', array(
                            'tag'  => 'div',
                            'id'   => 'scorers'
                        )
                    )
                )
            )
        );
        $this->scorers->clearValidators();
        $this->addElement(
            'hidden',
            'performances',
            array(
                'required' => false,
                'autoInsertNotEmptyValidator' => false,
                'decorators' => array(
                    array(
                        'HtmlTag', array(
                            'tag'  => 'div',
                            'id'   => 'performances'
                        )
                    )
                )
            )
        );
        $this->performances->clearValidators();
        $this->addElement('submit','submit',array(
            'label' => 'Dodaj mecz',
            'ignore' => true
        ));
    }
}
