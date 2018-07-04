<?php
/**
 * Model class used for the activities of an NGO
 * @author Sandeep Khan
 */
class NGOManager_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
                $this->load->dbutil();
        }

        /**
         * Fetches the details/record of volunteers of an NGO from database.
         * @param $ngo_id Id of an NGO
         * @param $limit  Number of records to be fetched
         * @param $offset Record number after which to start retrieving details
         * @return array Details of volunteers along with success and errorCode 
         */
        public function getVolunteers($ngo_id, $limit, $offset){
            $result;
            try{
                $this->db->select('id, name, username, address, phoneno');
                $this->db->where('ngo_id',$ngo_id);
                $this->db->where('active',1);
                $query = $this->db->get('volunteer_record',$limit,$offset);

            }catch(Exception $e){
                $result['success'] = "false";
                $result['errorCode'] = "1";
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
         * Inserts the details/record of volunteers of a NGO in database.
         * @param $data Details of a volunteer
         * @return array Details of volunteers along with success and errorCode 
         */
        public function insertVolunteer($data){
            $result;
            $con = get_instance()->db->conn_id;
            $data['name'] = mysqli_real_escape_string($con, $data['name']);
            $data['username'] = mysqli_real_escape_string($con, $data['username']);
            $data['address'] = mysqli_real_escape_string($con, $data['address']);
            $data['phoneno'] = mysqli_real_escape_string($con, $data['phoneno']);
            try{
                $this->db->insert('volunteer_record',$data);
                if($this->db->affected_rows()>0){
                    $result['success'] = "true";
                    $result['errorCode'] = "0";
                }else{
                    $result['success'] = "false";
                    $result['errorCode'] = "6";
                }
            }catch(Exception $e){
                $result['success'] = "false";
                $result['errorCode'] = "1";
            }
            return $result;
        }

        /**
         * Updates detail of a ngo volunteer
         * @param $id Id of Ngo volunteer
         * @param $data  Details of ngo volunteer
         * @return array Contains success and errorCode 
         */
        public function updateVolunteer($id,$data){
            $result;
            $con = get_instance()->db->conn_id;
            $data['name'] = mysqli_real_escape_string($con, $data['name']);
            $data['address'] = mysqli_real_escape_string($con, $data['address']);
            $data['phoneno'] = mysqli_real_escape_string($con, $data['phoneno']);
            try{
                $this->db->where('id',$id);
                $this->db->update('volunteer_record',$data);
                if($this->db->affected_rows()>0){
                    $result['success'] = "true";
                    $result['errorCode'] = "0";
                }else{
                    $result['success'] = "false";
                    $result['errorCode'] = "6";
                }
            }catch(Exception $e){
                $result['success'] = "false";
                $result['errorCode'] = "1";
            }
            return $result;
        }

        /**
         * Makes a ngo volunteer inactive
         * @param $id Id of Ngo volunteer
         * @param $data  Details of ngo volunteer
         * @return array Contains success and errorCode 
         */
        public function deleteVolunteer($id,$data){
            $result;
            try{
                $this->db->where('id',$id);
                $this->db->update('volunteer_record',$data);
                if($this->db->affected_rows()>0){
                    $result['success'] = "true";
                    $result['errorCode'] = "0";
                }else{
                    $result['success'] = "false";
                    $result['errorCode'] = "6";
                }
            }catch(Exception $e){
                $result['success'] = "false";
                $result['errorCode'] = "1";
            }
            return $result;

        }

         /**
         * Fetches the details/records of teachers of an NGO from database.
         * @param $ngo_id Id of the NGO
         * @param $verified 0 to fetch non verified teachers and 1 to fetch verified teachers 
         * @param $limit  Number of records to be fetched
         * @param $offset Record number after which to start retrieving details
         * @return array Details of teachers along with success and errorCode 
         */
        public function getTeachers($ngo_id,$verified, $limit, $offset){
            $result;
            $table_name="teacher_record";
            try{
                $this->db->select('id,name,username,phoneno,address,qualification');
                $this->db->where('ngo_id',$ngo_id);
                $this->db->where('verified',$verified);
                $this->db->where('active',1);
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
                $result['count'] = $i; //needs to be checked for logical errors
            }
          return $result;
        }

         /**
         * Changes the verified status of a teacher
         * @param $ngo_id Id of the NGO 
         * @param $id Teacher Id 
         * @param $data Details of teacher to be updated
         * @return array Contains success and errorCode 
         */
        public function changeVerifiedStatus($ngo_id, $id, $data){
            $result;
            $table_name="teacher_record";
            try{
                $this->db->where('ngo_id',$ngo_id);
                $this->db->where('id',$id);
                $this->db->where('active',1);
                $this->db->update($table_name,$data);

                if($this->db->affected_rows() > 0){
                    $result['success'] = "true";
                    $result['errorCode'] = "0";
                }else{
                    $result['success'] = "false";
                    $result['errorCode'] = "6";
                }

                return $result;
            }
            catch(Exception $e){
                $result['success']="false";
                $result['errorCode']="1";
                return $result;
             }
        }

         /**
         * Makes a teacher inactive
         * @param $ngo_id Id of the NGO 
         * @param $id Teacher Id 
         * @param $data Details of teacher to be updated
         * @return array Contains success and errorCode 
         */
        public function deleteTeacher($ngo_id, $id, $data){
            $result;
            $table_name="teacher_record";
            try{
                $this->db->where('ngo_id',$ngo_id);
                $this->db->where('id',$id);
                $this->db->update($table_name,$data);

                if($this->db->affected_rows() > 0){
                    $result['success'] = "true";
                    $result['errorCode'] = "0";
                }else{
                    $result['success'] = "false";
                    $result['errorCode'] = "6";
                }

                return $result;
            }
            catch(Exception $e){
                $result['success']="false";
                $result['errorCode']="1";
                return $result;
             }
        }

}
