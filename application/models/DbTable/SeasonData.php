<?php

class Application_Model_DbTable_SeasonData extends Zend_Db_Table_Abstract
{

    /**
     * Nazwa tabeli
     * @var string $_name
     */
    protected $_name = 'seasondata';

    /**
     * Pełna lista kolumn
     * @var array $columnListFull
     */
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

    /**
     * Lista kolumn na potrzeby strony głównej
     * @var array $columnListFull
     */
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
    public function getHomepageTable()
    {
        $select = $this->select()
                ->from($this->_name, $this->columnListForHomepage)
                ->setIntegrityCheck(false)
                ->join('teams','seasondata.team_id = teams.id','name')
                ->join('seasons','seasondata.season_id = seasons.id','')
                ->where('seasons.is_active = ?', 1)
                ->order(array('is_withdrawn ASC','rank ASC'));
        
        return $this->fetchAll($select);
    }

    /**
     * Zwraca listę zespołów uczestniczących w sezonie o danym ID
     * @param integer $season_id
     * @return array
     */
    public function getSavedTeamInSeason($season_id)
    {
        $select = $this->select()
                ->from($this->_name, 'team_id')
                ->where('season_id = ?', $season_id);
        
        $teamsArray = $this->fetchAll($select);
        $teams = array();
        foreach($teamsArray as $team){
            $teams[] = $team['team_id'];
        }
        
        return $teams;
    }
    
}

