/**
* Discussion Portal Manager manage Discussion Portal and Discussion Thread
* 
* @author Arijit Basu <thearijitxfx@gmail.com>
* 
* version 1.0
* 
*/

var vol_id = -1;
var offset_val = 0;
var limit_val = 10;


/**
 * match user's password with database password
 * 
 * @returns {Boolean}
 */
function matchPassword(){
	if(document.getElementById('password').value.trim()==document.getElementById('confirmpassword').value.trim())
		return true;
	return false;
}

/**
 * Validate a email address
 * 
 * @param {string} email
 * @returns {Boolean}
 */
function validateEmail(email) {
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

/**
 * load previous answers
 * 
 * @returns void
 */
function previous(){
	offset_val = offset_val - limit_val;
	loadRequiredCourses();
}

/**
 * load next answers
 * 
 * @returns {void}
 */
function next(){
	offset_val += limit_val;
	loadRequiredCourses();
}

/**
 * Add information of a new ngo
 * 
 * @returns {Boolean}
 */
function submit(){

	var name = $('#name').val().trim();
	var username = $('#username').val().trim();
	var password  = $('#password').val().trim();
	var address = $('#address').val().trim();
	var phoneno = $('#phoneno').val().trim();
	//validate form
	if(!String_isName(name, false, false) && !String_isName(name, true, false) ){
		alert("Invalid Name Format");
		return false;
	}

	if(!validateEmail(username)){
		alert("Invalid Email Format");
		return false;
	}


	if(vol_id==-1){
			if(!String_isDefaultPassword(password,1)){
					alert("Invalid Password Format");
					return false;
			}
	}
	if(!matchPassword()){
		alert("Password and Confirmed Password Mismatch");
		return false;
	}
	if(!String_isPhoneno(phoneno,"XXXXXXXXXX")){
		alert("Phone number not valid");
		return false;	
	}
	if(address.length<1){
		alert("Address is empty");
		return false;
	}
	
	var obj = {};
	obj.name = name;
	obj.username = username;
	obj.address = address;
	obj.phoneno = phoneno;

	var url_val = $('#hidden').val()+'/edunexus/AdminManager';
	if(vol_id==-1){
		//add volunter
		obj.password = password;
		$.ajax({
			url: url_val+'/addNGO',
			method: 'post',
			dataType: 'json',
			data: obj,
			success: function(response){
				console.log(response);
				
				//reload the content part using ajax
				if(response.success==="true"){
					loadNGOs();
					$('#addUserModal').modal('hide');
				}else if(response.errorCode==="1"){
					callError(response.errorCode);
				}else if(response.errorCode=="7"){
					alert('Duplicate email id. Please enter a new email id.');
				} 
			}
		});
	}else{
		//update volunter
		obj.id = vol_id;
		$.ajax({
			url: url_val+'/updateNGO',
			method: 'post',
			dataType: 'json',
			data: obj,
			success: function(response){
				console.log(response);
				
				if(response.success==="true"){
					$('#addUserModal').modal('hide');
					loadNGOs();
				}else if(response.errorCode==="1"){
					callError(response.errorCode);
				}else if(response.errorCode=="6"){
					$('#addUserModal').modal('hide');
				}
			}
		});
		vol_id = -1;
	}
}

/**
 * update information of a ngo
 * 
 * @param {HTML Object} val
 * @returns {void}
 */
function update(val){

	$('#myModalLabel').text('Update NGO Details');
	$('#add-user-btn').text('Update User');

	vol_id = $(val).closest('div').attr('id');//div id extraction
	var p4 = $('#'+vol_id).parent();
	var siblings = $(p4).siblings();
	
	$('#password').hide();
	$('#confirmpassword').hide();
	
	$('#input[type="text"]').val('');
	$('#addUserModal').modal('show');
	
	$('#name').val($(siblings[0]).text());
	$('#username').val(($(siblings[1]).text()).substring(7));
	$('#phoneno').val(($(siblings[2]).text()).substring(11));
	$('#address').val(($(siblings[3]).text()).substring(9));
	

}

/**
 * Delete an ngo
 * 
 * @param {html object} val
 * @returns {void}
 */
function deleteNGO(val){
	if(!confirm("Are you sure you want to delete?"))
		return;
	var vol_id = $(val).closest('div').attr('id');
	var url_val = $('#hidden').val()+'/edunexus/AdminManager/deleteNGO';
	$.ajax({
			url: url_val,
			method: 'post',
			dataType: 'json',
			data: {id:vol_id},
			success: function(response){
				console.log(response);
				if(response.success==="true")
					loadNGOs();
				else if(response.errorCode==="1"){
					callError(response.errorCode);
				}
			}
		});
}

/**
 * add a new ngo
 * 
 * @returns {void}
 */
function addUser(){
	vol_id = -1;
	$('#password').show();
	$('#confirmpassword').show();
	$('#myModalLabel').text('Add New NGO');
	$('#add-user-btn').text('Add User');
	$('#addUserModal input').val('');
	$('#addUserModal textarea').val('');
	$('#addUserModal').modal('show');
}

/**
 * 
 * Show registered ngo(s)
 * 
 * @param {int} id
 * @param {string} name
 * @param {string} username
 * @param {string} phoneno
 * @param {string} address
 * @returns {void}
 */
function ngoElement(id,name,username,phoneno,address){

	var div2 = $('<div class="thumbnail"></div>');
	var div3 = $('<div class="caption"></div>');
	var h3 = $('<h3>'+name+'</h3>');
	var p1 = $('<p>Email: '+username+'</p>');
	var p2 = $('<p>Phone no.: '+phoneno+'</p>');
	var p3 = $('<p>Address: '+address+'</p>');
	var p4 = $('<p></p>');
	var div4 = $('<div>').attr('id',""+id);
	div4.append('<a class="btn btn-default" role="button" onclick="deleteNGO(this)">Delete</a>');
	div4.append('<a class="btn btn-primary" role="button" onclick="update(this)">Update</a>');
	p4.append(div4);
	div3.append(h3);
	div3.append(p1);
	div3.append(p2);
	div3.append(p3);
	div3.append(p4);
	div2.append(div3);
	$('#content').append(div2);

}

/**
 * Load registered ngo(s)
 * 
 * @returns {void}
 */
function loadNGOs(){
	

	$('#content').empty();
	
	document.getElementById("prev").disabled = true;
	document.getElementById("next").disabled = true;

	var url_val = $('#hidden').val()+'/edunexus/AdminManager/showNGO';
	$.ajax({
			url: url_val,
			method: 'post',
			dataType: 'json',
			data: {
				offset: offset_val,
				limit: limit_val
			},
			success: function(response){
				console.log(response);
				//console.log(response.volunteer[0]['id']);
				if(response.success==="true"){
					setCountTextAndButtons(response.count);
					for(var x=0;x<response.count;x++){
						var id = response[x]['id'];
						var name = response[x]['name'];
						var username = response[x]['username'];
						var phoneno = response[x]['phoneno'];
						var address = response[x]['address'];
						ngoElement(id,name,username,phoneno,address);
					}
				}else if(response.errorCode==="1"){
					callError(response.errorCode);
				}
			}
		});
}

/**
 * Count previous and next answer(s)
 * 
 * @param {int} count
 * @returns {void}
 */
function setCountTextAndButtons(count){
	var offset = offset_val;

	if(offset!=0)
		document.getElementById("prev").disabled = false;

	if(count==0){
		$('#count_text').text('No elements to show');
	}else{
		$('#count_text').text("Showing "+(offset+1)+" to "+ (offset+count));
		if(count==limit_val)
			document.getElementById("next").disabled = false;
	}
}

