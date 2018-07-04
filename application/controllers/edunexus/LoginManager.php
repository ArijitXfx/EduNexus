<?php
/**
 * This is the LoginManager Controller of Edunexus
 * @author Sudipta Saha
 */
class LoginManager extends CI_Controller {
        private $cookie_name="ngo_edu-nexus";
        /**
         * This is the constructor for LoginManager Controller 
         */
        public function __construct()
        {
                parent::__construct();
                
                $this->load->helper('url_helper');
        }
        
        /**
         * The index function handles general login of the user. Supports login by password, cookie and session. 
         */
        public function index()
        {       
                require_once "Entities/Admin.php";
                require_once "Entities/NGO.php";
                require_once "Entities/NGOVolunteer.php";
                require_once "Entities/Teacher.php";
                $this->load->library('session');
                //$this->session->sess_destroy();
                
                $cookie_data=$this->getThisCookie();
                if($this->session->userdata('user_details')!=null){
                  $this->authenticateBySession();
                }
                else if($cookie_data!=null){
                  $this->authenticateByCookie($cookie_data);

                }
                else{
                $data['errMsg']="";
                //$this->load->view('edunexus/design_templates/header');
                $this->load->view('edunexus/login/login', $data);
                $this->load->view('edunexus/design_templates/footer');
                }
        }
        /**
         * If the user is already logged in the same browser, then this function performs auto log in through session.
         */
        public function authenticateBySession(){

          $result=$this->session->userdata('user_details');
          $usertype=$result->getUsertype();

          if($usertype=="1"){
            redirect('edunexus/AdminManager','refresh');
          }
          else if($usertype=="2"){
            redirect('edunexus/NGOManager','refresh');
          }
          else if($usertype=="3"){
            redirect('edunexus/NGOVolunteerManager','refresh');
          }
          else if($usertype=="4"){
            redirect('edunexus/TeacherManager','refresh');
          }

        }

        /**
         * If the user is already logged in using remember me, then this function performs auto log in through cookie validations.
         * @param type $cookie_data The cookie stored in the browser.
         */
        public function authenticateByCookie($cookie_data){

          $cookie_value=json_decode($cookie_data,true);
          $username=$cookie_value['username'];
          $usertype=$cookie_value['usertype'];
          $cookie_id=$cookie_value['cookie_id'];

          $this->load->model('edunexus/LoginManager_model');
          $result=$this->LoginManager_model->authenticateByCookie($username,$cookie_id, $usertype);
          if($result['success']=="false"){
            $data['errMsg']="";
            $this->deleteThisCookie();
            //$this->load->view('edunexus/design_templates/header');
            $this->load->view('edunexus/login/login', $data);
            $this->load->view('edunexus/design_templates/footer');

          }
          else{
            unset($result['success']);
            $result['usertype']=$usertype;
            $this->load->library('session');
            $this->session->set_userdata('cookie_id', $cookie_id);
            $this->linkToProfile($result);
            //  $this->getThisCookie("ngo_edu-nexus");
            //  $this->deleteThisCookie("ngo_edu-nexus");
            }


        }

        /**
         * This function performs authentication using username and password.
         */
        public function authenticate(){
            $this->load->model('edunexus/LoginManager_model');
            $username=$this->input->post('username');
            $password=$this->input->post('password');
            $usertype=$this->input->post('usertype');
            $result=$this->LoginManager_model->authenticateByPassword($username,$password, $usertype);

            if($result['success']=="false"){
              $data['errMsg']="Login unsuccessful";
              //$this->load->view('edunexus/design_templates/header');
              $this->load->view('edunexus/login/login', $data);
              $this->load->view('edunexus/design_templates/footer');
            }
            else{
              unset($result['success']);
              $usertype=$this->input->post('usertype');
              $result['usertype']=$usertype;
              $rememberme=$this->input->post('rememberme');
              if($rememberme==true){
                $cookie_id=time()."-".$result['id'];
                $this->setThisCookie($username,$usertype,$cookie_id);
                $this->load->library('session');
                $this->session->set_userdata('cookie_id', $cookie_id);
                $cookie_result=$this->LoginManager_model->setThisCookieData($username, $usertype, $cookie_id);
                if($cookie_result['success']=="false"){
                 
           $this->session->set_flashdata('errorCode',$result['errorCode']);
                redirect('edunexus/ErrorController','refresh');
                }

              }

              $this->linkToProfile($result);

            }
          }

