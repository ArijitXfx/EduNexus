<?php

class NGOManager_model_test extends TestCase
{
	public $volunteer_data=null;
	public $teacher_data = null;
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->database();
		$this->db = $this->CI->db;
		$this->CI->load->model('edunexus/NGOManager_model');
		$this->obj = $this->CI->NGOManager_model;

		$this->volunteer_data['active'] = 1;
		$this->volunteer_data['name'] = "Test Name";
		$this->volunteer_data['username'] = "test@gmail.com";
		$this->volunteer_data['password'] = md5("12345678");
		$this->volunteer_data['address'] = "Address";
		$this->volunteer_data['phoneno'] = "1234567899";
		$this->volunteer_data['ngo_id'] = 1;

		$this->teacher_data['active'] = 1;
		$this->teacher_data['name'] = "Teacher Name";
		$this->teacher_data['username'] = "teacher@gmail.com";
		$this->teacher_data['password'] = md5("12345678");
		$this->teacher_data['address'] = "Address";
		$this->teacher_data['phoneno'] = "1234567899";
		$this->teacher_data['ngo_id'] = 1;
		$this->teacher_data['qualification'] = "resources/resume/Resume.pdf";
		$this->teacher_data['verified'] = 0;
	}

	public function getId($table,$data){
        $this->db->select('id');
        $this->db->from($table);
        $this->db->where($data);

        $query = $this->db->get();
        $row = $query->row();
        return $row->id; 
	}

	public function test_getVolunteers()
	{
		$result = $this->obj->getVolunteers(1, 10, 0);
		$this->assertEquals($result['count'],3);
		$this->assertEquals($result[0]['name'],"Ramesh Saha");	
	}

	public function test_insertVolunteer()
	{

			$result = $this->obj->insertVolunteer($this->volunteer_data);
			$this->assertEquals($result['success'],"true");
			$this->assertEquals($result['errorCode'],"0");
	}

	public function test_updateVolunteer()
	{
		$id = $this->getId("volunteer_record",$this->volunteer_data);
		$this->volunteer_data['name'] = 'New Test Name';
		unset($this->volunteer_data['username']);
		$result = $this->obj->updateVolunteer($id,$this->volunteer_data);
		$this->assertEquals($result['success'],"true");
		$this->assertEquals($result['errorCode'],"0");

	}

	public function test_deleteVolunteer()
	{
		$this->volunteer_data['name'] = 'New Test Name';
		$id = $this->getId("volunteer_record",$this->volunteer_data);
		$data['active'] = 0;
		$result = $this->obj->deleteVolunteer($id,$data);
		$this->assertEquals($result['success'],"true");
		$this->assertEquals($result['errorCode'],"0");

		$this->db->delete('volunteer_record', array('id' => $id));

	}

	public function test_getTeachers()
	{
		$result = $this->obj->getTeachers(1,1, 10, 0);
		$this->assertEquals($result['count'],1);
		$result = $this->obj->getTeachers(1,0, 10, 0);
		$this->assertEquals($result['count'],2);
	}

	public function test_changeVerifiedStatus(){
		$this->db->insert('teacher_record', $this->teacher_data);
   		$id = $this->db->insert_id();
   		$data['verified'] = 1;
   		$result = $this->obj->changeVerifiedStatus($this->teacher_data['ngo_id'],$id,$data);
   		$this->assertEquals($result['success'],"true");
   		$this->assertEquals($result['errorCode'],"0");	
	}

	public function test_deleteTeacher()
	{
		$this->teacher_data['verified'] = 1;
		$id = $this->getId("teacher_record",$this->teacher_data);
		$data['active'] = 0;
		$result = $this->obj->deleteTeacher($this->teacher_data['ngo_id'],$id,$data);
		$this->assertEquals($result['success'],"true");
		$this->assertEquals($result['errorCode'],"0");

		$this->db->delete('teacher_record', array('id' => $id));

	}


}