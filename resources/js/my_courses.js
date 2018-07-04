/**
 *@author Sandeep Khan
 */
var verified_offset_val = 0;
var non_verified_offset_val = 0;
var limit_val = 10;
var showing_verified = true;
//var responseobj = {};

var keyword_filter="";
var board_filter ="none";
var standard_filter ="none";
var subject_filter ="none";
var language_filter ="none";

/**
 * Sets the value of showing_verified variable and loads the courses of a teacher
 * @param {Boolean} val true or false
 * @returns {undefined}
 */
function showVerifiedCourses(val){
	if(val){
		showing_verified = true;
		// $('#heading').text('Verified Materials');
	}
	else{
		showing_verified = false;
		// $('#heading').text('Non Verified Materials');
	}

	loadCourses();
}

/**
 * Decrements verified or non verified offset_val by limit val
 * and loads courses
 * @returns {undefined}
 */
function previous(){
	if(showing_verified){
		verified_offset_val = verified_offset_val - limit_val;
	}else{
		non_verified_offset_val = non_verified_offset_val - limit_val;
	}

	loadCourses(true);
}

/**
 * Increments verified or non verified offset_val by limit val
 * and loads courses
 * @returns {undefined}
 */
function next(){
	if(showing_verified){
		verified_offset_val = verified_offset_val + limit_val;
	}else{
		non_verified_offset_val = non_verified_offset_val + limit_val;
	}

	loadCourses(true);
}

/**
 * Fetches all board, language and subject options from server using ajax request.
 * It then fetches courses of a teacher using ajax request
 * @returns {undefined}
 */
function load(){
	$('#keyword').val('');
	$('#board-filter').find('option').not(':first').remove();
	$('#subject-filter').find('option').not(':first').remove();
	$('#language-filter').find('option').not(':first').remove();

	var url_val = $('#hidden1').val()+"/edunexus/TeacherManager";
	$.ajax({
		url: url_val+'/fillBoard',
		method: 'post',
		dataType: 'json',
		success: function(response){
			console.log(response);
			if(response.success==="true"){
				for(var x=0;x<response.count;x++){
						$("#board-filter").append('<option value='+response[x]['board']+'>'+response[x]['board']+'</option>');
				}
			}else if(response.errorCode==="1"){
				callError(response.errorCode);
			}
		}
	});

	$.ajax({
		url: url_val+'/fillLanguage',
		method: 'post',
		dataType: 'json',
		success: function(response){
			console.log(response);
			if(response.success==="true"){
				for(var x=0;x<response.count;x++){
						$("#language-filter").append('<option value='+response[x]['language']+'>'+response[x]['language']+'</option>');
				}
			}else if(response.errorCode==="1"){
				callError(response.errorCode);
			}
		}
	});

	$.ajax({
		url: url_val+'/fillSubject',
		method: 'post',
		dataType: 'json',
		success: function(response){
			console.log(response);
			if(response.success==="true"){
				for(var x=0;x<response.count;x++){
						$("#subject-filter").append('<option value='+response[x]['subject']+'>'+response[x]['subject']+'</option>');
				}
			}else if(response.errorCode==="1"){
				callError(response.errorCode);
			}
		}
	});

	showVerifiedCourses(true);
}

/**
 * Sets the value of global filter variables
 * @returns {undefined}
 */
function fillFilterVariables(){
	var keyword = $('#keyword').val().trim();
	var board = $('#board-filter').val();
	var subject = $('#subject-filter').val();
	var standard = $('#standard-filter').val();
	var language = $('#language-filter').val();

	if(keyword!=keyword_filter){
		keyword_filter = keyword;
		if(showing_verified)
			verified_offset_val = 0;
		else
			non_verified_offset_val = 0;
	}
	if(board!=board_filter){
		board_filter = board;
		if(showing_verified)
			verified_offset_val = 0;
		else
			non_verified_offset_val = 0;
	}
	if(language!=language_filter){
		language_filter = language;
		if(showing_verified)
			verified_offset_val = 0;
		else
			non_verified_offset_val = 0;
	}
	if(standard!=standard_filter){
		standard_filter = standard;
		if(showing_verified)
			verified_offset_val = 0;
		else
			non_verified_offset_val = 0;
	}
	if(subject!=subject_filter){
		subject_filter = subject;
		if(showing_verified)
			verified_offset_val = 0;
		else
			non_verified_offset_val = 0;
	}

}
/**
 * A wrapper function which calls loadVerifiedCourses() if showing_verified is true
 * and loadNonVerifiedCourses() if showing_verified is false1
 * @param {Boolean} skip If true it skips calling fillFilterVariables() else callsfillFilterVariables()
 * @returns {undefined}
 */
function loadCourses(skip = false){
	document.getElementById("prev").disabled = true;
	document.getElementById("next").disabled = true;

	if(!skip)
		fillFilterVariables();

	if(showing_verified){
		loadVerifiedCourses();
	}else{
		loadNonVerifiedCourses();
	}
}

/**
 * Fetches detail of verified courses of a teacher from server using ajax request
 * @returns {undefined}
 */
