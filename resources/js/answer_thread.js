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

/**
 * load previous answers
 * 
 * @returns void
 */
function previous(){
	offset_val = offset_val - limit_val;
	loadAnswer();
}

/**
 * load next answers
 * 
 * @returns {void}
 */
function next(){
	offset_val += limit_val;
	loadAnswer();
}

/**
 * Create an answer of a question
 * 
 * @returns {Boolean}
 */
function submitAnswer(){
	var discussion_id = $('#discussion_id').val();
	var url_val = $('#hidden').val()+"/edunexus/DiscussionPortalManager/setAnswers";
	var reply = tinymce.get('writeAnswer').getContent();
	//form validation here
	if(reply.length<1){
		alert("Answer required!");
		return false;
	}

	$.ajax({
			url: url_val,
			method: 'post',
			dataType: 'json',
			data: {
				id:discussion_id,
				answer: reply
			},
			success: function(response){
				console.log(response);
				if(response.success==="true"){
					$('#writeAnswer').val('');
					loadAnswer();
					$("#writeAnswerModal").modal('hide');
				}else if(response.errorCode==="1"){
					callError(response.errorCode);
				}
			}
		});
	
}

/**
 * Open writeAnswer modal to write answer
 * 
 * @returns {void}
 */
function writeAnswer(){
	tinymce.get('writeAnswer').setContent('');
	$('#writeAnswerModal').modal('show');
	$('#writeAnswer').val('');
}

/**
 * Set Question
 * 
 * @param {string} title
 * @param {string} description
 * @returns {void}
 */
function questionElement(title,description){
	$('#question').append('<h2>'+title+'</h2>');
	$('#question').append('<p>'+description+'</p>');
}

/**
 * Load question and answer
 * 
 * @returns {void}
 */
function loadQuestionAndAnswer(){
	loadQuestion();
	loadAnswer();
}

/**
 * Set answer(s) with its upvote(s) and downvote(s)
 * 
 * @param {int} id
 * @param {string} name
 * @param {string} reply
 * @param {int} upvote
 * @param {int} downvote
 * @param {boolean} isUpvote
 * @returns {void}
 */
function answerElement(id,name,reply,upvote,downvote,isUpvote){
	var div2 = $('<div class="thumbnail"></div>');
	var div3 = $('<div class="caption"></div>');
	var h3 = $('<h3>'+name+'</h3>');
	var p1 = $('<p>'+reply+'</p>');
	var p2 = $('<p></p>');
	var div4 = $('<div id="'+id+'"></div>');
	var downvoteLink = $('<a class="btn" role="button" onclick=setUpVoteDownVote(this,"downvote");><i class="fa fa-thumbs-down"></i> '+downvote+'</a>');
	var upvoteLink = $('<a class="btn" role="button" onclick=setUpVoteDownVote(this,"upvote");><i class="fa fa-thumbs-up"></i> '+upvote+'</a>');
	div4.append(downvoteLink);
	div4.append(upvoteLink);
	console.log(isUpvote==null);

	if(isUpvote==null){
		upvoteLink.addClass('btn-default');
		downvoteLink.addClass('btn-default');
	}else if(isUpvote=="1"){
		upvoteLink.addClass('btn-success');
		downvoteLink.addClass('btn-default');
	}else{
		upvoteLink.addClass('btn-default');
		downvoteLink.addClass('btn-danger');
	}

	p2.append(div4);
	div3.append(h3);
	div3.append(p1);
	div3.append(p2);
	div2.append(div3);
	$('#answers').append(div2);
}


/**
 * Set upvote and downvote
 * 
 * @param {html object} val
 * @param {string} type
 * @returns {void}
 */
