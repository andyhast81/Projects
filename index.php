<?php 
include_once 'inc/class.user.php';
include_once 'inc/class.projects.php';
include_once 'functions/functions.php';
if(session_id() == '') {
    session_start();
}

$user = new USER();
$projects = new PROJECT();

if(!$user->is_loggedin()){
	$user->redirect('login.php');
}

$user_id = $_SESSION['user_session'];
if($user->is_admin($user_id)){
	$admin = true;
};
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Projects</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/projects-script.js"></script>
  </head>
  <body>
  <?php include 'header.php'; ?>
    <div class="container">
	    <div class="starter-template">
	    	<h1>Projects Dashboard</h1>
	    </div>
      <div class="row">
        <div id="pro_pipe" class="col-xs-12 col-sm-8 col-md-8">
          <h3>Project Pipeline</h3>
            <div class="table-responsive">
              <table class="table table-bordered">
                
                <thead>
                  <tr>
                      <th>Project</th>
                      <th>Assigned To</th>
                      <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $results = $projects->GetProjectPipline();
                    
                    foreach ($results as $result) {
                      $pid = $result['project_id'];

                      echo '<tr>';
                      echo '<td><a href="view-project.php?pid='.$pid.'">'.$result['project_name'].'</a></td>';
                      $assigned = $user->GetUserNameById($result['assigned_to']);
                      echo '<td>'.$assigned.'</td>';
                      switch ($result['project_status']) {
                      
                        case 0:
                          echo '<td class="text-primary"><span class="glyphicon glyphicon-hand-right"></span> In Progress</td>';
                          break;
                          case 1:
                          echo '<td class="text-warning"><span class="glyphicon glyphicon-option-horizontal"></span> In Queue</td>';
                          break;
                          case 2:
                          echo '<td class="text-success"><span class="glyphicon glyphicon-thumbs-up"></span> Complete</td>';
                          break;
                          case 3:
                          echo '<td class="text-danger"><span class="glyphicon glyphicon-minus-sign"></span> Canceled</td>';
                          break;                        
                        default:
                          echo '<td class="text-warning"><span class="glyphicon glyphicon-option-horizontal"></span> In Queue</td>';
                          break;
                      }

                      echo '</tr>';
                    }
                  ?>
                  <!-- <tr>
                    <td>Create Banner</td>
                    <td>mathslicer</td>
                    <td>Andy</td>
                    <td class="text-primary"><span class="glyphicon glyphicon-hand-right"></span> In Progress</td>
                  </tr> -->
                </tbody>
              </table>
            </div>

        </div>
        <div id="my_pro" class="col-xs-12 col-sm-4 col-md-4">
        <h3>My Projects <a href="my-projects.php?uid=<?php echo $user_id;?>"><span style="font-size:.9em;">see all &raquo;</span></a></h3>
        <div id="my_projects">
          
          <div class="my_project list-group">
            <div class="list-group-item">
              <h4 class="list-group-item-heading">Update Wordpress</h4>
              <p class="list-group-item-text">Please update the Wordpress Core</p>
            </div>
          </div>
          <button type="button" class="btn btn-primary"><a href="add-project.php"><span class="glyphicon glyphicon-plus"></span> Add Project</a></button>
        </div>
        </div>
      </div>
    </div><!-- /.container -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.js"></script>
  </body>
</html>





