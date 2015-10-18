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
    }

    public function teamAction()
    {
        $playersMapper = new Application_Model_DbTable_Players();
        $players = $playersMapper->getAllKiloPlayers();
        $this->view->players = $players;
    }
    
    public function mediaAction()
    {
        
    }
    
    public function contactAction()
    {
        $form = new Zend_Form();
        $form->setMethod('post');
        
        $form->removeDecorator('Label');
        $form->setElementDecorators(array(
            'viewHelper',
            'Errors',
            array(
                array('row'=>'HtmlTag'),
                array('tag'=>'div')
            )
        ));
        $form->setDecorators(array(
            'FormElements',
            array(
                array('data'=>'HtmlTag'),
                array('tag'=>'div', 'class'=>'contact_form')
            ),
            'Form'
        ));
        
        $form->addElement('text','name',array(
            'required' => true,
            'validators' => array(
                'alnum'
            ),
            'placeholder' => 'Imię i nazwisko',
            'class' => 'col-xs-12'
        ));
        $form->addElement('text','mail',array(
            'required' => true,
            'validators' => array(
                array('regex',false,array(
                    'pattern' => '^[0-9a-zA-Z]+([0-9a-zA-Z]*[-._+])*[0-9a-zA-Z]+@[0-9a-zA-Z]+([-.][0-9a-zA-Z]+)*([0-9a-zA-Z]*[.])[a-zA-Z]{2,6}$',
                    'messages' => 'Nieprawidłowy adres e-mail!'
                ))
            ),
            'placeholder' => 'Adres e-mail',
            'class' => 'col-xs-12'
        ));
        $form->addElement('text','title',array(
            'required' => true,
            'validators' => array(
                'alnum'
            ),
            'placeholder' => 'Tytuł wiadomości',
            'class' => 'col-xs-12'
        ));
        $form->addDisplayGroup(array('name','mail','title'), 'info');
        $info = $form->getDisplayGroup('info');
        $info->setDecorators(array(
            'FormElements',
            'Fieldset',
            array('HtmlTag',array('tag'=>'div', 'class'=>'col-md-4 col-sm-12'))
        ));
        $form->addElement('textarea','message',array(
            'required' => true,
            'placeholder' => 'Treść wiadomości',
            'rows' => 5,
            'class' => 'col-xs-12'
        ));
        $form->addElement('submit','submit', array(
            'label' => 'Wyślij wiadomość',
            'id' => 'contact_form_submit'
        ));
        $form->addDisplayGroup(array('message','submit'), 'message_group');
        $message = $form->getDisplayGroup('message_group');
        $message->setDecorators(array(
            'FormElements',
            'Fieldset',
            array('HtmlTag',array('tag'=>'div', 'class'=>'col-md-8 col-sm-12'))
        ));
        echo $form->render();
    }
    
}

