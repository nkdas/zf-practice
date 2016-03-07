<?php
require_once APPLICATION_PATH . '/ConfigACL.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initSession()
    {
        Zend_Session::start();
    }
}