          /**
           * This function transfers control to the account associated with the usertype
           * @param type $user_details The details of the user.
           */
          public function linkToProfile($user_details){
            $this->load->library('session');

            if($user_details['usertype']=="1"){

              require "Entities/Admin.php";
              $user=new Admin($user_details);
              $this->session->set_userdata('user_details', $user);
              redirect('edunexus/AdminManager','refresh');
            }
            else if($user_details['usertype']=="2"){

              require "Entities/NGO.php";
              $user=new NGO($user_details);
              $this->session->set_userdata('user_details', $user);
              redirect('edunexus/NGOManager','refresh');
            }
            else if($user_details['usertype']=="3"){

              require "Entities/NGOVolunteer.php";
              $user=new NGOVolunteer($user_details);
              $this->session->set_userdata('user_details', $user);
              redirect('edunexus/NGOVolunteerManager','refresh');
            }
            else if($user_details['usertype']=="4"){

              require "Entities/Teacher.php";
              $user=new Teacher($user_details);
              $this->session->set_userdata('user_details', $user);
              redirect('edunexus/TeacherManager','refresh');
            }

          }
          
          /**
           * Set cookie into the browser
           * @param type $username email id of the logged in user.
           * @param type $usertype user type of the logged in user.
           * @param type $cookie_id
           */
          public function setThisCookie($username, $usertype, $cookie_id){
            $this->load->helper('cookie');
            $cookie_value['username']=$username;
            $cookie_value['usertype']=$usertype;
            $cookie_value['cookie_id']=$cookie_id;
            $cookie_value= json_encode($cookie_value);

            $cookie_data= array(
              'name' => $this->cookie_name,
              'value' =>$cookie_value,
              'expire' => 7776000,
            );

            $this->input->set_cookie($cookie_data);
            }
            
          /**
           * Get the saved cookie from the browser.
           * @return cookie_name The cookie stored in the browser 
           */  
          public function getThisCookie(){
            $this->load->helper('cookie');
            return $this->input->cookie($this->cookie_name);
          }
          
          /**
           * Delete the saved cookie from the browser
           */
          public function deleteThisCookie(){
            $this->load->helper('cookie');
            delete_cookie($this->cookie_name);
          }


          /**
           * This function is called when an user opts for forgotPassword option 
           */
          public function forgotPassword(){
            $this->load->model('edunexus/LoginManager_model');
            $username=$this->input->post('username');
            $usertype=$this->input->post('usertypesel');
            $result=$this->LoginManager_model->getSecuredInfo($username, $usertype);

            if($result['success']=="false" && $result['errorCode']=="2"){
              $data['errMsg']="Cannot find relevant user.";
              $this->load->view('edunexus/login/login', $data);
            }

            else if($result['success']=="false" && $result['errorCode']=="1"){
                $data['errMsg']="Database Error. Try after sometime";
                $this->load->view('edunexus/login/login', $data);
              }

            else{
                unset($result['success']);
                $recipentName=$result['name'];
                $recipentAddress=$username;
                $result['usertype']=$usertype;
                $otp=mt_rand(100000, 999999);

                $this->load->library('session');
                $this->session->set_userdata('gen_otp', $otp);
                $this->session->set_userdata('fp_user_details', $result);
                $this->session->set_userdata('option',"forgotPassword");
                $this->load->model('edunexus/NotificationManager_model');
                $result=$this->NotificationManager_model->sendForgotPasswordMail($recipentName,$recipentAddress,$otp);
               if($result['success']=="true"){
                $data['chance']=3;
                $data['errMsg']="";
                $this->load->view('edunexus/confirmOTP',$data);
              }
              else{
                $this->session->set_flashdata('errorCode',$result['errorCode']);
                redirect('edunexus/ErrorController','refresh');
              }
            }

        }
        
