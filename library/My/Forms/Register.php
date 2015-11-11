<?php

class My_Forms_Register extends Zend_Form
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
 
    public function init()
    {
        $this->setMethod('post');
 
        $firstName = new Zend_Form_Element_Text('name', array(
            'decorators' => $this->elementDecorators,
            'label' => 'Imię:',
            'required' => true,
            'filters' => array(
                'StringTrim'
            ),
            'validators' => array(
                array('StringLength', false, array(2, 50))
            ),
            'class' => 'input-text'
        ));
 
        $lastName = new Zend_Form_Element_Text('surname', array(
            'decorators' => $this->elementDecorators,
            'label' => 'Nazwisko:',
            'required' => true,
            'filters' => array(
                'StringTrim'
            ),
            'validators' => array(
                array('StringLength', false, array(2, 50))
            ),
            'class' => 'input-text'
        ));
 
        $email = new Zend_Form_Element_Text('email', array(
            'decorators' => $this->elementDecorators,
            'label' => 'Email:',
            'required' => true,
            'filters' => array(
                'StringTrim'
            ),
            'validators' => array(
                'EmailAddress'
            ),
            'class' => 'input-text'
        ));
 
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
 
        $passwordAgain = new Zend_Form_Element_Password('passwordAgain', array(
            'decorators' => $this->elementDecorators,
            'label' => 'Powtórz hasło:',
            'required' => true,
            'filters' => array(
                'StringTrim'
            ),
            'validators' => array(
                array('StringLength', false, array(6, 50))
            ),
            'class' => 'input-password'
        ));
 
        $submit = new Zend_Form_Element_Submit('register', array(
            'decorators' => $this->buttonDecorators,
            'label' => 'Rejestruj',
            'class' => 'input-submit'
        ));
 
        $this->addElements(array(
            $firstName,
            $lastName,
            $email,
            $username,
            $password,
            $passwordAgain,
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