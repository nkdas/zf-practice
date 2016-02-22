<?php

class My_Decorator_SimpleInput extends Zend_Form_Decorator_Abstract
{
    protected $_format = '
    <div class="col-md-4">
    <div class="form-group">
    <label for="%s">%s</label>
    <input id="%s" name="%s" type="%s" class="form-control"/>
    </div></div>';

    public function render($content)
    {
        $element = $this->getElement();
        $name = htmlentities($element->getFullyQualifiedName());
        $label = htmlentities($element->getLabel());
        $id = htmlentities($element->getId());
        $value = htmlentities($element->getValue());
        $type = htmlentities($element->getAttrib('type'));

        $markup = sprintf($this->_format, $name, $label, $id, $name, $type);

        return $markup;
    }
}

class My_Decorator_SimpleButton extends Zend_Form_Decorator_Abstract
{
    protected $_format = '
    <div class="row">
    <div class="col-md-4">
    <input id="%s" name="%s" type="%s" class="btn btn-primary" value="%s"/>
    </div></div>';

    public function render($content)
    {
        $element = $this->getElement();
        $name = htmlentities($element->getFullyQualifiedName());
        $id = htmlentities($element->getId());
        $value = htmlentities($element->getValue());
        $type = htmlentities($element->getAttrib('type'));

        $markup = sprintf($this->_format, $id, $name, $type, $value);

        return $markup;
    }
}

class Application_Form_Registration extends Zend_Form
{
    public function init()
    {
        $this->setName('registration');

        $decorator = new My_Decorator_SimpleInput();
        $firstName = new Zend_Form_Element(
            'firstName', array(
                'label' => 'First Name',
                'type' => 'text',
                'decorators' => array($decorator),
            )
        );

        $email = new Zend_Form_Element(
            'email', array(
                'label' => 'Email',
                'type' => 'text',
                'decorators' => array($decorator),
            )
        );

        $password = new Zend_Form_Element_Password(
            'password', array(
                'label' => 'Password',
                'type' => 'password',
                'decorators' => array($decorator),
            )
        );

        $reEnterPassword = new Zend_Form_Element_Password(
            'reEnterPassword', array(
                'label' => 'Re-enter password',
                'type' => 'password',
                'decorators' => array($decorator),
            )
        );

        $buttonDecorator = new My_Decorator_SimpleButton();
        $submit = new Zend_Form_Element_Submit(
            'submit', array(
                'value' => 'Register',
                'type' => 'submit',
                'decorators' => array($buttonDecorator),
            )
        );

        $this->addElements(array($firstName, $email, $password, $reEnterPassword, $submit));
    }
}