function loadVerifiedCourses(){
	$('#verified-content').empty();

	var dataObj={};
	dataObj.offset = verified_offset_val;
	dataObj.limit = limit_val;

	if(keyword_filter.length>0)
		dataObj.keyword = keyword_filter;

	if(board_filter!="none")
		dataObj.board = board_filter;
	
	if(standard_filter!="none")
		dataObj.standard = standard_filter;
	
	if(subject_filter!="none")
		dataObj.subject = subject_filter;
	
	if(language_filter!="none")
		dataObj.language = language_filter;


	var url_val = $('#hidden1').val()+"/edunexus/TeacherManager";
	
	$.ajax({
			url: url_val + '/getVerifiedCourses',
			method: 'post',
			dataType: 'json',
			data: dataObj,
			success: function(response){
				console.log(response);

				if(response.success==="true"){
					$('#filterModal').modal('hide');
					setSearchAndFilterText();
					setCountTextAndButtons(response.count);
					createCourseElement(response);
				}else if(response.errorCode==="1"){
					callError(response.errorCode);
				}
			}
		});
}
function setSearchAndFilterText(){
	var searchText="";
	var verified = "non verified";
	if(showing_verified)
		verified = "verified";

	if(keyword_filter.length>0){
		searchText = "Showing all "+ verified+" courses with title containing "+keyword_filter;
	}else{
		searchText = "Showing all  "+ verified+"  courses";
	}

	var filterText="";
	if(board_filter!="none"){
		filterText += "Board= "+board_filter+" ";
	}
	if(standard_filter!="none"){
		filterText += "Standard= "+standard_filter+" ";
	}
	if(language_filter!="none"){
		filterText += "Language= "+language_filter+" ";
	}
	if(subject_filter!="none"){
		filterText += "Subject= "+subject_filter+" ";
	}

	console.log(searchText+"\n"+filterText);
}


/**
 * Parse response and calls courseElement()
 * @param {Object} response Response containing course details from server
 * @returns {undefined}
 */
function createCourseElement(response){
	for(var x=0;x<response.count;x++){
		var id = response[x]['id'];
		var title = response[x]['title'];
		var authorname = response[x]['name'];
		var subject = response[x]['subject'];
		var standard = response[x]['standard'];
		var board = response[x]['board'];
		var language = response[x]['language'];
		courseElement(id,title,authorname,subject,standard,board,language);
	}
}

/**
 * Creates course element
 * @param {Number} id Course id
 * @param {String} title Course title
 * @param {String} authorname Course's author name
 * @param {String} subject Course's subject
 * @param {String} standard Course's subject
 * @param {String} board Course's subject
 * @param {String} language Course's subject
 * @returns {undefined}
 */
function courseElement(id,title,authorname,subject,standard,board,language){
	var url=$('#hidden').val();
	var div1 = $('<div class="col-sm-6 col-md-4"></div>');
	var div2 = $('<div class="thumbnail"></div>');
	var div3 = $('<div class="caption"></div>');
	var h3 = $('<h3>'+title+'</h3>');
	var p01 = $('<p>Author: '+authorname+'</p>'); 
	var p1 = $('<p>Subject: '+subject+'</p>');
	var p2 = $('<p>Standard: '+standard+'</p>');
	var p3 = $('<p>Board: '+board+'</p>');
	var p4 = $('<p>Language: '+language+'</p>');
	var p5 = $('<p></p>');
	var div4 = $('<div>').attr('id',""+id);
        
	div4.append('<a class="btn btn-primary" role="button" onclick="showCourse('+id+')">View Course</a>');	
	p5.append(div4);
	div3.append(h3);
	div3.append(p1);
	div3.append(p01);
	div3.append(p2);
	div3.append(p3);
	div3.append(p4);
	div3.append(p5);
	div2.append(div3);
	div1.append(div2);
	if(showing_verified)
		$('#verified-content').append(div1);
	else
		$('#non-verified-content').append(div1);

}

/**
 * Displays the course page
 * @param {Number} id Course id
 * @returns {undefined}
 */
function showCourse(id){
	var url_val = $('#hidden1').val()+"/edunexus/";
	$.ajax({
			url: url_val+'NGOVolunteerManager/setCourseId',
			method: 'post',
			dataType: 'json',
			data: {
				course_id: id
			},
			success: function(response){
				console.log(response.id==id);
				if(response.id==id){
					window.location = url_val+"TeacherManager/showCoursePage";
				}
			}
		});
}

/**
 * Fetches detail of non verified courses of a teahcer from server using ajax request
 * @returns {undefined}
 */
function loadNonVerifiedCourses(){
	$('#non-verified-content').empty();

	var dataObj={};
	dataObj.offset = non_verified_offset_val;
	dataObj.limit = limit_val;

	if(keyword_filter.length>0)
		dataObj.keyword = keyword_filter;

	if(board_filter!="none")
		dataObj.board = board_filter;
	
	if(standard_filter!="none")
		dataObj.standard = standard_filter;
	
	if(subject_filter!="none")
		dataObj.subject = subject_filter;
	
	if(language_filter!="none")
		dataObj.language = language_filter;


	var url_val = $('#hidden1').val()+"/edunexus/TeacherManager";
	
	$.ajax({
			url: url_val+'/getNonVerifiedCourses',
			method: 'post',
			dataType: 'json',
			data:dataObj,
			success: function(response){
				console.log(response);
				if(response.success==="true"){
					$('#filterModal').modal('hide');
					setSearchAndFilterText();
					setCountTextAndButtons(response.count);
					createCourseElement(response);
				}else if(response.errorCode==="1"){
					callError(response.errorCode);
				}
			}
		});
}

/**
 * Displays the number of courses fetched from server 
 * @param {Number} count Total number of courses fetched from server
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
