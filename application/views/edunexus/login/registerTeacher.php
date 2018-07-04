<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>course_page</title>
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/loginstyle.css">
</head>
<script src="<?php echo base_url('/resources/js/SimpleCipher.Strings.js?version=4')?>" type="application/javascript"></script>

<script src="<?php echo base_url('/resources/js/registerTeacher.js?version=4')?>" type="application/javascript"></script>

<body>
  <div class="container registerContainer" id="homePageContainer">
        <div class="thumbnail">
            <div class="caption">
                <h1>Sign up</h1>
                <p>Register as a teacher</p>
                <form action="<?php echo site_url('edunexus/LoginManager/initiateRegisterTeacher');?>" method="post" enctype="multipart/form-data" onsubmit="return tryRegister();">
                  <label for="ngo_id">Select NGO to contribute to : </label>
                  <select class="form-control" id="ngo_id" name="ngo_id">
                                                    <?php
                                                        foreach ($ngoList as $key => $value) {
                                                          echo '<option value="'.$value['id'].'">'.$value['name'].'</option><br>';
                                                        }
                                                    ?>
                                                </select>


                  <input type="text" name="name" id="name" onKeyDown="if(event.keyCode==13) shift('name');" class="form-control" placeholder="Name"/>
                                 
                  <input type="text" name="phoneno" id="phoneno" onKeyDown="if(event.keyCode==13) shift('phoneno');" class="form-control"  placeholder="Phone Number"/>
                                  
                  <input type="text" class="form-control" placeholder="Address" name="address" id="address" onKeyDown="if(event.keyCode==13) shift('address');"/>
                  
                  <input type="text" class="form-control" name="username" id="username" onKeyDown="if(event.keyCode==13) shift('username');" placeholder="Email Id"/>
                                  
                  <input type="password" class="form-control" name="password" id="password" onKeyDown="if(event.keyCode==13) shift('password');" placeholder="Password"/>
                              
                  <input type="password" name="confirmpassword" id="confirmpassword" onKeyDown="if(event.keyCode==13) shift('confirmpassword');" class="form-control" placeholder="Confirm Password" />
                                  
                  <!-- <textarea  class="form-control" cols="30" name="qualification" id="qualification" onKeyDown="if(event.keyCode==13) shift('qualification');" placeholder="Qualification"></textarea> -->
                  <label for="fileToUpload">Upload Resume in PDF</label>
                  <input type="file" id="qualification" name="qualification" class="form-control" accept="application/pdf" >

                  <input type="submit" class="btn btn-success btn-lg btn-block" id="submit" value="Register"/>
              </form>
              <p><span style="color: #ff0000" id="errorMsg" ><?php echo $errMsg;?></span></p>
            </div>
            </div>
        </div>
<!-- <form action="<?php echo site_url('edunexus/LoginManager/initiateRegisterTeacher');?>" method="post" onsubmit="return tryRegister();"> -->
<!-- Select NGO to contribute to : <select id="ngo_id" name="ngo_id">
  <?php
  foreach ($ngoList as $key => $value) {
    echo '<option value="'.$value['id'].'">'.$value['name'].'</option><br>';
  }
  ?>
</select><br> -->

<!-- Name : <input type="text" name="name" id="name" onKeyDown="if(event.keyCode==13) shift('name');"/><br>
Phone Number : <input type="text" name="phoneno" id="phoneno" onKeyDown="if(event.keyCode==13) shift('phoneno');"/><br>
Address : <input type="text" name="address" id="address" onKeyDown="if(event.keyCode==13) shift('address');"/><br>
Username : <input type="text" name="username" id="username" onKeyDown="if(event.keyCode==13) shift('username');"/><br>
Password : <input type="password" name="password" id="password" onKeyDown="if(event.keyCode==13) shift('password');"/><br>
Confirm Password : <input type="password" name="confirmpassword" id="confirmpassword" onKeyDown="if(event.keyCode==13) shift('confirmpassword');"/><br>
Qualification : <textarea rows="5" cols="30" name="qualification" id="qualification" onKeyDown="if(event.keyCode==13) shift('qualification');"></textarea><br>
<input type="submit" id="submit" value="Apply"/>

</form> -->

</body>
</html>