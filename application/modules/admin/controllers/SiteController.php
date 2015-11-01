<?php

class Admin_SiteController extends Zend_Controller_Action
{

    private $sitesMapper;
    
    public function init()
    {
        $this->_helper->layout->setLayout('admin-panel');
        $this->sitesMapper = new Application_Model_DbTable_Sites();
    }

    public function indexAction()
    {
        $this->view->sites = $this->sitesMapper->fetchAll();
    }
    
    public function addAction()
    {
        $form = new My_Forms_Site();
        echo $form->render();
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $values['url'] = $this->generateSlug($values['title']);
            $this->sitesMapper->insert($values);
            $this->redirect('/admin/site');
        }
    }
    
    public function editAction()
    {
        $id = $this->getParam('id');
        $site = $this->sitesMapper->getById($id);
        if(!$site){
            return null;
        }
        $form = new My_Forms_Site();
        $form->populate($site->toArray());
        echo $form->render();
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $values['url'] = $this->generateSlug($values['title']);
            $this->sitesMapper->update($values,'id = '.$id);
            $this->redirect('/admin/site');
        }
    }

    public function deleteAction()
    {
        $id = $this->getParam('id');
        $this->sitesMapper->delete('id = '.$id);
        $this->redirect('/admin/site');
    }
    
    private function generateSlug($string)
    {
        $string = strtr($string, 'ĘęÓóĄąŚśŁłŹźŻżĆćŃń', 'EeOoAaSsLlZzZzCcNn');
        $string = strtr($string, 'ˇ¦¬±¶Ľ','ASZasz');
        $string = preg_replace("'[[:punct:][:space:]]'",'-',$string);
        $string = strtolower($string);
        $znaki = '-'; 
        $powtorzen = 1;
        $string = preg_replace_callback('#(['.$znaki.'])\1{'.$powtorzen.',}#', create_function('$a', 'return substr($a[0], 0,'.$powtorzen.');'), $string);
        return $string;
    }

}

