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
                    $db = $this->_getParam('db');
                    $adapter = new Zend_Auth_Adapter_DbTable(
                        $db,
                        'student_details',
                        'email',
                        'password'
                    );

                    $adapter->setIdentity($form->getValue('email'));
                    $adapter->setCredential($form->getValue('password'));

                    $auth = Zend_Auth::getInstance();
                    $result = $auth->authenticate($adapter);

                    if ($result->isValid()) {
                        $dbObject = new Application_Model_DbTable_Students();
                        $record = $dbObject->getData($form->getValue('email'));
                        Zend_Auth::getInstance()->getStorage()->write(
                            $record
                        );

                        $this->_helper->FlashMessenger('Successful Login');
                        $this->_helper->redirector('home');

                        return;
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
                $students = new Application_Model_DbTable_Students();
                $status = $students->register($form);
                if ('success' == $status) {
                    $this->_redirect('/index');
                } else {
                    foreach ($status as $key => $value) {
                        echo $value.'<br>';
                    };
                    $form->populate($formData);
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
