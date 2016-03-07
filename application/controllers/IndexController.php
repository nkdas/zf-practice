<?php

/**
 * @Class   IndexController
 * @Description Controller for index, register and logout action
 */
class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    /**
     * @function indexAction()
     * @params   none
     * @return   none
     */
    public function indexAction()
    {
        /* Get Session data */
        $storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();

        if ($data) {
            /* If a session exists then redirect to the corresponding home page */

            $this->_redirect('index/home');
        } else {
            /* If a session doesn't exists then show the login form */

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
                            $storage = new Zend_Auth_Storage_Session();
                            $storage->write($record);

                            Zend_Auth::getInstance()->getStorage()->write($record);

                            $this->_redirect('index/home');
                        } else {
                            echo 'Invalid Credentials';
                        }
                    }
                }
            } catch (Exception $ex) {
                echo 'Unable to process your request! Exception: '.$ex;
            }
        }
    }

    /**
     * @function registerAction()
     * @params   none
     * @return   none
     */
    public function registerAction()
    {
        $form = new Application_Form_Registration();
        $form->submit->setLabel('Register');
        $this->view->form = $form;

        try {
            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                if ($form->isValid($formData)) {
                    $students = new Application_Model_DbTable_Students();
                    $status = $students->register($form);
                    if ('success' == $status) {
                        $this->_redirect('/index');
                    } else {
                        foreach ($status as $value) {
                            echo $value.'<br>';
                        };
                        $form->populate($formData);
                    }
                } else {
                    $form->populate($formData);
                }
            }
        } catch (Exception $ex) {
            echo 'Unable to process your request! Exception: '.$ex;
        }
    }

    /**
     * @function homeAction()
     * @params   none
     * @return   none
     */
    public function homeAction()
    {
        $storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();
        if (!$data) {
            $this->_redirect('index/index');
        }
        $this->view->firstname = $data['firstName'];
    }

    /**
     * @function logoutAction()
     * @params   none
     * @return   none
     */
    public function logoutAction()
    {
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $this->_redirect('index/index');
    }
}
