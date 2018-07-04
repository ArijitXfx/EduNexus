/**
 *@author Sandeep Khan
 */
var response_obj = {}; //to  fill requirement modal
var offset_val = 0;
var id_val = -1;
var limit_val = 10;

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
 * Fetches all board, language and subject options from server using ajax request.
 * It then fetches required courses using ajax request
 * @returns {undefined}
 */
function load(){
	$('#keyword').val('');
	$('#board-filter').find('option').not(':first').remove();
	$('#subject-filter').find('option').not(':first').remove();
	$('#language-filter').find('option').not(':first').remove();

	var url_val = $('#hidden').val()+"/edunexus/TeacherManager";
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
 * Fetches courses required by NGO from server using ajax request
 * @param {Boolean} skip If true it skips calling fillFilterVariables() else callsfillFilterVariables()
 * @returns {undefined}
 */
function loadRequiredCourses(skip=false){

	$('#content').empty();

	document.getElementById("prev").disabled = true;
	document.getElementById("next").disabled = true;

	response_obj={};

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


	var url_val = $('#hidden').val()+"/edunexus/TeacherManager";

	$.ajax({
			url: url_val+'/showRequiredCourses',
			method: 'post',
			dataType: 'json',
			data:dataObj,
			success: function(response){
				console.log(response);
				
				if(response.success==="true"){
					$('#filterModal').modal('hide');
					setCountTextAndButtons(response.count);
					setSearchAndFilterText();
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
		searchText = "Showing all required courses with title containing "+keyword_filter;
	}else{
		searchText = "Showing all required courses";
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
 * @param {String} subject Subject name 
 * @param {String} standard Standard name
 * @param {String} board Board name
 * @param {String} language Course language
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
	div4.append('<a class="btn btn-success" role="button" onclick="uploadMaterials(this)">Upload</a>');
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
 * Displays the number of required courses fetched from server 
 * @param {Number} count Total number of required courses fetched from server
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
 * Shows modal to upload materials for a required course.
 * It also fills read only input tags with appropriate valuse.
 * @param {Object} val Update button
 * @returns {undefined}
 */
function uploadMaterials(val){
	id_val = $(val).closest('div').attr('id');
	var siblings = $(val).closest('div').parent().siblings();
	
	var title = $(siblings[0]).text();
	var subject = ($(siblings[1]).text()).substring("Subject: ".length);
	var standard = ($(siblings[2]).text()).substring("Standard: ".length);
	var board = ($(siblings[3]).text()).substring("Board: ".length);
	var language = ($(siblings[4]).text()).substring("Language: ".length);

	tinymce.get('description').setContent('');
	$('#description').val('');
	$('#video').val('');
	document.getElementById('qabank').value="";
	$('#title').val(title);
	$('#subject').val(subject);
	$('#standard').val(standard);
	$('#board').val(board);
	$('#language').val(language);

	$('#courseModal').modal('show');
}

/**
 * Validates video link, Q&A bank and description and sends valid details to server
 * using ajax request.
 * @returns {Boolean} false if video link, Q&A bank and description are invalid 
 */
function submit(){
	if(id_val==-1){
		alert('Select a course first');
		return false;
	}
	var title = $('#title').val();
	var subject  = $('#subject').val();
	var standard  = $('#standard').val();
	var board  = $('#board').val();
	var description = tinymce.get('description').getContent();
	var qabank = document.getElementById('qabank').files[0];
	var video = $('#video').val();
	var language = $('#language').val();
	
	//validate description, video and qa 
	if(description.length==0){
		alert('Provide description');
		return false;
	}
	if(qabank==null){
		alert('Add QA bank ');
		return false;	
	}
	if(qabank!=null){
		if(((qabank.size/1024)/1024)>2){
			alert('File size should be less than or equal to 2MB');
			return false;
		}
	}
	var utube = "https://www.youtube.com/watch?v=";
	if(video.substr(0,utube.length)!=utube || video.length<=utube.length ){
		alert('Add valid youtube video link ');
		return false;	
	}

	var form = new FormData();
	form.append('title',title);
	form.append('subject',subject);
	form.append('standard',standard);
	form.append('board',board);
	form.append('language',language);
	form.append('description',description);
	form.append('qabank',qabank);
	form.append('video',video);
	form.append('req_id',id_val);
	//console.log('req_id'+id_val);

	//console.log(form.get('title'));
	console.log("sending data");
	var url_val = $('#hidden').val()+"/edunexus/TeacherManager";
		$.ajax({
			url: url_val+'/uploadMaterials',
			method: 'post',
			dataType: 'json',
			processData:false,
			contentType:false,
			data: form,
			success: function(response){
				console.log(response);
				
				if(response.success==="true"){
					$('#courseModal').modal('hide'); 
					id_val=-1;
					alert('Materials have been uploaded. Visit My Courses page to see the status of your upload.');
				}
				else if(response.errorCode=="1")
					callError(response.errorCode);
				else if(response.errorCode=="11"){
					$('#courseModal').modal('hide');
					alert(response.errMsg);
					}
				}
		});
	id_val = -1;
}
