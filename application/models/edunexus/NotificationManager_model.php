<?php

class NotificationManager_model extends CI_Model{

  private $fromName="NGO EDU-NEXUS";
  private $fromEmail="subho040995@gmail.com";

  public function __construct()
  {
          parent::__construct();
          $this->load->helper('url_helper');

    $this->load->library("phpmailer_config");

  }

  public function sendWelcomeMail($recipentName, $recipentAddress){

      $mail = $this->phpmailer_config->load();
      $mail->IsHTML(true);
      $mail->addAddress($recipentAddress);
      $mail->Subject='Greetings!';
      $data['recipentName']=$recipentName;
      $mailbody= $this->load->view('edunexus/templates/email_test',$data,true);
      $mail->Body =   $mailbody;
      if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
      }
      else {
      echo "Message sent!";
  }
  }

  public function sendForgotPasswordMail($recipentName, $recipentAddress, $otp){
      $result;
      $mail = $this->phpmailer_config->load();
      $mail->IsHTML(true);
      $mail->addAddress($recipentAddress);
      $mail->Subject='Password Change Request';
      $data['recipentName']=$recipentName;
      $data['otp']=$otp;
      $mailbody= $this->load->view('edunexus/email_templates/email_change_password',$data,true);
      $mail->Body =   $mailbody;
      if (!$mail->send()) {
        $result['success']="false";
        $result['errorCode']="5";
        return $result;
      }
      else {
        $result['success']="true";
        $result['errorCode']="0";
        return $result;
  }
  }

  public function sendTeacherRegisterMailToTeacher($recipentName, $recipentAddress, $otp){
      $result;
      $mail = $this->phpmailer_config->load();
      $mail->IsHTML(true);
      $mail->addAddress($recipentAddress);
      $mail->Subject='Verification for registration';
      $data['recipentName']=$recipentName;
      $data['otp']=$otp;
      $mailbody= $this->load->view('edunexus/email_templates/email_register_teacher_to_teacher',$data,true);
      $mail->Body =   $mailbody;
      if (!$mail->send()) {
        $result['success']="false";
        $result['errorCode']="5";
        return $result;
      }
      else {
        $result['success']="true";
        $result['errorCode']="0";
        return $result;

  }
  }

  public function sendTeacherRegisterMailToNGO($recipentName, $recipentAddress, $teacherName){
      $result;
      $mail = $this->phpmailer_config->load();
      $mail->IsHTML(true);
      $mail->addAddress($recipentAddress);
      $mail->Subject='Verification for registration';
      $data['recipentName']=$recipentName;
      $data['teacherName']=$teacherName;
      $mailbody= $this->load->view('edunexus/email_templates/email_register_teacher_to_ngo',$data,true);
      $mail->Body =   $mailbody;
      if (!$mail->send()) {
        $result['success']="false";
        $result['errorCode']="5";
        return $result;
      }
      else {
        $result['success']="true";
        $result['errorCode']="0";
        return $result;

  }
  }

  public function sendNewDiscussionAlert($recipentName,  $recipentAddress, $ngoVolunteer, $discussionLink){
      $result;
      $mail = $this->phpmailer_config->load();
      $mail->IsHTML(true);
      $mail->addAddress($recipentAddress);
      //  $recipentList = array('sandeepkhan08@gmail.com', 'subhomanutd040995@gmail.com');
      $mail->Subject='Request for answering to a newly posted question.';
      $data['ngoVolunteer']=$ngoVolunteer;
      $data['recipentName']=$recipentName;
      
      $data['discussionLink']=$discussionLink;
      $mailbody= $this->load->view('edunexus/email_templates/email_discussion_question',$data,true);
      $mail->Body =   $mailbody;
      if (!$mail->send()) {
        $result['success']="false";
        $result['errorCode']="5";
        return $result;
      }
      else {
        $result['success']="true";
        $result['errorCode']="0";
        return $result;

  }
  }

  public function sendNewAnswerAlert($recipentName,  $recipentAddress, $teacher, $discussionLink){
      $result;
      $mail = $this->phpmailer_config->load();
      $mail->IsHTML(true);
      $mail->addAddress($recipentAddress);
      //  $recipentList = array('sandeepkhan08@gmail.com', 'subhomanutd040995@gmail.com');
      $mail->Subject='New answer is available to a course\'s discussion.';
      $data['ngoVolunteer']=$ngoVolunteer;
      $data['discussionLink']=$discussionLink;
      $mailbody= $this->load->view('edunexus/email_templates/email_discussion_answer',$data,true);
      $mail->Body =   $mailbody;
      if (!$mail->send()) {
        $result['success']="false";
        $result['errorCode']="5";
        return $result;
      }
      else {
        $result['success']="true";
        $result['errorCode']="0";
        return $result;

  }
  }
}
?>