        /**
         * This function is called when user opts to register as a teacher
         */
        public function registerLinkTeacher(){

          $this->load->model('edunexus/TeacherManager_model');
          $result=$this->TeacherManager_model->getNGOList();
          if($result['success']=="true"){
            $data['ngoList']=array_slice($result, 0, $result['noofresults']);
            $data['errMsg']="";
            $this->load->library('session');
            $this->session->set_userdata('ngoList', $data['ngoList']);
            $this->load->view('edunexus/login/registerTeacher',$data);
           }
          else{
           $this->session->set_flashdata('errorCode',$result['errorCode']);
                redirect('edunexus/ErrorController','refresh');
          }


        }

        /**
         * This function is called once the form for register teacher is complete
         */
        public function initiateRegisterTeacher(){
          $this->load->model('edunexus/LoginManager_model');
          $username=$this->input->post('username');
          $result=$this->LoginManager_model->checkIfDuplicateUsername($username);
          if($result['success']=="true" && $result['count']!="0"){
            $data['errMsg']="Duplicate username. Please try again with new username.";
            $this->load->library('session');
            $data['ngoList']=$this->session->userdata('ngoList');
            $this->load->view('edunexus/login/registerTeacher',$data);
         
          }
          else if($result['success']=="true" && $result['count']=="0"){
            
            $config['upload_path']="./resources/resume/";
            $config['allowed_types'] = 'pdf';

            $this->load->library('upload',$config);
            if ( ! $this->upload->do_upload('qualification'))
                {
                          $this->load->library('session');
                          $data['ngoList']=$this->session->userdata('ngoList');
                          $data['errMsg']= "Resume file size should be less than or equal to ".ini_get("upload_max_filesize")."B";
                          $this->load->view('edunexus/login/registerTeacher',$data);
                         
                }
           else 
                {
                      $this->load->library('session');
                      $this->session->unset_userdata('ngoList');
                      $user_details['ngo_id']=$this->input->post('ngo_id');
                      $user_details['name']=$this->input->post('name');
                      $user_details['phoneno']=$this->input->post('phoneno');
                      $user_details['address']=$this->input->post('address');
                      $user_details['username']=$this->input->post('username');
                      $user_details['password']=$this->input->post('password');
                      $user_details['qualification']='resources/resume/'.$this->upload->data()['file_name'];
                      $user_details['usertype']="4";
                      $recipentName=$user_details['name'];
                      $recipentAddress=$user_details['username'];
                      $usertype="4";
                      $otp=mt_rand(100000, 999999);

                      $this->load->library('session');
                      $this->session->set_userdata('gen_otp', $otp);
                      $this->session->set_userdata('tr_user_details', $user_details);
                      $this->session->set_userdata('option',"registerTeacher");
                      $this->load->model('edunexus/NotificationManager_model');
                      $result=$this->NotificationManager_model->sendTeacherRegisterMailToTeacher($recipentName,$recipentAddress,$otp);
                      if($result['success']=="true"){
                       $data['chance']=3;
                       $data['errMsg']="";
                       $this->load->view('edunexus/confirmOTP',$data);
                     }
                     else{
                       $this->session->set_flashdata('errorCode',$result['errorCode']);
                redirect('edunexus/ErrorController','refresh');
                     }
                }

          }
          else if($result['success']=="false" && $result['errorCode']=="1"){
            $this->load->library('session');
           $this->session->set_flashdata('errorCode',$result['errorCode']);
                redirect('edunexus/ErrorController','refresh');
          }

        }

