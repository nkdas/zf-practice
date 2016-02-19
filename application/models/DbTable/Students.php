<?php
class Application_Model_DbTable_Students extends Zend_Db_Table_Abstract
{

    protected $_name = 'student_details';
    public function register($email, $password)
    {
        $error = array();
        $validator = new Zend_Validate_EmailAddress();
        $valid = new Zend_Validate_NotEmpty();
        if (!($validator->isValid($email))) {
            array_push($error, 'Invalid Email');
        }
        if (!($valid->isValid($password))) {
            array_push($error, 'Password cannot be blank');
        }

        if ($valid->isValid($error)) {
            return $error;
        } else {
            $data = array(
                'email' => $email,
                'password' => $password,
            );
            $this->insert($data);
            return 'success';
        }
            
    }

    public function getData($email)
    {
        $row = $this->fetchRow("email = '" . $email . "'");
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }
}
