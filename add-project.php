<?php 
include_once 'inc/class.user.php';
include_once 'inc/class.projects.php';
include_once 'functions/functions.php';
if(session_id() == '') {
    session_start();
}
$project = new PROJECT();
$user = new USER();

if(!$user->is_loggedin()){
  $user->redirect('login.php');
}

$user_id = $_SESSION['user_session'];
if($user->is_admin($user_id)){
  $admin = true;
};
if(filter_has_var(INPUT_POST,'create_project')){
  $pname = filter_var(trim($_POST['p_title']),FILTER_SANITIZE_STRING);
  $pdescription = filter_var($_POST['p_desc'],FILTER_SANITIZE_STRING);
  $purl = filter_var(trim($_POST['p_url']),FILTER_SANITIZE_URL);
  $pcreated = date('Y-m-d H:i:s');
  $plive = $_POST['daterange'];
  $convertdate = DateTime::createFromFormat('m/d/Y',$plive); 
  $newdate = $convertdate->format("Y-m-d");
  $pmod = date('Y-m-d H:i:s');
  $sitesec = '';
  $errors = [];
  if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on"){
    $sitesec = 'https://';
  }else{
    $sitesec = 'http://';
  }
  //=======add files from input ===========//
  if(count($_FILES['files']['name']) > 0 && $_FILES['files']['name'][0] != ''){
    $dir    = 'uploads';
    
    $existingfiles = scandir($dir, 1);
    for($i=0; $i<count($_FILES['files']['name']); $i++) {
      //Get the temp file path
      $tmpFilePath = $_FILES['files']['tmp_name'][$i];
      $name = $_FILES['files']['name'][$i];
      $file_type = $_FILES['files']['type'][$i];
      $allowed = array("image/vnd.adobe.photoshop","application/x-photoshop","application/photoshop","application/psd","image/psd", "image/gif", "image/jpg", "image/jpeg", "text/plain", "image/png", "application/pdf", "application/octet-stream", "text/csv", "image/x-photoshop", "application/xml","application/vnd.ms-excel","application/vnd.ms-excel","application/vnd.ms-excel","application/vnd.ms-excel.addin.macroEnabled.12","application/vnd.ms-excel.sheet.binary.macroEnabled.12","application/vnd.ms-excel.sheet.macroEnabled.12","application/vnd.ms-excel.template.macroEnabled.12","application/vnd.ms-word.document.macroEnabled.12","application/vnd.ms-word.template.macroEnabled.12","application/vnd.ms-powerpoint.addin.macroEnabled.12","application/vnd.ms-powerpoint.presentation.macroEnabled.12","application/vnd.ms-powerpoint.slideshow.macroEnabled.12","application/vnd.ms-powerpoint.template.macroEnabled.12","application/vnd.ms-powerpoint","application/vnd.ms-powerpoint");
      if(in_array($file_type, $allowed)) {
        foreach ($existingfiles as $exfile) {
          if($name == $exfile){
            $extension_pos = strrpos($name, '.');
            $name = substr($name, 0, $extension_pos) . date('m-d-Y_his') . substr($name, $extension_pos);
          }
        }
        //Make sure we have a filepath
        if($tmpFilePath != ""){

          $shortname = $name;
          $filePath = "uploads/".$name;
          if(move_uploaded_file($tmpFilePath, $filePath)) {

            $files[] = $sitesec.$_SERVER['HTTP_HOST'].'/'.$filePath;

          }
        }
      }else{
        $errors[] = "$file_type is not allowed for upload.";
      }
    }
  }
  //=========add files from d&d ==========//
  if(filter_has_var(INPUT_POST,'ddfiles') && !empty($_POST['ddfiles'])){    
    $ddfiles = filter_var(trim($_POST['ddfiles']),FILTER_SANITIZE_STRING);
    $ddfilearr = explode(',', $ddfiles);
  }

  
  if($pname == ''){
    $errors[] = "Please enter a title for the project.";
  }
  if($pdescription == ''){
    $errors[] = "Please enter a description for the project.";
  }
  if(count($errors) == 0){
    try{

        if($project->createProject($pname,$pdescription,$pcreated,$newdate,$pmod,$purl,$user_id)){ 
          if(isset($ddfilearr)){
            try{
              $stmt = $user->runQuery("SELECT MAX(project_id) FROM projects");
              $stmt->execute();
              $row=$stmt->fetch(PDO::FETCH_ASSOC);

              $pid = $row['MAX(project_id)'];
              if($project->uploadFiles($ddfilearr,$pid,$user_id)){

              }

            }
            catch(PDOException $e){
              echo $e->getMessage();
            }
          }
          if(isset($files)){
            try{
              $stmt = $user->runQuery("SELECT MAX(project_id) FROM projects");
              $stmt->execute();
              $row=$stmt->fetch(PDO::FETCH_ASSOC);

              $pid = $row['MAX(project_id)'];
              if($project->uploadFiles($files,$pid,$user_id)){

              }

            }
            catch(PDOException $e){
              echo $e->getMessage();
            }
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

    <title>Add Project</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/daterangepicker.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
    <script type="text/javascript" src="js/projects-script.js"></script>
  </head>
  <body>
  <?php include 'header.php'; ?>

    <div class="container">
    <h1>Add Project</h1>
      <?php
      if(isset($errors)){ 
          foreach($errors as $error){?>
            <div class="alert alert-danger">
              <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
            </div>
          <?php
          }
      }?>
      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" id="js-upload-form">
        <div class="form-group">
        
          <label for="pro_title">Project Title</label>
          <input type="text" class="form-control" id="pro_title" name="p_title" required>
        </div>
          <div class="form-group">
          <label for="pro_title">Requested Due Date</label>
            <div class='input-group date' id='datetimepicker'>
              <span style="top:0px!important;" class="glyphicon glyphicon-calendar input-group-addon"></span>
              <input class="form-control" type="text" name="daterange" value="" />
            </div>
        </div>

        <div class="form-group">
          <label for="pro_desc">Project Description (please be expain in detail)</label>
          <textarea  rows="15" class="form-control" id="pro_desc" name="p_desc" required></textarea>
        </div>
        <div class="form-group">
            <div class="panel panel-default">
        <div class="panel-heading"><strong>Upload Files</strong> <small>Accepted files (gif, jpg, jpeg, png, txt, pdf, psd)</small></div>
        <div class="panel-body">


          <h4>Select files from your computer</h4>
          <small>Upload PhotoShop(.psd) files here.</small>
            <div class="form-inline">
              <div class="form-group">
                <input type="file" name="files[]" id="js-upload-files" multiple="multiple">
              </div>
              
            </div>

          <!-- Drop Zone -->
          <h4>Or drag and drop files below</h4>
          <div class="upload-drop-zone" id="drop-zone">
          <input type="hidden" name="ddfiles" id="dd_ufiles" value="" multiple>
            Just drag and drop files here
          </div>

          <!-- Progress Bar -->
          <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
              
            </div>
          </div>

          <!-- Upload Finished -->
          <div class="js-upload-finished">
            <h3>Processed files</h3>
            <div id="uploadmsg" class="list-group">
              
              
            </div>
          </div>
        </div>
      </div>
          <p class="help-block">If files are larger than 2MB each, please create a Dropbox or Google Drive link and add the link below.</p>
        </div>   
        <div class="form-group">
          <label for="pro_desc">Project assets URL</label>               
          <input  class="form-control" type="url" name="p_url">
        </div>
       
        <button type="submit" name="create_project" class="btn btn-primary">Add Project</button>
      </form>

    </div>
        <script type="text/javascript">
            $(function () {

                $('input[name="daterange"]').daterangepicker({
                      "singleDatePicker": true,
                      "showCustomRangeLabel": false
                  }, function(start, end, label) {
                    
                  });
            });
        </script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="js/daterangepicker.js"></script>
  </body>
</html>




