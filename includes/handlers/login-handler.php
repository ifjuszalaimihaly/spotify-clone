<?php
if(isset($_POST['loginButton']))
{
    //Login button was pressed
    $loginUsername = $_POST['loginUsername'];
    $password = $_POST['loginPassword'];

    //Login function
    $result = $account->login($loginUsername,$password);
    if($result){
        $_SESSION['userLoggedIn'] = $loginUsername;
        header("Location: index.php");
    }
}
?>