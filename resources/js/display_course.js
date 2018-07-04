/**
 *@author Sandeep Khan
 */

var id = -1;

/**
 * Load the details of a course by making ajax request to server.
 * It hides Dicussion Forum button if an NGO access this page or
 * if the course is not verified.
 * @returns {undefined}
 */
function loadCourse(){
	var usertype = $('#hidden-user').val();
	if(usertype=="2")
		$('#forum-btn').hide();
	var url_val = $('#hidden').val()+"/edunexus/NGOVolunteerManager";
	$.ajax({
			url: url_val+'/getRecentCourse',
			method: 'post',
			dataType: 'json',
			success: function(response){
				console.log(response);
				if(response.success=="true"){
					if(response[0]['verified']=="0"){
						$('#forum-btn').hide();
					}
					id = response[0]['id'];
					$('#title').text(response[0]['title']);
					$('#subject').text('Subject : '+response[0]['subject']);
					$('#standard').text('Standard : '+response[0]['standard']);
					$('#language').text('Language : '+response[0]['language']);
					$('#board').text('Board : '+response[0]['board']);
					document.getElementById('description').innerHTML = response[0]['description'];
					document.getElementById("video-player").src = "http://www.youtube.com/embed/"+(response[0]['video']).substring("https://www.youtube.com/watch?v=".length);
					$("#qabank").attr("href", $('#base').val()+"/"+response[0]['qabank']);

					$('#name').text('Author : '+response[0]['name']);
					document.getElementById('author').innerHTML = '<i class="fa fa-user"></i>  '+response[0]['name'];
					document.getElementById('username').innerHTML = '<i class="fa fa-envelope"></i>  '+response[0]['username'];
					document.getElementById('phoneno').innerHTML = '<i class="fa fa-phone"></i>  '+response[0]['phoneno'];
				}else if(response.errorCode=="1"){
					callError(response.errorCode);
				}
			}
		});
}

/**
 * Opens discussion portal by making a post request to server.
 * @returns {undefined}
 */
function accessPortal(){
	if(id==-1)
		return;
	if($('#hidden-user').val()=="3")
		formUrl = $('#hidden').val()+"/edunexus/NGOVolunteerManager/openForum";
	else if($('#hidden-user').val()=="4")
		formUrl = $('#hidden').val()+"/edunexus/TeacherManager/openForum";
	else
		return;
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "course_id");
	input.setAttribute("value",id);

	var myForm = document.createElement("form");
	myForm.setAttribute('method', "post");
	myForm.setAttribute('action', formUrl);
	myForm.appendChild(input);
	document.body.appendChild(myForm);
	myForm.submit();
}