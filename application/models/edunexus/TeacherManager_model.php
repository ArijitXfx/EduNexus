<?php
/**
 * Model class
 * @auhtor Sudipta Saha
 */
class TeacherManager_model extends CI_Model{

  public function __construct()
  {
          $this->load->database();
  }

  /**
   * Inserts a teacher in database
   * @param array $teacher Teacher details 
   * @return array Contains success and errorCode
   */
  public function registerTeacher($teacher){
          $result;
          $con = get_instance()->db->conn_id;
          $table_name="teacher_record";

          $ngo_id = $teacher['ngo_id']; 
          $name=mysqli_real_escape_string($con, $teacher['name']);
          $username=mysqli_real_escape_string($con, strtolower($teacher['username']));
          $password=md5(mysqli_real_escape_string($con, $teacher['password']));
          $qualification=mysqli_real_escape_string($con, $teacher['qualification']);
          $address=mysqli_real_escape_string($con, $teacher['address']);
          $phoneno=mysqli_real_escape_string($con, $teacher['phoneno']);

          $query="INSERT INTO $table_name (`ngo_id`,`username`,`password`,`name`,`phoneno`,`address`,`qualification`) VALUES ('$ngo_id','$username','$password','$name','$phoneno','$address','$qualification');";

          try{
            if (!$this->db->query($query)) {
              $result['success']="false";
              $result['errorCode']="1";
              return $result;
            }
            else {
              $result['success']="true";
              $result['errorCode']="0";
              return $result;
            }
          }
          catch(Exception $e){
            $result['success']="false";
            $result['errorCode']=(string)$this->db->_error_number();
            return $result;

            }


        }

  /**
   * Fetches name and username of an ngo from database
   * @param type $ngo_id
   * @return string
   */
  public function getNGODetailsFromID($ngo_id){
    $result;
    $table_name="ngo_record";
    try{
    $query = $this->db->query("SELECT name, username FROM `$table_name` WHERE `id`='$ngo_id';");
    }
    catch(Exception $e){
        $result['success']="false";
        $result['errorCode']="1";
        return $result;

    }

   

  $result=$query->row_array();
  $result['success']="true";
  $result['errorCode']="0";

    return $result;
  }

  public function getNGOList(){
    $result;
    $table_name="ngo_record";
    try{
    $query = $this->db->query("SELECT id, name FROM `$table_name` WHERE active=true;");
    }
    catch(Exception $e){
        $result['success']="false";
        $result['errorCode']="1";
        return $result;

    }
    $i=$query->num_rows();


  $result=$query->result_array();
  $result['noofresults']=(string)$i;
  $result['success']="true";
  $result['errorCode']="0";

    return $result;
  }


}

?>
