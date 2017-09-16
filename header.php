  <header>
      <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="index.php"><span class="glyphicon glyphicon-dashboard"></span> Dashboard</a></li>
            <?php if(isset($admin)){?><li><a href="register-user.php"><span class="glyphicon glyphicon-user"></span> Register Users</a></li><?php } ?>
            <?php if(isset($admin)){?><li><a href="edit-users.php"><span class="glyphicon glyphicon-pencil"></span> Edit Users</a></li><?php } ?>
            <li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
  </header> 