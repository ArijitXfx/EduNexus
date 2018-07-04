/**
 *@author Sandeep Khan
 */

var offset_val = 0;
var limit_val = 10;
var response_obj={};

var keyword_filter="";
var board_filter ="none";
var standard_filter ="none";
var subject_filter ="none";
var language_filter ="none";



/**
 * Validates the title value passed. A valid title is one
 * which must start with a uppercase alphabet
 * @param {String} title Course title
 * @returns {Boolean} true if title is valid and false otherwise
 */
function checkTitle(title){
	var re=new RegExp("^[A-Z]{1}([a-z0-9 _.,?:;\(\)^%-+=\{\}])*$");
	return re.test(title); 
} 

/**
 * Fetches all board, language and subject options from server using ajax request.
 * It then fetches required courses of an NGO using ajax request
 * @returns {undefined}
 */
function load(){
	$('#keyword').val('');
	$('#board-filter').find('option').not(':first').remove();
	$('#subject-filter').find('option').not(':first').remove();
	$('#language-filter').find('option').not(':first').remove();

	var url_val = $('#hidden').val()+"/edunexus/NGOManager";
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

	loadRequiredCourses();
}

/**
 * Decrements the offset_val by limit_val and reloads courses
 * @returns {undefined}
 */
function previous(){
	offset_val = offset_val - limit_val;
	loadRequiredCourses(true);
}

/**
 * Increments the offset_val by limit_val and reloads courses
 * @returns {undefined}
 */
function next(){
	offset_val += limit_val;
	loadRequiredCourses(true);
}

/**
 * Opens modal to fill the details of a new course requirement of NGO
 * @returns {undefined}
 */
