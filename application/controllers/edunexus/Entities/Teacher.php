<?php
require_once('User.php');

class Teacher extends User{
  private $address=null;
  private $phoneno=null;
  private $ngo_id=null;
  private $qualification=null;
  public function __construct($details){
    if(empty($details['usertype'])){
        throw new UndefinedUserException("");

    }
    else if($details['usertype']!="4"){
        throw new UndefinedUserException(" incorrect value for key \"user\". Required: [Teacher = 4] Found: [Teacher=".$details['usertype']."]");

    }
    else{

          parent::__construct($details);
          $this->address=$details['address'] ?? parent::$errorCode;
          $this->phoneno=$details['phoneno'] ?? parent::$errorCode;
          $this->ngo_id=$details['ngo_id'] ?? parent::$errorCode;
          $this->qualification=$details['qualification'] ?? parent::$errorCode;
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

  public function getQualification(){
    return $this->qualification;
  }

}
?>