function setUpVoteDownVote(val,type){
	var thread_id = $(val).closest('div').attr('id');
	var url_val;
	if(type==="upvote"){
		var downvote_val = $(val).siblings('a')[0];
		var upvote_val = val;
		url_val = $('#hidden').val()+'/edunexus/DiscussionPortalManager/upvote';
	}else{
		var upvote_val = $(val).siblings('a')[0];
		//console.log($(upvote_val).text());
		var downvote_val = val;
		url_val = $('#hidden').val()+'/edunexus/DiscussionPortalManager/downvote';
	}
	//return;
	$.ajax({
			url: url_val,
			method: 'post',
			dataType: 'json',
			data: {
				id:thread_id,
				upvote: $(upvote_val).text().trim(),
				downvote:  $(downvote_val).text().trim()
			},
			success: function(response){
				console.log(response);
				if(response.success==="true"){
					upvote_val.innerHTML = '<i class="fa fa-thumbs-up"></i> '+response.upvote;
					downvote_val.innerHTML = '<i class="fa fa-thumbs-down"></i> '+response.downvote;
					if($(upvote_val).hasClass('btn-default') && $(downvote_val).hasClass('btn-default')){
						if(type=="upvote"){
							$(upvote_val).toggleClass('btn-success');
							$(upvote_val).toggleClass('btn-default');
						}else{
							$(downvote_val).toggleClass('btn-danger');
							$(downvote_val).toggleClass('btn-default');
						}
					}else if($(upvote_val).hasClass('btn-success') && $(downvote_val).hasClass('btn-default')){
						if(type=="upvote"){
							$(upvote_val).toggleClass('btn-success');
							$(upvote_val).toggleClass('btn-default');
						}else{
							$(downvote_val).toggleClass('btn-danger');
							$(downvote_val).toggleClass('btn-default');
							$(upvote_val).toggleClass('btn-success');
							$(upvote_val).toggleClass('btn-default');
						}
					}else if($(upvote_val).hasClass('btn-default') && $(downvote_val).hasClass('btn-danger')){
						if(type=="upvote"){
							$(upvote_val).toggleClass('btn-success');
							$(upvote_val).toggleClass('btn-default');
							$(downvote_val).toggleClass('btn-danger');
							$(downvote_val).toggleClass('btn-default');
						}else{
							$(downvote_val).toggleClass('btn-danger');
							$(downvote_val).toggleClass('btn-default');
						}
					}
					
				}else if(response.errorCode==="1"){
					callError(response.errorCode);
				}
			}
		});

}

/**
 * load question(s)
 * 
 * @returns {void}
 */
function loadQuestion(){
	$('#question').empty();
	var url_val = $('#hidden').val()+'/edunexus/DiscussionPortalManager/getDiscussion';
	var discussion_id = $('#discussion_id').val();
	$.ajax({
			url: url_val,
			method: 'post',
			dataType: 'json',
			data: {
				all:false,
				id:discussion_id
			},
			success: function(response){
				console.log(response);
				if(response.success==="true"){
					for(var x=0;x<response.count;x++){
						var title = response[x]['title'];
						var description = response[x]['description'];
						questionElement(title,description);
					}
				}else if(response.errorCode==="1"){
					callError(response.errorCode);
				}
			}
		});
}

/**
 * load answer(s)
 * 
 * @returns {void}
 */
function loadAnswer(){
	$('#answers').empty();

	document.getElementById("prev").disabled = true;
	document.getElementById("next").disabled = true;

	var url_val = $('#hidden').val()+'/edunexus/DiscussionPortalManager/getAnswer';
	var discussion_id = $('#discussion_id').val();
	$.ajax({
			url: url_val,
			method: 'post',
			dataType: 'json',
			data: {
				id:discussion_id,
				offset:offset_val,
				limit:limit_val
			},
			success: function(response){
				console.log(response);
				if(response.success==="true"){
					setCountTextAndButtons(response.count);
					for(var x=0;x<response.count;x++){
						var id = response[x]['id'];
						var reply = response[x]['reply'];
						var upvote = response[x]['upvote'];
						var downvote = response[x]['downvote'];
						var isUpvote = response[x]['isupvote'];
						if(response[x]['if_teacher']=="1")
							var name = response[x]['teacher'];
						else
							var name = response[x]['volunteer'];

						answerElement(id,name,reply,upvote,downvote,isUpvote);
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