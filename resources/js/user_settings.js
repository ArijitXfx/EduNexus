/**
 * This function validates an email format
 * @param {type} email The string that needs to be validated for email format
 * @returns {Boolean} True if valid email format else false.
 */
function validateEmail(email) {
     var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
     return re.test(email);
}

/**
 * Ajax function to get user details for logged in user.
 */
function load(){
  $('#qualification').val('');
  if($('#usertype').val()=="1"){
    $('#details-div').hide();
  }

  if($('#usertype').val()!="4"){
    $('#qualification-div').hide();
  }
  var url_val = $('#hidden').val()+'/edunexus/LoginManager';
  var base_url = $('#hidden1').val();
    $.ajax({
      url: url_val+'/getUserDetails',
      method: 'post',
      dataType: 'json',
      success: function(response){
        console.log(response);
        $('#name').val(response.name);
        $('#username').val(response.username);
        if(response.usertype!="1"){
          $('#phoneno').val(response.phoneno);
          $('#address').val(response.address);
        }      
        if(response.usertype=="4")
           $('#qualification-link').attr("href", base_url+"/"+response.qualification);
      }
    });
}

/**
 * Ajax function to update user details for a logged in user.
 */
function updateDetails(){
  var name = $('#name').val().trim();
  //var username = $('#username').val().trim();
  var phoneno = $('#phoneno').val().trim();
  var address = $('#address').val().trim();
  var qualification = document.getElementById('qualification').files[0];
  //validate details

  if(!String_isName(name, false, false) && !String_isName(name, true, false) ){
    alert("Invalid Name Format");
    return false;
  }
  
  // if($('#usertype').val()=="4"){
  //   if(qualification.length<1){
  //     alert("Qualification is empty");
  //     return false;
  //   }  
  // }
  

  var form = new FormData();
  form.append('name',name);
  
  if($('#usertype').val()!="1"){
    
    if(address.length<1){
      alert("Address is empty");
      return false;
    }
    form.append('address',address);
    
    if(!String_isPhoneno(phoneno,"XXXXXXXXXX")){
      alert("Phone number not valid");
      return false; 
    }
    form.append('phoneno',phoneno);
  
  }
  

  if($('#usertype').val()=="4"){
    if(qualification!=null){
      if(((qualification.size/1024)/1024)>2){
        alert('File size should be less than or equal to 2MB');
        return false;
      }
    }
    form.append('qualification',qualification);
  }

  var url_val = $('#hidden').val()+'/edunexus/LoginManager';
  //console.log('calling ajax');
    $.ajax({
      url: url_val+'/changeUserDetails',
      method: 'post',
      dataType: 'json',
      processData:false,
      contentType:false,
      data: form,
      success: function(response){
        console.log(response);
        if(response.success==="true"){
          alert('Details have been updated');
          load();
        }else if(response.errorCode==="1"){
          callError(response.errorCode);
        }else if(response.errorCode=="11"){
          document.getElementById('errorMsgUserDetails').innerHTML = response.errMsg;
        }
      }
    });
}

/**
 * Ajax function to change password for a logged in user
 */
function changePassword(){
  var password = $('#curpassword').val().trim();
  var newpassword = $('#newpassword').val().trim();
  var confirmpassword = $('#connewpassword').val().trim();
  
  //validate details
  if(!String_isDefaultPassword(password,1)){
      alert("Invalid format of current password");
      return false;
  }
  if(!String_isDefaultPassword(newpassword,1)){
    alert("Invalid format of new password");
    return false;
  }
  if(!String_isDefaultPassword(confirmpassword,1)){
    alert("Invalid format of confirm new password");
    return false;
  }
  if(newpassword!=confirmpassword){
    alert("New Password and Confirmed Password Mismatch");
    return false;
  }
  if(password==newpassword){
    alert("New Password and Old Password can't be same");
    return false;
  }
  var obj = {};
  obj.oldpassword = password;
  obj.newpassword = newpassword;

  var url_val = $('#hidden').val()+'/edunexus/LoginManager';
    $.ajax({
      url: url_val+'/changePassword',
      method: 'post',
      dataType: 'json',
      data: obj,
      success: function(response){
        console.log(response);
        if(response.success==="true"){
          $('#curpassword').val('');
          $('#connewpassword').val('');
          $('#newpassword').val('');
          alert('Password have been updated');
        }else if(response.errorCode=="1"){
          callError(response.errorCode);
        } 
        else if(response.errorCode=="8"){
          $('#curpassword').val('');
          $('#connewpassword').val('');
          $('#newpassword').val('');
          alert("Invalid old password.");
        } 
      }
    }); 
}

/**
 * Post function to delete account for a logged in user.
 */
function tryDelete(){
 if(!confirm("Are you sure you want to delete?"))
    return false;
  return true;
}