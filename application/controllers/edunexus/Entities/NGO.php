<?php
require_once('User.php');

class NGO extends User{
  private $address=null;
  private $phoneno=null;
  private $admin_id=null;

  public function __construct($details){



    if(empty($details['usertype'])){
        throw new UndefinedUserException("");

    }
    else if($details['usertype']!="2"){
        throw new UndefinedUserException(" incorrect value for key \"user\". Required: [NGO = 2] Found: [NGO=".$details['usertype']."]");

    }
    else{
          parent::__construct($details);
          $this->address=$details['address'] ?? parent::$errorCode;
          $this->phoneno=$details['phoneno'] ?? parent::$errorCode;
          $this->admin_id=$details['admin_id'] ?? parent::$errorCode;
        }
  }

  public function getAddress(){
    return $this->address;
  }
  public function getPhoneno(){
    return $this->phoneno;
  }
  public function getAdmin_id(){
    return $this->admin_id;
  }

}
?>
