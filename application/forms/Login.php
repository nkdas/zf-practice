<?php

class Application_Form_Login extends Zend_Form
{
    public function init()
    {
        $this->setName('login')
            ->setAttrib('class', 'form-vertical');

        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('int');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email')
            ->setAttrib('class', 'form-control');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
            ->setAttrib('class', 'form-control');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton')
            ->setAttrib('class', 'btn btn-primary form-group');

        $this->addElements(array($id, $email, $password, $submit));
    }
}
