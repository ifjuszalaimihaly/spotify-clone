<?php

use mysql_xdevapi\Result;

namespace\Account::class;

class Account
{
	private $errorArray;
	private $db;

	public function __construct(PDO $db)
	{
       $this->errorArray = array();
       $this->db = $db;
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
            return $this->insertUserDetails($username,$firstName,$lastName,$email,$password);
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

    private function insertUserDetails($username,$firstName,$lastName,$email,$password)
    {
        $password = password_hash($password,PASSWORD_BCRYPT);
        $profilePicture = "assets/images/profile-pics/head_emerald.png";
        $date = date("Y-m-d G:i:s");

        $stmt = $this->db->prepare("INSERT INTO users (username,first_name,last_name,email,password,sign_up_date,profile_picture) values (?,?,?,?,?,?,?);");
        /*$stmt->bindParam(1,$username,PDO::PARAM_STR);
        $stmt->bindParam(2,$firstName, PDO::PARAM_STR);
        $stmt->bindParam(3,$lastName,PDO::PARAM_STR);
        $stmt->bindParam(4,$email,PDO::PARAM_STR);
        $stmt->bindParam(5,$password,PDO::PARAM_STR);
        $stmt->bindParam(6,$date,PDO::PARAM_STR);
        $stmt->bindParam(7,$profilePicture,PDO::PARAM_STR);*/
        return $stmt->execute([$username,$firstName,$lastName,$email,$password,$date,$profilePicture]);
    }

	private function validateUsername($username) 
	{
		if(!$this->validateFieldLength("username", $username,5,25)){
		    return;
		}

		$stmt = $this->db->prepare("SELECT username FROM users WHERE username LIKE ?;");
		if(!$stmt->execute([$username])){
            $this->userNameTakenError();
            return;
        }

		if($stmt->rowCount()) {
            $this->userNameTakenError();
            return;
        }
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

        $stmt = $this->db->prepare("SELECT email FROM users WHERE email LIKE ?;");
        if(!$stmt->execute([$email])){
            $this->emailTakenError();
            return;
        }

        if($stmt->rowCount()) {
            $this->emailTakenError();
            return;
        }
	}

	private function validatePasswords($password, $password2)
	{
		if($password != $password2){
            if(!key_exists("password",$this->errorArray)){
                $this->errorArray["password"] = [];
            }
			array_push($this->errorArray["password"], Constants::$passwordsDoNoMatch);
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

    private function userNameTakenError(){
        if(!key_exists('username',$this->errorArray)){
            $this->errorArray['username'] = [];
        }
        array_push($this->errorArray['username'],Constants::$usernameTaken);
    }

    private function emailTakenError(){
        if(!key_exists('email',$this->errorArray)){
            $this->errorArray['email'] = [];
        }
        array_push($this->errorArray['email'],Constants::$emailTaken);
    }

    public function __destruct()
    {
        $this->db = null;
    }
}
?>