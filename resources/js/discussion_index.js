/**
* Discussion Portal Manager manage Discussion Portal and Discussion Thread
* 
* @author Arijit Basu <thearijitxfx@gmail.com>
* 
* version 1.0
* 
*/

var offset_val = 0;
var limit_val = 10;
var discussion_id = -1;

var keyword_filter="";

/**
 * load previous answers
 * 
 * @returns void
 */
function previous(){
	offset_val = offset_val - limit_val;
	loadQuestion(true);
}

/**
 * load next answers
 * 
 * @returns {void}
 */
function next(){
	offset_val += limit_val;
	loadQuestion(true);
}


/**
 * Set question(s)
 * 
 * @param {string} title
 * @param {string} description
 * @param {int} discussion_id
 * @param {string} duplicateLink
 * @returns {void}
 */
function questionElement(title,description,discussion_id,duplicateLink){

	var div2 = $('<div class="thumbnail"></div>');
	var div3 = $('<div class="caption"></div>');
	var h3 = $('<h3>'+title+'</h3>');
	var p1 = $('<p>'+description+'</p>');
	var p2 = $('<p></p>');
	var div4 = $('<div></div>');
	var usertype = $('#usertype').val();

	if(duplicateLink != ""){
		div2.addClass('duplicate');
		div4.append('<a class="btn btn-success" href="'+duplicateLink+'">Original Question</a>');
	}else{
		div4.append('<a class="btn btn-success" role="button" onclick="viewPage('+discussion_id+')">View Answers</a>');
	}

	if(usertype=="teacher"){
		if(duplicateLink == ""){
			div4.append('<a class="btn btn-primary" role="button" onclick="callDuplicateModal('+discussion_id+');">Add Duplicate Link</a>');
		}else{
			div4.append('<a class="btn btn-danger" role="button" onclick="cancelDuplicate('+discussion_id+');">Undo Duplicate</a>');
		}
	}

	p2.append(div4);
	div3.append(h3);
	div3.append(p1);
	div3.append(p2);
	div2.append(div3);
	$('#question').append(div2);
}

function viewPage(id){
  var input = document.createElement("input");
  input.setAttribute("type", "hidden");
  input.setAttribute("name", "id");
  input.setAttribute("value",id);

  var myForm = document.createElement("form");
  myForm.setAttribute('method', "get");
  myForm.setAttribute('action', $('#hidden').val()+"/edunexus/DiscussionPortalManager/view");
  myForm.appendChild(input);
  document.body.appendChild(myForm);
  myForm.submit();
}

/**
 * show modal for write duplicate link
 * 
 * @param {int} id
 * @returns {void}
 */
function callDuplicateModal(id){
	$('#addDuplicateModal').modal('show');
	$('#addlink').val('');
	discussion_id = id;
}

/**
 * remove duplicate link
 * 
 * @param {int} discussion_id
 * @returns {void}
 */
function cancelDuplicate(discussion_id){
	var url_val = $('#hidden').val()+'/edunexus/DiscussionPortalManager/cancelDuplicateLink';
	$.ajax({
		url: url_val,
		method: 'post',
		dataType: 'json',
		data: {
			id:discussion_id
		},
		success: function(response){
		console.log(response);
		if(response.success==="true"){
			loadQuestion();
			}else if(response.errorCode==="1"){
				callError(response.errorCode);
			}
		}
	});
}

/**
 * Add duplicate link
 * 
 * @returns {Boolean}
 */
function setDuplicate(){
	var duplicateLink = $('#addlink').val();
	if(duplicateLink.length<=0){
		alert('Provide a link');
		return false;
	}
	var url_val = $('#hidden').val()+'/edunexus/DiscussionPortalManager/setDuplicateLink';

	$.ajax({
		url: url_val,
		method: 'post',
		dataType: 'json',
		data: {
			id:discussion_id,
			duplicatelink:duplicateLink
		},
		success: function(response){
			console.log(response);
			if(response.success==="true"){
				loadQuestion();
					$("#addDuplicateModal").modal('hide');
			}else if(response.errorCode==="1"){
					callError(response.errorCode);
			}
		}
	});
}

/**
 * Open modal for create a new question
 * 
 * @returns {void}
 */
function createThread(){
	tinymce.get('description').setContent('');
	$('#createThreadModal').modal('show');
	$('#title').val('');
	$('#description').val('');
}

/**
 * Load all question
 * 
 * @returns {void}
 */
function load(){
	var usertype = $('#usertype').val();
	if(usertype==="teacher"){
		$('#threadButton').hide();
	}
	loadQuestion();
}

/**
 * 
 * post a created question
 * 
 * @returns {Boolean}
 */
function postThread(){
	var title_val = $('#title').val();
	var desc = tinymce.get('description').getContent();	
	//validate form
	if(title_val.length<1){
		alert("Title required!");
		return false;
	}

	if(desc.length<1){
		alert("description required!");
		return false;
	}
	//console.log(title_val+" "+desc);

	var url_val = $('#hidden').val()+'/edunexus/DiscussionPortalManager/create';
	$.ajax({
			url: url_val,
			method: 'post',
			dataType: 'json',
			data: {
				title:title_val,
				description:desc
			},
			success: function(response){
				console.log(response);
				if(response.success==="true"){
					loadQuestion();
					$("#createThreadModal").modal('hide');
				}else if(response.errorCode==="1"){
					callError(response.errorCode);
				}
			}
		});
}

/**
 * 
 * @param {boolean} skip
 * @returns {void}
 */
function loadQuestion(skip=false){
	$('#question').empty();

	if(!skip)
		fillFilterVariables();

	document.getElementById("prev").disabled = true;
	document.getElementById("next").disabled = true;

	var dataObj={};
	dataObj.offset = offset_val;
	dataObj.limit = limit_val;
	dataObj.all = true;

	if(keyword_filter.length>0)
		dataObj.keyword = keyword_filter;
	
	var url_val = $('#hidden').val()+'/edunexus/DiscussionPortalManager/getDiscussion';
	$.ajax({
			url: url_val,
			method: 'post',
			dataType: 'json',
			data:dataObj,
			success: function(response){
				console.log(response);
				if(response.success==="true"){
					
					setCountTextAndButtons(response.count);

					for(var x=0;x<response.count;x++){
						var title = response[x]['title'];
						var description = response[x]['description'];
						var discussion_id = response[x]['id'];
						var duplicateLink = response[x]['duplicate'];
						questionElement(title,description,discussion_id,duplicateLink);
					}
				}else if(response.errorCode==="1"){
					callError(response.errorCode);
				}
			}
		});
}

/**
 * Filter with searching keyword
 * 
 * @returns {void}
 */
function fillFilterVariables(){
	var keyword = $('#keyword').val().trim();
	if(keyword!=keyword_filter){
		keyword_filter = keyword;
		offset_val = 0;
	}
}

/**
 * set a text based on search result
 * 
 * @returns {void}
 */
function setSearchAndFilterText(){
	var searchText="";
	if(keyword_filter.length>0){
		searchText = "Showing all questions with title and descrption containing "+keyword_filter;
	}else{
		searchText = "Showing all  questions ";
	}
	console.log(searchText);
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