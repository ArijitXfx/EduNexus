<?php
require_once('User.php');

class Admin extends User{

  public function __construct($details){
      if(empty($details['usertype'])){
          throw new UndefinedUserException("");

      }
      else if($details['usertype']!="1"){
          throw new UndefinedUserException(" incorrect value for key \"user\". Required: [Admin = 1] Found: [Admin=".$details['usertype']."]");

      }
      else{

          parent::__construct($details);
        }
  }

}
?>
