<?php

class LoginManager_model_test extends TestCase
{	public $user_details=null;
	public function setUp()
	{
		$this->resetInstance();
		$this->CI->load->database();
		$this->db = $this->CI->db;
		$this->user_details=array(

            'name' => 'Dr. Deb Nath',
            'phoneno' => '9384456715',
            'address' => 'Salt Lake, Kolkata',
            'username' => 'db@gmail.com',
            'password' => md5('12345678'),
            'admin_id' => '1',
            'active' => '1',
            'cookie_id' => null,
            );
          
          
		$this->CI->load->model('edunexus/LoginManager_model');
		$this->obj = $this->CI->LoginManager_model;
	}

	public function getId($table){
        $this->db->select('id');
        $this->db->from($table);
        $this->db->where(array('username' =>  $this->user_details['username']));

        $query = $this->db->get();
        $row = $query->row();
        return $row->id; 
	}

	public function test_authenticateByPassword()
	{	$this->db->insert('ngo_record',$this->user_details);
		$expectedresult=$this->user_details;
		$expectedresult['id']=$this->getId('ngo_record',$this->user_details);
		$expectedresult['success']="true";
		$expectedresult['errorCode']="0";
        $username="db@gmail.com";
        $password="12345678";
        $usertype="2";
        
        $foundresult = $this->obj->authenticateByPassword($username,$password,$usertype);
		
		$this->assertEquals($expectedresult,$foundresult,"expectedresult didn't matched with foundresult.");
			
	}
/*
	public function test_authenticateByCookie()
	{
		$expectedresult=array(

		$expectedresult=$this->user_details;
		$expectedresult['id']=$this->getId('ngo_record',$this->user_details);
		$expectedresult['success']="true";
		$expectedresult['errorCode']="0";
        
        $username="rnk@gmail.com";
        $cookie_id=null;
        $usertype="2";
        
        $foundresult = $this->obj->authenticateByCookie($username,$cookie_id,$usertype);
		
		$this->assertEquals($expectedresult,$foundresult,"expectedresult didn't matched with foundresult.");
			
	}  */

	public function test_getSecuredInfo()
	{
		$expectedresult=$this->user_details;
		$expectedresult['id']=$this->getId("ngo_record");
		$expectedresult['success']="true";
		$expectedresult['errorCode']="0";
        
        $username=$this->user_details['username'];
        $usertype="2";
        
        $foundresult = $this->obj->getSecuredInfo($username,$usertype);
		
		$this->assertEquals($expectedresult,$foundresult,"expectedresult didn't matched with foundresult.");
			
	}

	public function test_changeUserDetails()
	{
		$expectedresult=array(

		'success' => 'true',
        'errorCode' => '0',
    	);

    	$this->user_details['name']="Dr. Madhav Bham";
        
        $this->user_details['usertype']="2";

        $foundresult = $this->obj->changeUserDetails($this->user_details);
		
		$this->assertEquals($expectedresult,$foundresult,"expectedresult didn't matched with foundresult.");
	}

	public function test_changePassword()
	{
		$expectedresult=array(

		'success' => 'true',
        'errorCode' => '0',
        
            );

        $username=$this->user_details['username'];
        $oldpassword="12345678";
        $newpassword="87654321";
        $usertype="2";
        $forgot=false;
        $foundresult = $this->obj->changePassword($username,$oldpassword,$newpassword,$usertype,$forgot);
		
		$this->assertEquals($expectedresult,$foundresult,"expectedresult didn't matched with foundresult.");
		$this->user_details['password']=md5($newpassword);
				
	}

	public function checkIfDuplicateUsername(){
		$expectedresult=array(

		'success' => 'true',
        'errorCode' => '0',
		'count' => '1',        
        );
        $foundresult = $this->obj->checkIfDuplicateUsername($this->user_details['username']);
		$this->assertEquals($expectedresult,$foundresult,"expectedresult didn't matched with foundresult.");
		

	}

	public function test_deleteMyAccount()
	{
		$expectedresult=array(

		'success' => 'true',
        'errorCode' => '0',
    	);

    	
        $foundresult = $this->obj->deleteMyAccount($this->user_details['username'], "2");
		
		$this->assertEquals($expectedresult,$foundresult,"expectedresult didn't matched with foundresult.");
		$this->db->delete('ngo_record', array('id' =>  $this->getId("ngo_record")));
	}
	

}
?>