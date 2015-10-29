<?php
/**
 * Description of ContactForm
 *
 * @author Kamil
 */

class My_Forms_Contact extends Zend_Form {
    
    public function init()
    {
        $this->setMethod('post');
        
        $this->removeDecorator('Label');
        $this->setElementDecorators(array(
            'viewHelper',
            'Errors',
            array(
                array('row'=>'HtmlTag'),
                array('tag'=>'div')
            )
        ));
        $this->setDecorators(array(
            'FormElements',
            array(
                array('data'=>'HtmlTag'),
                array('tag'=>'div', 'class'=>'contact_form')
            ),
            'Form'
        ));
        
        $this->addElement('text','name',array(
            'required' => true,
//            'validators' => array(
//                'alnum'
//            ),
            'placeholder' => 'Imię i nazwisko',
            'class' => 'col-xs-12'
        ));
        $this->addElement('text','mail',array(
            'required' => true,
//            'validators' => array(
//                array('regex',false,array(
//                    'pattern' => '^[0-9a-zA-Z]+([0-9a-zA-Z]*[-._+])*[0-9a-zA-Z]+@[0-9a-zA-Z]+([-.][0-9a-zA-Z]+)*([0-9a-zA-Z]*[.])[a-zA-Z]{2,6}$',
//                    'messages' => 'Nieprawidłowy adres e-mail!'
//                ))
//            ),
            'placeholder' => 'Adres e-mail',
            'class' => 'col-xs-12'
        ));
        $this->addElement('text','title',array(
            'required' => true,
//            'validators' => array(
//                'alnum'
//            ),
            'placeholder' => 'Tytuł wiadomości',
            'class' => 'col-xs-12'
        ));
        $this->addDisplayGroup(array('name','mail','title'), 'info');
        $info = $this->getDisplayGroup('info');
        $info->setDecorators(array(
            'FormElements',
            'Fieldset',
            array('HtmlTag',array('tag'=>'div', 'class'=>'col-md-4 col-sm-12'))
        ));
        $this->addElement('textarea','message',array(
            'required' => true,
            'placeholder' => 'Treść wiadomości',
            'rows' => 5,
            'class' => 'col-xs-12'
        ));
        $this->addElement('submit','submit', array(
            'label' => 'Wyślij wiadomość',
            'id' => 'contact_form_submit',
            'ignore' => true
        ));
        $this->addDisplayGroup(array('message','submit'), 'message_group');
        $message = $this->getDisplayGroup('message_group');
        $message->setDecorators(array(
            'FormElements',
            'Fieldset',
            array('HtmlTag',array('tag'=>'div', 'class'=>'col-md-8 col-sm-12'))
        ));
    }
}
