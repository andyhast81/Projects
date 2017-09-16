<?php 
require_once('inc/db.php');
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

	public function createProject($pname,$pdesc,$pdcreate,$pdlive,$pdmod,$aurl,$puid){
		try{

			$stmt = $this->conn->prepare("INSERT INTO projects(project_name,project_description,date_created,live_date,date_modified,asset_url,user_id) 
		                                               VALUES(:pname, :pdesc, :pdcreate, :pdlive, :pdmod,:aurl, :puid)");
												  
			$stmt->bindparam(":pname", $pname);
			$stmt->bindparam(":pdesc", $pdesc);
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
}
