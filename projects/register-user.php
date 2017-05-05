<?php 
require_once('inc/class.user.php');
  if(session_id() == '') {
      session_start();
  }
$user = new USER();
$user_id = $_SESSION['user_session'];
  if(!$user->is_admin($user_id)){
    $user->redirect('index.php');
  };


if(filter_has_var(INPUT_POST,'submit')){
  $fName = filter_var($_POST['first_name'],FILTER_SANITIZE_STRING);
  $lName = filter_var($_POST['last_name'],FILTER_SANITIZE_STRING);
  $uName = filter_var($_POST['user_name'],FILTER_SANITIZE_STRING);
  $email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
  $email2 = filter_var($_POST['email2'],FILTER_SANITIZE_EMAIL);
  $password = filter_var($_POST['password'],FILTER_SANITIZE_STRING);
  $uaccess = filter_var($_POST['user_access'],FILTER_SANITIZE_STRING);
  $reg_failed = false;

  $errors = [];  
  if($fName == ''){
    $errors[] = "Please enter your first name.";
    $reg_failed = true; 
  }
  if($lName == ''){
    $errors[] = "Please enter your last name.";
    $reg_failed = true; 
  }
  if($uName == ''){
    $errors[] = "Please enter a username.";
    $reg_failed = true; 
  }
  if($email == ''){
    $errors[] = "Please enter your email address.";
    $reg_failed = true; 
  }else{
    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
      $errors[] = "Please enter a valid email address.";
      $reg_failed = true; 
    }
  }
  if($email2 == ''){
    $errors[] = "Please re-enter your email address.";
    $reg_failed = true; 
  }
  if($email != $email2){
    $errors[] = "Email addresses do not match.  Please re-enter your email address.";
    $reg_failed = true; 
  }
  if($password == ''){
    $errors[] = "Please enter a password.";
    $reg_failed = true; 
  }
  if($uaccess == ''){
    $errors[] = "Please select an access level.";
    $reg_failed = true; 
  }
  if(!$reg_failed){
    try{
      $stmt = $user->runQuery("SELECT user_name, user_email FROM users WHERE user_name=:uname OR user_email=:umail");
      $stmt->execute(array(':uname'=>$uName, ':umail'=>$email));
      $row=$stmt->fetch(PDO::FETCH_ASSOC);
        
      if($row['user_name']==$uName) {
        $errors[] = "Sorry the username <strong>$uName</strong> is already taken!";
      }
      else if($row['user_email']==$email) {
        $errors[] = 'An account using the email address (<strong>'.$email.'</strong>) already exists!';
      }else{
        if($user->register($fName,$lName,$uName,$email,$password,$uaccess)){ 
          $user->redirect('register-user.php?joined');
          
        }
      }
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
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
 <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>
            <li class="active"><a href="register-user.php">Register Users</a></li>
            <li><a href="edit-users.php">Edit Users</a></li>
            <li><a href="logout.php?logout=true">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

      <form class="form-signin" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <h2 class="form-signin-heading">Register a user</h2>
          <?php
            if(isset($errors)){ 
              foreach($errors as $error){?>
                <div class="alert alert-danger">
                  <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                </div>
              <?php
              }
            }else if(isset($_GET['joined'])){ ?>
               <div class="alert alert-info">
                    <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered <a href='index.php'>login</a> here
               </div>
            <?php } ?>
        <label for="inputfName" class="">First Name</label>
        <input type="text" name="first_name" id="inputfName" class="form-control" value="<?php echo isset($_POST['first_name']) ? $fName : ''; ?>" required autofocus>
        <label for="inputlName" class="">Last Name</label>
        <input type="text" name="last_name" id="inputlName" class="form-control" value="<?php echo isset($_POST['last_name']) ? $lName : ''; ?>" required>
        <label for="inputuName" class="">Username</label>
        <input type="text" name="user_name" id="inputUName" class="form-control" value="<?php echo isset($_POST['user_name']) ? $uName : ''; ?>" required>
        <label for="inputEmail" class="">Email address</label>
        <input type="email" name="email" id="inputEmail" class="form-control" value="<?php echo isset($_POST['email']) ? $email : ''; ?>" required>
        <label for="inputEmail2" class="">Re-enter email address</label>
        <input type="email2" name="email2" id="inputEmail2" class="form-control" value="<?php echo isset($_POST['email2']) ? $email2 : ''; ?>" required>
        <label for="inputPassword" class="">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" value="<?php echo isset($_POST['password']) ? $password : ''; ?>" required>
        <label for="inputPassword" class="">User access level</label>
        <select name="user_access" id="inputaccess" class="form-control" required>
        <option value="">Please make a selection</option>
        <option value="1">Contributer</option>
        <option value="2">Admin</option>
        </select>
        <button style="margin-top: 15px;" class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Register</button>
      </form>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>

