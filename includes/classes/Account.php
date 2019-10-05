<?php
class Account
{
	private $errorArray;
	private $db;

	public function __construct(PDO $db)
	{
       $this->errorArray = array();
       $this->db = $db;
	}

	public function login($username,$password)
    {
        $stmt = $this->db->prepare("SELECT password FROM users WHERE username LIKE ?;");
        if(!$stmt->execute([$username])){
            $this->loginFailedError();
            return false;
        }
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        if($stmt->rowCount() && password_verify($password,$stmt->fetch()['password'])){
            return true;
        }
        $this->loginFailedError();
        return false;
    }

	public function register($username,$firstName,$lastName,$email,$email2,$password,$password2)
	{
		$this->validateUsername($username);
		$this->validateFieldLength("first name",$firstName,2,25);
		$this->validateFieldLength("last name",$lastName,2,25);
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

    private function splitFieldName($fieldName)
    {
	   $names = explode(" ",$fieldName);
	   $camelCase = $names[0];
	   for($i = 1; $i<count($names); $i++){
	       $camelCase .= ucfirst($names[$i]);
       }
	   return $camelCase;
    }

    private function userNameTakenError()
    {
        if(!key_exists('username',$this->errorArray)){
            $this->errorArray['username'] = [];
        }
        array_push($this->errorArray['username'],Constants::$usernameTaken);
    }

    private function emailTakenError()
    {
        if(!key_exists('email',$this->errorArray)){
            $this->errorArray['email'] = [];
        }
        array_push($this->errorArray['email'],Constants::$emailTaken);
    }

    private function loginFailedError()
    {
        if(!key_exists('loginFailed',$this->errorArray)){
            $this->errorArray['loginFailed'] = [];
        }
        array_push($this->errorArray['loginFailed'],Constants::$loginFailed);
    }

    public function __destruct()
    {
        $this->db = null;
    }
}
?>