function showForm(){
	//vol_id = -1;
	$('#subject').find('option').not(':first').remove();
	$('#language').find('option').not(':first').remove();
	$('#board').find('option').not(':first').remove();

	var url_val = $('#hidden').val()+"/edunexus/NGOManager";
	$.ajax({
		url: url_val+'/fillBoard',
		method: 'post',
		dataType: 'json',
		success: function(response){
			console.log(response);
			if(response.success==="true"){
				for(var x=0;x<response.count;x++){
						$("#board").append('<option value='+response[x]['board']+'>'+response[x]['board']+'</option>');
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
				//fill language
				for(var x=0;x<response.count;x++){
						$("#language").append('<option value='+response[x]['language']+'>'+response[x]['language']+'</option>');
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
				//fill language
				for(var x=0;x<response.count;x++){
						$("#subject").append('<option value='+response[x]['subject']+'>'+response[x]['subject']+'</option>');
				}
			}else if(response.errorCode==="1"){
				callError(response.errorCode);
			}
		}
	});

	$('#addRequiredCourseModal input').val('');
	$('#addRequiredCourseModal textarea').val('');
	tinymce.get('requirement').setContent('');
	$('#standard').val('none');
	$('#addRequiredCourseModal').modal('show');
}

/**
 * Checks if the requirement entered in modal is valid or not.
 * Any requirement whose length is greater than 0 is valid. 
 * @param {String} requirement Course requirement
 * @returns {Boolean} true if requirement is valid and false if it's invalid
 */
function validateRequirement(requirement){
		if(requirement.length>0)
				return true;
		return false;
}

/**
 * Validates the value passed
 * @param {String} value Select option value
 * @returns {Boolean} true if value is not "none" and false otherwise
 */
function validateSelect(value){
		if(value=="none")
				return false;
		return true;
}

/**
 * Validates title, requirement, language, subject, standard and
 * board fields of the modal and sends valid details to server
 * using ajax request.
 * @returns {Boolean} false if fields in modal is invalid
 */
function submit(){

	var title = $('#title').val().trim();
	var requirement = tinymce.get('requirement').getContent().trim();
	var language  = $('#language').val().trim();
	var subject  = $('#subject').val().trim();
	var standard  = $('#standard').val().trim();
	var board  = $('#board').val().trim();
	
	//validate form
	if(!checkTitle(title)){
			alert('Invalid Title');
			return false;
	}

	if(!validateRequirement(requirement)){
			alert('Invalid Requirement');
			return false;
	}

	if(!validateSelect(board)){
			alert('Invalid Board');
			return false;
	}


	if(!validateSelect(language)){
			alert('Invalid language');
			return false;
	}
	if(!validateSelect(subject)){
			alert('Invalid subject');
			return false;
	}
	if(!validateSelect(standard)){
			alert('Invalid standard');
			return false;
	}

	var obj = {};
	obj.title = title;
	obj.requirement = requirement;
	obj.subject = subject;
	obj.standard = standard;
	obj.language = language;
	obj.board = board;
		//add course requirement
	var url_val = $('#hidden').val()+"/edunexus/NGOManager";
	$.ajax({
			url: url_val+'/addRequiredCourse',
			method: 'post',
			dataType: 'json',
			data: obj,
			success: function(response){
				console.log(response);

				//reload the content part using ajax
				if(response.success==="true"){
					loadRequiredCourses();
					$('#addRequiredCourseModal').modal('hide');
				}else if(response.errorCode==="1"){
				callError(response.errorCode);
			}
			}
		});
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
		offset_val = 0;
	}
	if(board!=board_filter){
		board_filter = board;
		offset_val = 0;
	}
	if(language!=language_filter){
		language_filter = language;
		offset_val = 0;
	}
	if(standard!=standard_filter){
		standard_filter = standard;
		offset_val = 0;
	}
	if(subject!=subject_filter){
		subject_filter = subject;
		offset_val = 0;
	}

}

/**
 * Fetches required courses posted by a NGO from server using ajax request
 * @param {Boolean} skip If true it skips calling fillFilterVariables() else callsfillFilterVariables()
 * @returns {undefined}
 */
function loadRequiredCourses(skip=false){
	response_obj={};

	$('#content').empty();

	if(!skip)
		fillFilterVariables();

	document.getElementById("prev").disabled = true;
	document.getElementById("next").disabled = true;

	var dataObj={};
	dataObj.offset = offset_val;
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


	var url_val = $('#hidden').val()+"/edunexus/NGOManager";
	
	$.ajax({
			url: url_val+'/showRequiredCourses',
			method: 'post',
			dataType: 'json',
			data: dataObj,
			success: function(response){
				console.log(response);
				//console.log(response.volunteer[0]['id']);

				if(response.success==="true"){
					$('#filterModal').modal('hide');
					setSearchAndFilterText();

					setCountTextAndButtons(response.count)

					for(var x=0;x<response.count;x++){
						var id = response[x]['id'];
						var title = response[x]['title'];
						var subject = response[x]['subject'];
						var standard = response[x]['standard'];
						var board = response[x]['board'];
						var language = response[x]['language'];
						response_obj[id+""] =response[x]['requirement']; 
						courseElement(id,title,subject,standard,board,language);
					}
				}else if(response.errorCode==="1"){
					callError(response.errorCode);
				}
			}
		});
}

function setSearchAndFilterText(){
	var searchText="";
	if(keyword_filter.length>0){
		searchText = "Showing all Required courses with title containing "+keyword_filter;
	}else{
		searchText = "Showing all Required courses";
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
 * Displays the number of required courses, posted by an NGO, fetched from server 
 * @param {Number} count Total number of required courses, posted by an NGO, fetched from server
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

/**
 * Creates a required course element
 * @param {Number} id Id of required course
 * @param {String} title  Title of course
 * @param {String} subject Subject name 
 * @param {String} standard Standard name
 * @param {String} board Board name
 * @param {String} language Required course language
 * @returns {undefined}
 */
function courseElement(id,title,subject,standard,board,language){
	var div1 = $('<div class="col-sm-6 col-md-4"></div>');
	var div2 = $('<div class="thumbnail"></div>');
	var div3 = $('<div class="caption"></div>');
	var h3 = $('<h3>'+title+'</h3>');
	var p1 = $('<p>Subject: '+subject+'</p>');
	var p2 = $('<p>Standard: '+standard+'</p>');
	var p3 = $('<p>Board: '+board+'</p>');
	var p4 = $('<p>Language: '+language+'</p>');
	var p5 = $('<p></p>');
	var div4 = $('<div>').attr('id',""+id);
	div4.append('<a class="btn btn-default" role="button" onclick="deleteRequiredCourse(this)">Delete</a>');
	div4.append('<a class="btn btn-primary" role="button" onclick="showRequirement('+id+')">See Requirement</a>');
	p5.append(div4);
	div3.append(h3);
	div3.append(p1);
	div3.append(p2);
	div3.append(p3);
	div3.append(p4);
	div3.append(p5);
	div2.append(div3);
	div1.append(div2);
	$('#content').append(div1);
}

/**
 * Displays modal to show course requirement
 * @param {Number} id Index for response_obj variable
 * @returns {undefined}
 */
function showRequirement(id){
	document.getElementById('show-requirement').innerHTML = response_obj[id];
	$('#requirementModal').modal('show');
}

/**
 * Deletes the required course by sending an ajax request to server
 * @param {Object} val Delete button
 * @returns {undefined}
 */
function deleteRequiredCourse(val){
	if(!confirm("Are you sure you want to delete?"))
		return;
	var vol_id = $(val).closest('div').attr('id');
	var url_val = $('#hidden').val()+"/edunexus/NGOManager";
	$.ajax({
			url: url_val+'/deleteRequiredCourse',
			method: 'post',
			dataType: 'json',
			data: {id:vol_id},
			success: function(response){
				console.log(response);

				if(response.success==="true")
					loadRequiredCourses(true);
				else if(response.errorCode==="1"){
					callError(response.errorCode);
				}
			}
		});
}
