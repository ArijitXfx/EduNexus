<?php
	/**
	 * Controller class that manages the activity of an NGOVolunteer
	 * @author Sandeep Khan
	 */
	require "Entities/NGOVolunteer.php";
	class NGOVolunteerManager extends CI_Controller
	{
		public function __construct()
        {
                parent::__construct();
                $this->load->helper('url_helper');
                $this->load->model('edunexus/CourseManager_model');
                $this->load->library('session');
				if($this->session->userdata('user_details')==null)
					redirect('edunexus/LoginManager','refresh');
        }

		/**
		 * Displays the view_course_materials page
		 */
		public function index(){
			$data['name'] = $this->session->userdata('user_details')->getName();
			$this->load->view('edunexus/design_templates/header');
			$this->load->view('edunexus/ngo_volunteer/view_course_materials',$data);
			$this->load->view('edunexus/design_templates/footer');
		}

		/**
		 * Receives coourse id from post request and saves it in session
		 */
		public function setCourseId(){
			$id=$this->input->post('course_id');
			$this->session->set_userdata('course_id',$id);
			$result['id'] = $id;
			echo json_encode($result);

		}

		/**
		 * Displays display_course page
		 */
		public function showCoursePage(){
				$data['usertype'] = $this->session->userdata('user_details')->getUsertype();
				$this->load->view('edunexus/design_templates/header');
				$this->load->view('edunexus/ngo_volunteer/display_course',$data);
				$this->load->view('edunexus/design_templates/footer');
		}

		/**
		 * Extracts the course id saved in session and pass it on to model
		 * class CourseManager_model to show the details of that course
		 */
		public function getRecentCourse(){
			$id = $this->session->userdata('course_id');
			if($id==null){
				$result['success'] = "false";
				$result['errorCode'] = "1";
			}else{
				$result = $this->CourseManager_model->showCourseWithID($id);	
			}
			echo json_encode($result);
		}

		/**
		 * Receives post request and pass it on to model class CourseManager_model
		 * to show verified courses
		 */
		public function showVerifiedCourses(){
			$ngo_id = $this->session->userdata('user_details')->getNgo_id();
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


			$result = $this->CourseManager_model->getVerifiedCourses($ngo_id, $keyword, $filter_clause,$limit, $offset);
			echo json_encode($result);
		}

		/**
		 * Displays the discussion forum by redirecting to the
		 * controller class DiscussionPortalManager 
		 */
		public function openForum(){
				$course_id = $this->input->post('course_id');
				$this->session->set_userdata('course_id',$course_id);
				redirect('edunexus/DiscussionPortalManager','refresh');
			
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
