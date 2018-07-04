<html>
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/loginstyle.css">
</head>
<script type="text/javascript">
  function validatePassword(val){
    if (val.trim().length>=5)
      return true;
    return false;
  }

  function matchPassword(){
    if(document.getElementById('newpassword').value.trim()==document.getElementById('confirmnewpassword').value.trim())
      return true;
    return false;
  }

  function shift(val) {
    window.event.preventDefault();
      if(val=='newpassword') {
          if(!validatePassword(document.getElementById('newpassword').value)){
              alert('Invalid Password format');
          }
          else{
              document.getElementById('confirmnewpassword').focus();
          }

      }
      else if(val=='confirmnewpassword'){
        if(!validatePassword(document.getElementById('confirmnewpassword').value)){
            alert('Invalid Password format');
        }
        else if(!matchPassword()){
          alert('New Password and Confirm New Password Mismatch');
        }
        else{
          document.getElementById('submit').click();
        }

      }
  }



  function verify(){
  ////  var newpassword=document.getElementById('newpassword').value;
  //  var confirmnewpassword=document.getElementById('confirmnewpassword').value;
    if(!validatePassword(document.getElementById('newpassword').value)){
        alert('Invalid Password format');
        return false;
    }
    else if(!matchPassword()){
        alert('New Password and Confirm New Password Mismatch');
        return false;
    }
    else{
      //  alert('ready to process');
      return true;
    }




  }

</script>
<body>
     <div class="container loginContainer" id="homePageContainer">
    <div class="thumbnail">
      <div class="caption">
        <h1>Change Password</h1>
         
        <!-- <form action="<?php echo site_url('edunexus/LoginManager/matchOTP');?>" method="post" onsubmit="return verify();">
            <input type="text" class="form-control" name="otp" id="otp" placeholder="OTP HERE"/>
            <input type="submit" value="Verify OTP" class="btn btn-primary btn-lg btn-block"/>
            <input type="hidden" name="chance" value="<?php echo $chance?>"/>
            <p><span style="color: #ff0000" id="errorMsg" ><?php echo $errMsg;?></span></p>
      
        </form> -->

        <form action="<?php echo site_url('edunexus/LoginManager/changeAfterForgotPassword');?>" method="post" onsubmit="return verify();" >
          <input type="password" name="newpassword" class="form-control" id="newpassword" placeholder="New Password" onKeyDown="if(event.keyCode==13) shift('newpassword');"/>
          <input type="password" name="confirmnewpassword" class="form-control" id="confirmnewpassword" placeholder="Confirm New Password" onKeyDown="if(event.keyCode==13) shift('confirmnewpassword');"/>

          <input type="submit" value="Change Password" id="submit" class="btn btn-success btn-lg btn-block"/>

          </form>

        
      </div>
    </div>
  </div>
      <!-- <form action="<?php echo site_url('edunexus/LoginManager/matchOTP');?>" method="post" onsubmit="return verify();"> -->
      <!-- Enter OTP : <input type="text" name="otp" id="otp" placeholder="OTP HERE"/>
      <input type="submit" value="Verify OTP"/>
      <input type="hidden" name="chance" value="<?php echo $chance?>"/>
      </form> -->

      
</body>
</html>
