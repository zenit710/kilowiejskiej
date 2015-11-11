<?php

class My_Forms_Login extends Zend_Form
{
    private $elementDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element')),
        'Label',
        array(array('row' => 'HtmlTag'), array('tag' => 'li')),
    );
 
    private $buttonDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'button')),
        array(array('row' => 'HtmlTag'), array('tag' => 'li')),
    );
 
    private $checkboxDecorators = array(
        'Label',
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'checkbox')),
        array(array('row' => 'HtmlTag'), array('tag' => 'li')),
    );
 
    public function init()
    {
        $this->setMethod('post');
 
        $username = new Zend_Form_Element_Text('nickname', array(
            'decorators' => $this->elementDecorators,
            'label' => 'Login:',
            'required' => true,
            'filters' => array(
                'StringTrim'
            ),
            'validators' => array(
                array('StringLength', false, array(3, 50))
            ),
            'class' => 'input-text'
        ));
 
        $password = new Zend_Form_Element_Password('password', array(
            'decorators' => $this->elementDecorators,
            'label' => 'Hasło:',
            'required' => true,
            'filters' => array(
                'StringTrim'
            ),
            'validators' => array(
                array('StringLength', false, array(6, 50))
            ),
            'class' => 'input-password'
        ));
 
        $rememberMe = new Zend_Form_Element_Checkbox('rememberMe', array(
            'decorators' => $this->checkboxDecorators,
            'label' => 'Zapamiętaj?',
            'required' => true,
            'class' => 'input-checkbox'
        ));
 
        $submit = new Zend_Form_Element_Submit('login', array(
            'decorators' => $this->buttonDecorators,
            'label' => 'Zaloguj',
            'class' => 'input-submit'
        ));
 
        $this->addElements(array(
            $username,
            $password,
            $rememberMe,
            $submit
        ));
    }
 
    public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
            'FormErrors',
            'FormElements',
            array('HtmlTag', array('tag' => 'ol')),
            'Form'
        ));
    }
}