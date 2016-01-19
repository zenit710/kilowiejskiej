<?php
/**
 * Description of Menu
 *
 * @author Kamil
 */

class My_View_Helper_Matches extends Zend_View_Helper_Abstract {

    public function matches()
    {
        $matchMapper = new Application_Model_DbTable_Matches();
        $previous = $matchMapper->getPreviousMatch();
        $next = $matchMapper->getNextMatch();
        $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
        $view->previousMatch = $previous;
        $view->nextMatch = $next;
        return $view->render('matches.phtml');
    }

}
