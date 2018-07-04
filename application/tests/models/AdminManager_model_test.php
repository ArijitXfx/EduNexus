<?php

class AdminManager_model_test extends TestCase
{
	public $ngo_data=null;
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->database();
		$this->db = $this->CI->db;
		$this->CI->load->model('edunexus/AdminManager_model');
		$this->obj = $this->CI->AdminManager_model;

		$this->ngo_data['active'] = 1;
		$this->ngo_data['name'] = "Test Name";
		$this->ngo_data['username'] = "test@gmail.com";
		$this->ngo_data['password'] = md5("12345678");
		$this->ngo_data['address'] = "Address";
		$this->ngo_data['phoneno'] = "1234567899";
		$this->ngo_data['admin_id'] = 1;
	}
	

	public function getId($table,$data){
        $this->db->select('id');
        $this->db->from($table);
        $this->db->where($data);

        $query = $this->db->get();
        $row = $query->row();
        return $row->id; 
	}

	public function test_showNGO()
	{
		$result = $this->obj->showNGO(1, 10, 0);
		$this->assertEquals($result['count'],2);
		$this->assertEquals($result[0]['name'],"Ram Nath");	
	}

	public function test_insertNGO()
	{

			$result = $this->obj->insertNGO($this->ngo_data);
			$this->assertEquals($result['success'],"true");
			$this->assertEquals($result['errorCode'],"0");
	}

	public function test_updateNGO()
	{
		$id = $this->getId("ngo_record",$this->ngo_data);
		$this->ngo_data['name'] = 'New Test Name';
		$result = $this->obj->updateNGO($id,$this->ngo_data);
		$this->assertEquals($result['success'],"true");
		$this->assertEquals($result['errorCode'],"0");

	}

	public function test_deleteNGO()
	{
		$this->ngo_data['name'] = 'New Test Name';
		$id = $this->getId("ngo_record",$this->ngo_data);
		$result = $this->obj->deleteNGO($id);
		$this->assertEquals($result['success'],"true");
		$this->assertEquals($result['errorCode'],"0");

		$this->db->delete('ngo_record', array('id' => $id));

	}

}