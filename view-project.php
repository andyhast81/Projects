<?php 
include_once 'inc/class.user.php';
include_once 'inc/class.projects.php';
include_once 'functions/functions.php';
if(session_id() == '') {
    session_start();
}

$user = new USER();
$projects = new PROJECT();
$pid = $_GET['pid'];

$project = $projects->ViewProject($pid);

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
    <title>View Project</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/projects-script.js"></script>
    <script type="text/javascript" src="js/view-project.js"></script>
  </head>
  <body>
  <?php include 'header.php'; ?>

    <div class="container-fluid bg-primary text-white">
      
        
        <h1><?php echo $project['project_name'];?></h1>
        <?php $date=date_create($project['date_created']);?>
        <p style="padding-left:20px;font-size:.9em;">Created on <span><?php echo date_format($date, 'm/d/Y');?></span> submitted by <span><?php echo $user->GetUserNameById($project['assigned_to']);?></span></p>
      
    </div>
    <div class="mt-4 col-md-1">

    </div>
    <div class="mt-4 col-md-7">
      <div class="card">
        <div class="card-header">
          <h4>Project description</h4>
        </div>
        <div class="card-block">
          
          <p class="card-text"><?php echo $project['project_description'];?></p>
          
        </div>
      </div>
    </div>

    <div class="col-md-4" style="padding-left:0;">
      <div class="mt-4">
        <div class="card">
          <div class="card-header">
            <h4><span class="glyphicon glyphicon-list-alt"></span> Additional notes <button id="add_note" class="btn btn-primary btn-sm f_right"><span class="glyphicon glyphicon-plus"></span> Add note</button></h4>
          </div>
          <div id="notes_content" class="card-block">
            <div id="add_note_div" class="clearfix">
              <textarea  rows="5" class="form-control" id="note_text" name="p_desc"></textarea>
              <button data-user="<?php echo $user_id;?>" data-assigned="<?php echo $project['assigned_to'];?>" data-project-id="<?php echo $pid;?>" style="margin-left:5px;" id="submit_note" class="btn btn-primary btn-sm f_right"><span class="glyphicon glyphicon-cloud-upload"></span> Submit note</button>
              <button id="cancel_note" class="btn btn-primary btn-sm f_right"><span class="glyphicon glyphicon-remove-circle"></span> Cancel</button>
            </div>
            <div id="notes" class="clearfix">
              <?php $pageNotes = $projects->GetNotes($pid);
              PreDump($pageNotes);?>
              <!-- <p style="padding:20px;" class="col-lg-10 card-text my_note bg-success"><strong>Andy</strong> - Today at 12:03pm<br><?php //echo $project['project_description'];?></p>
              <p style="padding:20px;" class="col-lg-10 card-text other_note bg-info f_right"><strong>Andy</strong> - Yesterday at 3:30pm<br><?php //echo $project['project_description'];?></p> -->
            </div>
          </div>
        </div>
      </div>
    </div>

      <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.js"></script>
  </body>
</html>