<?php
class ErrorController extends CI_Controller {
        private $errorTitle = array('ERROR!',
        	'Service Temporarily Unavailable',
        	'ERROR!',
        	'ERROR!',
        	'ERROR!',
        	'Email Error'
        );
        
        private $errorDescription = array('Please log in again!',
        'Oops! Server Down. The server is temporarily unable to service your request due to maintenance downtime or capacity problems. Please try again later.',
        'Please log in again!',
        'Please log in again!',
        'Please log in again!',
        'Oops! Something went wrong while trying to send the email. Your email message was unable to be sent because the connection to your mail server was interrupted or due to some other issues. Please try again after sometime.'
      );

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('url_helper');
            $this->load->library('session');
        }

        public function index(){
          if($this->session->userdata('user_details')!=null){

            require_once "Entities/Admin.php";
            require_once "Entities/NGO.php";
            require_once "Entities/NGOVolunteer.php";
            require_once "Entities/Teacher.php";

            $user_details=$this->session->userdata('user_details');

            $this->session->unset_userdata('user_details');

            if($this->session->userdata('cookie_id')!=null){
                $cookie_id=$this->session->userdata('cookie_id');
                $this->session->unset_userdata('cookie_id');
                $this->deleteThisCookie();
                $this->load->model('edunexus/LoginManager_model');
                $username=$user_details->getUsername();
                $usertype=$user_details->getUsertype();
                $result=$this->LoginManager_model->deleteThisCookieData($username, $cookie_id, $usertype);
                $this->session->sess_destroy();
            }
            else
                $this->session->sess_destroy();

            $errorCode = $this->session->flashdata('errorCode');
            if(!isset($errorCode))
              $errorCode=$this->input->post("errorCode");

            if($errorCode==null)
                $errorCode = 0;


            $data['title'] = $this->errorTitle[$errorCode];
            $data['description'] = $this->errorDescription[$errorCode];


            //$this->load->view('edunexus/design_templates/header');
            $this->load->view('edunexus/error/errorUI', $data);
            //$this->load->view('edunexus/design_templates/footer');
        }


}
}

?>