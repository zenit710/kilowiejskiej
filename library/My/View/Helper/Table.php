<?php
/**
 * table widget
 *
 * @author Kamil
 */

class My_View_Helper_Table extends Zend_View_Helper_Abstract {

    public function table()
    {
        $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');

        // GET INFO ABOUT ACTUAL SEASON
        $seasonMapper = new Application_Model_DbTable_Seasons();
        $view->seasonInfo = $seasonMapper->getActualSeasonInfo();

        // GET TABLE FOR ACTUAL SEASON
        $seasonDataMapper = new Application_Model_DbTable_SeasonData();
        $view->table = $seasonDataMapper->getHomepageTable();

        return $view->render('table.phtml');
    }

}