        /**
         * This function is called once the email id is validated using match OTP
         */  
        public function registerTeacher(){
          $this->load->library('session');
          $user_details=$this->session->userdata('tr_user_details');
          $this->load->model('edunexus/TeacherManager_model');
          $result=$this->TeacherManager_model->registerTeacher($user_details);
          if($result['success']=="true"){

            $this->session->sess_destroy();

            $this->load->model('edunexus/NotificationManager_model');
            $ngoList=$this->TeacherManager_model->getNGODetailsFromID($user_details['ngo_id']);
            if($ngoList['success']=='true')
              $result=$this->NotificationManager_model->sendTeacherRegisterMailToNGO($ngoList['name'], $ngoList['username'], $user_details['name']);

          $data['title']="Application has been registered";
          $data['description']="Further to be verified by NGO!";
          $this->load->view('edunexus/resultLinkToIndex',$data);
          }
          else{

           $this->session->set_flashdata('errorCode',$result['errorCode']);
                redirect('edunexus/ErrorController','refresh');
          }

        }
        /**
         * This function facilitates the OTP matching
         */
        public function matchOTP(){
            $userotp=$this->input->post('otp');
            $chance=$this->input->post('chance');
            $this->load->library('session');
            $gen_otp=$this->session->userdata('gen_otp');
            if($userotp==$gen_otp){
              $option=$this->session->userdata('option');
              if($option=="forgotPassword"){
                $this->session->unset_userdata('gen_otp');
                $this->load->view('edunexus/login/changeAfterForgotPassword');
              }
              else if($option=="registerTeacher"){
                $this->session->unset_userdata('gen_otp');
                redirect('edunexus/LoginManager/registerTeacher','refresh');
              }
            }
            else{
              if($chance>0){
              $data['chance']=$chance-1;

              $data['errMsg']="OTP didn't match. You have $chance more turns left!";
              $this->load->view('edunexus/confirmOTP',$data);
              }
              else{
                $this->session->sess_destroy();
                redirect('edunexus/LoginManager','refresh');
              }
            }
        }
        /**
         * This function is called after matchOTP is verified
         */
        
        public function changeAfterUpdatePassword(){
            $this->load->library('session');
            $oldpassword=trim($this->input->post('oldpassword'));
            $newpassword=trim($this->input->post('newpassword'));
            $result=$this->session->userdata('user_details');
            $username=$result['username'];
            $usertype=$result['usertype'];
            $this->load->model('edunexus/LoginManager_model');
            $result=$this->LoginManager_model->changePassword($username,$oldpassword,$newpassword,$usertype,false);
            if($result['success']=="false"){
               
           $this->session->set_flashdata('errorCode',$result['errorCode']);
                redirect('edunexus/ErrorController','refresh');
            }
            else{

              $data['title']="Password Changed Successfully!";
              $data['description']="Login again to access portal!";
              $this->load->view('edunexus/resultLinkToIndex',$data);
              $this->load->view('edunexus/design_templates/footer');
            }
          
          }

          /**
           * This function is called when the match OTP is verified and user has filled in new password as a result of forgotPassword
           */
          public function changeAfterForgotPassword(){
            $this->load->library('session');
            $newpassword=trim($this->input->post('newpassword'));
            $result=$this->session->userdata('fp_user_details');
            $oldpassword=$result['password'];
            $username=$result['username'];
            $usertype=$result['usertype'];
            $this->load->model('edunexus/LoginManager_model');
            $result=$this->LoginManager_model->changePassword($username,$oldpassword,$newpassword,$usertype,true);
            if($result['success']=="false"){
               
           $this->session->set_flashdata('errorCode',$result['errorCode']);
                redirect('edunexus/ErrorController','refresh');
            }
            else{
              $this->session->sess_destroy();
              $data['title']="Password Changed Successfully!";
              $data['description']="---";
              $this->load->view('edunexus/resultLinkToIndex',$data);
              
          
          }
        }

