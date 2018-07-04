<?php

/**
 * Discussion Portal Manager manage Discussion Portal and Discussion Thread
 * 
 * @author Arijit Basu <thearijitxfx@gmail.com>
 * 
 * Version 1.0
 */
require "Entities/NGOVolunteer.php";
require "Entities/Teacher.php";

class DiscussionPortalManager extends CI_Controller {
        
        /**
         * Constructor
         */
        public function __construct()
        {
            parent::__construct();
            $this->load->model('edunexus/DiscussionPortalManager_model');
            $this->load->helper('url_helper');
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->load->library('session');
            if($this->session->userdata('user_details')==null)
                    redirect('edunexus/LoginManager','refresh');
            $this->admin_id = $this->session->userdata('user_details')->getId();
        }
           
        /**
         * Open Discussion Portal Page
         * 
         * @param void do not have any parameter
         * 
         * @return void not returning anything
         */
        public function index()
        { 
            $data['course'] = $this->session->userdata('course_id');
            $data['usertype'] = "volunteer";
            $data['name'] = $this->session->userdata('user_details')->getName();
            if($this->session->userdata('user_details')->getUsertype()==="4")
                $data['usertype'] = "teacher";

            $this->load->view('edunexus/design_templates/header');
            $this->load->view('edunexus/discussion/index',$data);
            $this->load->view('edunexus/design_templates/footer');
        }

        /**
         * Gather all or a particular discussion(s)
         * 
         * @param void do not have any parameter
         * 
         * @return void not returning anything
         */
        public function getDiscussion(){
            $all = $this->input->post('all');
            if($all==="true"){
                $course_id = $this->session->userdata('course_id');
                $limit = $this->input->post('limit');
                $offset = $this->input->post('offset');
                $keyword = $this->input->post('keyword');
                $result = $this->DiscussionPortalManager_model->getDiscussion($all,Null,$course_id,$limit,$offset,$keyword);
            }
            else{
                $id = $this->input->post('id');
                $result = $this->DiscussionPortalManager_model->getDiscussion($all,$id,Null,Null,Null,Null);
            }
            echo json_encode($result);
        }

        /**
         * Gather all Answers and comments of a particular Question
         * 
         * @param void do not have any parameter
         * 
         * @return void not returning anything
         */
        public function getAnswer(){
            $id = $this->input->post('id');
            $limit = $this->input->post('limit');
            $offset = $this->input->post('offset');
            $userName = $this->session->userdata('user_details')->getUsername();
            $result = $this->DiscussionPortalManager_model->getAnswer($id,$limit,$offset,$userName);
            echo json_encode($result);
        }
        
        
        /**
         * Open Discussion Thread with required discussion id
         * 
         * @param void do not have any parameter
         * 
         * @return void not returning anything
         */
        public function view()
        {
            $id = $this->input->get('id');
            $data['discussion_id'] = $id;
            $this->load->view('edunexus/design_templates/header');
            $this->load->view('edunexus/discussion/view_answer', $data);
            $this->load->view('edunexus/design_templates/footer');
            
        }

        /**
         * Add a link to a question which was already asked 
         * 
         * @param void do not have any parameter
         * 
         * @return void not returning anything
         */
        public function setDuplicateLink(){
            $id = $this->input->post('id');
            $duplicateLink = $this->input->post('duplicatelink');
            $query = $this->DiscussionPortalManager_model->setDuplicateLink($id,$duplicateLink);
            echo json_encode($query);
        }
        
        /**
         * Cancel duplicate link 
         * @param void do not have any parameter
         * 
         * @return void not returning anything
         */

        public function cancelDuplicateLink(){
            $id = $this->input->post('id');
            $query = $this->DiscussionPortalManager_model->cancelDuplicateLink($id);
            echo json_encode($query);
        }
        
        /**
         * Write a new question
         * @param void do not have any parameter
         * 
         * @return void not returning anything
         */
        public function createDiscussion()
        {
        
            $course_id = $this->session->userdata('course_id');
            $result = $this->DiscussionPortalManager_model->getTeacherFromCourse($course_id); 
            $volunteer_id = $this->session->userdata('user_details')->getId();
            $query = $this->DiscussionPortalManager_model->setDiscussion($course_id,$_POST['title'],$_POST['description'],$volunteer_id);
            if($query['success'] == "false"){
                $this->session->set_flashdata('errorCode',$query['errorCode']);
                redirect('edunexus/ErrorController');
            }
            elseif ($result['success'] == "false") {
                $this->session->set_flashdata('errorCode',$result['errorCode']);
                redirect('edunexus/ErrorController');
            }
            else{
                $this->load->model('edunexus/NotificationManager_model');
                
                $discussionLink = site_url().'/edunexus/DiscussionPortalManager/view?id='.$query['lastId'];
                
                $result=$this->NotificationManager_model->sendNewDiscussionAlert($result['name'],  $result['username'], $this->session->userdata('user_details')->getName(), $discussionLink);
                
                if($result['success']=="true")
                    redirect('edunexus/DiscussionPortalManager/view?id='.$query['lastId']);
                else{
                    $this->session->set_flashdata('errorCode',$result['errorCode']);
                    redirect('edunexus/ErrorController'); 

                
                }
            }

            
        }

