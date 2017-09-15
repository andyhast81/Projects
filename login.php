<?php 
require_once('inc/class.user.php');
	if(session_id() == '') {
	    session_start();
	}
	$user = new USER();
	if($user->is_loggedin()){

		$user->redirect('index.php');
	}
	if(filter_has_var(INPUT_POST,'submit')){
		$email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
	  	$password = filter_var($_POST['password'],FILTER_SANITIZE_STRING);
	  	if($email == ''){
		    $errors[] = "Please enter your email address.";
		  }else{
		    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
		      $errors[] = "Please enter a valid email address.";
		  	}
		}

		if($password == ''){
			$errors[] = "Please enter a password.";
		}
		if($user->doLogin($email,$password)){
			$user->redirect('index.php');

		}else{
			$errors[] = "The email/username and/or password entered were incorrect.";
		}	
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Sign In</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <form class="form-signin"  action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <h2 class="form-signin-heading">Please sign in</h2>
            <?php
            if(isset($errors)){ 
              foreach($errors as $error){?>
                <div class="alert alert-danger">
                  <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                </div>
              <?php
              }
            } ?>
        <label for="inputEmail">Email address</label>
        <input type="email" name="email" id="inputEmail" class="form-control" value="<?php echo isset($_POST['email']) ? $email : ''; ?>" required autofocus>
        <label for="inputPassword">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" value="<?php echo isset($_POST['password']) ? $email : ''; ?>" required>
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>

