<?php

class SiteController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function itemAction()
    {
        $url = $this->getRequest()->getParam('url');
        $sitesMapper = new Application_Model_DbTable_Sites();
        $this->view->site = $site = $sitesMapper->getSiteByUrl($url);
        
        if($url == 'o-nas'){
            $this->view->about_page = true;
        } else {
            $this->view->turniej_page = true;
        }
        
        $this->getInvokeArg('bootstrap')->getResource('view')->headTitle($site['title']);
        $this->getInvokeArg('bootstrap')->getResource('view')->headMeta()
                ->setName('keywords', $site['keywords']);
    }

    public function teamAction()
    {
        $playersMapper = new Application_Model_DbTable_Players();
        $players = $playersMapper->getAllKiloPlayers();
        $this->view->players = $players;
        $this->view->squad_page = true;
        
        $this->getInvokeArg('bootstrap')->getResource('view')->headTitle('Skład');
        
    }
    
    public function mediaAction()
    {
        
    }
    
    public function contactAction()
    {
        $form = new My_Forms_Contact();
        $this->view->form = $form;
        $this->getInvokeArg('bootstrap')->getResource('view')->headTitle('Kontakt');
        $this->view->contact_page = true;
        
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $html= 'Wiadomość od: '.$values['name'].'<br /><br />';
            $html.= $values['message'];
//            
//            $config = Zend_Registry::get('config')->mail->config;
//            $smtp = Zend_Registry::get('config')->mail->smtp;
            $sendTo = Zend_Registry::get('config')->mail->to;

//            $transport = new Zend_Mail_Transport_Smtp($smtp, (array)$config);
            
            $mail = new Zend_Mail('utf-8');
            $mail->setFrom($values['mail']);
            $mail->setReplyTo($values['mail']);
            $mail->setSubject($values['title']);
            $mail->setBodyHtml($html);
            $mail->addTo($sendTo);
            $mail->send();//$transport);
        }
    }
    
}

