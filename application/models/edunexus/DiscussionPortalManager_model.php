<?php

/**
* Discussion Portal Manager manage Discussion Portal and Discussion Thread
* 
* @author Arijit Basu <thearijitxfx@gmail.com>
* 
* version 1.0
* 
*/
class DiscussionPortalManager_model extends CI_Model{
    
        /**
         * Constructor
         */
	public function __construct(){
		try{
			$this->load->database();
		}catch(Exception $e){
			show_404();
		}
	}

        /**
         * Fetching discussion(s) of a course
         * 
         * @param boolean $all
         * @param int $discussion_id
         * @param int $course_id
         * @param int $limit
         * @param int $offset
         * @param string $keyword
         * @return associate array
         */
	public function getDiscussion($all,$discussion_id=Null,$course_id=Null,$limit=Null,$offset=Null,$keyword){
		$result;
		$con = get_instance()->db->conn_id;

		try{
			if($all==="true"){
				
				$this->db->select('id,title,description,time,duplicate');
				$this->db->where('course_id', $course_id);
				if($keyword!=null){
					$this->db->like('title',$keyword);
					$this->db->or_like('description',$keyword);
				}
				$query =  $this->db->get('discussion_portal',$limit,$offset);
			}else{
				//echo 'inside else';
				$this->db->where('id', $discussion_id);
				$query =  $this->db->get('discussion_portal');
			}
		}
		catch(Exception $e){
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
         * Create a new question
         * 
         * @param int $course_id
         * @param string $title
         * @param string $desc
         * @param int $volunteer_id
         * 
         * @return associate array
         */
	public function setDiscussion($course_id,$title,$desc,$volunteer_id)
	{
		$result['success'] = "true";
		$result['errorCode'] = "0";
		$con = get_instance()->db->conn_id;
        $title=mysqli_real_escape_string($con, $title);
		try{

		    $data = array(
		        'title' => $title,
		        'course_id' => $course_id,
		        'description' => $desc,
		        'ngovolunteer_id' => $volunteer_id

		    );

		    $query =  $this->db->insert('discussion_portal', $data);
		    $result['lastId'] = $this->db->insert_id();
		    if($query!=1){
		    	$result['success'] = "false";
		    	$result['errorCode'] = "2";
		    }
		}catch(Exception $e){
			$result['success'] = "false";
			$result['errorCode'] = "1";
		}
		return $result;
	    
	}

        /**
         * Create a new answer
         * 
         * @param int $discussion_id
         * @param boolean $if_teacher
         * @param string $answer
         * @return associate array
         */
	public function setAnswer($discussion_id,$if_teacher,$answer)
	{

		$result['success'] = "true";
		$result['errorCode'] = "0";

		$con = get_instance()->db->conn_id;
        // $answer=mysqli_real_escape_string($con, $answer);
		try{

		    $data = array(
		    	'discussion_id' => $discussion_id,
		        'reply' => $answer,
		        'user_id' => 1,
		        'upvote' => 0,
		        'downvote' => 0,
		        'if_teacher' => $if_teacher

		    );

		    $query = $this->db->insert('discussion_thread', $data);
		    if($query!=1){
		    	$result['success'] = "false";
		    	$result['errorCode'] = "2";
		    }
		}catch(Exception $e){
			$result['success'] = "false";
			$result['errorCode'] = "1";
		}
		return $result;
	}

        /**
         * Gather answer(s) of a question
         * 
         * @param int $id
         * @param int $limit
         * @param int $offset
         * @param string $userName
         * @return associate array
         */
	public function getAnswer($id,$limit,$offset,$userName){
		$result;
		try{
			$this->db->select('d.id,d.reply,t.name as teacher,v.name as volunteer,d.if_teacher,d.upvote,d.downvote,vc.isupvote');
			$this->db->from('discussion_thread as d');
			$this->db->join('teacher_record as t', 'd.user_id=t.id AND  d.if_teacher =1','left');
			$this->db->join('volunteer_record as v', 'd.user_id=v.id AND  d.if_teacher =0','left');

			$this->db->join('vote_count as vc', 'd.id = vc.id AND  vc.username ="'.$userName.'"','left');

			$this->db->where('d.discussion_id',$id);
			$this->db->order_by("d.time", "asc");
			$this->db->limit($limit,$offset);
			$query = $this->db->get();
		}
		catch(Exception $e){
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
         * Set upvote and downvote of a answer
         * 
         * @param string $user_name
         * @param int $discussion_thread_id
         * @return associate array
         */
	public function getUpvoteDownvote($user_name,$discussion_thread_id){

		$result['success'] = "true";
		$result['errorCode'] = "0";
		try{
			$this->db->select('isupvote',false);
			$this->db->from('vote_count as vc');
			$this->db->where('LOWER(vc.username)=',$user_name);
			$this->db->where('LOWER(vc.id)=',$discussion_thread_id);
			$result['data'] = $this->db->get()->result();
			if(count($result['data'])==0){
				$result['success'] = "false";
				$result['errorCode'] = "2";
			}
		}catch(Exception $e){
			$result['success'] = "false";
			$result['errorCode'] = "1";
		}
		return $result;
	}
        
        /**
         * Count upvote and downvote of a answer
         * 
         * @param string $user_name
         * @param int $discussion_thread_id
         * @param boolean $isUpvote
         * @param int $voteCount
         * @param boolean $upvote_or_downvote
         * @return associate array
         */
	public function setVoteCount($user_name,$discussion_thread_id,$isUpvote,$voteCount,$upvote_or_downvote){
		$result['success'] = "true";
		$result['errorCode'] = "0";
		try{
			if($voteCount['errorCode']==="2"){
				$data = array(
			        'username' => $user_name,
			        'id' => $discussion_thread_id,
			        'isupvote' => $isUpvote

		    	);

		    	$query =  $this->db->insert('vote_count', $data);
			}else if($voteCount['errorCode']=== "0"){
				if(($voteCount['data'][0]->isupvote==0 && $upvote_or_downvote==1)||($voteCount['data'][0]->isupvote==1 && $upvote_or_downvote==0)){
					$this->db->where('username', $user_name);
					$this->db->where('id', $discussion_thread_id);
	    			$this->db->update('vote_count', array('isupvote' => $isUpvote));
				}else{
					$this->db->where('username', $user_name);
					$this->db->where('id', $discussion_thread_id);
			    	$this->db->delete('vote_count');
				}
				
			}else{
				$result['errorCode'] = "1";
				$result['success'] = "false";
			}
			
		}catch(Exception $e){
			$result['success'] = "false";
			$result['errorCode'] = "1";
		}
		return $result;
	}

        /**
         * Set upvote of a answer
         * 
         * @param int $id
         * @param int $upvote
         * @return associate array
         */
	public function setUpvote($id,$upvote){
		$result['success']  = "true";
	    $result['errorCode'] = "0";
		try{
	    	$this->db->where('id', $id);
	    	$this->db->update('discussion_thread', array('upvote' => $upvote));
	    	$num =  $this->db->affected_rows();
	        
	        if($num>0){
				$result['success']  = "true";
				$result['errorCode'] = "0";
	        }	
	        
		}catch(Exception $e){
			$result['success']  = "false";
	        $result['errorCode'] = "1";
		}
		return $result;
	}

        /**
         * Set downvote of a answer
         * 
         * @param int $id
         * @param int $downvote
         * @return associate array
         */
	public function setDownvote($id,$downvote){
		$result['success']  = "true";
	    $result['errorCode'] = "0";
		try{
	    	$this->db->where('id', $id);
	    	$this->db->update('discussion_thread', array('downvote' => $downvote));
	    	$num =  $this->db->affected_rows();
	        
	        if($num>0){
				$result['success']  = "true";
				$result['errorCode'] = "0";
	        }	
	        
		}catch(Exception $e){
			$result['success']  = "false";
	        $result['errorCode'] = "1";
		}
		return $result;
	}

        /**
         * Set link of a predefined similar question
         * 
         * @param int $id
         * @param string $duplicateLink
         * @return associate array
         */
	public function setDuplicateLink($id,$duplicateLink){
		$result['success']  = "true";
	    $result['errorCode'] = "0";
		try{
	    	$this->db->where('id', $id);
	    	$this->db->update('discussion_portal', array('duplicate' => $duplicateLink));
	    	$num =  $this->db->affected_rows();
	        if($num>0){
				$result['success']  = "true";
				$result['errorCode'] = "0";
	        }	
	        
		}catch(Exception $e){
			$result['success']  = "false";
	        $result['errorCode'] = "1";
		}
		return $result;
	}

        /**
         * Remove duplicate link from a question
         * 
         * @param int $id
         * @return associate array
         */
	public function cancelDuplicateLink($id){
		$result['success']  = "true";
	    $result['errorCode'] = "0";
		try{
	    	$this->db->where('id', $id);
	    	$this->db->update('discussion_portal', array('duplicate' => ''));
	    	$num =  $this->db->affected_rows();
	        
	        if($num>0){
				$result['success']  = "true";
				$result['errorCode'] = "0";
	        }	
	        
		}catch(Exception $e){
			$result['success']  = "false";
	        $result['errorCode'] = "1";
		}
		return $result;
	}

        /**
         * Get information of a teacher by his course id
         * 
         * @param int $course_id
         * @return associate array
         */
	public function getTeacherFromCourse($course_id){
          $result;
          try{
            $this->db->select('t.username, t.name');
            $this->db->from('courses as c');
            $this->db->join('teacher_record as t','c.teacher_id=t.id','inner');
            $this->db->where('c.id',$course_id);
            $query = $this->db->get();
          }
          catch(Exception $e){
	          $result['success']="false";
	          $result['errorCode']="1";
	          return $result;
          }
          $result['success']  = "true";
		  $result['errorCode'] = "0";
          foreach ($query->result_array() as $row){
          	$result['username']=$row['username'];
            $result['name']=$row['name'];
           }
        return $result;
    }
}