<?php

class TeachernManager_model_test extends TestCase
{	public $user_details=null;
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->database();
		$this->db = $this->CI->db;
		 $this->user_details=array(

            'name' => 'Dr. Deb Nath',
            'ngo_id' => 1,
            'phoneno' => '9384456715',
            'address' => 'Salt Lake, Kolkata',
            'username' => 'db@gmail.com',
            'password' => '12345678',
            'qualification' => '/resources/resume/db.pdf',
            );
          
          
		$this->CI->load->model('edunexus/TeacherManager_model');
		$this->obj = $this->CI->TeacherManager_model;
	}


    public function test_registerTeacher(){
        $expectedresult['success']="true";
        $expectedresult['errorCode']="0";
        $foundresult = $this->obj->registerTeacher($this->user_details);
        $this->assertEquals($expectedresult,$foundresult,"expectedresult didn't matched with foundresult.");
        $this->db->delete('teacher_record', array('username' =>  $this->user_details['username']));
       
    }

    
       
}
?>