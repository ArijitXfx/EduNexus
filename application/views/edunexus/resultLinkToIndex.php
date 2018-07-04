<!-- <h1><?php echo $title?></h1>
<h5><?php echo $description?></h5>
<center>
<a href="<?php echo site_url('edunexus')?>">Click here to login.</a>
</center> -->


<html>
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>course_page</title>
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/resources/css/loginstyle.css">
</head>
<script type="text/javascript">
  function verify(){
    var userotp=document.getElementById('otp').value;
    if(isNaN(userotp) || userotp.length!=6){
      alert("OTP doesn't meet required format.");
      return false;
    }
    else{
    //  alert('ready to proceed');
      return true;

    }
  //  return false;
  }

</script>
<body>
     <div class="container loginContainer" id="homePageContainer">
    <div class="thumbnail">
      <div class="caption">
        <h1><?php echo $title?></h1>
		<h5><?php echo $description?></h5>
		<center>
		<a href="<?php echo site_url('edunexus')?>">Click here to login.</a>
		</center>
        
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
