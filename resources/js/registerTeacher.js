/**
 * This function matches input password with confirm password
 * @returns {Boolean} True if passwords match else false.
 */
function matchPassword(){
  if(document.getElementById('password').value.trim()==document.getElementById('confirmpassword').value.trim())
    return true;
  return false;
}
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
 * This function shifts focus of cursor to next field on pressing enter button
 * @param {type} val The current field that has focus
 */
function shift(val){
  window.event.preventDefault();
  if(val=="name"){
    if(!String_isName(document.getElementById(val).value.trim(), false, false) && !String_isName(document.getElementById(val).value.trim(), true, false) )
      alert("Invalid Name Format");
    else
      document.getElementById('phoneno').focus();
  }
  else if(val=="phoneno"){
    if(!String_isPhoneno(document.getElementById(val).value.trim(),"XXXXXXXXXX"))
      alert("Invalid Phone Number Format");
    else
      document.getElementById('address').focus();

  }
  else if(val=="address"){
      document.getElementById('username').focus();
  }

  else if(val=="username"){
    if(!validateEmail(document.getElementById(val).value.trim()))
      alert("Invalid Email Format");
    else
      document.getElementById('password').focus();
  }
  else if(val=="password"){
    if(!String_isDefaultPassword(document.getElementById(val).value.trim(),1))
      alert("Invalid Password Format");
    else
      document.getElementById('confirmpassword').focus();
  }
  else if(val=="confirmpassword"){
    if(!matchPassword())
      alert("Password and Confirmed Password Mismatch");
    else
      document.getElementById('qualification').focus();
  }
  else if(val=="qualification"){
      document.getElementById('submit').click();
  }
}

/**
 * This function checks if the fields in the form are valid or not.
 * @returns {Boolean} True if all the fields are rightly formatted else returns false.
 */
function tryRegister(){
  name=document.getElementById('name').value.trim();
  if(!String_isName(name, false, false) && !String_isName(name, true, false) ){
    alert("Invalid Name Format");
    return false;
  }
  else if(!String_isPhoneno(document.getElementById('phoneno').value.trim(),"XXXXXXXXXX")){
    alert("Invalid Phone Number Format");
    return false;
  }
  else if(document.getElementById('address').value.trim().length<1){
    alert("Address required!");
    return false;
  }
  else if(!validateEmail(document.getElementById('username').value.trim())){
    alert("Invalid Email Format");
    return false;
  }
  else if(!String_isDefaultPassword(document.getElementById('password').value.trim(),1)){
    alert("Invalid Password Format");
    return false;
  }
  else if(!matchPassword()){
    alert("Password and Confirmed Password Mismatch");
    return false;
  }else if(document.getElementById('qualification').files[0]==null){
    alert("Resume is empty");
    return false;
  }
  else{
  return true;
}

}
