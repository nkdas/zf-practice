<?php

/**
 * @Class   Application_Model_DbTable_Students
 */
class Application_Model_DbTable_Students extends Zend_Db_Table_Abstract
{
    protected $_name = 'student_details';

    /**
     * @function register($form)
     * @purpose  saves form data to the database
     * @params   form
     * @return   string registration status whether success or failure
     */
    public function register($form)
    {
        $error = array();
        $validator = new Zend_Validate_EmailAddress();
        $valid = new Zend_Validate_NotEmpty();
        if (!($valid->isValid($form->getValue('firstName')))) {
            array_push($error, 'First name cannot be blank');
        }
        if (!($validator->isValid($form->getValue('email')))) {
            array_push($error, 'Invalid Email');
        }
        if (!($valid->isValid($form->getValue('password')))) {
            array_push($error, 'Password cannot be blank');
        }
        if (!($valid->isValid($form->getValue('reEnterPassword')))) {
            array_push($error, 'Please re-enter your Password');
        }
        if ($form->getValue('password') != $form->getValue('reEnterPassword')) {
            array_push($error, 'Password donot match');
        }

        if ($valid->isValid($error)) {
            return $error;
        } else {
            $data = array(
                'firstName' => $form->getValue('firstName'),
                'email' => $form->getValue('email'),
                'password' => $form->getValue('password'),
            );
            $this->insert($data);

            return 'success';
        }
    }

    /**
     * @function getData($email)
     * @purpose  returns the record for the provided email
     * @params   string $email
     * @return   array database record for the provided email
     */
    public function getData($email)
    {
        $row = $this->fetchRow("email = '".$email."'");
        if (!$row) {
            throw new Exception("Could not find row $email");
        }

        return $row->toArray();
    }
}
