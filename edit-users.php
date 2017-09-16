<?php 
require_once('inc/class.user.php');
require_once('functions/functions.php');
  if(session_id() == '') {
      session_start();
  }
$user = new USER();
$user_id = $_SESSION['user_session'];
if(!$user->is_admin($user_id) || !$user->is_loggedin()){
  $user->redirect('index.php');
}
if($user->is_admin($user_id)){
  $admin = true;
};
if(filter_has_var(INPUT_POST,'deleteuser')){
  $uID = filter_var($_POST['duid'],FILTER_SANITIZE_NUMBER_INT);
  if($user->deleteUser($uID)){
    
    $message = "User has been deleted";
  
  }
}

if(filter_has_var(INPUT_POST,'register')){
  $uID = filter_var(trim($_POST['user_id']),FILTER_SANITIZE_NUMBER_INT);
  $fName = filter_var(trim($_POST['first_name']),FILTER_SANITIZE_STRING);
  $lName = filter_var(trim($_POST['last_name']),FILTER_SANITIZE_STRING);
  $uName = filter_var(trim($_POST['user_name']),FILTER_SANITIZE_STRING);
  $email = filter_var(trim($_POST['email']),FILTER_SANITIZE_EMAIL);
  $password = filter_var(trim($_POST['password']),FILTER_SANITIZE_STRING);
  $uaccess = filter_var(trim($_POST['user_access']),FILTER_SANITIZE_STRING);
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
  if($password == ''){
    $pwordempty = false;
  }
  if($uaccess == ''){
    $errors[] = "Please select an access level.";
    $reg_failed = true; 
  }


  if(!$reg_failed){
    $stmt = $user->runQuery("SELECT user_name, user_email FROM users WHERE user_id=:uid");
    $stmt->bindParam(':uid', $uID);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $currentEmail = $row['user_email'];
    $currentUsername = $row['user_name'];
  }
  if(!$reg_failed){
    try{
      $stmt = $user->runQuery("SELECT user_name, user_email FROM users WHERE user_name=:uname OR user_email=:umail");
      $stmt->execute(array(':uname'=>$uName, ':umail'=>$email));
      $row=$stmt->fetch(PDO::FETCH_ASSOC);
        
      if($row['user_name']==$uName && $currentUsername != $row['user_name']) {
        $errors[] = "Sorry the username <strong>$uName</strong> is already taken!";
      }
      else if($row['user_email']==$email && $currentEmail != $row['user_email']) {
        $errors[] = 'An account using the email address (<strong>'.$email.'</strong>) already exists!';
      }else{
        if($user->updateUser($uID,$fName,$lName,$uName,$email,$password,$uaccess)){ 
          $message = "User has been updated";
          
        }
      }
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }else{

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

    <title>Edit Users</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
  <?php include 'header.php'; ?>

    <div class="container">
    <?php if(isset($message)){
      echo '<div class="alert alert-success" role="alert">'.$message.'</div>';
    }?>
    <h2>Edit Users</h2>
    <?php $results = $user->get_all_users();?>
    <div class="table-responsive">
	    <table class="table table-striped">
	    	
			<thead>
				<tr>
	    			<th>First Name</th>
	    			<th>Last Name</th>
	    			<th>Username</th>
	    			<th>Email address</th>
	    			<th>User access level</th>
	    			<th><span class="glyphicon glyphicon-pencil"></span></th>
            <th><span class="glyphicon glyphicon-trash"></span></th>
				</tr>
			</thead>
			<tbody>
			<?php
				foreach ($results as $result) {
					$uid = $result['user_id'];
					echo '<tr>';
					echo '<td>'.$result['first_name'].'</td>';
					echo '<td>'.$result['last_name'].'</td>';
					echo '<td>'.$result['user_name'].'</td>';
					echo '<td>'.$result['user_email'].'</td>';
					$tempacc = $result['user_access'];
					if($tempacc == 1){$tName = 'Contributor';}else{$tName = 'Admin';}
					echo '<td>'.$tName.'</td>';
					echo '<td><button data-uid="'.$uid.'" data-fname="'.$result['first_name'].'" data-lname="'.$result['last_name'].'" data-uname="'.$result['user_name'].'" data-email="'.$result['user_email'].'" data-uacces="'.$result['user_access'].'" type="button" class="btn btn-primary edit_user" data-toggle="modal" data-target="#edituser">Edit</button></td>';
          echo '<td>';
          echo '<button id="del_user" class="btn btn-default" data-toggle="modal" data-target="#deleteuser" data-uid="'.$uid.'" data-fname="'.$result['first_name'].'" data-lname="'.$result['last_name'].'" type="button">Delete</button>';
          echo '</td>';
					echo '</tr>';
				} ?>
	    	</tbody>
	    </table>
	</div>

  <div id="deleteuser" class="modal fade">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Delete user</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger" role="alert"><i class="glyphicon glyphicon-warning-sign"></i> Are you sure you want to delete <strong><span id="del_f_name"></span> <span id="del_l_name"></span></strong>?  This action can't be undone!</div>
          <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
          <input type="hidden" id="duId" name="duid" value="">
          <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button class="btn btn-danger delete_user" name="deleteuser" type="submit" type="button">Delete</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="edituser" class="modal <?php if(!isset($errors)){?>fade<?php }?>">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Edit user</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <?php
          if(isset($errors)){ 
            foreach($errors as $error){?>
              <div class="alert alert-danger">
                <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
              </div>
            <?php
            }
            } ?>
        <form class="form-signin" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
          <input type="hidden" id="uId" name="user_id" value="<?php echo isset($_POST['user_id']) ? $uID : ''; ?>">
          <label for="inputfName" class="">First Name</label>
          <input type="text" name="first_name" id="inputfName" class="form-control" value="t_name" id="inputfName" class="form-control" value="<?php echo isset($_POST['first_name']) ? $fName : ''; ?>" autofocus>
          <label for="inputlName" class="">Last Name</label>
          <input type="text" name="last_name" id="inputlName" class="form-control" value="<?php echo isset($_POST['last_name']) ? $lName : ''; ?>">
          <label for="inputuName" class="">Username</label>
          <input type="text" name="user_name" id="inputUName" class="form-control" value="<?php echo isset($_POST['user_name']) ? $uName : ''; ?>">
          <label for="inputEmail" class="">Email address</label>
          <input type="email"  autocomplete="off" name="email" id="inputEmail" class="form-control" value="<?php echo isset($_POST['email']) ? $email : ''; ?>">
          <label for="inputPassword" class="">Password</label>
          <p style="font-size:.8em;"><em>only add if you want to change the password</em></p>
          <input type="password"  autocomplete="off" name="password" id="inputPassword" class="form-control" value="<?php echo isset($_POST['password']) ? $password : ''; ?>">
          <label for="inputPassword" class="">User access level</label>
          <select name="user_access" id="inputaccess" class="form-control">
          <option value="">Please make a selection</option>
          <option value="1">Contributor</option>
          <option value="2">Admin</option>
          </select>
          <button style="margin-top: 15px;" class="btn btn-lg btn-primary btn-block" name="register" type="submit">Submit Edits</button>
        </form>
        </div>

      </div>
    </div>
  </div>
    </div> <!-- /container -->

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/projects-script.js"></script>
  </body>
</html>