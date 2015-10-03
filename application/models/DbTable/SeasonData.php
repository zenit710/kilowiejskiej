<?php

class Application_Model_DbTable_SeasonData extends Zend_Db_Table_Abstract
{

    protected $_name = 'seasondata';

    private $columnListFull = array(
        'id',
        'season_id',
        'team_id',
        'wins',
        'draws',
        'loses',
        'goals_scored',
        'goals_list',
        'points',
        'rank',
        'is_withdrawn'
    );
    private $columnListForHomepage = array(
        'points',
        'rank',
        'is_withdrawn'
    );
    
    /**
     * Zwraca tabelę sezonu o podanym ID dla strony głównej
     * 
     * @param int $seasonId
     * @return Zend_Db_Table_Rowset
     */
    public function getHomepageTable(){
        $select = $this->select()
                ->from($this->_name, $this->columnListForHomepage)
                ->setIntegrityCheck(false)
                ->join('teams','seasondata.team_id = teams.id','name')
                ->join('seasons','seasondata.season_id = seasons.id','')
                ->where('seasons.is_active = ?', 1)
                ->order(array('is_withdrawn ASC','rank ASC'));
        
        return $this->fetchAll($select);
    }
    
}

