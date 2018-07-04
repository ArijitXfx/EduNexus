<?php
/**
* Discussion Portal Manager manage Discussion Portal and Discussion Thread
* 
* @author Arijit Basu <thearijitxfx@gmail.com>
* 
* version 1.0
* 
*/
class AdminManager_model extends CI_Model {

    
        /**
         * Constructor
         */
        public function __construct()
        {
                $this->load->database();
                $this->load->dbutil();
        }

        /**
         * Add a new NGO
         * 
         * @param associate array $data
         * @return associate array
         */
        public function insertNGO($data){
            $result;
            $table_name = 'ngo_record';
            $con = get_instance()->db->conn_id;
            $data['name'] = mysqli_real_escape_string($con, $data['name']);
            $data['username'] = mysqli_real_escape_string($con, $data['username']);
            $data['address'] = mysqli_real_escape_string($con, $data['address']);
            try{
                $this->db->insert($table_name,$data);
                if($this->db->affected_rows()>0){
                    $result['success'] = "true";
                    $result['errorCode'] = "0";
                }else{
                    $result['success'] = "false";
                    $result['errorCode'] = "1";
                }
                return $result;
            }catch(Exception $e){
                $result['success'] = "false";
                $result['errorCode'] = "1";
                return $result;
            }
        }
        
        /**
         * Update information of a ngo
         * 
         * @param int $id
         * @param associate array $data
         * @return associate array
         */
        public function updateNGO($id,$data){
            $result;
            $table_name = 'ngo_record';
            $con = get_instance()->db->conn_id;
            $id=mysqli_real_escape_string($con, $id);
            $data['address'] = mysqli_real_escape_string($con, $data['address']);
            $data['name'] = mysqli_real_escape_string($con, $data['name']);
            $data['username'] = mysqli_real_escape_string($con, $data['username']);
            try{
                $this->db->where('id',$id);
                $this->db->update($table_name,$data);
                if($this->db->affected_rows()>0){
                    $result['success'] = "true";
                    $result['errorCode'] = "0";
                }else{
                    $result['success'] = "false";
                    $result['errorCode'] = "6";
                }
                return $result;
            }catch(Exception $e){
                $result['success'] = "false";
                $result['errorCode'] = "1";
                return $result;
            }
        }

        /**
         * Delete a ngo
         * 
         * @param int $id
         * @return associate array
         */
        public function deleteNGO($id){
            $result;
            $table_name="ngo_record";
            $con = get_instance()->db->conn_id;
            $id=mysqli_real_escape_string($con, $id);

            $query ="DELETE FROM `$table_name` WHERE id='$id' ;";

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
         * show all registered ngo
         * 
         * @param int $admin_id
         * @param int $limit
         * @param int $offset
         * @return associate array
         */
        public function showNGO($admin_id, $limit, $offset){
            $result;
            $table_name = "ngo_record";
            $con = get_instance()->db->conn_id;
            $admin_id=mysqli_real_escape_string($con, $admin_id);
            
            try{
                $this->db->select('id, name, username,phoneno,address');
                $this->db->where('admin_id',$admin_id);
                $query = $this->db->get($table_name,$limit,$offset);
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
        
}
