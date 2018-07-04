/**
 *@author Sandeep Khan
 */
var offset_val = 0;
var limit_val = 6;
//var responseobj={};
var keyword_filter="";
var board_filter ="none";
var standard_filter ="none";
var subject_filter ="none";
var language_filter ="none";

/**
 * Decrements the offset_val by limit_val and reloads courses
 * @returns {undefined}
 */
function previous(){
	offset_val = offset_val - limit_val;
	loadAllCourses(true);
}

/**
 * Increments the offset_val by limit_val and reloads courses
 * @returns {undefined}
 */

function next(){
	offset_val += limit_val;
	loadAllCourses(true);	
}

/**
 * Fetches all board, language and subject options from server using ajax request.
 * It then fetches courses using ajax request
 * @returns {undefined}
 */
function load(){
	$('#keyword').val('');
	$('#board-filter').find('option').not(':first').remove();
	$('#subject-filter').find('option').not(':first').remove();
	$('#language-filter').find('option').not(':first').remove();

	var url_val = $('#hidden').val()+"/edunexus/NGOVolunteerManager";
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

	loadAllCourses();
}

/**
 * Displays the number of courses fetched from server 
 * @param {Number} count Total number of courses fetched from server
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
 * Fetches courses from server using ajax request
 * @param {Boolean} skip If true it skips calling fillFilterVariables() else callsfillFilterVariables()
 * @returns {undefined}
 */
function loadAllCourses(skip=false) {
	//responseobj={};
	$('#content').empty();

	document.getElementById("prev").disabled = true;
	document.getElementById("next").disabled = true;
	
	if(!skip)
		fillFilterVariables();

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

	console.log(dataObj);

	var url_val = $('#hidden').val()+"/edunexus/NGOVolunteerManager";
	$.ajax({
			url: url_val+'/showVerifiedCourses',
			method: 'post',
			dataType: 'json',
			data: dataObj,
			success: function(response){
				console.log(response);
				if(response.success==="true"){
					$('#filterModal').modal('hide');

					setCountTextAndButtons(response.count);
					setSearchAndFilterText();
					for(var x=0;x<response.count;x++){
						var id = response[x]['id'];
						var title = response[x]['title'];
						var authorname = response[x]['name'];
						//var video = response[x]['video'];
						var subject = response[x]['subject'];
						var standard = response[x]['standard'];
						var board = response[x]['board'];
						var language = response[x]['language'];
						//var qabank = response[x]['qabank'];
						//responseobj[id+""] = response[x]['description'];
						courseElement(id,title,authorname,subject,standard,board,language);
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
		searchText = "Showing all verified courses with title containing "+keyword_filter;
	}else{
		searchText = "Showing all verified courses";
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
 * Creates a course element
 * @param {Number} id Id of course
 * @param {String} title  Title of course
 * @param {String} authorname Name of the teacher
 * @param {String} subject Subject name 
 * @param {String} standard Standard name
 * @param {String} board Board name
 * @param {String} language Course language
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
	div4.append('<a class="btn btn-primary" role="button" onclick="viewCourse('+id+')">View Course</a>');
	p5.append(div4);
	div3.append(h3);
	div3.append(p1);
	div3.append(p01);
	//div3.append(p02);
	div3.append(p2);
	div3.append(p3);
	div3.append(p4);
	div3.append(p5);
	div2.append(div3);
	div1.append(div2);
	
	$('#content').append(div1);
}

/**
 * Creates a form and makes a post request to server
 * to open discussion portal 
 * @param {Number} val Course id
 * @returns {undefined}
 */

function accessPortal(val){
	var input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", "course_id");
	input.setAttribute("value",val);

	var myForm = document.createElement("form");
	myForm.setAttribute('method', "post");
	myForm.setAttribute('action', $('#hidden').val()+"/edunexus/NGOVolunteerManager/openForum");
	myForm.appendChild(input);
	document.body.appendChild(myForm);
	myForm.submit();
}

/**
 * Opens the course page
 * @param {Number} id Course id
 * @returns {undefined}
 */

function viewCourse(id){
	//var id = $(val).closest('div').attr('id');
	var url_val = $('#hidden').val()+"/edunexus/NGOVolunteerManager";
	$.ajax({
			url: url_val+'/setCourseId',
			method: 'post',
			dataType: 'json',
			data: {
				course_id: id
			},
			success: function(response){
				console.log(response.id==id);
				if(response.id==id){
					window.location = url_val+"/showCoursePage";
				}
			}
		});
}