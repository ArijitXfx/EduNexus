<?php
class CourseManager_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }

        
        /**
         * This function returns array of verified courses.
         * @param type $ngo_id The id of the ngo who has verified the course
         * @param type $keyword Matching keyword with title for search
         * @param array $filter_clause Specific clauses that need to be matched for search
         * @param type $limit How many entries do you need.
         * @param type $offset From what index do you need entries.
         * @return array all the verified courses
         */
        public function getVerifiedCourses($ngo_id, $keyword, $filter_clause, $limit, $offset){
          $result;
          $filter_clause['c.verified'] = 1;
          $table_name="courses";
          try{
            $this->db->select('c.*,t.name');
            $this->db->from('courses as c');
            $this->db->join('teacher_record as t','c.teacher_id=t.id','inner');
            $this->db->where('c.ngo_id',$ngo_id);
            if($keyword!=null){
              $con = get_instance()->db->conn_id;
              $keyword = mysqli_real_escape_string($con, $keyword); 
              $this->db->like('title',$keyword);
            }
            $this->db->where($filter_clause);
            $this->db->limit($limit,$offset);
            $query = $this->db->get();
          }
          catch(Exception $e){
              $result['success']="false";
              $result['errorCode']="1";
              return $result;
          }

          $i=0;
          $result['success']="true";
          $result['errorCode']="0";

          if($query->num_rows()==0){
            $i=0;
            $result['count'] = $i;
          }
          else{
            foreach ($query->result_array() as $row){
                $result[(string)$i]=$row;
                $i=$i+1;
            }
            $result['count'] = $i;
          }
          return $result;
        }
        
        /**
         * Return course for a given id.
         * @param type $course_id The id of the course that needs to be tallied.
         * @return array of the course
         */
        public function showCourseWithID($course_id){
          $result;
          $table_name="courses";
          try{
            $this->db->select('d.*,t.name,t.username,t.qualification,t.phoneno');
            $this->db->from('courses as d');
            $this->db->join('teacher_record as t', 'd.teacher_id=t.id');
            $this->db->where('d.id',$course_id);
            $query = $this->db->get();
          }
          catch(Exception $e){
              $result['success']="false";
              $result['errorCode']="1";
              return $result;

          }
          $i=0;
          $result['success']="true";
          $result['errorCode']="0";
          if($query->num_rows()==0){
            $i=0;
            $result['count'] = $i;
          }
          else{

          foreach ($query->result_array() as $row){
              $result[(string)$i]=$row;
              $i=$i+1;
            }
            $result['count'] = $i; 
          }
          return $result;
        }




       /**
        * This function returns array of all the courses.
        * @param type $ngo_id The id of the ngo who has verified the course
        * @param type $limit How many entries do you need.
        * @param type $offset From what index do you need entries.
        * @return array all the courses   
        */
        public function getAllCourses($ngo_id, $limit, $offset){
          $result;
          $table_name="courses";
          try{
            $this->db->where('ngo_id',$ngo_id);
            $query = $this->db->get($table_name,$limit,$offset);
          }
          catch(Exception $e){
              $result['success']="false";
              $result['errorCode']="1";
              return $result;

          }
          $i=0;
          $result['success']="true";
          $result['errorCode']="0";
          if($query->num_rows()==0){
            $i=0;
            $result['count'] = $i;
          }
          else{

          foreach ($query->result_array() as $row){
              $result[(string)$i]=$row;
              $i=$i+1;
            }
            $result['count'] = $i;
          }
          return $result;
        }

        /**
         * This function returns array of non verified courses.
         * @param type $ngo_id The id of the ngo who is supposed to verify the course
         * @param type $keyword Matching keyword with title for search
         * @param array $filter_clause Specific clauses that need to be matched for search
         * @param type $limit How many entries do you need.
         * @param type $offset From what index do you need entries.
         * @return array all the non verified courses
         */

        public function getNonVerifiedCourses($ngo_id, $keyword, $filter_clause, $limit, $offset){
          $result;
          $filter_clause['c.verified'] = 0;
          $table_name="courses";
          try{
            $this->db->select('c.*,t.name');
            $this->db->from('courses as c');
            $this->db->join('teacher_record as t','c.teacher_id=t.id','inner');
            $this->db->where('c.ngo_id',$ngo_id);
            if($keyword!=null){
              $con = get_instance()->db->conn_id;
              $keyword = mysqli_real_escape_string($con, $keyword); 
              $this->db->like('title',$keyword);
            }
            $this->db->where($filter_clause);
            $this->db->limit($limit,$offset);
            $query = $this->db->get();
          }
          catch(Exception $e){
              $result['success']="false";
              $result['errorCode']="1";
              return $result;
          }

          $i=0;
          $result['success']="true";
          $result['errorCode']="0";

          if($query->num_rows()==0){
            $i=0;
            $result['count'] = $i;
          }
          else{
            foreach ($query->result_array() as $row){
                $result[(string)$i]=$row;
                $i=$i+1;
            }
             $result['count'] = $i;
          }
          return $result;
      }
      
      
        /**
         * This function returns array of required courses.
         * @param type $ngo_id The id of the ngo who has requirement for a course
         * @param type $limit How many entries do you need.
         * @param type $offset From what index do you need entries.
         * @param type $keyword Matching keyword with title for search
         * @param array $filter_clause Specific clauses that need to be matched for search
         * @return array all required courses
         */

        public function getRequiredCourses($ngo_id, $limit, $offset,$keyword,$filter_clause){
          $result;
          $table_name="ngo_req";
          try{
            $this->db->select('*');
            $this->db->from($table_name);
            $this->db->where('ngo_id',$ngo_id);
            if($keyword!=null){
              $con = get_instance()->db->conn_id;
              $keyword = mysqli_real_escape_string($con, $keyword); 
              $this->db->like('title',$keyword);
            }
            if($filter_clause!=null)
              $this->db->where($filter_clause);

            $this->db->limit($limit,$offset);
            $query = $this->db->get();
          }
          catch(Exception $e){
              $result['success']="false";
              $result['errorCode']="1";
              return $result;
          }
          $i=0;
          $result['success']="true";
          $result['errorCode']="0";
          if($query->num_rows()==0){
              $i=0;
              $result['count'] = $i;
          }
          else{
              foreach ($query->result_array() as $row){
                $result[(string)$i]=$row;
                $i=$i+1;
              }
              $result['count'] = $i;
        }
          return $result;
      }


        /**
         * This function is called when an ngo needs to set new requirement for a course
         * @param type $course Course details
         * @return array Returns true if successful operation
         */
        public function setRequiredCourses($course){
          $result;
          $con = get_instance()->db->conn_id;
          $table_name="ngo_req";
          $ngo_id=mysqli_real_escape_string($con, $course['ngo_id']);
          $title=mysqli_real_escape_string($con, $course['title']);
          $requirement=mysqli_real_escape_string($con, $course['requirement']);
          $language=$course['language'];
          $subject=mysqli_real_escape_string($con, $course['subject']);
          $standard=$course['standard'];
          $board=$course['board'];

          $query="INSERT INTO $table_name (`ngo_id`,`title`,`requirement`,`language`,`subject`,`standard`,`board`) VALUES ('$ngo_id','$title','$requirement','$language','$subject','$standard','$board');";

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
            $result['errorCode']="1";
            return $result;

            }


        }
        /**
         * This function is called when a teacher needs to set a new course
         * @param type $course Course details
         * @return array Returns true if successful operation
         */
        public function setCourses($course){
          $result;
          $con = get_instance()->db->conn_id;
          $table_name="courses";
          $req_id=mysqli_real_escape_string($con, $course['req_id']);
          $teacher_id=mysqli_real_escape_string($con, $course['teacher_id']);
          $ngo_id=mysqli_real_escape_string($con, $course['ngo_id']);
          $title=mysqli_real_escape_string($con, $course['title']);
          $description=mysqli_real_escape_string($con, $course['description']);
          $subject=mysqli_real_escape_string($con, $course['subject']);
          $standard=$course['standard'];
          $language=$course['language'];
          $board=$course['board'];
          $qabank=$course['qabank'];
          $video=$course['video'];

          $query="INSERT INTO `$table_name` (`req_id`,`teacher_id`,`ngo_id`,`title`,`description`,`subject`,`standard`,`board`,`qabank`,`video`,`language`) VALUES ('$req_id','$teacher_id','$ngo_id','$title','$description','$subject','$standard','$board','$qabank','$video','$language');";
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
            $result['errorCode']="1";
            return $result;

          }


        }

        /**
         * This function is called when an ngo needs to set a course as verified
         * @param type $course_id id of the course that needs to be set as verified
         * @param type $ngo_id id of the ngo who wants to verify
         * @return array Returns true if successful operation
         */
        public function setVerifiedCourse($course_id, $ngo_id){
          $result;
          $table_name="courses";

          $query="UPDATE `$table_name` SET `verified`=true WHERE `id`='$course_id' AND `ngo_id`='$ngo_id' AND verified=false;";

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



        /**
         * This function is called when an ngo needs to set a course as non verified
         * @param type $course_id id of the course that needs to be set as non verified
         * @param type $ngo_id id of the ngo who wants to set it as non verified
         * @return array Returns true if successful operation
         */
        public function setNonVerifiedCourse($course_id, $ngo_id){
          $result;
          $table_name="courses";

          $query="UPDATE `$table_name` SET `verified`=false WHERE `id`='$course_id' AND `ngo_id`='$ngo_id' AND verified=true;";

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
        
        /**
         * This function is called by ngo to delete a course
         * @param type $req_id Requirement id of the course
         * @param type $ngo_id id of the ngo who wants to delete it
         * @return string
         */
        public function deleteRequiredCourse($req_id, $ngo_id){
          $result;
          $table_name="ngo_req";

          $query ="DELETE FROM `$table_name` WHERE ngo_id='$ngo_id' AND id='$req_id';";

          try{
            if ($this->db->query($query) && $this->db->affected_rows() > 0){
              $result['success']="true";
              $result['errorCode']="0";
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

        /**
         * This function is called by ngo to delete a course
         * @param type $course_id id of the course
         * @param type $ngo_id id of the ngo who wants to delete it
         * @return string
         */
        public function deleteCourse($course_id, $ngo_id){
          $result;
          $table_name="courses";

          $query ="DELETE FROM `$table_name` WHERE `ngo_id`='$ngo_id' AND `id`='$course_id';";

          try{
            if ($this->db->query($query) && $this->db->affected_rows() > 0){
              $result['success']="true";
              $result['errorCode']="0";
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

        /**
         * Function returns possible language options for the course
         * @return array Languages
         */
        public function getLanguageOptions(){
            $result;
            $con = get_instance()->db->conn_id;
            $table_name="language_options";
            try{
            $query = $this->db->query("SELECT `language` FROM `$table_name`;");
            }
            catch(Exception $e){
                $result['success']="false";
                $result['errorCode']="1";
                return $result;

            }

            $i=$query->num_rows();


            $result=$query->result_array();
            $result['count']=(string)$i;
            $result['success']="true";
            $result['errorCode']="0";

            return $result;
        }
        
        /**
         * Function returns possible subject options for the course
         * @return array Subjects
         */
         public function getSubjectOptions(){
            $result;
            $con = get_instance()->db->conn_id;
            $table_name="subject_options";
            try{
            $query = $this->db->query("SELECT `subject` FROM `$table_name`;");
            }
            catch(Exception $e){
                $result['success']="false";
                $result['errorCode']="1";
                return $result;

            }

            $i=$query->num_rows();


            $result=$query->result_array();
            $result['count']=(string)$i;
            $result['success']="true";
            $result['errorCode']="0";

            return $result;
        }
        /**
         * Function returns possible board options for the course
         * @return array Boards
         */
        public function getBoardOptions(){
          $result;
          $con = get_instance()->db->conn_id;
          $table_name="board_options";
          try{
          $query = $this->db->query("SELECT `board` FROM `$table_name`;");
          }
          catch(Exception $e){
              $result['success']="false";
              $result['errorCode']="1";
              return $result;

          }

          $i=$query->num_rows();


          $result=$query->result_array();
          $result['count']=(string)$i;
          $result['success']="true";
          $result['errorCode']="0";

          return $result;
        }
        

        /**
         * Returns course associated with a teacher
         * @param type $teacher_id id of the teacher
         * @param type $limit How many entries do you need.
         * @param type $offset From what index do you need entries.
         * @param type $verified boolean if verified or not
         * @param type $keyword Matching keyword with title for search
         * @param array $filter_clause Specific clauses that need to be matched for search
         * @return array course details
         */    
        public function getCoursesOfATeacher($teacher_id,$limit,$offset,$verified,$keyword,$filter_clause){
          $result;
          $filter_clause['c.verified'] = $verified;
          $table_name="courses";
          try{
            $this->db->select('c.*,t.name');
            $this->db->from('courses as c');
            $this->db->join('teacher_record as t','c.teacher_id=t.id','inner');
            $this->db->where('c.teacher_id',$teacher_id);
            if($keyword!=null){
              $con = get_instance()->db->conn_id;
              $keyword = mysqli_real_escape_string($con, $keyword); 
              $this->db->like('title',$keyword);
            }
            $this->db->where($filter_clause);
            $this->db->limit($limit,$offset);
            $query = $this->db->get();
          }
          catch(Exception $e){
              $result['success']="false";
              $result['errorCode']="1";
              return $result;
          }

          $i=0;
          $result['success']="true";
          $result['errorCode']="0";

          if($query->num_rows()==0){
            $i=0;
            $result['count'] = $i;
          }
          else{
            foreach ($query->result_array() as $row){
                $result[(string)$i]=$row;
                $i=$i+1;
            }
            $result['count'] = $i;
          }
          return $result;
        }
}

?>
