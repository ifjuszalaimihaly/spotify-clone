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
    <link rel="stylesheet" type="text/css" href="assets/css/register.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>
</head>
<body>
<?php if(isset($_POST['registerButton'])) : ?>
    <script>
	    $(document).ready(function() {
		    $("#loginForm").hide();
			$("#registerForm").show();
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
			$("#loginForm").show();
			$("#registerForm").hide();
		});
	</script>
<?php endif; ?>
    <div id="background">
        <div id="loginContainer">
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
                        <input id="loginUsername"
                               name="loginUsername"
                               type="text"
                               autocomplete="off"
                               placeholder="mihalyszalai"
                               required>
                    </p>
                    <p>
                        <label for="loginPassword">Password</label>
                        <input id="loginPassword" name="loginPassword" type="password" placeholder="Your password" required>
                    </p>
                    <button type="submit" name="loginButton">LOG IN</button>
                    <div class="hasAccountText">
                       <span id="hideLogin">
                        Don't have an account yet? Sing up here.
                       </span>
                    </div>
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
                               autocomplete="off"
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
                               autocomplete="off"
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
                               autocomplete="off"
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
                               autocomplete="off"
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
                               autocomplete="off"
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
                     <div class="hasAccountText">
                       <span id="hideRegister">
                            Already have an account? Login here.
                       </span>
                     </div>
                 </form>
            </div>
            <div id="loginText">
                <h1>Get great music, right now</h1>
                <h2>Listen to loads  of songs here</h2>
                <ul>
                    <li>Discover music you'll fall in love with</li>
                    <li>Create your own playlists</li>
                    <li>Follow artists to keep up to date</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>