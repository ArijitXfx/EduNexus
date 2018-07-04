<?php

class CourseManager_model_test extends TestCase
{	public $mycourse=null;
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->database();
		$this->db = $this->CI->db;
		$this->mycourse=array(
            'req_id' => "3",
            'teacher_id' => "1",
            'ngo_id'=> "1",
            'title'=> "Alphabets",
            'subject' => "English",
            'standard' => "2",
            'board' => "ICSE",
            'description' => "This is a tutorial on Alphabets.",
            'language' => "English",
            'qabank' => "resources/qabank/alphabets.pdf",
            'video' => "https://www.youtube.com/watch?v=AhFjfCdG61Y",
            'time' => "2018-05-05 11:15:06",
            'verified' => "1", 
           
          );
		$this->CI->load->model('edunexus/CourseManager_model');
		$this->obj = $this->CI->CourseManager_model;
	}


	public function getId($table,$title){
        $this->db->select('id');
        $this->db->from($table);
        $this->db->where(array('title' =>  $title));

        $query = $this->db->get();
        $row = $query->row();
        return $row->id; 
	}


	public function test_getVerifiedCourses()
	{	
		
		$expectedresult['success']='true';
        $expectedresult['errorCode']='0';
            
        $course=array(
            'id' => "3",
            'req_id' => "3",
            'teacher_id' => "1",
            'ngo_id'=> "1",
            'title'=> "Pythagoras Theorem.",
            'subject' => "English",
            'standard' => "7",
            'board' => "CBSE",
            'language' => "English",
            'qabank' => "resources/qabank/pythagoras.pdf",
            'video' => "https://www.youtube.com/watch?v=AhFjfCdG61Y",
            'time' => "2018-05-05 11:15:06",
            'verified' => "1", 
            'name' => "Profulla Chanda",
          );
        $expectedresult['0']=$course;       
        $expectedresult['count']="1";     
		$foundresult = $this->obj->getVerifiedCourses(1,null,null,1,0);
		unset($foundresult['0']['description']);  
		$this->assertEquals($expectedresult['count'],$foundresult['count'],"result['count'] didn't matched.");
		$this->assertEquals($expectedresult['0']['id'],$foundresult['0']['id'],"result course at '0' didn't matched.");
		$this->assertEquals($expectedresult['success'],$foundresult['success'],"result['success'] didn't matched.");
		$this->assertEquals($expectedresult['errorCode'],$foundresult['errorCode'],"result['errorCode'] didn't matched.");
				
	}  

	public function test_showCourseWithID()
	{	$this->db->insert('courses',$this->mycourse);
		$expectedresult['success']='true';
        $expectedresult['errorCode']='0';
        $course_id=$this->getId('courses', $this->mycourse['title']);    
        $this->mycourse['id']=$course_id;
        $this->mycourse['name']="Profulla Chanda";
        $this->mycourse['username']="thearijitxfx@gmail.com";
        $this->mycourse['qualification']="resources/resume/SandeepKhan.pdf";
        $this->mycourse['phoneno']="9841235768";    
        $expectedresult['0']=$this->mycourse;       
        $expectedresult['count']="1";     
		$foundresult = $this->obj->showCourseWithID($course_id);
		$this->assertEquals($expectedresult['0'],$foundresult['0'],"result course at '0' didn't matched.");
		$this->assertEquals($expectedresult['success'],$foundresult['success'],"result['success'] didn't matched.");
		$this->assertEquals($expectedresult['errorCode'],$foundresult['errorCode'],"result['errorCode'] didn't matched.");
		$this->db->delete('courses', array('id' =>  $this->getId("courses", $this->mycourse['title'])));
		
	}
    
	
	public function test_getAllCourses()
	{
		$expectedresult['success']='true';
        $expectedresult['errorCode']='0';
            
        $course=array(
            'id' => "3",
            'req_id' => "3",
            'teacher_id' => "1",
            'ngo_id'=> "1",
            'title'=> "Pythagoras Theorem.",
            'subject' => "English",
            'standard' => "7",
            'board' => "CBSE",
            'language' => "English",
            'qabank' => "resources/qabank/pythagoras.pdf",
            'video' => "https://www.youtube.com/watch?v=AhFjfCdG61Y",
            'time' => "2018-05-05 11:15:06",
            'verified' => "1", 

          );
        $expectedresult['0']=$course;       
        $expectedresult['count']="1";     
		$foundresult = $this->obj->getAllCourses(1,1,0);
		unset($foundresult['0']['description']);  
		$this->assertEquals($expectedresult['count'],$foundresult['count'],"result['count'] didn't matched.");
		$this->assertEquals($expectedresult['0'],$foundresult['0'],"result course at '0' didn't matched.");
		$this->assertEquals($expectedresult['success'],$foundresult['success'],"result['success'] didn't matched.");
		$this->assertEquals($expectedresult['errorCode'],$foundresult['errorCode'],"result['errorCode'] didn't matched.");
		
	}

	public function test_getNonVerifiedCourses()
	{
		$expectedresult['success']='true';
        $expectedresult['errorCode']='0';
            
        $course=array(
            'id' => "9",
            'req_id' => "6",
            'teacher_id' => "4",
            'ngo_id'=> "1",
            'title'=> "Tense.",
            'subject' => "English",
            'standard' => "5",
            'board' => "ICSE",
            'language' => "English",
            'qabank' => "resources/qabank/test1.pdf",
            'video' => "https://www.youtube.com/watch?v=u2ucLpNVVl4",
            'time' => "2018-05-06 02:51:57",
            'verified' => "0", 
            'name' => "Sandy Sen", 

          );
        $expectedresult['0']=$course;       
        $expectedresult['count']="1";     
		$foundresult = $this->obj->getNonVerifiedCourses(1,null,null,1,0);
		unset($foundresult['0']['description']);  
        $this->assertEquals($expectedresult['count'],$foundresult['count'],"result['count'] didn't matched.");
		$this->assertEquals($expectedresult['success'],$foundresult['success'],"result['success'] didn't matched.");
		$this->assertEquals($expectedresult['errorCode'],$foundresult['errorCode'],"result['errorCode'] didn't matched.");
		
	}

	public function test_setRequiredCourses()
	{
		$expectedresult['success']='true';
        $expectedresult['errorCode']='0';
            
        $course=array(
            'ngo_id'=> "1",
            'title'=> "Alphabets.",
            'subject' => "English",
            'requirement' => "We require a tutorial Alphabets.",
            'standard' => "3",
            'board' => "ICSE",
            'language' => "English",
        
          );

        $foundresult = $this->obj->setRequiredCourses($course);
		$this->assertEquals($expectedresult['success'],$foundresult['success'],"result['success'] didn't matched.");
		$this->assertEquals($expectedresult['errorCode'],$foundresult['errorCode'],"result['errorCode'] didn't matched.");
		$this->db->delete('ngo_req', array('id' =>  $this->getId("ngo_req", $course['title'])));
	}


	public function test_setCourses()
	{	$expectedresult['success']='true';
        $expectedresult['errorCode']='0';
            
        

        $foundresult = $this->obj->setCourses($this->mycourse);
		$this->assertEquals($expectedresult['success'],$foundresult['success'],"result['success'] didn't matched.");
		$this->assertEquals($expectedresult['errorCode'],$foundresult['errorCode'],"result['errorCode'] didn't matched.");
		$this->db->delete('courses', array('id' =>  $this->getId("courses", $this->mycourse['title'])));	
	}

	public function test_setVerifiedCourse()
	{	$this->mycourse['verified']="0";
		$this->db->insert('courses',$this->mycourse);
		$expectedresult['success']='true';
        $expectedresult['errorCode']='0';
            
      	$course_id=$this->getId("courses",$this->mycourse['title']);
      	$ngo_id=$this->mycourse['ngo_id'];
        $foundresult = $this->obj->setVerifiedCourse($course_id,$ngo_id);
		$this->assertEquals($expectedresult['success'],$foundresult['success'],"result['success'] didn't matched.");
		$this->assertEquals($expectedresult['errorCode'],$foundresult['errorCode'],"result['errorCode'] didn't matched.");
		$this->db->delete('courses', array('id' =>  $this->getId("courses", $this->mycourse['title'])));		
	}

	public function test_setNonVerifiedCourse()
	{	
		$this->db->insert('courses',$this->mycourse);
		$expectedresult['success']='true';
        $expectedresult['errorCode']='0';
            
      	$course_id=$this->getId("courses",$this->mycourse['title']);
      	$ngo_id=$this->mycourse['ngo_id'];
        $foundresult = $this->obj->setNonVerifiedCourse($course_id,$ngo_id);
		$this->assertEquals($expectedresult['success'],$foundresult['success'],"result['success'] didn't matched.");
		$this->assertEquals($expectedresult['errorCode'],$foundresult['errorCode'],"result['errorCode'] didn't matched.");
		$this->db->delete('courses', array('id' =>  $this->getId("courses", $this->mycourse['title'])));	
	}


	public function test_deleteRequiredCourse()
	{	unset($this->mycourse['req_id']);
		unset($this->mycourse['teacher_id']);
		unset($this->mycourse['description']);
		unset($this->mycourse['qabank']);
		unset($this->mycourse['video']);
		unset($this->mycourse['time']);
		unset($this->mycourse['verified']);

		$this->db->insert('ngo_req',$this->mycourse);
		
		$expectedresult['success']='true';
        $expectedresult['errorCode']='0';
            
      	$req_id=$this->getId("ngo_req",$this->mycourse['title']);
      	$ngo_id=$this->mycourse['ngo_id'];
        $foundresult = $this->obj->deleteRequiredCourse($req_id,$ngo_id);
		$this->assertEquals($expectedresult['success'],$foundresult['success'],"result['success'] didn't matched.");
		$this->assertEquals($expectedresult['errorCode'],$foundresult['errorCode'],"result['errorCode'] didn't matched.");
		
	}




	public function test_deleteCourse()
	{	

		$this->db->insert('courses',$this->mycourse);

		$expectedresult['success']='true';
        $expectedresult['errorCode']='0';
            
      	$course_id=$this->getId("courses",$this->mycourse['title']);;
      	$ngo_id=$this->mycourse['ngo_id'];
        $foundresult = $this->obj->deleteCourse($course_id,$ngo_id);
		$this->assertEquals($expectedresult['success'],$foundresult['success'],"result['success'] didn't matched.");
		$this->assertEquals($expectedresult['errorCode'],$foundresult['errorCode'],"result['errorCode'] didn't matched.");
		
	}



	
}
?>