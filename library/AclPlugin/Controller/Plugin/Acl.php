<?php

class AclPlugin_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        // echo "string";exit;
        $loginController = 'index';
        $loginAction = 'index';

        $auth = Zend_Auth::getInstance();

        // If user is not logged in and is not requesting login page
        // - redirect to login page.
        if (!$auth->hasIdentity()
                && $request->getControllerName() != $loginController
                && $request->getActionName()     != $loginAction) {
            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
            $redirector->gotoSimpleAndExit($loginAction, $loginController);
        }

        // User is logged in or on login page.

        if ($auth->hasIdentity()) {
            $registry = Zend_Registry::getInstance();
            $acl = $registry->get('acl');
            $identity = $auth->getIdentity();

            try {

                $isAllowed = $acl->isAllowed(
                    $identity['role'],
                    $request->getControllerName(),
                    $request->getActionName()
                );

                if (!$isAllowed) {
                    $this->endSessionAndExit();
                }
            } catch (Exception $ex) {
                $this->endSessionAndExit();
            }
        }
    }

    public function endSessionAndExit()
    {
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
        $redirector->gotoUrlAndExit('index/index');
    }
}
