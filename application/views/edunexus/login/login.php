<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login_page</title>
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/Pretty-Footer.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/styles.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/Navigation-with-Button.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="shortcut icon"  href="<?php echo base_url()?>/resources/images/logo.png ?>">
    <script type="text/javascript" src="<?php echo base_url()?>resources/js/jquery.min.js"></script>
    <script src="<?php echo base_url()?>/resources/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>/resources/js/manage_course.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>/resources/js/SimpleCipher.Strings.js"></script>
</head>

<script src="<?php echo base_url('/resources/js/SimpleCipher.Strings.js?version=4')?>" type="application/javascript"></script>
<script src="<?php echo base_url('/resources/js/login.js?version=4')?>" type="application/javascript"></script>

<style>
.rum{
     cursor:pointer;
     color:blue;
     text-decoration:underline;
   }
   .rum:hover{
     text-decoration:none;
      }
</style>
  <body>

    <nav class="navbar navbar-default navigation-clean-button navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header"><a class="navbar-brand" href="#"><img src="<?php echo base_url()?>/resources/images/logo.png ?>" id="logo">EduNexus</a><button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button></div>
                <div
                    class="collapse navbar-collapse" id="navcol-1">
                    <p class="navbar-text navbar-right actions"> 
                      <a class="btn btn-default action-button navbarbutton" href="<?php echo site_url('edunexus/LoginManager/registerLinkTeacher');?>">Register as a Teacher</a>
                    </p>
                </div>
            </div>
    </nav>

    <div id="loginpromo">
      <div id="formContainer" class="container">
        
         <form action="<?php echo site_url('edunexus/LoginManager/authenticate');?>" method="post" onsubmit="return trylogin();">
              <input id="username" name="username" onKeyDown="if(event.keyCode==13) shift('username');" type="text" class="form-control" placeholder="Enter User Email Id"  required>
              <input class="form-control" placeholder="Password" type="password" id="password" name="password" onKeyDown="if(event.keyCode==13) shift('password');" 
         required>
              <select class="form-control" name="usertype" id="usertypesel">
                  <option value="1">Admin</option>
                  <option value="2">NGO</option>
                  <option value="3">NGO Volunteer</option>
                  <option value="4">Teacher</option>
              </select>
              <div class="checkbox">
                  <label> <input type="checkbox" id="rememberme" name="rememberme"/> Remember me </label>
              </div> 
              <!-- <p><span style="color: #ff0000" id="errorMsg" ><?php echo $errMsg;?></span></p> -->
              <input type="hidden" id="siteurl" name="siteurl" value="<?php echo site_url(); ?>" />
              <input type="submit" class="btn btn-success" value="Login" id="submit"></input>
              <div id="forgetpass">
                <p class="message"><a href="#" onclick="forgotPassword();">Forgot password?</a></p>
              </div>
              <?php if($errMsg != "") echo '<div class="alert alert-danger">'.$errMsg.'</div>' ?>
        </form>
        <!-- <form>
        <input type="email" class="form-control" id="inputEmail3" placeholder="Email"/>
         <input type="password" class="form-control" id="inputPassword3" placeholder="Password"/>
           <select class="form-control" id="sel1">
              <option>Admin</option>
              <option>NGO</option>
              <option>NGO Volunteer</option>
              <option>Teacher</option>
          </select>
          <div class="checkbox">
              <label>
                  <input type="checkbox"/> Remember me
              </label>
          </div>
          <button type="submit" class="btn btn-success">Sign in</button>
          <div id="forgetpass">
            <a href="forgetpassword.php"><p>Forget Password</p></a>
        </div>
      </form> -->
      </div>
    </div>
    <div class="container site-section" id="welcome">
        <h1>Welcome to EduNexus</h1>
        <p>Edunexus is a web-based, application that enables teachers to provide study materials for the ngo students without having to be present at the location. Edunexus aims to set up a platform where several contributors can upload study materials for the students coupled with discussion portal where the students may air there queries. This emancipates volunteers from maintaining burdensome teaching schedules and facilitate them to teach without being physically present. <br><br><br></p>
    </div>
    <div class="dark-section">
        <div class="container site-section" id="why">
            <h1>Why Choose Us</h1>
            <div class="row">
                <div class="col-md-4 item"><i class="fa fa-user"></i>
                    <h2>Portablity</h2>
                    <p><br>Access anywhere anytime. Upload course on the go. Teach without being physically present!<br><br></p>
                </div>
                <div class="col-md-4 item"><i class="fa fa-check"></i>
                    <h2>Extensive Repository</h2>
                    <p><br>Contribution made by teachers excelling in not only professional but also educational field.<br><br></p>
                </div>
                <div class="col-md-4 item"><i class="fa fa-gear"></i>
                    <h2>Interactive UI</h2>
                    <p><br>Ease of use, dynamic design coupled with responsive design architecture for multifarious screen size. <br><br></p>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>
