<?php
require_once('User.php');

class NGOVolunteer extends User{
  private $address=null;
  private $phoneno=null;
  private $ngo_id=null;

  public function __construct($details){

    if(empty($details['usertype'])){
        throw new UndefinedUserException("");

    }
    else if($details['usertype']!="3"){
        throw new UndefinedUserException(" incorrect value for key \"user\". Required: [NGOVolunteer = 3] Found: [NGOVolunteer=".$details['usertype']."]");

    }
    else{

          parent::__construct($details);
          $this->address=$details['address'] ?? parent::$errorCode;
          $this->phoneno=$details['phoneno'] ?? parent::$errorCode;
          $this->ngo_id=$details['ngo_id'] ?? parent::$errorCode;
        }
  }

  public function getAddress(){
    return $this->address;
  }
  public function getPhoneno(){
    return $this->phoneno;
  }
  public function getNgo_id(){
    return $this->ngo_id;
  }

}
?>
