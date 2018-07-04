/**
 *@author Sandeep Khan
 */

var vol_id = -1;
var offset_val = 0;
var limit_val = 10;

/**
 * Matches password and confirm password fields value
 * @returns {Boolean} true if password and confirm password are same and false otherwise
 */
function matchPassword(){
	if(document.getElementById('password').value.trim()==document.getElementById('confirmpassword').value.trim())
		return true;
	return false;
}

/**
 * Validates an email id.
 * @param {String} email Email id   
 * @returns {Boolean} true if email is in valid format and false otherwise
 */
function validateEmail(email) {
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

/**
 * Decrements the offset_val by limit_val and loads detail of all volunteers
 * of a NGO 
 * @returns {undefined}
 */
function previous(){
	offset_val = offset_val - limit_val;
	loadVolunteers();
}

/**
 * Increments the offset_val by limit_val and loads detail of all volunteers
 * of a NGO 
 * @returns {undefined}
 */
function next(){
	offset_val += limit_val;
	loadVolunteers();
}

/**
 * Validates modal fields and sends data to server using ajax request to register a
 * new volunteer or update the details of an existing volunteer.
 * If vol_id is -1 it makes a request to register a new volunteer and updates detail
 * of an existing volunteer for other value of vol_id. 
 * @returns {Boolean} false if modal fields are  invalid
 */
function submit(){
	console.log(vol_id);

	var name = $('#name').val().trim();
	var username = $('#username').val().trim();
	var password  = $('#password').val().trim();
	var address  = $('#address').val().trim();
	var phoneno  = $('#phoneno').val().trim();

	if(!String_isName(name, false, false) && !String_isName(name, true, false) ){
		alert("Invalid Name Format");
		return false;
	}
	if(!String_isPhoneno(phoneno,"XXXXXXXXXX")){
		alert("Invalid Phone Number Format");
		return false;
	}
	if(address.length<1){
		alert("Address required!");
		return false;
	}
	
	if(vol_id==-1){
			if(!String_isDefaultPassword(password,1)){
					alert("Invalid Password Format");
					return false;
			}
			if(!matchPassword()){
				alert("Password and Confirmed Password Mismatch");
				return false;
			}
			if(!validateEmail(username)){
				alert("Invalid Email Format");
				return false;
		}
	}
	


	var obj = {};
	obj.name = name;
	obj.address = address;
	obj.phoneno = phoneno;

	var url_val = $('#hidden').val()+"/edunexus/NGOManager";
	if(vol_id==-1){
		//add volunter
		obj.password = password;
		obj.username = username;
		$.ajax({
			url: url_val+'/addVolunteer',
			method: 'post',
			dataType: 'json',
			data: obj,
			success: function(response){
				console.log(response);

				//reload the content part using ajax
				if(response.success==="true"){
					loadVolunteers();
					$('#addUserModal').modal('hide');
				}else if(response.errorCode=="1"){
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
			url: url_val+'/updateVolunteer',
			method: 'post',
			dataType: 'json',
			data: obj,
			success: function(response){
				console.log(response);

				if(response.success==="true"){
					$('#addUserModal').modal('hide');
					loadVolunteers();
				}else if(response.errorCode==="6"){
					$('#addUserModal').modal('hide');
				}else if(response.errorCode==="1"){
					callError("1");
				}
			}
		});
		vol_id = -1;
	}
}

/**
 * Open modal to fill in the fields required to send details to server
 * to update a volunteer
 * @param {Object} val Update button
 * @returns {undefined}
 */
function updateVolunteer(val){

	$('#myModalLabel').text('Update Volunteer');
	$('#add-user-btn').text('Update Volunteer');

	vol_id = $(val).closest('div').attr('id');//div id extraction
	var p4 = $('#'+vol_id).parent();
	var siblings = $(p4).siblings();
	
	$('#password').hide();
	$('#confirmpassword').hide();
	$('#username').hide();

	$('#input[type="text"]').val('');
	$('#addUserModal').modal('show');
	
	$('#name').val($(siblings[0]).text());
	
	$('#phoneno').val(($(siblings[2]).text()).substring(11));
	$('#address').val(($(siblings[3]).text()).substring(9));

}

/**
 * Deletes a volunteer by sending an ajax request to server
 * @param {Object} val Delete button
 * @returns {undefined}
 */
function deleteVolunteer(val){
	if(confirm("Are you sure you want to delete the volunteer?")){
			var vol_id = $(val).closest('div').attr('id');
			var url_val = $('#hidden').val()+"/edunexus/NGOManager";
			$.ajax({
					url: url_val+'/deleteVolunteer',
					method: 'post',
					dataType: 'json',
					data: {id:vol_id},
					success: function(response){
						console.log(response);

						if(response.success==="true")
							loadVolunteers();
						else if(response.errorCode==="1"){
							callError(response.errorCode);
						}
					}
			});
	}
}

/**
 * Open modal to fill in the fields required to send details to server
 * to register a new volunteer
 * @param {Object} val Add Volunteer button
 * @returns {undefined}
 */
function addUser(){
	vol_id = -1;
	$('#password').show();
	$('#confirmpassword').show();
	$('#username').show();
	$('#myModalLabel').text('Add New Volunteer');
	$('#add-user-btn').text('Add Volunteer');
	$('#addUserModal input').val('');
	$('#addUserModal').modal('show');
}

/**
 * Creates a volunteer element
 * @param {Number} id Volunteer Id
 * @param {String} name Volunteer's name
 * @param {String} username Volunteer's username
 * @param {String} phoneno Volunteer's phoneno
 * @param {String} address Volunteer's address
 * @returns {undefined}
 */
function userElement(id,name,username,phoneno,address){
	
	var div2 = $('<div class="thumbnail"></div>');
	var div3 = $('<div class="caption"></div>');
	var h3 = $('<h3>'+name+'</h3>');
	var p1 = $('<p>Email: '+username+'</p>');
	var p2 = $('<p>Phone no.: '+phoneno+'</p>');
	var p3 = $('<p>Address: '+address+'</p>');
	var p4 = $('<p></p>');
	var div4 = $('<div>').attr('id',""+id);
	div4.append('<a class="btn btn-default" role="button" onclick="deleteVolunteer(this)">Delete</a>');
	div4.append('<a class="btn btn-primary" role="button" onclick="updateVolunteer(this)">Update</a>');
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
 * Fetches all volunteers of an NGO from server using ajax request
 * @returns {undefined}
 */
function loadVolunteers(){
	$('#content').empty();

	document.getElementById("prev").disabled = true;
	document.getElementById("next").disabled = true;

	var url_val = $('#hidden').val()+"/edunexus/NGOManager";
	$.ajax({
			url: url_val+'/showVolunteers',
			method: 'post',
			dataType: 'json',
			data: {offset: offset_val},
			success: function(response){
				console.log(response);
				if(response.success==="true"){

					setCountTextAndButtons(response.count);

					for(var x=0;x<response.count;x++){
						var id = response[x]['id'];
						var name = response[x]['name'];
						var username = response[x]['username'];
						var phoneno = response[x]['phoneno'];
						var address = response[x]['address'];
						userElement(id,name,username,phoneno,address);
					}
				}else if(response.errorCode==="1"){
				callError(response.errorCode);
			}
			}
		});
}

/**
 * Displays the number of volunteers fetched from server 
 * @param {Number} count Total number of volunteers fetched from server
 * @returns {undefined}
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