        /**
         * Post a new answer of a question
         * 
         * @param void do not have any parameter
         * 
         * @return void not returning anything
         */
        public function setAnswers(){

            $discussion_id = $this->input->post('id');
            $answer = $this->input->post('answer');
            $if_teacher = 1;
            if($this->session->userdata('user_details')->getUsertype()==='4'){
                $if_teacher = 1;
            }else{
                $if_teacher = 0;
            }
            $query = $this->DiscussionPortalManager_model->setAnswer($discussion_id,$if_teacher,$answer);
            echo json_encode($query);
        }

        /**
         * Set Upvote of a question
         * 
         * @param void do not have any parameter
         * 
         * @return void not returning anything
         */
        public function upvote(){
            $result;

            $id=$this->input->post('id');
            $upvote = $this->input->post('upvote');
            $downvote = $this->input->post('downvote');
            
            $userName = $this->session->userdata('user_details')->getUsername();
            $voteCount = $this->DiscussionPortalManager_model->getUpvoteDownvote($userName,$id);
            $proceed = true;

            $isUpvote = False;
            
            if($voteCount['errorCode'] === "2"){
                $upvote = $upvote + 1;
                $isUpvote = True;
            }else if($voteCount['errorCode']=="0"){
                if($voteCount['data'][0]->isupvote==0){
                  $upvote = $upvote + 1; 
                  $downvote = $downvote - 1; 
                  $isUpvote = True;
                  $this->DiscussionPortalManager_model->setDownvote($id,$downvote);
                }else{
                    $upvote = $upvote - 1;
                    $isUpvote = False;
                } 
            }else{
                $result['success'] = "false";
                $result['errorCode'] = "1";  
                $proceed = false;
            }
            if($proceed===true){
                 $query1 = $this->DiscussionPortalManager_model->setVoteCount($userName,$id,$isUpvote,$voteCount,1);
                
                  $query2 = $this->DiscussionPortalManager_model->setUpvote($id,$upvote);
            
                if($query1['success']=="true" && $query2['success']=="true"){
                        $result['success'] = "true";
                        $result['errorCode'] = "0";
                        $result['upvote'] = $upvote;
                        $result['downvote'] = $downvote;
                }else{
                        $result['success'] = "false";
                        $result['errorCode'] = "1";  
                }
            }
            echo json_encode($result);
            
        }

        /**
         * Set downvote of a question
         * 
         * @param void do not have any parameter
         * 
         * @return void not returning anything
         */
        public function downvote(){

            $result;

            $id = $this->input->post('id');
            $upvote = $this->input->post('upvote');
            $downvote = $this->input->post('downvote');
            $userName = $this->session->userdata('user_details')->getUsername();
            $voteCount = $this->DiscussionPortalManager_model->getUpvoteDownvote($userName,$id);

            $proceed = true;

            $isUpvote = True;
            if($voteCount['errorCode'] == "2"){
                $downvote = $downvote + 1;
                $isUpvote = False;
            }else if($voteCount['errorCode']=="0"){
                if($voteCount['data'][0]->isupvote==1){
                  $downvote = $downvote + 1; 
                  $upvote = $upvote - 1; 
                  $isUpvote = False;
                  $this->DiscussionPortalManager_model->setUpvote($id,$upvote);
                }else{
                    $downvote = $downvote - 1;
                    $isUpvote = True;
                } 
                
            }else{
                //Go to some error page
                $result['success'] = "false";
                $result['errorCode'] = "1";  
                $proceed = false;
            }
            if($proceed===true){
                $query1 = $this->DiscussionPortalManager_model->setVoteCount($userName,$id,$isUpvote,$voteCount,0);
                $query2 = $this->DiscussionPortalManager_model->setDownvote($id,$downvote);
                if($query1['success']=="true" && $query2['success']=="true"){
                            $result['success'] = "true";
                            $result['errorCode'] = "0";
                            $result['upvote'] = $upvote;
                            $result['downvote'] = $downvote;
                }else{
                            $result['success'] = "false";
                            $result['errorCode'] = "1";  
                }
            }
            echo json_encode($result);
        }
}
