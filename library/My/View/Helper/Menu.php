<?php
/**
 * Description of Menu
 *
 * @author Kamil
 */

class My_View_Helper_Menu extends Zend_View_Helper_Abstract {
    
    public function menu()
    {
        $sitesMapper = new Application_Model_DbTable_Sites();
        $sites = $sitesMapper->getMenuElements();
        $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
        $view->sites = $sites;
        return $view->render('menu.phtml');
    }
    
}
