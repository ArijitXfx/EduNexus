<?php
/**user :
 1- Admin
 2- NGO
 3- NGOVolunteer
 4- Teacher
 */
require_once("application/core/UndefinedUserException.php");
class User{
  private $usertype=null;
  private $id=null;
  private $name=null;
  private $username=null;

  static protected $errorCode='{ "errorCode" : "3" }';

  public function __construct($details){
          //$this->load->model('news_model');

          $this->usertype=$details['usertype'];
          $this->id=$details['id'] ?? $this->errorCode;
          $this->name=$details['name'] ?? $this->errorCode;
          $this->username=$details['username'] ?? $this->errorCode;



  }

  public function getId(){
    return $this->id;
  }
  public function getName(){
    return $this->name;
  }
  public function getUsername(){
    return $this->username;
  }

  public function getUsertype(){
    return $this->usertype;
  }

}
?>
