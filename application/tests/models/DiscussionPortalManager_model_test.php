<?php

class DiscussionPortalManager_model_test extends TestCase
{

	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->database();
		$this->db = $this->CI->db;
		$this->CI->load->model('edunexus/DiscussionPortalManager_model');
		$this->obj = $this->CI->DiscussionPortalManager_model;
	}

	public function getId($table,$data){
        $this->db->select('id');
        $this->db->from($table);
        $this->db->where($data);

        $query = $this->db->get();
        $row = $query->row();
        return $row->id; 
	}

	public function test_setDiscussion(){
		$result = $this->obj->setDiscussion(7,"testing title","testing description",1);
		$this->assertEquals($result['success'],"true");
		$this->assertEquals($result['errorCode'],"0");
	}

	public function test_getDiscussion(){
		$result = $this->obj->getDiscussion(true,31,7,null,null,"test");
		$this->assertEquals($result[0]['title'],"testing title");
		$this->assertEquals($result[0]['description'],"testing description");

		$result = $this->obj->getDiscussion(false,31,7,null,null,null);
		$this->assertEquals($result[0]['title'],"testing title");
		$this->assertEquals($result[0]['description'],"testing description");
	}

	public function test_setAnswer(){
		$result = $this->obj->setAnswer(31,1,"testing answer");
		$this->assertEquals($result['success'],"true");
		$this->assertEquals($result['errorCode'],"0");
	}

	public function test_getAnswer(){
		$result = $this->obj->getAnswer(31,0,50,"thearijitxfx@gmail.com");
		$this->assertEquals($result['success'],"true");
		$this->assertEquals($result['errorCode'],"0");
	}

	public function test_getUpvoteDownvote(){
		$result = $this->obj->getUpvoteDownvote('rnk@gmail.com',7);
		$this->assertEquals($result['data'][0]->isupvote,1);
	}

	public function test_setVoteCount(){
		$test_voteCount['success'] = "false";
		$test_voteCount['errorCode'] = "2";
		
		$result = $this->obj->setVoteCount('thearijitxfx@gmail.com',10,true,$test_voteCount,1);
		$this->assertEquals($result['success'],"true");
		$this->assertEquals($result['errorCode'],"0");
	}

	public function test_setUpvote(){
		$result = $this->obj->setUpvote(31,15);
		$this->assertEquals($result['success'],"true");
		$this->assertEquals($result['errorCode'],"0");
	}

	public function test_setDownvote(){
		$result = $this->obj->setDownvote(25,15);
		$this->assertEquals($result['success'],"true");
		$this->assertEquals($result['errorCode'],"0");
	}

	public function test_setDuplicateLink(){
		$result = $this->obj->setDuplicateLink(40,"testing link");
		$this->assertEquals($result['success'],"true");
		$this->assertEquals($result['errorCode'],"0");
	}

	public function test_cancelDuplicateLink(){
		$result = $this->obj->cancelDuplicateLink(40);
		$this->assertEquals($result['success'],"true");
		$this->assertEquals($result['errorCode'],"0");
	}

	public function test_getTeacherFromCourse(){
		$result = $this->obj->getTeacherFromCourse(3);
		$this->assertEquals($result['username'],"thearijitxfx@gmail.com");
		$this->assertEquals($result['name'],"Profulla Chanda");;
	}
}