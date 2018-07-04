<?php
	/**
        * Discussion Portal Manager manage Discussion Portal and Discussion Thread
        * 
        * @author Arijit Basu <thearijitxfx@gmail.com>
        * 
        * version 1.0
        * 
        */
	require "Entities/Admin.php";
	class AdminManager extends CI_Controller
	{

		public $admin_id=null;
                
                /**
                 * Constructor
                 */
		public function __construct()
        {
                parent::__construct();
                $this->load->helper('url_helper');
                $this->load->model('edunexus/AdminManager_model');
                $this->load->model('edunexus/LoginManager_model');
                $this->load->library('session');
                if($this->session->userdata('user_details')==null)
					redirect('edunexus/LoginManager','refresh');
				$this->admin_id = $this->session->userdata('user_details')->getId();
        }

                /**
                 * Opening Manage NGO page
                 * 
                 * @param void do not have any parameter
                 * 
                 * @return void not returning anything
                 */
		public function index(){
			$data['name'] = $this->session->userdata('user_details')->getName();
			$this->load->view('edunexus/design_templates/header');
			$this->load->view('edunexus/admin/manage_ngo',$data);
			$this->load->view('edunexus/design_templates/footer');
		}
                
                /**
                 * Add a new NGO
                 *
                 * @param void do not have any parameter
                 * 
                 * @return void not returning anything
                 */
		public function addNGO(){
			$data['name'] = $this->input->post('name');
			$data['username'] = $this->input->post('username');
			$data['password'] = md5($this->input->post('password'));
			$data['address'] = $this->input->post('address');
			$data['phoneno'] = $this->input->post('phoneno');
			$data['admin_id'] = $this->admin_id;

			$duplicate = $this->LoginManager_model->checkIfDuplicateUsername($data['username']);
                        if($duplicate['success']=="true" && $duplicate['count']==0){
                                $result = $this->AdminManager_model->insertNGO($data);
                                echo json_encode($result);      
                        }else if($duplicate['success']=="true" && $duplicate['count']!=0){
                                $duplicate['success']="false";
                                $duplicate['errorCode'] = "7";
                                unset($duplicate['count']);
                                
                                echo json_encode($duplicate);
                        }else{
                                echo json_encode($duplicate);
                        }
		}

                /**
                 * Update name, email, address, phone no of a NGO
                 * 
                 * @param void do not have any parameter
                 * 
                 * @return void not returning anything
                 */
		public function updateNGO(){
			$data['name'] = $this->input->post('name');
			$data['username'] = $this->input->post('username');
			$data['address'] = $this->input->post('address');
			$data['phoneno'] = $this->input->post('phoneno');
			$id = $this->input->post('id');

			$result = $this->AdminManager_model->updateNGO($id,$data);
			echo json_encode($result);
		}

                /**
                 * Delete a NGO
                 * 
                 * @param void do not have any parameter
                 * 
                 * @return void not returning anything
                 */
		public function deleteNGO(){
			$id = $this->input->post('id');
			$result = $this->AdminManager_model->deleteNGO($id);
			echo json_encode($result);
		}

                /**
                 * Show all NGO(s)
                 * 
                 * @param void do not have any parameter
                 * 
                 * @return void not returning anything
                 */
		public function showNGO(){
			$limit = $this->input->post('limit');
			$offset = $this->input->post('offset');
			$result = $this->AdminManager_model->showNGO($this->admin_id, $limit, $offset);
			echo json_encode($result);
		}

	}
?>
