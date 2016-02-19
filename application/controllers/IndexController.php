<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $form = new Application_Form_Login();
        $form->submit->setLabel('Login');
        $this->view->form = $form;

        try {
            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                if ($form->isValid($formData)) {
                    $email = $form->getValue('email');
                    $password = $form->getValue('password');
                    $students = new Application_Model_DbTable_Students();
                    $row = $students->getData($email);
                    if ($row['password'] == $password) {
                        $this->_helper->redirector('home');
                    } else {
                        echo 'Invalid email or password!';
                    }
                }
            }
        } catch (Exception $ex) {
            echo 'Invalid email or password!';
        }
    }

    public function registerAction()
    {
        $form = new Application_Form_Registration();
        $form->submit->setLabel('Register');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $email = $form->getValue('email');
                $password = $form->getValue('password');
                $students = new Application_Model_DbTable_Students();
                $status = $students->register($email, $password);
                if ('success' == $status) {
                    $this->_redirect('/index');
                } else {
                    foreach ($status as $key => $value) {
                        echo $value . '<br>';
                    };
                }
                
            } else {
                $form->populate($formData);
            }
        }
    }

    public function homeAction()
    {
        // action body
    }
}
