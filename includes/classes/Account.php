<?php

namespace\Account::class;

class Account
{
	private $errorArray;

	public function __construct()
	{
       $this->errorArray = array();
	}

	public function register($username,$firstName,$lastName,$email,$email2,$password,$password2)
	{
		$this->validateUsername($username);
		$this->validateFieldLength(_("first name"),$firstName,2,25);
		$this->validateFieldLength(_("last name"),$lastName,2,25);
		$this->validateEmails($email,$email2);
		$this->validatePasswords($password,$password2);

		if(empty($this->errorArray)) {
			//Insert into db
			return true;
		} else {
			return false;
		}
	}

	public function getError($field)
    {
        if(!key_exists($field, $this->errorArray)) {
	        return [];
        }
	    return $this->errorArray[$field];
    }

	private function validateUsername($username) 
	{
		if(!$this->validateFieldLength("username", $username,5,25)){
		    return;
        }

		//TODO: check if username exists
	}

	private function validateEmails($email, $email2)
	{
		if($email != $email2) {
            if(!key_exists("email",$this->errorArray)){
                $this->errorArray["email"] = [];
            }
			array_push($this->errorArray["email"], Constants::$emailsDoNotMatch);
			return;
		}
		if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            if(!key_exists("email",$this->errorArray)){
                $this->errorArray["email"] = [];
            }
            array_push($this->errorArray["email"], Constants::$emailInvalid);
			return;
		}

		//TODO: Check that username hasn't already been used.
	}

	private function validatePasswords($password, $password2)
	{
		if($password != $password2){
            if(!key_exists("password",$this->errorArray)){
                $this->errorArray["password"] = [];
            }
			array_push($this->errorArray[_("password")], Constants::$passwordsDoNoMatch);
			return;
		}
		if (preg_match('/[^A-Za-z0-9]/', $password)) {
            if(!key_exists("password",$this->errorArray)){
                $this->errorArray["password"] = [];
            }
            array_push($this->errorArray["password"],Constants::$passwordNotAlphanumeric);
			return;
		}
        if(!$this->validateFieldLength("password", $password,5,25)){
            return;
        }

	}

	private function validateFieldLength($field, $value,  $minLength, $maxLength)
    {
        if(strlen($value) > $maxLength || strlen($value) < $minLength) {
            if(!key_exists($field,$this->errorArray)){
                $this->errorArray[$field] = [];
            }
            $camelFieldName = $this->splitFieldName($field);
            $message = get_class_vars("Constants")[$camelFieldName."Characters"];
            array_push($this->errorArray[$field],$message);
            return false;
        }
        return true;
    }

    private function splitFieldName($fieldName){
	   $names = explode(" ",$fieldName);
	   $camelCase = $names[0];
	   for($i = 1; $i<count($names); $i++){
	       $camelCase .= ucfirst($names[$i]);
       }
	   return $camelCase;
    }
}
?>