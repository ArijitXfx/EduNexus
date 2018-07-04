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
* This function checks if the fields in the form are valid or not.
* @returns {Boolean} True if all the fields are rightly formatted else returns false.
*/
function trylogin(){
  username=document.getElementById('username').value;
  password=document.getElementById('password').value;
  rememberme=document.getElementById('rememberme').checked;
  if(!validateEmail(username)){
    alert('Invalid Email Format');
    return false;
  }
else if(!String_isDefaultPassword(password,1)){
  alert('Invalid Password Format');
  return false;
}
else{
  return true;
}

}

/**
* This function shifts focus of cursor to next field on pressing enter button
* @param {type} val The current field that has focus
*/
function shift(val) {
  window.event.preventDefault();
  if(val=='username') {
      if(!validateEmail(document.getElementById('username').value)){
          alert('Invalid email');
      }
      else{
          document.getElementById('password').focus();
      }

  }
  else if(val=='password'){
    document.getElementById('submit').click();
  }
}
/**
* Checks if email id is entered when forgot password is clicked
*/
function forgotPassword(){
  if(!validateEmail(document.getElementById('username').value)){
    alert('Invalid email');
  }
  else{
    var site_url=document.getElementById('siteurl').value;
    var myForm = document.createElement("form");
    myForm.setAttribute('method', "post");
    myForm.setAttribute('action', site_url+"/edunexus/LoginManager/forgotPassword");

    var username = document.createElement("input");
    username.setAttribute("type", "hidden");
    username.setAttribute("name", "username");
    username.setAttribute("value", document.getElementById('username').value);

    var usertype = document.createElement("input");
    usertype.setAttribute("type", "hidden");
    usertype.setAttribute("name", "usertypesel");
    var selectvar=document.getElementById('usertypesel');
    //  alert(selectvar.options[selectvar.selectedIndex].value);
    usertype.setAttribute("value", selectvar.options[selectvar.selectedIndex].value);

    var button = document.createElement("input");
    button.setAttribute('type', "submit");

    myForm.appendChild(username);
    myForm.appendChild(usertype);
    myForm.appendChild(button);
    document.body.appendChild(myForm);
    myForm.submit();

  }

}
