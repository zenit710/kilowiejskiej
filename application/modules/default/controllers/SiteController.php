<?php

class SiteController extends Zend_Controller_Action
{

    const SQUAD_SITE = 'Skład';
    const CONTACT_SITE = 'Kontakt';

    public function init()
    {
        /* Initialize action controller here */
    }

    public function itemAction()
    {
        $url = $this->getRequest()->getParam('url');
        $sitesMapper = new Application_Model_DbTable_Sites();
        $this->view->site = $site = $sitesMapper->getSiteByUrl($url);
        if (!$site) {
            throw new My_Exception_NotFound('Strona, której szukasz, nie istnieje!');
        }
        
        $this->view->currentPage = $site->title;

        $this->getInvokeArg('bootstrap')->getResource('view')->headTitle($site['title']);
        $this->getInvokeArg('bootstrap')->getResource('view')->headMeta()
                ->setName('keywords', $site['keywords']);
    }

    public function teamAction()
    {
        $playersMapper = new Application_Model_DbTable_Players();
        $players = $playersMapper->getAllKiloPlayers();
        $this->view->players = $players;

        $this->view->currentPage = self::SQUAD_SITE;

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

        $this->view->currentPage = self::CONTACT_SITE;
        
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $html= 'Wiadomość od: '.$values['name'].'<br /><br />';
            $html.= $values['message'];

            $sendTo = Zend_Registry::get('config')->mail->to;
            $mail = new Zend_Mail('utf-8');
            $mail->setFrom($values['mail']);
            $mail->setReplyTo($values['mail']);
            $mail->setSubject($values['title']);
            $mail->setBodyHtml($html);
            $mail->addTo($sendTo);
            $mail->send();

            $form->reset();
            $this->view->mailSend = 'Twoja wiadomość została pomyślnie wysłana!';
        }
    }
    
}

