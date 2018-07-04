<?php
	/**
	 * Controller class that manages the activity of a Teacher
	 * @author Sandeep Khan
	 */
	require "Entities/Teacher.php";
	class TeacherManager extends CI_Controller
	{

		public function __construct()
        {
                parent::__construct();
                $this->load->helper('url_helper');
                $this->load->library('session');
                $this->load->model('edunexus/CourseManager_model');
                if($this->session->userdata('user_details')==null)
					redirect('edunexus/LoginManager','refresh');
        }

        public function index(){
  			$this->showUploadPage();		
		}

		/**
		 * Displays the upload page
		 */
		public function showUploadPage(){
			$data['name'] = $this->session->userdata('user_details')->getName();
			$this->load->view('edunexus/design_templates/header');
			$this->load->view('edunexus/teacher/upload',$data);
			$this->load->view('edunexus/design_templates/footer');
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
		 * Displays my_courses page
		 */
		public function showMyCourses(){
			$data['name'] = $this->session->userdata('user_details')->getName();
			$this->load->view('edunexus/design_templates/header');
			$this->load->view('edunexus/teacher/my_courses',$data);
			$this->load->view('edunexus/design_templates/footer');
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
		 * Receives post request and pass it on to the model class
		 * CourseManager_model to show the details of courses required
		 * by the NGO
		 */
		public function showRequiredCourses(){
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


			$result = $this->CourseManager_model->getRequiredCourses($ngo_id,$limit, $offset,$keyword,$filter_clause);
			echo json_encode($result);
		}

		/**
		 * Receives post request and pass it on to the model class
		 * CourseManager_model to show the details of courses whose
		 * materials have been uploaded by the Teacher and has been verifed 
		 * by NGO
		 */
		public function getVerifiedCourses(){
			$id= $this->session->userdata('user_details')->getId();
			$limit=$this->input->post('limit');
			$offset=$this->input->post('offset');
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


			$result = $this->CourseManager_model->getCoursesOfATeacher($id,$limit,$offset,1,$keyword,$filter_clause);
			echo json_encode($result);
		}

		/**
		 * Receives post request and pass it on to the model class
		 * CourseManager_model to show the details of courses whose
		 * materials have been uploaded by the Teacher and has not been verifed 
		 * by NGO
		 */
		public function getNonVerifiedCourses(){
			$id= $this->session->userdata('user_details')->getId();
			$limit=$this->input->post('limit');
			$offset=$this->input->post('offset');
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


			$result = $this->CourseManager_model->getCoursesOfATeacher($id,$limit,$offset,0,$keyword,$filter_clause);
			echo json_encode($result);
		}

		/**
		 * Receives details of course along with materials via post request
		 * and pass it on to model class CourseManager_model to upload the
		 * materials  
		 */
		public function uploadMaterials(){
			$config['upload_path']="./resources/qabank/";
			$config['allowed_types'] = 'pdf';
			//$config['file_name'] = $username.time().'\.pdf';
			$this->load->library('upload',$config);
        	if ( ! $this->upload->do_upload('qabank'))
                {
                        $response['success'] = 'false';
                        $response['errorCode'] = "11";
                        $response['errMsg'] = "Q&A Bank file size should be less than or equal to ".ini_get("upload_max_filesize")."B";
                        echo json_encode($response);
                }
                else
                {
                        $data['qabank'] = 'resources/qabank/'.$this->upload->data()['file_name'];
						$data['teacher_id'] = $this->session->userdata('user_details')->getId();
						$data['ngo_id'] = $this->session->userdata('user_details')->getNgo_id();
						$data['req_id'] = $this->input->post('req_id');
						$data['title'] = $this->input->post('title');
						$data['language'] = $this->input->post('language');
						$data['subject'] = $this->input->post('subject');
						$data['standard'] =$this->input->post('standard');
						$data['board'] = $this->input->post('board');
						$data['description'] = $this->input->post('description');
						$data['video'] = $this->input->post('video');
						$data['verified'] = 0;   
						$result = $this->CourseManager_model->setCourses($data);
						echo json_encode($result);                 
                }

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