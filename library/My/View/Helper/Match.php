<?php
/**
 * Description of Menu
 *
 * @author Kamil
 */

class My_View_Helper_Match extends Zend_View_Helper_Abstract {

    public function match()
    {
        $matchMapper = new Application_Model_DbTable_Matches();
        $previous = $matchMapper->getPreviousMatch();
        $next = $matchMapper->getNextMatch();
        $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
        $view->previous = $previous;
        $view->next = $next;
        return $view->render('match.phtml');
    }

}
