<?php
session_start();
if(isset($_SESSION['userLoggedIn'])){
    header("Location: index.php");
}
include('includes/db_connect.php');
include('includes/classes/Account.php');
include('includes/classes/Constants.php');

$account = new Account($db);

include('includes/handlers/register-handler.php');
include('includes/handlers/login-handler.php');

?>
<!DOCTYPE html>
<html>
<head>
	<title>Welcome to Slotify!</title>
</head>
<body>
	<div id="inputContainer">
		 <form id="loginForm" action="register.php" method="POST">
		 	<h2>
		 		Login to your account
		 	</h2>
		 	<p>
                <?php foreach ($account->getError("loginFailed") as $error) : ?>
                    <span class="errorMessage"><?=  $error ?></span>
                <?php endforeach; ?>
		 		<label for="loginUsername">Username</label>
		 		<input id="loginUsername" name="loginUsername" type="text" placeholder="mihalyszalai" required>
		 	</p>
		 	<p>
		 		<label for="loginPassword">Password</label>
		 		<input id="loginPassword" name="loginPassword" type="password" placeholder="Your password" required>
		 	</p>
		 	<button type="submit" name="loginButton">LOG IN</button>
		 </form>

		 <form id="registerForm" action="register.php" method="POST">
		 	<h2>
		 		 Create your free account
		 	</h2>
		 	<p>
                <?php foreach ($account->getError("username") as $error) : ?>
                    <span class="errorMessage"><?=  $error ?></span>
                <?php endforeach; ?>
		 		<label for="username">Username</label>
		 		<input id="username" name="username"
                       type="text"
                       placeholder="mihalyszalai"
                       <?php if(isset($username)): ?>
                           value="<?=  $username ?>"
                       <?php endif; ?>
                       required>
		 	</p>
		 	<p>
                <?php foreach ($account->getError("first name") as $error) : ?>
                    <span class="errorMessage"><?=  $error ?></span>
                <?php endforeach; ?>
		 		<label for="firstName">First name</label>
		 		<input id="firstName"
                       name="firstName"
                       type="text"
                       placeholder="Mihály"
                       <?php if(isset($firstName)): ?>
                           value="<?=  $firstName ?>"
                       <?php endif; ?>
                       required>
		 	</p>
		 	<p>
                <?php foreach ($account->getError("last name") as $error) : ?>
                    <span class="errorMessage"><?=  $error ?></span>
                <?php endforeach; ?>
		 		<label for="lastName">Last name</label>
		 		<input id="lastName"
                       name="lastName"
                       type="text"
                       placeholder="Szalai"
                       <?php if(isset($lastName)): ?>
                           value="<?=  $lastName ?>"
                       <?php endif; ?>
                       required>
		 	</p>
		 	<p>
                <?php foreach ($account->getError("email") as $error) : ?>
                    <span class="errorMessage"><?=  $error ?></span>
                <?php endforeach; ?>
		 		<label for="email">Email</label>
		 		<input id="email"
                       name="email"
                       type="email"
                       placeholder="ifjuszalaimihaly@gmail.com"
                       <?php if(isset($email)): ?>
                           value="<?=  $email ?>"
                       <?php endif; ?>
                       required>
		 	</p>
		 	<p>
		 		<label for="email2">Confirm email</label>
		 		<input id="email2"
                       name="email2"
                       type="email"
                       placeholder="ifjuszalaimihaly@gmail.com"
                       <?php if(isset($email2)): ?>
                           value="<?=  $email2 ?>"
                       <?php endif; ?>
                       required>
		 	</p>
		 	<p>
                <?php foreach ($account->getError("password") as $error) : ?>
                    <span class="errorMessage"><?=  $error ?></span>
                <?php endforeach; ?>
		 		<label for="password">Password</label>
		 		<input id="password"
                       name="password"
                       type="password"
                       placeholder="Your password"
                       <?php if(isset($password)): ?>
                           value="<?=  $password ?>"
                       <?php endif; ?>
                       required>
		 	</p>
		 	<p>
		 		<label for="password2">Confirm password</label>
		 		<input id="password2"
                       name="password2"
                       type="password"
                       placeholder="Your password"
                       <?php if(isset($password2)): ?>
                           value="<?=  $password2 ?>"
                       <?php endif; ?>
                       required>
		 	</p>
		 	<button type="submit" name="registerButton">SIGN UP</button>
		 </form>
	</div>
</body>
</html>