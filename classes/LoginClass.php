<?php

class loginClass{
    public function validateUser($post, $connPdo, $objpdoClass, $objmessageClass) {
        $data = $post;        
        $rules = array(
            "username" => "Require"
        );

        $objValidator = new Validator($rules);

        if (!$objValidator->isValid($data)) {            
            $errors = $objValidator->ErrorFields();

            $strError = $objValidator->formatError($errors);
            $objmessageClass->add('e', $strError);
        }else{
            $arrBindingValues = array();
            $arrBindingValues[':value1'] = strtolower($post['username']);
           /* $arrBindingValues[':value2'] = $post['password']; */

            $query = "SELECT * FROM user_login WHERE email=:value1 ORDER BY id LIMIT 1";
            $user = $objpdoClass->exceuteQuery($connPdo, $query, $arrBindingValues);

            if(count($user) > 0){
                //if($user[0]['password'] === $post['password']){
                    Logger::info("Login Successful [validateUser] : Success " . $post['username']);                    
                    return $this->createSession($user, $connPdo, $objpdoClass);
                //}else{
                   // Logger::info("Login Failed [validateUser] : Failed " . $post['username']);
                   // $objmessageClass->add('e', 'Invalid User Name and Password, Please try again');
                //}
            }else{
                Logger::info("Login Failed [validateUser] : Failed " . $post['username']);
                $objmessageClass->add('e', 'Invalid User Name and Password, Please try again');
            }
        }
    }

    public function createSession($user, $connPdo, $objpdoClass){
        $userDetails['login'] = "Yes";
        $userDetails['userid'] = $user[0]['id'];
        $_SESSION['userid'] = $user[0]['id'];
        $_SESSION['username'] = $user[0]['email'];
       // $_SESSION['Planttype'] = $user[0]['Planttype'];
        $_SESSION['login'] = "Yes";
        return $userDetails;
    }
    
}
?>