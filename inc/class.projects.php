<?php 
require_once('db.php');
class PROJECT{
	private $conn;
	function __construct(){
		$database = new DATABASE();
		$db = $database->dbConnection();
		$this->conn = $db;	

	}
	public function runQuery($sql){
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}

	public function createProject($pname,$pdesc,$purl,$pdcreate,$pdlive,$pdmod,$aurl,$puid){
		try{

			$stmt = $this->conn->prepare("INSERT INTO projects(project_name,project_description,project_url,date_created,live_date,date_modified,asset_url,user_id) 
		                                               VALUES(:pname, :pdesc, :purl, :pdcreate, :pdlive, :pdmod,:aurl, :puid)");
												  
			$stmt->bindparam(":pname", $pname);
			$stmt->bindparam(":pdesc", $pdesc);
			$stmt->bindparam(":purl", $purl);
			$stmt->bindparam(":pdcreate", $pdcreate);
			$stmt->bindparam(":pdlive", $pdlive);
			$stmt->bindparam(":pdmod", $pdmod);										  
			$stmt->bindparam(":aurl", $aurl);										  
			$stmt->bindparam(":puid", $puid);										  
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}				
	}

	public function uploadFiles($upurl,$pid,$uid){
		try{

			$stmt = $this->conn->prepare("INSERT INTO project_uploads(upload_url,project_id,user_id) 
		                                     VALUES(:upurl, :pid, :uid)");
			
			foreach($upurl as $url){
				
				$stmt->bindparam(":upurl", $url);
				$stmt->bindparam(":pid", $pid);
				$stmt->bindparam(":uid", $uid);										  
					
				$stmt->execute();	
			}							  
			
			return $stmt;	
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}			
	}

	public function GetProjectPipline(){
		$stmt = $this->runQuery("SELECT project_id, project_name, project_status, assigned_to FROM projects ORDER BY project_status");
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}

	public function ViewProject($pid){
		$stmt = $this->runQuery("SELECT * FROM projects WHERE project_id=:pid");
		$stmt->execute(array(':pid'=>$pid));
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	public function AddNote($date,$userId,$assignedTo,$projectId,$cleanNote){
		try{

			$stmt = $this->conn->prepare("INSERT INTO project_updates(update_date,user_id,assigned_id,project_id,update_text) 
		                VALUES(:udate, :uid, :aid, :pid, :utext)");
												  
			$stmt->bindparam(":udate", $date);
			$stmt->bindparam(":uid", $userId);
			$stmt->bindparam(":aid", $assignedTo);
			$stmt->bindparam(":pid", $projectId);
			$stmt->bindparam(":utext", $cleanNote);										  
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}	
	}

	public function GetNotes($pid){

		$stmt = $this->runQuery("SELECT * FROM project_updates WHERE project_id=:pid ORDER BY update_date DESC");
		$stmt->execute(array(':pid'=>$pid));
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;

	}
}
