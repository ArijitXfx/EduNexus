<?php
class LoginManager_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }

        public function authenticateByPassword($username,$password, $usertype){
            $result;
            $con = get_instance()->db->conn_id;
            $username=mysqli_real_escape_string($con, strtolower($username));
            $password=md5(mysqli_real_escape_string($con, $password));
            $usertype=mysqli_real_escape_string($con, $usertype);
            $userCases=array('XXX','admin_record','ngo_record','volunteer_record','teacher_record');
            $table_name=$userCases[$usertype];
            try{
              if($usertype=='4')
                $query = $this->db->query("SELECT *FROM `$table_name` WHERE username='$username' AND password='$password' AND active=true AND verified=true;");
              else
                $query = $this->db->query("SELECT *FROM `$table_name` WHERE username='$username' AND password='$password' AND active=true;");
            }
            catch(Exception $e){
                $result['success']="false";
                $result['errorCode']="1";
                return $result;

            }

          if($query->num_rows()>=1){
            $result= $query->row_array();
            $result['success']="true";
            $result['errorCode']="0";
            return $result;

          }

          else {
            $result['success']="false";
            $result['errorCode']="2";
            return $result;
          }
        }

          public function authenticateByCookie($username, $cookie_id, $usertype){
            $result;
            $con = get_instance()->db->conn_id;
            $username=mysqli_real_escape_string($con, strtolower($username));
            $usertype=mysqli_real_escape_string($con, $usertype);
            $userCases=array('XXX','admin_record','ngo_record','volunteer_record','teacher_record');
            $table_name=$userCases[$usertype];
            try{
              $query = $this->db->query("SELECT *FROM `$table_name` WHERE username='$username' AND cookie_id='$cookie_id';");
            }
            catch(Exception $e){
                $result['success']="false";
                $result['errorCode']="1";
                return $result;

            }

          if($query->num_rows()>=1){
            $result= $query->row_array();
            $result['success']="true";
            $result['errorCode']="0";
            return $result;

          }

          else {
            $result['success']="false";
            $result['errorCode']="2";
            return $result;
          }
        }




        public function getSecuredInfo($username,$usertype){
          $result;
          $userCases=array('XXX','admin_record','ngo_record','volunteer_record','teacher_record');


          $con = get_instance()->db->conn_id;
          $username=mysqli_real_escape_string($con, strtolower($username));
          $usertype=mysqli_real_escape_string($con, $usertype);
          $table_name=$userCases[$usertype];
          try{
            $query = $this->db->query("SELECT *FROM `$table_name` WHERE username='$username';");
            }
          catch(Exception $e){
              $result['success']="false";
              $result['errorCode']="1";
              return $result;

          }

        if($query->num_rows()>=1){
          $result= $query->row_array();
          $result['success']="true";
          $result['errorCode']="0";
          return $result;

        }

        else {
          $result['success']="false";
          $result['errorCode']="2";
          return $result;
        }
        }

        public function changePassword($username, $oldpassword, $newpassword, $usertype, $forgot){
          $result;
          $userCases=array('XXX','admin_record','ngo_record','volunteer_record','teacher_record');

          $table_name=$userCases[$usertype];
          $con = get_instance()->db->conn_id;
          $username=mysqli_real_escape_string($con, strtolower($username));

          if($forgot)
            $oldpassword=mysqli_real_escape_string($con, $oldpassword);
          else
            $oldpassword=md5(mysqli_real_escape_string($con, $oldpassword));

          $newpassword=md5(mysqli_real_escape_string($con, $newpassword));
          $query="UPDATE `$table_name` SET `password`='$newpassword' WHERE `username`='$username' AND `password`='$oldpassword';";
        //  echo $query;

          try{
            if ($this->db->query($query) && $this->db->affected_rows() > 0){
              $result['success']="true";
              $result['errorCode']="0";
              return $result;
            }
            else{
              $result['success']="false";
              $result['errorCode']="8";
              return $result;
            }
          }
          catch(Exception $e){
            $result['success']="false";
            $result['errorCode']="1";
            return $result;

          }
        }

        public function changeUserDetails($user_details){
         $result;
         $userCases=array('XXX','admin_record','ngo_record','volunteer_record','teacher_record');
         $table_name=$userCases[$user_details['usertype']];
         $con = get_instance()->db->conn_id;
         $username=$user_details['username'];
         $usertype=$user_details['usertype'];
         $name=mysqli_real_escape_string($con, $user_details['name']);
         $query="UPDATE `$table_name` SET `name`='$name' WHERE `username`='$username';";
          
         if($usertype!="1"){
            $address=mysqli_real_escape_string($con, $user_details['address']);
            $phoneno=mysqli_real_escape_string($con, $user_details['phoneno']);
            $query="UPDATE `$table_name` SET `name`='$name',  `address`='$address', `phoneno`='$phoneno' WHERE `username`='$username';";
          
         }
         
         if($usertype=="4"){
           $qualification=mysqli_real_escape_string($con, $user_details['qualification']);
           $query="UPDATE `$table_name` SET `name`='$name',  `address`='$address', `phoneno`='$phoneno', `qualification`='$qualification'  WHERE `username`='$username';";
         }
         
         try{
           if ($this->db->query($query)){
               if($this->db->affected_rows() > 0){
                   $result['success']="true";
                   $result['errorCode']="0";
               }
               else{
                   $result['success']="false";
                   $result['errorCode']="6";
               }
             return $result;
           }
           else{
                   $result['success']="false";
                   $result['errorCode']="1";
                   return $result;
           }

         }
         catch(Exception $e){
           $result['success']="false";
           $result['errorCode']="1";
           return $result;

         }

       }

       public function setThisCookieData($username, $usertype, $cookie_id){
         $result;
         $con = get_instance()->db->conn_id;
         $username=mysqli_real_escape_string($con, strtolower($username));
         $usertype=mysqli_real_escape_string($con, $usertype);


         $userCases=array('XXX','admin_record','ngo_record','volunteer_record','teacher_record');


         $table_name=$userCases[$usertype];

         $query="UPDATE `$table_name` SET `cookie_id`='$cookie_id' WHERE `username`='$username';";
         try{
           if ($this->db->query($query)){
               if($this->db->affected_rows() > 0){
                   $result['success']="true";
                   $result['errorCode']="0";
               }
               else{
                   $result['success']="false";
                   $result['errorCode']="6";
               }
             return $result;
           }
           else{
                   $result['success']="false";
                   $result['errorCode']="1";
                   return $result;
           }

         }
         catch(Exception $e){
           $result['success']="false";
           $result['errorCode']="1";
           return $result;

         }

       }
       public function getThisCookieData($username, $usertype, $cookie_id){
         $result;
         $con = get_instance()->db->conn_id;
         $username=mysqli_real_escape_string($con, strtolower($username));
         $usertype=mysqli_real_escape_string($con, $usertype);


         $userCases=array('XXX','admin_record','ngo_record','volunteer_record','teacher_record');


         $table_name=$userCases[$usertype];
          try{
           $query = $this->db->query("SELECT *FROM `$table_name` WHERE `username`='$username' AND `cookie_id`='$cookie_id';");
           }
         catch(Exception $e){
             $result['success']="false";
             $result['errorCode']="1";
             return $result;

         }

       if($query->num_rows()>=1){
         $result= $query->row_array();
         $result['success']="true";
         $result['errorCode']="0";
         return $result;

       }

       else {
         $result['success']="false";
         $result['errorCode']="2";
         return $result;
       }
       }



       public function deleteThisCookieData($username, $usertype, $cookie_id){
         $result;
         $con = get_instance()->db->conn_id;
         $username=mysqli_real_escape_string($con, strtolower($username));
         $usertype=mysqli_real_escape_string($con, $usertype);


         $userCases=array('XXX','admin_record','ngo_record','volunteer_record','teacher_record');


         $table_name=$userCases[$usertype];

         $query="UPDATE `$table_name` SET `cookie_id`=null WHERE `username`='$username';";
         try{
           if ($this->db->query($query)){
               if($this->db->affected_rows() > 0){
                   $result['success']="true";
                   $result['errorCode']="0";
               }
               else{
                   $result['success']="false";
                   $result['errorCode']="6";
               }
             return $result;
           }
           else{
                   $result['success']="false";
                   $result['errorCode']="1";
                   return $result;
           }

         }
         catch(Exception $e){
           $result['success']="false";
           $result['errorCode']="1";
           return $result;

         }
       }

       public function deleteMyAccount($username, $usertype){
         $result;

         $userCases=array('XXX','admin_record','ngo_record','volunteer_record','teacher_record');


         $table_name=$userCases[$usertype];

         $query="UPDATE `$table_name` SET `active`=false WHERE `username`='$username';";
         try{
           if ($this->db->query($query)){
               if($this->db->affected_rows() > 0){
                   $result['success']="true";
                   $result['errorCode']="0";
               }
               else{
                   $result['success']="false";
                   $result['errorCode']="6";
               }
             return $result;
           }
           else{
                   $result['success']="false";
                   $result['errorCode']="1";
                   return $result;
           }

         }
         catch(Exception $e){
           $result['success']="false";
           $result['errorCode']="1";
           return $result;

         }
       }

   public function checkIfDuplicateUsername($username){
    $result;
    $con = get_instance()->db->conn_id;
    $username=mysqli_real_escape_string($con, $username);
    try{
      $query = $this->db->query("SELECT `username` FROM (SELECT username FROM `admin_record` UNION SELECT username FROM `ngo_record` UNION SELECT username FROM `volunteer_record` UNION SELECT username FROM `teacher_record`) as `username` WHERE `username`='$username';");
    }
    catch(Exception $e){
        $result['success']="false";
        $result['errorCode']="1";
        return $result;

    }
    $i=$query->num_rows();
    $result['count']=(string)$i;
    $result['success']="true";
    $result['errorCode']="0";

    return $result;
  }

}

?>
