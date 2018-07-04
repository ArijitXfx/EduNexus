/**
 *@author Sandeep Khan
 */
var limit_val = 10;
var verified_offset_val = 0;
var non_verified_offset_val = 0;
var showing_verified = true;

/**
 * Sets the value of showing_verified variable and loads the teachers
 * @param {Boolean} val true or false
 * @returns {undefined}
 */
function showVerifiedTeachers(val){
	if(val){
		showing_verified = true;
		// $('#heading').text('Verified Teachers');
	}
	else{
		showing_verified = false;
		// $('#heading').text('Non Verified Teachers');
	}

	loadTeachers();
}

/**
 * Decrements verified or non verified offset_val by limit val
 * and loads teacher
 * @returns {undefined}
 */
function previous(){
	if(showing_verified){
		verified_offset_val = verified_offset_val - limit_val;
	}else{
		non_verified_offset_val = non_verified_offset_val - limit_val;
	}

	loadTeachers();
}

/**
 * Increments verified or non verified offset_val by limit val
 * and loads teacher
 * @returns {undefined}
 */
function next(){
	if(showing_verified){
		verified_offset_val = verified_offset_val + limit_val;
	}else{
		non_verified_offset_val = non_verified_offset_val + limit_val;
	}

	loadTeachers();
}

/**
 * A wrapper function which calls loadVerifiedTeachers() if showing_verified is true
 * and loadNonVerifiedTeachers() if showing_verified is false
 * @returns {undefined}
 */
function loadTeachers(){
	//disabling prev and next buttons
	document.getElementById("prev").disabled = true;
	document.getElementById("next").disabled = true;

	if(showing_verified){
		loadVerifiedTeachers();
	}else{
		loadNonVerifiedTeachers();
	}

}

/**
 * Fetches detail of verified teachers from server using ajax request
 * @returns {undefined}
 */
function loadVerifiedTeachers(){
	$('#verified-content').empty();
	var url_val = $('#hidden1').val()+"/edunexus/NGOManager";
	$.ajax({
			url: url_val+'/showVerifiedTeachers',
			method: 'post',
			dataType: 'json',
			data: {
				offset: verified_offset_val,
				limit: limit_val
			},
			success: function(response){
				console.log(response);
				if(response.success === 'true'){
					setCountTextAndButtons(response.count);
					createTeacherElement(response);
				}else if(response.errorCode==="1"){
					callError(response.errorCode);
				}
				}
			});
}

/**
 * Parse response and calls teacherElement()
 * @param {Object} response Response containing teacher details from server
 * @returns {undefined}
 */
function createTeacherElement(response){
	for(var x=0;x<response.count;x++){
		var id = response[x]['id'];
		var username= response[x]['username'];
		var name = response[x]['name'];
		var phoneno = response[x]['phoneno'];
		var address = response[x]['address'];
		var qualification = response[x]['qualification'];
		teacherElement(id,name,username,phoneno,address,qualification);
	}
}

/**
 * Creates teacher element
 * @param {Number} id Teacher id
 * @param {String} name Teacher's name
 * @param {String} username Teacher's email id
 * @param {String} phoneno Teacher's phone number
 * @param {String} address Teacher's address
 * @param {String} qualification Link to download teacher's resume
 * @returns {undefined}
 */
function teacherElement(id,name,username,phoneno,address,qualification){
	var div2 = $('<div class="thumbnail"></div>');
	var div3 = $('<div class="caption"></div>');
	var h3 = $('<h3>'+name+'</h3>');
	var p1 = $('<p>Email: '+username+'</p>');
	var p2 = $('<p>Phone no.: '+phoneno+'</p>');
	var p21 = $('<p><a href="'+$('#hidden').val()+'/'+qualification+'" download>Download Resume</a></p>');
	var p3 = $('<p>Address: '+address+'</p>');
	var p4 = $('<p></p>');
	var div4 = $('<div>').attr('id',""+id);
	div4.append('<a class="btn btn-danger" role="button" onclick="deleteTeacher(this)">Delete</a>');
	if(!showing_verified)
		div4.append('<a class="btn btn-success" role="button" onclick="toggleVerifiedStatus(this)">Verify</a>');
	else
		div4.append('<a class="btn btn-primary" role="button" onclick="toggleVerifiedStatus(this)">Disapprove</a>');
	p4.append(div4);
	div3.append(h3);
	div3.append(p1);
	div3.append(p2);
	div3.append(p21);
	div3.append(p3);
	div3.append(p4);
	div2.append(div3);
	if(showing_verified)
		$('#verified-content').append(div2);
	else
		$('#non-verified-content').append(div2);

}

/**
 * Fetches detail of non verified teachers from server using ajax request
 * @returns {undefined}
 */
function loadNonVerifiedTeachers(){
	$('#non-verified-content').empty();
	var url_val = $('#hidden1').val()+"/edunexus/NGOManager";
	$.ajax({
			url: url_val+'/showNonVerifiedTeachers',
			method: 'post',
			dataType: 'json',
			data: {
				offset: non_verified_offset_val,
				limit: limit_val
			},
			success: function(response){
				console.log(response);
				if(response.success === 'true'){
					setCountTextAndButtons(response.count);
					createTeacherElement(response);
				}else if(response.errorCode==="1"){
					callError(response.errorCode);
				}
			}
		});
}

/**
 *Displays the number of teachers fetched from server 
 * @param {Number} count Total number of teacher fetched from server
 * @returns {undefined}
 */
function setCountTextAndButtons(count){
	if(showing_verified)
		var offset = verified_offset_val;
	else
		var offset = non_verified_offset_val;

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

/**
 * Toggle the verified status of a teacher via ajax request
 * @param {Object} val Verify or Disprove button
 * @returns {undefined}
 */
function toggleVerifiedStatus(val){
	var vol_id = $(val).closest('div').attr('id');
	console.log(vol_id);
	
	var obj={};
	obj.id = vol_id;
	if(showing_verified)
		obj.verified = 0;
	else
		obj.verified = 1;

	var url_val = $('#hidden1').val()+"/edunexus/NGOManager";
	$.ajax({
			url: url_val+'/changeVerifiedStatus',
			method: 'post',
			dataType: 'json',
			data: obj,
			success: function(response){
				console.log(response);
				if(response.success==='true')
					loadTeachers();
				else if(response.errorCode==="1")
						callError(response.errorCode);
			}
		});
}

/**
 * Deletes a teacher by making ajax request
 * @param {Object} val Delete Button
 * @returns {undefined}
 */
function deleteTeacher(val){
	if(!confirm("Are you sure you want to delete?"))
		return;
	var vol_id = $(val).closest('div').attr('id');
	console.log(vol_id);
	var url_val = $('#hidden1').val()+"/edunexus/NGOManager";
	$.ajax({
			url: url_val+'/deleteTeacher',
			method: 'post',
			dataType: 'json',
			data: {id:vol_id},
			success: function(response){
				console.log(response);
				if(response.success==='true')
					loadTeachers();
				else if(response.errorCode==="1")
					callError(response.errorCode);
			}
		});
}
