<?php
	/**
	 * Controller class that manages the activity of an NGO
	 * @author Sandeep Khan
	 */
	require "Entities/NGO.php";
	class NGOManager extends CI_Controller
	{

		public $ngo_id=null;
		public function __construct()
        {
                parent::__construct();
                $this->load->helper('url_helper');
                $this->load->model('edunexus/NGOManager_model');
                $this->load->model('edunexus/CourseManager_model');
                $this->load->model('edunexus/LoginManager_model');
                $this->load->library('session');
                if($this->session->userdata('user_details')==null)
					redirect('edunexus/LoginManager','refresh');
				$this->ngo_id =$this->session->userdata('user_details')->getId();
        }

		public function index(){
			$this->manageVolunteersPage();
		}

		/**
		 * Displays the manage_volunteers page
		 */
		public function manageVolunteersPage(){
			$data['name'] = $this->session->userdata('user_details')->getName();
			$this->load->view('edunexus/design_templates/header');
			$this->load->view('edunexus/ngo/manage_users',$data);
			$this->load->view('edunexus/design_templates/footer');
		}

		/**
		 * Displays manage_courses page
		 */
		public function manageCoursesPage(){
			$data['name'] = $this->session->userdata('user_details')->getName();
			$this->load->view('edunexus/design_templates/header');
			$this->load->view('edunexus/ngo/manage_courses',$data);
			$this->load->view('edunexus/design_templates/footer');
		}

		/**
		 * Displays a display_course page
		 */
		public function showCoursePage(){
			$data['usertype'] = $this->session->userdata('user_details')->getUsertype();
			$this->load->view('edunexus/design_templates/header');
			$this->load->view('edunexus/ngo_volunteer/display_course',$data);
			$this->load->view('edunexus/design_templates/footer');
		}

		/**
		 * Displays verify_materials page
		 */
		public function verifyMaterialsPage(){
			$data['name'] = $this->session->userdata('user_details')->getName();
			$this->load->view('edunexus/design_templates/header');
			$this->load->view('edunexus/ngo/verify_materials',$data);
			$this->load->view('edunexus/design_templates/footer');
		}

		/**
		 * Displays verify_teachers page
		 */
		public function verifyTeachersPage(){
			$data['name'] = $this->session->userdata('user_details')->getName();
			$this->load->view('edunexus/design_templates/header');
			$this->load->view('edunexus/ngo/verify_teachers',$data);
			$this->load->view('edunexus/design_templates/footer');
		}

		/**
		 * Receives the details of a volunteer from post requests
		 * and pass it on to the model class NGOManager_model to create new a volunteer
		 * for the NGO
		 */
		public function addVolunteer(){
			$data['active'] = 1;
			$data['name'] = $this->input->post('name');
			$data['username'] = $this->input->post('username');
			$data['password'] = md5($this->input->post('password'));
			$data['address'] = $this->input->post('address');
			$data['phoneno'] = $this->input->post('phoneno');
			$data['ngo_id'] = $this->ngo_id;

			$duplicate = $this->LoginManager_model->checkIfDuplicateUsername($data['username']);
			
			if($duplicate['success']=="true" && $duplicate['count']==0){
				$response = $this->NGOManager_model->insertVolunteer($data);
				echo json_encode($response);
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
		 * Receives the details of an existing volunteer from post requests
		 * and pass it on to the model class NGOManager_model to update volunteer
		 * details
		 */
		public function updateVolunteer(){
			$data['active'] = 1;
			$data['name'] = $this->input->post('name');
			$data['address'] = $this->input->post('address');
			$data['phoneno'] = $this->input->post('phoneno');
			$id = $this->input->post('id');

			$response = $this->NGOManager_model->updateVolunteer($id,$data);
			echo json_encode($response);
		}

		/**
		 * Receives the details of an existing volunteer from post requests
		 * and pass it on to the model class NGOManager_model to deactivate
		 * the volunteer
		 */
		public function deleteVolunteer(){
			$data['active'] = 0; //deleting volunteer
			$id = $this->input->post('id');

			$response = $this->NGOManager_model->deleteVolunteer($id,$data);
			echo json_encode($response);
		}
		
		/**
		 * Receives post requests and pass it on to the model class NGOManager_model to show volunteer
		 * details
		 */
		public function showVolunteers(){
			$offset = $this->input->post('offset');
			$limit = $this->input->post('limit');
			$response = $this->NGOManager_model->getVolunteers($this->ngo_id, $limit, $offset);
            echo json_encode($response);
		}

		/**
		 * Receives the details of a course required by the NGO from post requests
		 * and pass it on to the model class CourseManager_model to create a new
		 * required course
		 */
		public function addRequiredCourse(){
			$data['ngo_id'] = $this->ngo_id;
			$data['title'] = $this->input->post('title');
			$data['requirement'] = $this->input->post('requirement');
			$data['language'] = $this->input->post('language');
			$data['subject'] = $this->input->post('subject');
			$data['standard'] = $this->input->post('standard');
			$data['board'] = $this->input->post('board');

			$result = $this->CourseManager_model->setRequiredCourses($data);
			echo json_encode($result);
		}

		/**
		 * Receives the id of an existing course required by the NGO from post requests
		 * and pass it on to the model class CourseManager_model to delete the
		 * required course
		 */
		public function deleteRequiredCourse(){
			$id =  $this->input->post('id');
			$result = $this->CourseManager_model->deleteRequiredCourse($id,$this->ngo_id);
			echo json_encode($result);
		}

		/**
		 * Receives post requests and pass it on to the model class CourseManager_model to show details
		 * of courses required by the NGO
		 */
		public function showRequiredCourses(){
			$offset = $this->input->post('offset');
			$limit = $this->input->post('limit');
			$keyword = $this->input->post('keyword');
			$board = $this->input->post('board');
			$language = $this->input->post('language');
			$standard = $this->input->post('standard');
			$subject = $this->input->post('subject');

			$filter_clause=null;
			if($board!=null)
				$filter_clause['board'] = $board;
			if($language!=null)
				$filter_clause['language'] = $language;
			if($standard!=null)
				$filter_clause['standard'] = $standard;
			if($subject!=null)
				$filter_clause['subject'] = $subject;

			$result = $this->CourseManager_model->getRequiredCourses($this->ngo_id, $limit, $offset,$keyword,$filter_clause);
			echo json_encode($result);
		}

		/**
		 * Receives the id of a course whose materials have been uploaded by a Teacher from post requests
		 * and pass it on to the model class CourseManager_model to make it a verified
		 * course so that NGOVolunteer can view it
		 */
		public function verifyCourse(){
			$id = $this->input->post('id');
			$result = $this->CourseManager_model->setVerifiedCourse($id,$this->ngo_id);
			echo json_encode($result);
		}

		/**
		 * Receives post requests and pass it on to the model class CourseManager_model to show details
		 * of verified courses
		 */
		public function showVerifiedCourses(){
			$offset =$this->input->post('offset');
			$limit = $this->input->post('limit');
			$keyword = $this->input->post('keyword');
			$board = $this->input->post('board');
			$language = $this->input->post('language');
			$standard = $this->input->post('standard');
			$subject = $this->input->post('subject');

			$filter_clause=null;
			if($board!=null)
				$filter_clause['board'] = $board;
			if($language!=null)
				$filter_clause['language'] = $language;
			if($standard!=null)
				$filter_clause['standard'] = $standard;
			if($subject!=null)
				$filter_clause['subject'] = $subject;


			$result = $this->CourseManager_model->getVerifiedCourses($this->ngo_id, $keyword, $filter_clause,$limit, $offset);

			echo json_encode($result);
		}

		/**
		 * Receives post requests and pass it on to the model class CourseManager_model to show details
		 * of non verified courses
		 */
		public function showNonVerifiedCourses(){
			$offset = $this->input->post('offset');
			$limit = $this->input->post('limit');
			$keyword = $this->input->post('keyword');
			$board = $this->input->post('board');
			$language = $this->input->post('language');
			$standard = $this->input->post('standard');
			$subject = $this->input->post('subject');

			$filter_clause=null;
			if($board!=null)
				$filter_clause['board'] = $board;
			if($language!=null)
				$filter_clause['language'] = $language;
			if($standard!=null)
				$filter_clause['standard'] = $standard;
			if($subject!=null)
				$filter_clause['subject'] = $subject;

			$result = $this->CourseManager_model->getNonVerifiedCourses($this->ngo_id, $keyword, $filter_clause,$limit, $offset);

			echo json_encode($result);
		}

		/**
		 * Receives the id of a course whose materials has been uploaded by a Teacher via 
		 * post request and pass it on to the model class CourseManager_model to delete it 
		 */
		public function deleteCourse(){
			$id = $this->input->post('id');
			$result = $this->CourseManager_model->deleteCourse($id,$this->ngo_id);
			echo json_encode($result);
		}
		
		/**
		 * Receives post requests and pass it on to the model class NGOManager_model to show details
		 * of verified Teachers
		 */
		public function showVerifiedTeachers(){
			$limit = $this->input->post('limit');
			$offset = $this->input->post('offset');
			$result = $this->NGOManager_model->getTeachers($this->ngo_id,1,$limit,$offset);
			echo json_encode($result);
		}

		/**
		 * Receives post requests and pass it on to the model class NGOManager_model to show details
		 * of non verified Teachers
		 */
		public function showNonVerifiedTeachers(){
			$limit = $this->input->post('limit');
			$offset = $this->input->post('offset');
			$result = $this->NGOManager_model->getTeachers($this->ngo_id,0,$limit,$offset);
			echo json_encode($result);
		}

		/**
		 * Receives id of a Teacher from post requests and pass it on to the model class NGOManager_model to toggle
		 * the verified status of the Teacher
		 */
		public function changeVerifiedStatus(){
			$id = $this->input->post('id');
			$data['verified'] = $this->input->post('verified');
			$result = $this->NGOManager_model->changeVerifiedStatus($this->ngo_id, $id, $data);
			echo json_encode($result);
		}

		/**
		 * Receives id of a Teacher from post requests and pass it on to the model class NGOManager_model to
		 * deactivate him/her
		 */
		public function deleteTeacher(){
			$id = $this->input->post('id');
			$data['active'] = 0;
			$result = $this->NGOManager_model->deleteTeacher($this->ngo_id, $id, $data);
			echo json_encode($result);
		}

		/**
		 * Shows all type of board present in database
		 */
		public function fillBoard(){
			$result = $this->CourseManager_model->getBoardOptions();
			echo json_encode($result);
		}

		/**
		 * Shows all type of language present in database
		 */
		public function fillLanguage(){
			$result = $this->CourseManager_model->getLanguageOptions();
			echo json_encode($result);
		}

		/**
		 * Shows all type of subject present in database
		 */
		public function fillSubject(){
			$result = $this->CourseManager_model->getSubjectOptions();
			echo json_encode($result);
		}



	}
?>