          /**
           * Returns the details of the logged in user.
           */
          public function getUserDetails(){
            
              require_once "Entities/Admin.php";
              require_once "Entities/NGO.php";
              require_once "Entities/NGOVolunteer.php";
              require_once "Entities/Teacher.php";

            $this->load->library('session');
            $result=$this->session->userdata('user_details');
            $user_details['usertype']=$result->getUsertype();
            $user_details['username']=$result->getUsername();
            $user_details['name']=$result->getName();
            if($user_details['usertype']!="1"){            
            $user_details['address']=$result->getAddress();
            $user_details['phoneno']=$result->getPhoneno();
            
            }
            if($user_details['usertype']=="4")
                  $user_details['qualification']=$result->getQualification();
            
            echo json_encode($user_details);      
          }

         /**
          * This function is called when the logged in user clicks settings option.
          */ 
          public function settingsPanel(){
              require_once "Entities/Admin.php";
              require_once "Entities/NGO.php";
              require_once "Entities/NGOVolunteer.php";
              require_once "Entities/Teacher.php";
              $this->load->library('session');
              if($this->session->userdata('user_details')!=null){
                $data['usertype'] = $this->session->userdata('user_details')->getUsertype();
                $this->load->view('edunexus/design_templates/header');
                $this->load->view('edunexus/userSettings',$data);
                $this->load->view('edunexus/design_templates/footer');
              }else{
                redirect('edunexus/LoginManager','refresh');
              }
          }
          
          /**
           * This function is called when new user details need to be updated.
           */
          public function changeUserDetails(){

            require_once "Entities/Admin.php";
            require_once "Entities/NGO.php";
            require_once "Entities/NGOVolunteer.php";
            require_once "Entities/Teacher.php";

            $this->load->library('session');
            $result=$this->session->userdata('user_details');
            $user_details['id']=$result->getId();
            $user_details['usertype']=$result->getUsertype();
            $user_details['username']=$result->getUsername();
            $user_details['name']=$this->input->post('name');
            
            if($user_details['usertype']!="1"){
              $user_details['address']=$this->input->post('address');
              $user_details['phoneno']=$this->input->post('phoneno');
            }
            
            if($user_details['usertype']=="4"){
              $qualification=$this->input->post('qualification');
              
              if($qualification=="undefined"){
                  $user_details['qualification']=$this->session->userdata('user_details')->getQualification();
              }else{
                $config['upload_path']="./resources/resume/";
                $config['allowed_types'] = 'pdf';

                $this->load->library('upload',$config);
                if ( ! $this->upload->do_upload('qualification'))
                    {
                      $result['errorCode']= "11";
                      $result['success'] = "false";
                      $result['errMsg'] = "Resume file size should be less than or equal to ".ini_get("upload_max_filesize")."B";
                      echo json_encode($result);
                    }
                else
                {
                      $user_details['qualification']='resources/resume/'.$this->upload->data()['file_name'];             
                }

              }
              
            }

              $this->load->model('edunexus/LoginManager_model');
              $value=$this->LoginManager_model->changeUserDetails($user_details);
              if($value["success"]=="true"){
                $this->load->library('session');
                if($user_details['usertype']=="1")
                  $user=new Admin($user_details);
                

                else if($user_details['usertype']=="2"){
                  $user_details['admin_id']=$result->getAdmin_id();
                  $user=new NGO($user_details);
                }


                else if($user_details['usertype']=="3"){
                  $user_details['ngo_id']=$result->getNgo_id();
                  $user=new NGOVolunteer($user_details);
                }


                else if($user_details['usertype']=="4"){
                  $user_details['ngo_id']=$result->getNgo_id();
                  $user=new Teacher($user_details);
                }


                $this->session->set_userdata('user_details', $user);
              }
              echo json_encode($value);
          }
          
         /**
           * This function is called when user has updated the password through settings panel.
           */ 
          public function changePassword(){

            require "Entities/Admin.php";
            require "Entities/NGO.php";
            require "Entities/NGOVolunteer.php";
            require "Entities/Teacher.php";
            $this->load->library('session');
            $result=$this->session->userdata('user_details');
            $usertype=$result->getUsertype();
            $username=$result->getUsername();
            $oldpassword=$this->input->post('oldpassword');
            $newpassword=$this->input->post('newpassword');
            $this->load->model('edunexus/LoginManager_model');
            echo json_encode($this->LoginManager_model->changePassword($username, $oldpassword, $newpassword, $usertype, false));
          }
          
          /**
           * This function is called when a logged in user opts to delete account.
           */
          public function deleteMyAccount(){
            require "Entities/Admin.php";
            require "Entities/NGO.php";
            require "Entities/NGOVolunteer.php";
            require "Entities/Teacher.php";
            $this->load->library('session');
            $result=$this->session->userdata('user_details');
            $usertype=$result->getUsertype();
            $username=$result->getUsername();
            $this->load->model('edunexus/LoginManager_model');
            $result=$this->LoginManager_model->deleteMyAccount($username, $usertype);
            if($result['success']=="true"){
              $this->session->unset_userdata('user_details');

               if($this->session->userdata('cookie_id')!=null){
                  $cookie_id=$this->session->userdata('cookie_id');
                  $this->session->unset_userdata('cookie_id');
                  $this->deleteThisCookie();
                  $result=$this->LoginManager_model->deleteThisCookieData($username, $cookie_id, $usertype);
                  if($result['success']=="false" && $result['errorCode']=="1"){
                    $this->session->sess_destroy();
                     
           $this->session->set_flashdata('errorCode',$result['errorCode']);
                redirect('edunexus/ErrorController','refresh');
                  }
                  else{
                    $this->session->sess_destroy();
                    $data['title']="Account Deleted!";
                    $data['description']="Your account has been deleted successfully. We are sad to see you leave our portal :(";
                    $this->load->view('edunexus/resultLinkToIndex',$data);
                  }

             
              }
              else{
             
                 $this->session->sess_destroy();
                  $data['title']="Account Deleted!";
                  $data['description']="Your account has been deleted successfully. We are sad to see you leave our portal :(";
                  $this->load->view('edunexus/resultLinkToIndex',$data);
              }
          }
            else{
               
           $this->session->set_flashdata('errorCode',$result['errorCode']);
                redirect('edunexus/ErrorController','refresh');
            }
          }
          
          /**
           * This function is called when an user opts to logout 
           */
          public function logout(){
            require_once "Entities/Admin.php";
            require_once "Entities/NGO.php";
            require_once "Entities/NGOVolunteer.php";
            require_once "Entities/Teacher.php";
            $this->load->library('session');
            if($this->session->userdata('user_details')!=null){

              

              $user_details=$this->session->userdata('user_details');

              $this->session->unset_userdata('user_details');
              if($this->session->userdata('cookie_id')!=null){
                  $cookie_id=$this->session->userdata('cookie_id');
                  $this->session->unset_userdata('cookie_id');
                  $this->deleteThisCookie();
                  $this->load->model('edunexus/LoginManager_model');
                  $username=$user_details->getUsername();
                  $usertype=$user_details->getUsertype();
                  $result=$this->LoginManager_model->deleteThisCookieData($username, $usertype, $cookie_id);
                  if($result['success']=="false" && $result['errorCode']=="1"){
                    $this->session->sess_destroy();
                     
           $this->session->set_flashdata('errorCode',$result['errorCode']);
                redirect('edunexus/ErrorController','refresh');
                  }
                  else{
                    $this->session->sess_destroy();
                    $data['title']="Logout Successful";
                    $data['description']="You have successfully logged out of the system. Click below to login in again!";
                    $this->load->view('edunexus/resultLinkToIndex',$data);
                  }


              }
              else{
                $this->session->sess_destroy();
              $data['title']="Logout Successful";
              $data['description']="You have successfully logged out of the system. Click below to login in again!";
              $this->load->view('edunexus/resultLinkToIndex',$data);
  
              }

              
            }

            else{
              redirect('edunexus/LoginManager','refresh');
    
            }
            

          }

}

?>
